<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Event;
use App\Models\MonkExchangeProgram;
use App\Models\MouAgreement;
use App\Models\News;
use App\Models\PartnerOrganization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        return response()->json([
            'stats' => [
                'news_published'  => News::where('status', 'published')->count(),
                'events_upcoming' => Event::where('status', 'upcoming')->count(),
                'mou_active'      => MouAgreement::where('status', 'active')->count(),
                'partners_active' => PartnerOrganization::where('status', 'active')->count(),
                'contacts_unread' => ContactMessage::where('is_read', false)->count(),
                'monk_open'       => MonkExchangeProgram::where('status', 'open')->count(),
            ],
            'recent_news'     => News::with('category')->latest()->limit(8)->get()->map(fn($n) => [
                'id'         => $n->id,
                'title'      => $n->title_lo ?: $n->title_en,
                'status'     => $n->status,
                'category'   => $n->category?->name_lo,
                'created_at' => $n->created_at?->toDateString(),
            ]),
            'recent_contacts' => ContactMessage::where('is_read', false)->latest()->limit(5)->get()->map(fn($c) => [
                'id'         => $c->id,
                'name'       => $c->name,
                'subject'    => $c->subject,
                'created_at' => $c->created_at?->format('d/m/Y H:i'),
            ]),
        ]);
    }

    public function chart()
    {
        // ຂ່າວລາຍເດືອນ ໃນ 12 ເດືອນທີ່ຜ່ານມາ
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

        return response()->json([
            'news_monthly'   => $newsMonthly,
            'events_monthly' => $eventsMonthly,
            'news_by_status' => [
                ['name' => 'ເຜີຍແຜ່', 'value' => News::where('status', 'published')->count()],
                ['name' => 'ຮ່າງ',    'value' => News::where('status', 'draft')->count()],
                ['name' => 'ເກັບ',    'value' => News::where('status', 'archived')->count()],
            ],
            'events_by_status' => [
                ['name' => 'ກຳລັງຈະມາ',  'value' => Event::where('status', 'upcoming')->count()],
                ['name' => 'ດຳເນີນຢູ່', 'value' => Event::where('status', 'ongoing')->count()],
                ['name' => 'ສຳເລັດ',    'value' => Event::where('status', 'completed')->count()],
            ],
        ]);
    }
}
