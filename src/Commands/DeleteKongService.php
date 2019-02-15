<?php

namespace Metawesome\KongPublish\Commands;

use Illuminate\Console\Command;

class DeleteKongService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'KongService:down';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete microservice in Kong Api Gateway';

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
     * @return mixed
     */
    public function handle()
    {
        echo "down";
    }
}
