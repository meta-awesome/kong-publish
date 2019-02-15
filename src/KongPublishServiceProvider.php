<?php

namespace Metawesome\KongPublish;

use Illuminate\Support\ServiceProvider;

class KongPublishServiceProvider extends ServiceProvider
{
    protected $commands = [
        'Metawesome\KongPublish\Commands\CreateKongService',
    ];

    /**
     * Bootstrap de application services
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config' => base_path('config/'),
        ]);
    }

    /**
     * Register the commands
     *
     * @return void
     */
    public function register()
    {
        $this->commands($this->commands);
    }
}
