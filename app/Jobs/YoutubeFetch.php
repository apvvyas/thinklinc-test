<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Youtube;
use Alaouy\Youtube\Youtube as YTube;
use Carbon\Carbon;
use App\Models\YoutubeVideo;
use App\Models\YoutubeSetting;
use App\Models\HandleImport;

class YoutubeFetch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $youtube = '';

    protected $filter = [
        'q'             => 'Android',
        'type'          => 'video',
        'part'          => 'id, snippet'
    ];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($limit = '', $last_processed = '')
    {
        $lastImport = HandleImport::latest()->first();

        if(!empty($limit))
            $this->filter['maxResults'] = $limit;

        if($lastImport)
            $this->filter['publishedAfter'] = $lastImport->last_published_at_proccessed->format('Y-m-d\TH:i:s\Z');

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {   
        $youtube = $this->youtubeInitialize();

        if($youtube){
            $data = $youtube->searchAdvanced($this->filter);
    
            if(!empty($data)){

                $youtubeVideos = collect($data)->sortBy('snippet.publishedAt')->map(function($item){
                    return YoutubeVideo::updateOrcreate([
                        'youtube_id' => $item->id->videoId,
                    ],[
                        'title' => $item->snippet->title,
                        'description' => $item->snippet->description,
                        'thumbnails' => $item->snippet->thumbnails,
                        'published_at' => Carbon::parse($item->snippet->publishedAt)->toDateTimeString(),
                        'etag' => $item->etag
                    ]);
                });
                
                $last = $youtubeVideos->last();
                
                HandleImport::create([
                    'last_published_at_proccessed' => $last->published_at,
                    'youtube_id' => $last->youtube_id
                ]);
            }
        }
        
    }

    public function youtubeInitialize()
    {
        $settings = YoutubeSetting::notExpired()->get();
        $youtube = null;
        $foundKey = false;
        $self = $this;
        $expiredDefault = YoutubeSetting::IsExpired()->where('api_key', config('youtube.key'))->first();

        if(!$expiredDefault)
            $youtube = new YTube(config('youtube.key'));
        
        
        $settings->each(function($setting) use(&$youtube, &$foundKey, $self){
            try{
                $youtube = new YTube($setting->api_key);

                $youtube->search($self->filter['q'], 1);

                $foundKey = true;

                return false;
            }
            catch(Exception $e){
                $youtube = new YTube(config('youtube.key'));
                $setting->setExpired()->save();
            }
            return true;
        });
        
        if(!$foundKey){
            try{
                $youtube->search($this->filter['q'], 1);
            }
            catch(Exception $e){
                YoutubeSetting::setExpired()->create([
                    'api_key' => config('youtube.key'),
                ]);
                $youtube = null;
            }
        }

        return $youtube;
    }


}
