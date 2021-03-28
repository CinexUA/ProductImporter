<?php

namespace App\Providers;

use App\Models\Import;
use App\Observers\ImportObserver;
use Illuminate\Support\ServiceProvider;

class ObserverServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Import::observe(ImportObserver::class);
    }
}
