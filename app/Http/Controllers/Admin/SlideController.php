<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use Illuminate\Http\Request;

class SlideController extends Controller
{
    public function index()
    {
        $slides = HeroSlide::orderBy('sort_order')->orderBy('id')->paginate(20);
        return view('admin.slides.index', compact('slides'));
    }

    public function create()
    {
        return view('admin.slides.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_lo'      => 'required|string|max:300',
            'title_en'      => 'nullable|string|max:300',
            'title_zh'      => 'nullable|string|max:300',
            'tag_lo'        => 'nullable|string|max:100',
            'tag_en'        => 'nullable|string|max:100',
            'tag_zh'        => 'nullable|string|max:100',
            'subtitle_lo'   => 'nullable|string',
            'subtitle_en'   => 'nullable|string',
            'subtitle_zh'   => 'nullable|string',
            'image_file'    => 'nullable|image|max:4096',
            'image_url'     => 'nullable|string|max:500',
            'btn1_text_lo'  => 'nullable|string|max:80',
            'btn1_text_en'  => 'nullable|string|max:80',
            'btn1_text_zh'  => 'nullable|string|max:80',
            'btn1_url'      => 'nullable|string|max:500',
            'btn2_text_lo'  => 'nullable|string|max:80',
            'btn2_text_en'  => 'nullable|string|max:80',
            'btn2_text_zh'  => 'nullable|string|max:80',
            'btn2_url'      => 'nullable|string|max:500',
            'sort_order'    => 'nullable|integer|min:0|max:9999',
            'is_active'     => 'nullable|boolean',
        ]);

        // File upload takes priority over URL text input
        if ($request->hasFile('image_file')) {
            $validated['image_url'] = '/storage/' . $request->file('image_file')->store('slides', 'public');
        } elseif (empty($validated['image_url'])) {
            return back()->withInput()->withErrors(['image_url' => 'ກະລຸນາໃສ່ຮູບພາບ (ອັບໂຫຼດໄຟລ໌ ຫຼື URL)']);
        }

        unset($validated['image_file']);
        $validated['is_active']  = $request->boolean('is_active');
        $validated['sort_order'] = (int) $request->input('sort_order', 0);

        HeroSlide::create($validated);

        return redirect()->route('admin.slides.index')->with('success', 'ເພີ່ມ Slide ສຳເລັດແລ້ວ');
    }

    public function show(HeroSlide $slide)
    {
        return view('admin.slides.show', compact('slide'));
    }

    public function edit(HeroSlide $slide)
    {
        return view('admin.slides.edit', compact('slide'));
    }

    public function update(Request $request, HeroSlide $slide)
    {
        $validated = $request->validate([
            'title_lo'      => 'required|string|max:300',
            'title_en'      => 'nullable|string|max:300',
            'title_zh'      => 'nullable|string|max:300',
            'tag_lo'        => 'nullable|string|max:100',
            'tag_en'        => 'nullable|string|max:100',
            'tag_zh'        => 'nullable|string|max:100',
            'subtitle_lo'   => 'nullable|string',
            'subtitle_en'   => 'nullable|string',
            'subtitle_zh'   => 'nullable|string',
            'image_file'    => 'nullable|image|max:4096',
            'image_url'     => 'nullable|string|max:500',
            'btn1_text_lo'  => 'nullable|string|max:80',
            'btn1_text_en'  => 'nullable|string|max:80',
            'btn1_text_zh'  => 'nullable|string|max:80',
            'btn1_url'      => 'nullable|string|max:500',
            'btn2_text_lo'  => 'nullable|string|max:80',
            'btn2_text_en'  => 'nullable|string|max:80',
            'btn2_text_zh'  => 'nullable|string|max:80',
            'btn2_url'      => 'nullable|string|max:500',
            'sort_order'    => 'nullable|integer|min:0|max:9999',
            'is_active'     => 'nullable|boolean',
        ]);

        if ($request->hasFile('image_file')) {
            $validated['image_url'] = '/storage/' . $request->file('image_file')->store('slides', 'public');
        } elseif (!empty($validated['image_url'])) {
            // keep submitted URL
        } else {
            // keep existing image
            $validated['image_url'] = $slide->image_url;
        }

        unset($validated['image_file']);
        $validated['is_active']  = $request->boolean('is_active');
        $validated['sort_order'] = (int) $request->input('sort_order', 0);

        $slide->update($validated);

        return redirect()->route('admin.slides.index')->with('success', 'ແກ້ໄຂ Slide ສຳເລັດແລ້ວ');
    }

    public function destroy(HeroSlide $slide)
    {
        $slide->delete();
        return redirect()->route('admin.slides.index')->with('success', 'ລຶບ Slide ສຳເລັດແລ້ວ');
    }
}
