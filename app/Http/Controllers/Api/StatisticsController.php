<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AidProject;
use App\Models\CommitteeMember;
use App\Models\Event;
use App\Models\MonkExchangeProgram;
use App\Models\MouAgreement;
use App\Models\News;
use App\Models\PartnerOrganization;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index()
    {
        return response()->json([
            'totals' => [
                'news'      => News::count(),
                'events'    => Event::count(),
                'partners'  => PartnerOrganization::count(),
                'mou'       => MouAgreement::count(),
                'committee' => CommitteeMember::count(),
                'monk'      => MonkExchangeProgram::count(),
                'aid'       => AidProject::count(),
            ],
            'news_monthly_12m' => $this->monthlyCount(News::class),
            'events_monthly_12m' => $this->monthlyCount(Event::class),
            'partners_by_type' => PartnerOrganization::select('type', DB::raw('count(*) as total'))
                ->whereNotNull('type')
                ->groupBy('type')
                ->get(),
            'mou_by_status' => MouAgreement::select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->get(),
            'aid_by_status' => AidProject::select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->get(),
            'monk_by_year' => MonkExchangeProgram::select('year', DB::raw('count(*) as total'))
                ->whereNotNull('year')
                ->groupBy('year')
                ->orderBy('year')
                ->get(),
        ]);
    }

    private function monthlyCount(string $model): array
    {
        return $model::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->toArray();
    }
}
