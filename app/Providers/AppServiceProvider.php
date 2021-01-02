<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use LINE\LINEBot;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('line-bot', function($app, array $parameters){
            return new LINEBot(
                new LINEBot\HTTPClient\CurlHTTPClient(config('app.line_access_token')),
                ['channelSecret' => config('app.channel_secret')]
            );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
