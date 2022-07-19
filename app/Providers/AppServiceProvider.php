<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;
use Statamic\Statamic;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if(app()->environment() === 'production'){
            \URL::forceScheme('https');
        }

        $this->app->singleton('toggl', function () {
            return Http::withBasicAuth(config('services.toggl.token'), 'api_token')
                ->baseUrl('https://api.track.toggl.com/api/v9/');
        });


        // Statamic::script('app', 'cp');
        // Statamic::style('app', 'cp');
    }
}
