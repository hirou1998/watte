<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
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
            // return new LINEBot(
            //     new LINEBot\HTTPClient\CurlHTTPClient('dmwRzaMHXTCvcpFjZO0j1QXH47Cb2PBm1wmfErjI9x8tHIwJKX1fEnalIXjShxj2rkqKHhnggL4QCEWJHLRkRNaqWqNivEL5tizPQYfjdewlEz2VJDNc7GjQPg8hj0EMv6BYKaMVA7plZDoYtAtK6gdB04t89/1O/w1cDnyilFU='),
            //     ['channelSecret' => 'bad9d2d3404174ae078135c46a687279']
            // );
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
