<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale('id');
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        try {
            $settings_data = \App\Models\Setting::all()->pluck('value', 'key');
            \Illuminate\Support\Facades\View::share('settings_data', $settings_data);
        } catch (\Exception $e) {
            // Ignore if tables not migrated yet
        }
    }
}
