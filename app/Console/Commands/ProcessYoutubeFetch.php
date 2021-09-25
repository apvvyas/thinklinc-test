<?php

namespace App\Console\Commands;

use App\Jobs\YoutubeFetch;
use Illuminate\Console\Command;

class ProcessYoutubeFetch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'youtube:fetch {limit?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command initializes the youtube fetch process';

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
        YoutubeFetch::dispatchNow($this->argument('limit'));
    }
}
