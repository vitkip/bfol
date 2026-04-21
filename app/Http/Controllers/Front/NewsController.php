<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\News;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) SiteSetting::get('news_per_page', 10);

        $query = News::published()->with('category')->latest('published_at');

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        return view('front.news.index', [
            'news'       => $query->paginate($perPage)->withQueryString(),
            'categories' => Category::active()->ofType('news')->orderBy('sort_order')->get(),
        ]);
    }

    public function show(string $slug)
    {
        $item = News::published()->where('slug', $slug)->with(['category', 'tags', 'author'])->firstOrFail();
        $item->increment('view_count');

        $related = News::published()
            ->where('id', '!=', $item->id)
            ->where('category_id', $item->category_id)
            ->latest('published_at')
            ->limit(4)
            ->get();

        return view('front.news.show', compact('item', 'related'));
    }
}
