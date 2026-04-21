<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::orderBy('group')->orderBy('id')->get()->groupBy('group');

        $groupLabels = [
            'general' => ['lo' => 'ທົ່ວໄປ',      'icon' => 'fa-globe'],
            'contact' => ['lo' => 'ຕິດຕໍ່',      'icon' => 'fa-address-card'],
            'social'  => ['lo' => 'ສັງຄົມ',      'icon' => 'fa-share-alt'],
            'display' => ['lo' => 'ການສະແດງ',   'icon' => 'fa-sliders-h'],
            'system'  => ['lo' => 'ລະບົບ',       'icon' => 'fa-cog'],
        ];

        return view('admin.settings.index', compact('settings', 'groupLabels'));
    }

    public function store(Request $request)
    {
        $values = $request->input('settings', []);
        $files  = $request->file('settings_files', []);

        // Handle image uploads first so they take priority over hidden text values
        foreach ($files as $key => $file) {
            if ($file && $file->isValid()) {
                $setting = SiteSetting::where('key', $key)->first();

                if ($setting?->value && str_starts_with($setting->value, '/storage/')) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $setting->value));
                }

                $path = $file->store('settings', 'public');
                SiteSetting::where('key', $key)->update([
                    'value'      => '/storage/' . $path,
                    'updated_by' => auth()->id(),
                ]);
                Cache::forget("setting_{$key}");
                unset($values[$key]);
            }
        }

        // Handle all remaining values
        foreach ($values as $key => $value) {
            SiteSetting::where('key', $key)->update([
                'value'      => $value ?? '',
                'updated_by' => auth()->id(),
            ]);
            Cache::forget("setting_{$key}");
        }

        return redirect()->route('admin.settings.index')
                         ->with('success', 'ບັນທຶກການຕັ້ງຄ່າສຳເລັດ');
    }
}
