<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    private const STYLES = [
        'banner-blue'  => ['lo' => 'ສີຟ້າ (Primary)', 'bg' => 'bg-blue-700',   'text' => 'text-white',       'preview' => 'from-blue-600 to-blue-800'],
        'banner-green' => ['lo' => 'ສີຂຽວ',            'bg' => 'bg-green-600',  'text' => 'text-white',       'preview' => 'from-green-600 to-green-800'],
        'banner-gold'  => ['lo' => 'ສີທອງ',            'bg' => 'bg-amber-500',  'text' => 'text-white',       'preview' => 'from-amber-500 to-amber-700'],
        'banner-dark'  => ['lo' => 'ສີດຳ',             'bg' => 'bg-gray-800',   'text' => 'text-white',       'preview' => 'from-gray-700 to-gray-900'],
        'banner-light' => ['lo' => 'ສີຂາວ',            'bg' => 'bg-gray-50',    'text' => 'text-gray-800',    'preview' => 'from-gray-50 to-gray-200'],
        'banner-red'   => ['lo' => 'ສີແດງ',            'bg' => 'bg-red-600',    'text' => 'text-white',       'preview' => 'from-red-500 to-red-700'],
    ];

    private const POSITIONS = [
        'sidebar' => ['lo' => 'Sidebar',    'icon' => 'fa-columns'],
        'top'     => ['lo' => 'ດ້ານເທິງ',  'icon' => 'fa-arrow-up'],
        'bottom'  => ['lo' => 'ດ້ານລຸ່ມ',  'icon' => 'fa-arrow-down'],
        'popup'   => ['lo' => 'Popup',      'icon' => 'fa-window-restore'],
        'inline'  => ['lo' => 'Inline',     'icon' => 'fa-align-center'],
    ];

    public function index(Request $request)
    {
        $query = Banner::orderBy('sort_order')->orderBy('id');

        if ($request->position) {
            $query->where('position', $request->position);
        }
        if ($request->has('active') && $request->active !== '') {
            $query->where('is_active', (bool) $request->active);
        }

        $banners = $query->paginate(20)->withQueryString();

        return view('admin.banners.index', [
            'banners'   => $banners,
            'styles'    => self::STYLES,
            'positions' => self::POSITIONS,
        ]);
    }

    public function create()
    {
        return view('admin.banners.create', [
            'styles'    => self::STYLES,
            'positions' => self::POSITIONS,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_lo'     => 'required|string|max:200',
            'title_en'     => 'nullable|string|max:200',
            'title_zh'     => 'nullable|string|max:200',
            'subtitle_lo'  => 'nullable|string',
            'subtitle_en'  => 'nullable|string',
            'subtitle_zh'  => 'nullable|string',
            'image_file'   => 'nullable|image|max:4096',
            'image_url'    => 'nullable|string|max:500',
            'btn_text_lo'  => 'nullable|string|max:80',
            'btn_text_en'  => 'nullable|string|max:80',
            'btn_text_zh'  => 'nullable|string|max:80',
            'btn_url'      => 'nullable|string|max:500',
            'style'        => 'required|in:banner-blue,banner-green,banner-gold,banner-dark,banner-light,banner-red',
            'position'     => 'required|in:sidebar,top,bottom,popup,inline',
            'sort_order'   => 'nullable|integer|min:0|max:9999',
            'is_active'    => 'nullable|boolean',
        ]);

        if ($request->hasFile('image_file')) {
            $validated['image_url'] = '/storage/' . $request->file('image_file')->store('banners', 'public');
        }

        unset($validated['image_file']);
        $validated['is_active']  = $request->boolean('is_active');
        $validated['sort_order'] = (int) $request->input('sort_order', 0);

        Banner::create($validated);

        return redirect()->route('admin.banners.index')
                         ->with('success', 'ເພີ່ມ Banner ສຳເລັດ');
    }

    public function show(Banner $banner)
    {
        return view('admin.banners.show', [
            'banner'    => $banner,
            'styles'    => self::STYLES,
            'positions' => self::POSITIONS,
        ]);
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', [
            'banner'    => $banner,
            'styles'    => self::STYLES,
            'positions' => self::POSITIONS,
        ]);
    }

    public function update(Request $request, Banner $banner)
    {
        $validated = $request->validate([
            'title_lo'     => 'required|string|max:200',
            'title_en'     => 'nullable|string|max:200',
            'title_zh'     => 'nullable|string|max:200',
            'subtitle_lo'  => 'nullable|string',
            'subtitle_en'  => 'nullable|string',
            'subtitle_zh'  => 'nullable|string',
            'image_file'   => 'nullable|image|max:4096',
            'image_url'    => 'nullable|string|max:500',
            'btn_text_lo'  => 'nullable|string|max:80',
            'btn_text_en'  => 'nullable|string|max:80',
            'btn_text_zh'  => 'nullable|string|max:80',
            'btn_url'      => 'nullable|string|max:500',
            'style'        => 'required|in:banner-blue,banner-green,banner-gold,banner-dark,banner-light,banner-red',
            'position'     => 'required|in:sidebar,top,bottom,popup,inline',
            'sort_order'   => 'nullable|integer|min:0|max:9999',
            'is_active'    => 'nullable|boolean',
        ]);

        if ($request->hasFile('image_file')) {
            $this->deleteLocalImage($banner->image_url);
            $validated['image_url'] = '/storage/' . $request->file('image_file')->store('banners', 'public');
        } elseif (!empty($validated['image_url'])) {
            // keep submitted URL
        } else {
            $validated['image_url'] = $banner->image_url;
        }

        if ($request->boolean('clear_image')) {
            $this->deleteLocalImage($banner->image_url);
            $validated['image_url'] = null;
        }

        unset($validated['image_file']);
        $validated['is_active']  = $request->boolean('is_active');
        $validated['sort_order'] = (int) $request->input('sort_order', 0);

        $banner->update($validated);

        return redirect()->route('admin.banners.show', $banner)
                         ->with('success', 'ອັບເດດ Banner ສຳເລັດ');
    }

    public function destroy(Banner $banner)
    {
        $this->deleteLocalImage($banner->image_url);
        $banner->delete();

        return redirect()->route('admin.banners.index')
                         ->with('success', 'ລຶບ Banner ສຳເລັດ');
    }

    private function deleteLocalImage(?string $url): void
    {
        if ($url && str_starts_with($url, '/storage/')) {
            Storage::disk('public')->delete(substr($url, 9));
        }
    }
}
