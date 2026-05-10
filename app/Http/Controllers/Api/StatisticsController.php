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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index()
    {
        // Cache stats for 10 minutes — aggregate queries are expensive
        $data = Cache::remember('api_statistics', 600, function () {
            // 7 COUNT queries → 1 batched query
            $totals = DB::selectOne('
                SELECT
                  (SELECT COUNT(*) FROM news) AS news,
                  (SELECT COUNT(*) FROM events) AS events,
                  (SELECT COUNT(*) FROM partner_organizations) AS partners,
                  (SELECT COUNT(*) FROM mou_agreements) AS mou,
                  (SELECT COUNT(*) FROM committee_members) AS committee,
                  (SELECT COUNT(*) FROM monk_exchange_programs) AS monk,
                  (SELECT COUNT(*) FROM aid_projects) AS aid
            ');

            return [
                'totals'             => (array) $totals,
                'news_monthly_12m'   => $this->monthlyCount(News::class),
                'events_monthly_12m' => $this->monthlyCount(Event::class),
                'partners_by_type'   => PartnerOrganization::select('type', DB::raw('count(*) as total'))
                    ->whereNotNull('type')->groupBy('type')->get()->toArray(),
                'mou_by_status'      => MouAgreement::select('status', DB::raw('count(*) as total'))
                    ->groupBy('status')->get()->toArray(),
                'aid_by_status'      => AidProject::select('status', DB::raw('count(*) as total'))
                    ->groupBy('status')->get()->toArray(),
                'monk_by_year'       => MonkExchangeProgram::select('year', DB::raw('count(*) as total'))
                    ->whereNotNull('year')->groupBy('year')->orderBy('year')->get()->toArray(),
            ];
        });

        return response()->json($data);
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
