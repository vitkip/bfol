<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $query = Page::latest('sort_order')->latest();

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(fn($s) => $s->where('title_lo', 'like', "%{$q}%")
                                      ->orWhere('title_en', 'like', "%{$q}%")
                                      ->orWhere('slug', 'like', "%{$q}%"));
        }

        if ($request->filled('status')) {
            $query->where('is_published', $request->status === 'published');
        }

        $pages = $query->paginate(15)->withQueryString();

        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        $parentPages = Page::select('slug', 'title_lo')->orderBy('title_lo')->get();
        return view('admin.pages.create', compact('parentPages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_lo'         => 'required|string|max:300',
            'title_en'         => 'nullable|string|max:300',
            'title_zh'         => 'nullable|string|max:300',
            'slug'             => ['nullable', 'string', 'max:150', 'unique:pages,slug', 'regex:/^[a-z0-9\-]+$/'],
            'content_lo'       => 'nullable|string',
            'content_en'       => 'nullable|string',
            'content_zh'       => 'nullable|string',
            'meta_title_lo'    => 'nullable|string|max:200',
            'meta_title_en'    => 'nullable|string|max:200',
            'meta_title_zh'    => 'nullable|string|max:200',
            'meta_description' => 'nullable|string|max:500',
            'thumbnail'        => 'nullable|image|max:2048',
            'parent_slug'      => 'nullable|string|exists:pages,slug',
            'sort_order'       => 'nullable|integer|min:0|max:9999',
            'is_published'     => 'nullable|boolean',
        ]);

        $validated['slug']         = $this->uniqueSlug($request->slug, $request->title_lo);
        $validated['is_published'] = $request->boolean('is_published');
        $validated['sort_order']   = (int) $request->input('sort_order', 0);
        $validated['author_id']    = auth()->id();

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('pages/thumbnails', 'public');
        }

        Page::create($validated);

        return redirect()->route('admin.pages.index')->with('success', 'ສ້າງໜ້າຂໍ້ມູນສຳເລັດແລ້ວ');
    }

    public function show(Page $page)
    {
        return view('admin.pages.show', compact('page'));
    }

    public function edit(Page $page)
    {
        $parentPages = Page::where('slug', '!=', $page->slug)
                           ->select('slug', 'title_lo')
                           ->orderBy('title_lo')
                           ->get();

        return view('admin.pages.edit', compact('page', 'parentPages'));
    }

    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title_lo'         => 'required|string|max:300',
            'title_en'         => 'nullable|string|max:300',
            'title_zh'         => 'nullable|string|max:300',
            'slug'             => ['nullable', 'string', 'max:150', Rule::unique('pages','slug')->ignore($page->id), 'regex:/^[a-z0-9\-]+$/'],
            'content_lo'       => 'nullable|string',
            'content_en'       => 'nullable|string',
            'content_zh'       => 'nullable|string',
            'meta_title_lo'    => 'nullable|string|max:200',
            'meta_title_en'    => 'nullable|string|max:200',
            'meta_title_zh'    => 'nullable|string|max:200',
            'meta_description' => 'nullable|string|max:500',
            'thumbnail'        => 'nullable|image|max:2048',
            'parent_slug'      => ['nullable', 'string', Rule::exists('pages','slug'), Rule::notIn([$page->slug])],
            'sort_order'       => 'nullable|integer|min:0|max:9999',
            'is_published'     => 'nullable|boolean',
        ], [
            'parent_slug.not_in' => 'ບໍ່ສາມາດຕັ້ງໜ້ານີ້ເປັນໜ້າແມ່ຂອງຕົວເອງໄດ້',
        ]);

        // Keep existing slug if input is empty
        $validated['slug']         = $request->filled('slug') ? $validated['slug'] : $page->slug;
        $validated['is_published'] = $request->boolean('is_published');
        $validated['sort_order']   = (int) $request->input('sort_order', 0);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('pages/thumbnails', 'public');
        } else {
            unset($validated['thumbnail']);
        }

        $page->update($validated);

        return redirect()->route('admin.pages.index')->with('success', 'ແກ້ໄຂໜ້າຂໍ້ມູນສຳເລັດແລ້ວ');
    }

    public function destroy(Page $page)
    {
        $children = Page::where('parent_slug', $page->slug)->count();
        if ($children > 0) {
            return redirect()->back()
                ->with('error', "ບໍ່ສາມາດລຶບໄດ້: ມີໜ້າຍ່ອຍ {$children} ໜ້າທີ່ອ້າງອີງໜ້ານີ້ຢູ່");
        }

        $page->delete();

        return redirect()->route('admin.pages.index')->with('success', 'ລຶບໜ້າຂໍ້ມູນສຳເລັດແລ້ວ');
    }

    private function uniqueSlug(?string $input, string $fallback): string
    {
        $base = $input ? Str::slug($input) : (Str::slug($fallback) ?: 'page');
        if (!$base) $base = 'page';

        $slug = $base;
        $i    = 1;
        while (Page::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}
