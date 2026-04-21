<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) SiteSetting::get('events_per_page', 9);

        $query = Event::with('category')->orderBy('start_date', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        return view('front.events.index', [
            'events'     => $query->paginate($perPage)->withQueryString(),
            'categories' => Category::active()->ofType('event')->orderBy('sort_order')->get(),
        ]);
    }

    public function show(string $slug)
    {
        $item = Event::where('slug', $slug)->with(['category', 'tags'])->firstOrFail();
        $item->increment('view_count');

        return view('front.events.show', compact('item'));
    }
}
