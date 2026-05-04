<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\AidProject;
use App\Models\Event;
use App\Models\MonkExchangeProgram;
use App\Models\MouAgreement;
use App\Models\News;
use App\Models\Page;
use App\Models\PartnerOrganization;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->string('q')->trim()->value();

        if (strlen($q) < 2) {
            return view('front.search', ['results' => collect(), 'q' => $q, 'total' => 0]);
        }

        $L    = app()->getLocale();
        $like = "%{$q}%";

        // Search multiple columns at once
        $search = fn($builder, array $cols) => $builder->where(function ($w) use ($cols, $like) {
            foreach ($cols as $col) {
                $w->orWhere($col, 'like', $like);
            }
        });

        $news = $search(
            News::published()->with('category'),
            ["title_{$L}", "excerpt_{$L}", "content_{$L}"]
        )->latest('published_at')->limit(8)->get()
         ->map(fn($i) => ['type' => 'news', 'item' => $i]);

        $events = $search(
            Event::query(),
            ["title_{$L}", "description_{$L}"]
        )->latest()->limit(5)->get()
         ->map(fn($i) => ['type' => 'event', 'item' => $i]);

        $pages = $search(
            Page::published(),
            ["title_{$L}", "content_{$L}"]
        )->limit(4)->get()
         ->map(fn($i) => ['type' => 'page', 'item' => $i]);

        $partners = $search(
            PartnerOrganization::active(),
            ["name_{$L}", "description_{$L}", 'acronym']
        )->limit(6)->get()
         ->map(fn($i) => ['type' => 'partner', 'item' => $i]);

        $mous = $search(
            MouAgreement::query(),
            ["title_{$L}", "description_{$L}", "scope_{$L}"]
        )->latest('signed_date')->limit(5)->get()
         ->map(fn($i) => ['type' => 'mou', 'item' => $i]);

        $projects = $search(
            AidProject::query(),
            ["title_{$L}", "description_{$L}"]
        )->latest('start_date')->limit(5)->get()
         ->map(fn($i) => ['type' => 'aid', 'item' => $i]);

        $programs = $search(
            MonkExchangeProgram::query(),
            ["title_{$L}", "description_{$L}", "requirements_{$L}"]
        )->latest('year')->limit(5)->get()
         ->map(fn($i) => ['type' => 'program', 'item' => $i]);

        $results = $news
            ->merge($events)
            ->merge($pages)
            ->merge($partners)
            ->merge($mous)
            ->merge($projects)
            ->merge($programs);

        $total = $results->count();

        return view('front.search', compact('results', 'q', 'total'));
    }
}
