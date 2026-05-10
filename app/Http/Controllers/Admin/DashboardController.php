<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\News;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 6 COUNT queries → 1 batched query, cached 2 minutes
        // Cast to array to avoid stdClass unserialization errors with file cache.
        $stats = Cache::remember('dashboard_stats', 120, fn() => (array) DB::selectOne('
            SELECT
              (SELECT COUNT(*) FROM news    WHERE status = "published") AS news_count,
              (SELECT COUNT(*) FROM events  WHERE status = "upcoming")  AS events_upcoming,
              (SELECT COUNT(*) FROM mou_agreements WHERE status = "active") AS mou_active,
              (SELECT COUNT(*) FROM partner_organizations WHERE status = "active") AS partners_count,
              (SELECT COUNT(*) FROM contact_messages WHERE is_read = 0) AS unread_contacts,
              (SELECT COUNT(*) FROM monk_exchange_programs WHERE status = "open") AS monk_open
        '));

        $recentNews = News::select('id','title_lo','title_en','status','category_id','created_at')
            ->with(['category:id,name_lo'])
            ->latest()
            ->limit(8)
            ->get();

        $recentContacts = ContactMessage::select('id','name','subject','is_read','created_at')
            ->where('is_read', false)
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', array_merge((array) $stats, [
            'recent_news'     => $recentNews,
            'recent_contacts' => $recentContacts,
        ]));
    }
}
