<?php

namespace App\Providers;

use App\Http\View\Composers\NotificationComposer;
use App\Models\Activity;
use App\Observers\ActivityObserver;
use Illuminate\Support\Facades\View;
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
        Activity::observe(ActivityObserver::class);
        View::composer('layouts.app', NotificationComposer::class);
    }
}
