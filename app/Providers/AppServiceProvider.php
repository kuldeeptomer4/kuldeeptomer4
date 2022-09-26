<?php

namespace App\Providers;

use Laravel\Sanctum\Sanctum;
use App\Models\PersonalAccessToken;
use Illuminate\Support\ServiceProvider;

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
        if ( ! is_dir( config('view.compiled') ) ) {
            mkdir( config('view.compiled'), 0755, true );
        }
    }
}
