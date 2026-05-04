<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\AidProject;
use App\Models\CommitteeMember;
use App\Models\Document;
use App\Models\HeroSlide;
use App\Models\MonkExchangeProgram;
use App\Models\MouAgreement;
use App\Models\News;
use App\Models\PartnerOrganization;
use App\Models\SiteStatistic;

class HomeController extends Controller
{
    public function index()
    {
        $statistics = SiteStatistic::active()->get();

        $liveStats = [
            ['icon' => 'fas fa-users',             'value' => CommitteeMember::where('is_active', true)->count(), 'suffix' => '', 'lo' => 'ສະມາຊິກຄະນະກຳມະການ', 'en' => 'Committee Members',      'zh' => '委員會成員'],
            ['icon' => 'fas fa-globe',             'value' => PartnerOrganization::active()->count(),             'suffix' => '', 'lo' => 'ອົງການຄູ່ຮ່ວມ',         'en' => 'Partner Organisations',  'zh' => '合作機構'],
            ['icon' => 'fas fa-exchange-alt',      'value' => MonkExchangeProgram::count(),                      'suffix' => '', 'lo' => 'ໂຄງການແລກປ່ຽນ',         'en' => 'Exchange Programmes',    'zh' => '交流項目'],
            ['icon' => 'fas fa-file-contract',     'value' => MouAgreement::active()->count(),                   'suffix' => '', 'lo' => 'ບົດບັນທຶກຄວາມເຂົ້າໃຈ', 'en' => 'MOU Agreements',         'zh' => '諒解備忘錄'],
            ['icon' => 'fas fa-hand-holding-heart','value' => AidProject::active()->count(),                     'suffix' => '', 'lo' => 'ໂຄງການຊ່ວຍເຫຼືອ',       'en' => 'Aid Projects',           'zh' => '援助項目'],
            ['icon' => 'fas fa-folder-open',       'value' => Document::public()->count(),                       'suffix' => '', 'lo' => 'ເອກະສານ',               'en' => 'Documents',              'zh' => '文件'],
        ];

        return view('front.home.index', [
            'slides'      => HeroSlide::active()->get(),
            'statistics'  => $statistics,
            'liveStats'   => $liveStats,
            'latest_news' => News::published()->with('category')->latest('published_at')->limit(6)->get(),
            'partners'    => PartnerOrganization::active()->orderBy('sort_order')->limit(12)->get(),
        ]);
    }
}
