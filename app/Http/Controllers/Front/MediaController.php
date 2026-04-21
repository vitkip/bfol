<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MediaItem;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $query = MediaItem::published()->with('category')->latest('published_at');

        if ($request->filled('type')) {
            $query->ofType($request->type);
        }
        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        return view('front.media.index', [
            'items'      => $query->paginate(12)->withQueryString(),
            'categories' => Category::active()->ofType('media')->orderBy('sort_order')->get(),
        ]);
    }
}
