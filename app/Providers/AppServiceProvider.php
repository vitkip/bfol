<?php

namespace App\Providers;

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
        // Share $settings and $locale with every front.* Blade view
        \Illuminate\Support\Facades\View::composer('front.*', function ($view) {
            static $settings = null;
            if ($settings === null) {
                $keys = [
                    'site_name_lo','site_name_en','site_name_zh',
                    'site_phone','site_email',
                    'site_address_lo','site_address_zh',
                    'site_facebook','site_youtube','site_line','site_wechat','site_whatsapp',
                    'logo_url','favicon_url','office_hours_lo',
                ];
                $data = [];
                foreach ($keys as $k) {
                    $data[$k] = \App\Models\SiteSetting::get($k, '');
                }
                $settings = (object) $data;
            }
            $view->with('settings', $settings)
                 ->with('locale', app()->getLocale());
        });
    }
}
