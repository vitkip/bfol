<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\News;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::with(['category', 'author'])->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('title_lo', 'like', "%$s%")
                  ->orWhere('title_en', 'like', "%$s%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $news       = $query->paginate(20)->withQueryString();
        $categories = Category::active()->ofType('news')->orderBy('name_lo')->get();

        $counts = [
            'all'       => News::count(),
            'published' => News::where('status', 'published')->count(),
            'draft'     => News::where('status', 'draft')->count(),
            'archived'  => News::where('status', 'archived')->count(),
        ];

        return view('admin.news.index', compact('news', 'categories', 'counts'));
    }

    public function create()
    {
        $news       = new News();
        $categories = Category::active()->ofType('news')->orderBy('name_lo')->get();
        $tags       = Tag::orderBy('name_lo')->get();

        return view('admin.news.form', compact('news', 'categories', 'tags'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title_lo'     => ['required', 'string', 'max:300'],
            'title_en'     => ['nullable', 'string', 'max:300'],
            'title_zh'     => ['nullable', 'string', 'max:300'],
            'excerpt_lo'   => ['nullable', 'string', 'max:1000'],
            'excerpt_en'   => ['nullable', 'string', 'max:1000'],
            'excerpt_zh'   => ['nullable', 'string', 'max:1000'],
            'content_lo'   => ['required', 'string'],
            'content_en'   => ['nullable', 'string'],
            'content_zh'   => ['nullable', 'string'],
            'category_id'  => ['nullable', 'exists:categories,id'],
            'status'       => ['required', Rule::in(['draft', 'published', 'archived'])],
            'is_featured'  => ['nullable', 'boolean'],
            'is_urgent'    => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'thumbnail'    => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'tag_ids'      => ['nullable', 'array'],
            'tag_ids.*'    => ['exists:tags,id'],
        ]);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('news/thumbnails', 'public');
        }

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_urgent']   = $request->boolean('is_urgent');
        $data['author_id']   = auth()->id();

        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $news = News::create($data);
        $news->tags()->sync($request->input('tag_ids', []));

        return redirect()->route('admin.news.index')->with('success', 'ສ້າງຂ່າວສໍາເລັດແລ້ວ');
    }

    public function show(News $news)
    {
        return redirect()->route('admin.news.edit', $news);
    }

    public function edit(News $news)
    {
        $categories = Category::active()->ofType('news')->orderBy('name_lo')->get();
        $tags       = Tag::orderBy('name_lo')->get();

        return view('admin.news.form', compact('news', 'categories', 'tags'));
    }

    public function update(Request $request, News $news)
    {
        $data = $request->validate([
            'title_lo'     => ['required', 'string', 'max:300'],
            'title_en'     => ['nullable', 'string', 'max:300'],
            'title_zh'     => ['nullable', 'string', 'max:300'],
            'excerpt_lo'   => ['nullable', 'string', 'max:1000'],
            'excerpt_en'   => ['nullable', 'string', 'max:1000'],
            'excerpt_zh'   => ['nullable', 'string', 'max:1000'],
            'content_lo'   => ['required', 'string'],
            'content_en'   => ['nullable', 'string'],
            'content_zh'   => ['nullable', 'string'],
            'category_id'  => ['nullable', 'exists:categories,id'],
            'status'       => ['required', Rule::in(['draft', 'published', 'archived'])],
            'is_featured'  => ['nullable', 'boolean'],
            'is_urgent'    => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'thumbnail'    => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'tag_ids'      => ['nullable', 'array'],
            'tag_ids.*'    => ['exists:tags,id'],
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($news->thumbnail) {
                Storage::disk('public')->delete($news->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('news/thumbnails', 'public');
        } else {
            unset($data['thumbnail']);
        }

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_urgent']   = $request->boolean('is_urgent');

        if ($data['status'] === 'published' && ! $news->published_at && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $news->update($data);
        $news->tags()->sync($request->input('tag_ids', []));

        return redirect()->route('admin.news.index')->with('success', 'ອັບເດດຂ່າວສໍາເລັດແລ້ວ');
    }

    public function destroy(News $news)
    {
        if ($news->thumbnail) {
            Storage::disk('public')->delete($news->thumbnail);
        }

        $news->delete();

        return redirect()->route('admin.news.index')->with('success', 'ລົບຂ່າວສໍາເລັດແລ້ວ');
    }
}
