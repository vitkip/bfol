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
        \Illuminate\Support\Facades\View::composer('front.*', function ($view) {
            // Cache as plain array (stdClass fails to unserialize from file cache).
            // Cast to object after retrieval so Blade views keep $settings->key syntax.
            $settingsArr = \Illuminate\Support\Facades\Cache::remember('site_settings', 3600, function () {
                $keys = [
                    'site_name_lo','site_name_en','site_name_zh',
                    'site_phone','site_email',
                    'site_address_lo','site_address_zh',
                    'site_facebook','site_youtube','site_line','site_wechat','site_whatsapp',
                    'logo_url','favicon_url','office_hours_lo',
                ];
                $rows = \App\Models\SiteSetting::whereIn('key', $keys)->get(['key','value'])->pluck('value','key');
                return collect($keys)->mapWithKeys(fn($k) => [$k => $rows[$k] ?? ''])->all();
            });
            $settings = (object) $settingsArr;

            // Cache as plain arrays to avoid Eloquent Collection unserialize errors.
            $bannersArr = \Illuminate\Support\Facades\Cache::remember('site_banners', 3600, fn() =>
                \App\Models\Banner::active()
                    ->orderBy('sort_order')
                    ->orderBy('id')
                    ->get()
                    ->map(fn($b) => $b->toArray())
                    ->all()
            );
            $bannersByPos = collect($bannersArr)
                ->map(fn($b) => (object) $b)
                ->groupBy('position')
                ->map(fn($g) => $g->values());

            $navArr = \Illuminate\Support\Facades\Cache::remember('site_navmenus', 3600, fn() =>
                \App\Models\NavigationMenu::active()
                    ->with(['children' => fn($q) => $q->active()->orderBy('sort_order')
                        ->with(['children' => fn($q2) => $q2->active()->orderBy('sort_order')
                            ->with(['children' => fn($q3) => $q3->active()->orderBy('sort_order')])
                        ])
                    ])
                    ->whereNull('parent_id')
                    ->orderBy('sort_order')
                    ->get()
                    ->toArray()
            );
            $toCollection = null;
            $toCollection = function (array $items) use (&$toCollection): \Illuminate\Support\Collection {
                return collect($items)->map(function ($item) use (&$toCollection) {
                    $obj = (object) $item;
                    $obj->children = isset($item['children']) && is_array($item['children'])
                        ? $toCollection($item['children'])
                        : collect();
                    return $obj;
                });
            };
            $navMenus = $toCollection($navArr);

            $view->with('settings', $settings)
                 ->with('locale', app()->getLocale())
                 ->with('bannersByPos', $bannersByPos)
                 ->with('navMenus', $navMenus);
        });
    }
}
