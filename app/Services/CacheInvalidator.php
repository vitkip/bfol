<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheInvalidator
{
    public static function news(): void
    {
        Cache::forget('dashboard_stats');
        Cache::forget('dashboard_chart');
        Cache::forget('api_home_featured_news');
        Cache::forget('api_home_latest_news');
        Cache::forget('home_latest_news');
        Cache::forget('api_statistics');
    }

    public static function events(): void
    {
        Cache::forget('dashboard_stats');
        Cache::forget('dashboard_chart');
        Cache::forget('api_home_upcoming_events');
        Cache::forget('api_statistics');
    }

    public static function partners(): void
    {
        Cache::forget('api_partners');
        Cache::forget('api_home_partners');
        Cache::forget('home_partners');
        Cache::forget('home_live_stats');
        Cache::forget('api_statistics');
    }

    public static function settings(): void
    {
        Cache::forget('site_settings');
        Cache::forget('api_site_settings');
    }

    public static function slides(): void
    {
        Cache::forget('api_slides');
        Cache::forget('home_slides');
    }

    public static function navigation(): void
    {
        Cache::forget('site_navmenus');
    }

    public static function banners(): void
    {
        Cache::forget('site_banners');
    }

    public static function mou(): void
    {
        Cache::forget('home_live_stats');
        Cache::forget('api_statistics');
    }

    public static function committee(): void
    {
        Cache::forget('home_live_stats');
    }

    public static function aidProjects(): void
    {
        Cache::forget('home_live_stats');
        Cache::forget('api_statistics');
    }

    public static function documents(): void
    {
        Cache::forget('home_live_stats');
    }

    public static function statistics(): void
    {
        Cache::forget('home_statistics');
        Cache::forget('api_statistics');
    }
}
