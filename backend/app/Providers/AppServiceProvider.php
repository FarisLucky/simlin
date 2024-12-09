<?php

namespace App\Providers;

use App\Models\Daftar;
use App\Observers\DaftarObserver;
use Illuminate\Support\Carbon;
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
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        config(['app.locale' => 'id']);
        Carbon::setLocale('id');

        /**
         * OBSERVER
         */
        Daftar::observe(DaftarObserver::class);
    }
}
