<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Event;
use App\Models\MouAgreement;
use App\Models\MonkExchangeProgram;
use App\Models\News;
use App\Models\PartnerOrganization;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'news_count'       => News::where('status', 'published')->count(),
            'events_upcoming'  => Event::where('status', 'upcoming')->count(),
            'mou_active'       => MouAgreement::where('status', 'active')->count(),
            'partners_count'   => PartnerOrganization::where('status', 'active')->count(),
            'unread_contacts'  => ContactMessage::where('is_read', false)->count(),
            'monk_open'        => MonkExchangeProgram::where('status', 'open')->count(),
            'recent_news'      => News::with('category')->latest()->limit(8)->get(),
            'recent_contacts'  => ContactMessage::where('is_read', false)->latest('created_at')->limit(5)->get(),
        ]);
    }
}
