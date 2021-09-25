<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Youtube;
use Carbon\Carbon;
use App\Models\YoutubeVideo;
use App\Models\HandleImport;

class YoutubeFetch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        
        $data = Youtube::searchAdvanced($this->filter);
        //dd($data);
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
