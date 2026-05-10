<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Event;
use App\Models\MonkExchangeProgram;
use App\Models\MouAgreement;
use App\Models\News;
use App\Models\PartnerOrganization;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 6 COUNT queries → 1 single DB query (batched subqueries)
        // Cast to array to avoid stdClass unserialization errors with file cache.
        $stats = Cache::remember('dashboard_stats', 120, fn() => (array) DB::selectOne('
            SELECT
              (SELECT COUNT(*) FROM news    WHERE status = "published") AS news_published,
              (SELECT COUNT(*) FROM events  WHERE status = "upcoming")  AS events_upcoming,
              (SELECT COUNT(*) FROM mou_agreements WHERE status = "active") AS mou_active,
              (SELECT COUNT(*) FROM partner_organizations WHERE status = "active") AS partners_active,
              (SELECT COUNT(*) FROM contact_messages WHERE is_read = 0) AS contacts_unread,
              (SELECT COUNT(*) FROM monk_exchange_programs WHERE status = "open") AS monk_open
        '));

        $recentNews = News::select('id','title_lo','title_en','status','category_id','created_at')
            ->with(['category:id,name_lo'])
            ->latest()
            ->limit(8)
            ->get()
            ->map(fn($n) => [
                'id'         => $n->id,
                'title'      => $n->title_lo ?: $n->title_en,
                'status'     => $n->status,
                'category'   => $n->category?->name_lo,
                'created_at' => $n->created_at?->toDateString(),
            ]);

        $recentContacts = ContactMessage::select('id','name','subject','created_at')
            ->where('is_read', false)
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn($c) => [
                'id'         => $c->id,
                'name'       => $c->name,
                'subject'    => $c->subject,
                'created_at' => $c->created_at?->format('d/m/Y H:i'),
            ]);

        return response()->json([
            'stats'           => (array) $stats,
            'recent_news'     => $recentNews,
            'recent_contacts' => $recentContacts,
        ]);
    }

    public function chart()
    {
        $data = Cache::remember('dashboard_chart', 300, function () {
            $newsMonthly = News::select(
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('COUNT(*) as total')
                )
                ->where('created_at', '>=', now()->subMonths(12))
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get();

            $eventsMonthly = Event::select(
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('COUNT(*) as total')
                )
                ->where('created_at', '>=', now()->subMonths(12))
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get();

            // 6 status counts → 2 GROUP BY queries instead of 6 separate counts
            $newsByStatus = News::select('status', DB::raw('COUNT(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status');

            $eventsByStatus = Event::select('status', DB::raw('COUNT(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status');

            return [
                'news_monthly'   => $newsMonthly,
                'events_monthly' => $eventsMonthly,
                'news_by_status' => [
                    ['name' => 'ເຜີຍແຜ່', 'value' => $newsByStatus['published'] ?? 0],
                    ['name' => 'ຮ່າງ',    'value' => $newsByStatus['draft']     ?? 0],
                    ['name' => 'ເກັບ',    'value' => $newsByStatus['archived']  ?? 0],
                ],
                'events_by_status' => [
                    ['name' => 'ກຳລັງຈະມາ',  'value' => $eventsByStatus['upcoming']  ?? 0],
                    ['name' => 'ດຳເນີນຢູ່', 'value' => $eventsByStatus['ongoing']   ?? 0],
                    ['name' => 'ສຳເລັດ',    'value' => $eventsByStatus['completed'] ?? 0],
                ],
            ];
        });

        return response()->json($data);
    }
}
