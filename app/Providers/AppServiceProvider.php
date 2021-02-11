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
                new LINEBot\HTTPClient\CurlHTTPClient(config('line.line_access_token')),
                ['channelSecret' => config('line.channel_secret')]
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
        Schema::defaultStringLength(191);
        if (\App::environment('production')) {
            \URL::forceScheme('https');
        }
    }
}
