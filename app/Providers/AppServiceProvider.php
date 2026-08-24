<?php

namespace App\Providers;

use App\Http\Middleware\CheckRole;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Register middleware aliases
        $this->app['router']->alias('role', CheckRole::class);

        // Share site settings with all frontend views
        View::composer('layouts.app', function ($view) {
            $view->with('siteSettings', SiteSetting::getGroup('general'));
            $view->with('seoSettings', SiteSetting::getGroup('seo'));
            $view->with('whatsappNumber', SiteSetting::get('whatsapp_number', config('aldeftech.whatsapp.number')));
            $view->with('whatsappMessage', SiteSetting::get('whatsapp_default_message', config('aldeftech.whatsapp.default_message')));
        });
    }
}
