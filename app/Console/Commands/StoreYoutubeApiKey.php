<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\YoutubeSetting;

class StoreYoutubeApiKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'youtube:set-api-key {key}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Stores a new API key to the Youtube Settings table for backup api key manage';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if(empty($this->argument('key'))){
            $this->error('Please provide a proper key to insert');
            return false; 
        }
        
        $this->info('Api Key inserted successfully');
        YoutubeSetting::create([
            'api_key' => $this->argument('key')
        ]);
        
    }
}
