<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Pagination\Paginator;
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
        // The admin console runs on Bootstrap 5; the public site passes its own
        // paginator view explicitly, so this default only affects the admin.
        Paginator::useBootstrapFive();

        // Share site settings with all frontend views
        View::composer('layouts.app', function ($view) {
            $view->with('siteSettings', SiteSetting::getGroup('general'));
            $view->with('seoSettings', SiteSetting::getGroup('seo'));
            $view->with('whatsappNumber', SiteSetting::get('whatsapp_number', config('aldeftech.whatsapp.number')));
            $view->with('whatsappMessage', SiteSetting::get('whatsapp_default_message', config('aldeftech.whatsapp.default_message')));
        });
    }
}
