<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use App\Models\News;
use App\Models\PartnerOrganization;
use App\Models\SiteStatistic;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // 6 COUNT queries → 1 batched query, cached 10 minutes
        // Cast to array: DB::selectOne() returns stdClass which can fail to
        // unserialize from file cache on subsequent requests.
        $counts = Cache::remember('home_live_stats', 600, fn() => (array) DB::selectOne('
            SELECT
              (SELECT COUNT(*) FROM committee_members WHERE is_active = 1) AS committee,
              (SELECT COUNT(*) FROM partner_organizations WHERE status = "active") AS partners,
              (SELECT COUNT(*) FROM monk_exchange_programs) AS monk_programs,
              (SELECT COUNT(*) FROM mou_agreements WHERE status = "active") AS mou,
              (SELECT COUNT(*) FROM aid_projects WHERE status = "active") AS aid_projects,
              (SELECT COUNT(*) FROM documents WHERE is_public = 1 AND published_at <= NOW()) AS documents
        '));

        $liveStats = [
            ['icon' => 'fas fa-users',             'value' => $counts['committee'],    'suffix' => '', 'lo' => 'ສະມາຊິກຄະນະກຳມະການ', 'en' => 'Committee Members',     'zh' => '委員會成員'],
            ['icon' => 'fas fa-globe',             'value' => $counts['partners'],     'suffix' => '', 'lo' => 'ອົງການຄູ່ຮ່ວມ',         'en' => 'Partner Organisations', 'zh' => '合作機構'],
            ['icon' => 'fas fa-exchange-alt',      'value' => $counts['monk_programs'],'suffix' => '', 'lo' => 'ໂຄງການແລກປ່ຽນ',         'en' => 'Exchange Programmes',   'zh' => '交流項目'],
            ['icon' => 'fas fa-file-contract',     'value' => $counts['mou'],          'suffix' => '', 'lo' => 'ບົດບັນທຶກຄວາມເຂົ້າໃຈ', 'en' => 'MOU Agreements',        'zh' => '諒解備忘錄'],
            ['icon' => 'fas fa-hand-holding-heart','value' => $counts['aid_projects'], 'suffix' => '', 'lo' => 'ໂຄງການຊ່ວຍເຫຼືອ',       'en' => 'Aid Projects',          'zh' => '援助項目'],
            ['icon' => 'fas fa-folder-open',       'value' => $counts['documents'],    'suffix' => '', 'lo' => 'ເອກະສານ',               'en' => 'Documents',             'zh' => '文件'],
        ];

        // Cache as plain arrays to avoid Eloquent Collection unserialize errors,
        // then hydrate back to model instances so blade can call ->trans() and Carbon methods.
        $slides = HeroSlide::hydrate(
            Cache::remember('home_slides', 1800, fn() =>
                HeroSlide::active()->get(['id','tag_lo','title_lo','subtitle_lo','image_url','btn1_text_lo','btn1_url','btn2_text_lo','btn2_url'])->toArray()
            )
        );

        $statistics = SiteStatistic::hydrate(
            Cache::remember('home_statistics', 1800, fn() =>
                SiteStatistic::active()->get()->toArray()
            )
        );

        $partners = PartnerOrganization::hydrate(
            Cache::remember('home_partners', 1800, fn() =>
                PartnerOrganization::active()->orderBy('sort_order')->limit(12)->get(['id','name_lo','name_en','acronym','logo_url','sort_order'])->toArray()
            )
        );

        // Latest news: cache raw array, manually hydrate News + Category so
        // ->trans() and ->published_at->translatedFormat() work in the blade.
        $latestNewsArr = Cache::remember('home_latest_news', 300, fn() =>
            News::published()
                ->select('id','title_lo','title_en','title_zh','excerpt_lo','excerpt_en','thumbnail','category_id','slug','published_at','is_urgent','is_featured')
                ->with(['category:id,name_lo'])
                ->latest('published_at')
                ->limit(6)
                ->get()
                ->toArray()
        );
        $latestNews = collect($latestNewsArr)->map(function ($item) {
            $catData = $item['category'] ?? null;
            unset($item['category']);
            $news = (new News)->newFromBuilder($item);
            if ($catData) {
                $news->setRelation('category', (new \App\Models\Category)->newFromBuilder($catData));
            }
            return $news;
        });

        return view('front.home.index', [
            'slides'      => $slides,
            'statistics'  => $statistics,
            'liveStats'   => $liveStats,
            'latest_news' => $latestNews,
            'partners'    => $partners,
        ]);
    }
}
