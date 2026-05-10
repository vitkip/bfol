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
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->string('q')->trim()->value();

        if (strlen($q) < 2) {
            return view('front.search', ['results' => collect(), 'q' => $q, 'total' => 0]);
        }

        // Use FULLTEXT MATCH AGAINST when available (fast) with LIKE fallback.
        // FULLTEXT is ~10-100x faster than LIKE on large tables.
        $news = News::published()
            ->select('id','title_lo','title_en','excerpt_lo','excerpt_en','thumbnail','slug','category_id','published_at')
            ->with(['category:id,name_lo'])
            ->where(fn($w) => $w
                ->whereRaw('MATCH(title_lo, title_en, excerpt_lo, excerpt_en) AGAINST(? IN BOOLEAN MODE)', ["+{$q}*"])
                ->orWhere('title_lo', 'like', "%{$q}%")
                ->orWhere('title_en', 'like', "%{$q}%")
            )
            ->latest('published_at')->limit(8)->get()
            ->map(fn($i) => ['type' => 'news', 'item' => $i]);

        $events = Event::select('id','title_lo','title_en','thumbnail','start_date','location_lo','slug','status')
            ->where(fn($w) => $w
                ->whereRaw('MATCH(title_lo, title_en, description_lo, description_en) AGAINST(? IN BOOLEAN MODE)', ["+{$q}*"])
                ->orWhere('title_lo', 'like', "%{$q}%")
                ->orWhere('title_en', 'like', "%{$q}%")
            )
            ->latest()->limit(5)->get()
            ->map(fn($i) => ['type' => 'event', 'item' => $i]);

        $pages = Page::published()
            ->select('id','title_lo','title_en','slug','thumbnail')
            ->where(fn($w) => $w
                ->whereRaw('MATCH(title_lo, title_en, content_lo, content_en) AGAINST(? IN BOOLEAN MODE)', ["+{$q}*"])
                ->orWhere('title_lo', 'like', "%{$q}%")
                ->orWhere('title_en', 'like', "%{$q}%")
            )
            ->limit(4)->get()
            ->map(fn($i) => ['type' => 'page', 'item' => $i]);

        $partners = PartnerOrganization::active()
            ->select('id','name_lo','name_en','acronym','logo_url','country_name_lo')
            ->where(fn($w) => $w
                ->whereRaw('MATCH(name_lo, name_en, description_lo, description_en, acronym) AGAINST(? IN BOOLEAN MODE)', ["+{$q}*"])
                ->orWhere('name_lo', 'like', "%{$q}%")
                ->orWhere('name_en', 'like', "%{$q}%")
                ->orWhere('acronym', 'like', "%{$q}%")
            )
            ->limit(6)->get()
            ->map(fn($i) => ['type' => 'partner', 'item' => $i]);

        $mous = MouAgreement::select('id','title_lo','title_en','signed_date','status')
            ->where(fn($w) => $w
                ->whereRaw('MATCH(title_lo, title_en, description_lo, description_en) AGAINST(? IN BOOLEAN MODE)', ["+{$q}*"])
                ->orWhere('title_lo', 'like', "%{$q}%")
                ->orWhere('title_en', 'like', "%{$q}%")
            )
            ->latest('signed_date')->limit(5)->get()
            ->map(fn($i) => ['type' => 'mou', 'item' => $i]);

        $projects = AidProject::select('id','title_lo','title_en','start_date','status')
            ->where(fn($w) => $w
                ->whereRaw('MATCH(title_lo, title_en, description_lo, description_en) AGAINST(? IN BOOLEAN MODE)', ["+{$q}*"])
                ->orWhere('title_lo', 'like', "%{$q}%")
                ->orWhere('title_en', 'like', "%{$q}%")
            )
            ->latest('start_date')->limit(5)->get()
            ->map(fn($i) => ['type' => 'aid', 'item' => $i]);

        $programs = MonkExchangeProgram::select('id','title_lo','title_en','year','status')
            ->where(fn($w) => $w
                ->whereRaw('MATCH(title_lo, title_en, description_lo, description_en) AGAINST(? IN BOOLEAN MODE)', ["+{$q}*"])
                ->orWhere('title_lo', 'like', "%{$q}%")
                ->orWhere('title_en', 'like', "%{$q}%")
            )
            ->latest('year')->limit(5)->get()
            ->map(fn($i) => ['type' => 'program', 'item' => $i]);

        $results = $news->merge($events)->merge($pages)->merge($partners)
                        ->merge($mous)->merge($projects)->merge($programs);

        $total = $results->count();

        return view('front.search', compact('results', 'q', 'total'));
    }
}
