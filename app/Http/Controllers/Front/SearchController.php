<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\News;
use App\Models\Page;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->string('q')->trim()->value();

        if (strlen($q) < 2) {
            return view('front.search', ['results' => collect(), 'q' => $q]);
        }

        $locale = app()->getLocale();
        $col    = "title_{$locale}";

        $news   = News::published()->where($col, 'like', "%{$q}%")->limit(10)->get()->map(fn($i) => ['type' => 'news',  'item' => $i]);
        $events = Event::where($col, 'like', "%{$q}%")->limit(10)->get()->map(fn($i) => ['type' => 'event', 'item' => $i]);
        $pages  = Page::published()->where($col, 'like', "%{$q}%")->limit(5)->get()->map(fn($i) => ['type' => 'page',  'item' => $i]);

        $results = $news->merge($events)->merge($pages);

        return view('front.search', compact('results', 'q'));
    }
}
