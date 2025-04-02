<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        //DB::listen(function ($query) {
        // Log::info($query->sql, $query->bindings, $query->time);
        //Log::info($query->sql, $query->bindings);     // the query being executed
        //Log::info($query->time);
        //});
    }
}
