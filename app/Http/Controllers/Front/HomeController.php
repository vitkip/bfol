<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use App\Models\News;
use App\Models\PartnerOrganization;
use App\Models\SiteStatistic;

class HomeController extends Controller
{
    public function index()
    {
        return view('front.home', [
            'slides'      => HeroSlide::active()->get(),
            'statistics'  => SiteStatistic::active()->get(),
            'latest_news' => News::published()->with('category')->latest('published_at')->limit(6)->get(),
            'partners'    => PartnerOrganization::active()->orderBy('sort_order')->limit(12)->get(),
        ]);
    }
}
