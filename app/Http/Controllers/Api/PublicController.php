<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Event;
use App\Models\HeroSlide;
use App\Models\NavigationMenu;
use App\Models\News;
use App\Models\Page;
use App\Models\PartnerOrganization;
use App\Models\SiteSetting;
use App\Models\SiteStatistic;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function slides()
    {
        $slides = HeroSlide::active()->get()->map(fn($s) => [
            'id'           => $s->id,
            'tag'          => $s->tag_lo,
            'title'        => $s->title_lo,
            'subtitle'     => $s->subtitle_lo,
            'image_url'    => $s->image_url,
            'btn1_text'    => $s->btn1_text_lo,
            'btn1_url'     => $s->btn1_url,
            'btn2_text'    => $s->btn2_text_lo,
            'btn2_url'     => $s->btn2_url,
        ]);

        return response()->json($slides);
    }

    public function stats()
    {
        $stats = SiteStatistic::active()->get()->map(fn($s) => [
            'id'     => $s->id,
            'label'  => $s->label_lo,
            'value'  => (int) $s->value,
            'icon'   => $s->icon,
            'suffix' => $s->suffix,
        ]);

        return response()->json($stats);
    }

    public function news(Request $request)
    {
        $query = News::with(['category'])
            ->published()
            ->latest('published_at');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('featured')) {
            $query->featured();
        }

        $news = $query->paginate($request->get('per_page', 6));

        return response()->json($news->through(fn($n) => [
            'id'           => $n->id,
            'title_lo'     => $n->title_lo,
            'title_en'     => $n->title_en,
            'title_zh'     => $n->title_zh,
            'excerpt_lo'   => $n->excerpt_lo,
            'excerpt_en'   => $n->excerpt_en,
            'excerpt_zh'   => $n->excerpt_zh,
            'slug'         => $n->slug,
            'thumbnail'    => $n->thumbnail ? asset('storage/' . $n->thumbnail) : null,
            'category'     => $n->category?->name_lo,
            'is_featured'  => $n->is_featured,
            'is_urgent'    => $n->is_urgent,
            'published_at' => $n->published_at?->format('d/m/Y'),
            'published_at_raw' => $n->published_at?->toDateString(),
        ]));
    }

    public function events(Request $request)
    {
        $query = Event::with('category')
            ->where('status', '!=', 'cancelled')
            ->latest('start_date');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $events = $query->paginate($request->get('per_page', 6));

        return response()->json($events->through(fn($e) => [
            'id'           => $e->id,
            'title_lo'     => $e->title_lo,
            'title_en'     => $e->title_en,
            'title_zh'     => $e->title_zh,
            'slug'         => $e->slug,
            'thumbnail'    => $e->thumbnail ? asset('storage/' . $e->thumbnail) : null,
            'location_lo'  => $e->location_lo,
            'location_en'  => $e->location_en,
            'country'      => $e->country,
            'start_date'   => $e->start_date?->format('d/m/Y'),
            'end_date'     => $e->end_date?->format('d/m/Y'),
            'status'       => $e->status,
            'is_featured'  => $e->is_featured,
            'category'     => $e->category?->name_lo,
        ]));
    }

    public function partners()
    {
        $partners = PartnerOrganization::active()
            ->orderBy('sort_order')
            ->get()
            ->map(fn($p) => [
                'id'       => $p->id,
                'name'     => $p->name_lo ?: $p->name_en,
                'acronym'  => $p->acronym,
                'logo_url' => $p->logo_url,
                'website'  => $p->website_url,
                'country'  => $p->country_name_lo ?: $p->country_name_en,
            ]);

        return response()->json($partners);
    }

    public function home()
    {
        return response()->json([
            'slides'          => $this->slides()->original,
            'stats'           => $this->stats()->original,
            'featured_news'   => News::with('category')->published()->featured()->latest('published_at')->limit(4)->get()->map(fn($n) => [
                'id'          => $n->id,
                'title_lo'    => $n->title_lo,
                'title_en'    => $n->title_en,
                'title_zh'    => $n->title_zh,
                'excerpt_lo'  => $n->excerpt_lo,
                'excerpt_en'  => $n->excerpt_en,
                'excerpt_zh'  => $n->excerpt_zh,
                'thumbnail'   => $n->thumbnail ? asset('storage/' . $n->thumbnail) : null,
                'category'    => $n->category?->name_lo,
                'published_at'=> $n->published_at?->format('d/m/Y'),
                'is_urgent'   => $n->is_urgent,
                'slug'        => $n->slug,
            ]),
            'latest_news'     => News::with('category')->published()->latest('published_at')->limit(4)->get()->map(fn($n) => [
                'id'          => $n->id,
                'title_lo'    => $n->title_lo,
                'title_en'    => $n->title_en,
                'title_zh'    => $n->title_zh,
                'thumbnail'   => $n->thumbnail ? asset('storage/' . $n->thumbnail) : null,
                'category'    => $n->category?->name_lo,
                'published_at'=> $n->published_at?->format('d/m/Y'),
                'slug'        => $n->slug,
            ]),
            'upcoming_events' => Event::where('status', 'upcoming')
                ->latest('start_date')->limit(3)->get()->map(fn($e) => [
                    'id'         => $e->id,
                    'title_lo'   => $e->title_lo,
                    'title_en'   => $e->title_en,
                    'title_zh'   => $e->title_zh,
                    'thumbnail'  => $e->thumbnail ? asset('storage/' . $e->thumbnail) : null,
                    'start_date' => $e->start_date?->format('d/m/Y'),
                    'location_lo'=> $e->location_lo,
                    'location_en'=> $e->location_en,
                    'slug'       => $e->slug,
                ]),
            'partners'        => PartnerOrganization::active()->orderBy('sort_order')->get()->map(fn($p) => [
                'id'      => $p->id,
                'name'    => $p->name_lo ?: $p->name_en,
                'acronym' => $p->acronym,
                'logo_url'=> $p->logo_url,
            ]),
        ]);
    }

    public function storeContact(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:150',
            'email'   => 'required|email|max:150',
            'phone'   => 'nullable|string|max:30',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:5000',
        ]);

        $data['ip_address'] = $request->ip();
        $data['language']   = 'lo';

        ContactMessage::create($data);

        return response()->json(['message' => 'ສົ່ງຂໍ້ຄວາມສຳເລັດ'], 201);
    }

    public function settings()
    {
        $rows = SiteSetting::all(['key', 'value']);
        $map  = $rows->pluck('value', 'key');

        return response()->json([
            'site_name_lo'   => $map['site_name_lo']   ?? 'ອົງສ · BFOL',
            'site_name_en'   => $map['site_name_en']   ?? 'BFOL',
            'site_name_zh'   => $map['site_name_zh']   ?? '',
            'logo_url'       => $map['logo_url']        ?? null,
            'favicon_url'    => $map['favicon_url']     ?? null,
            'site_phone'     => $map['site_phone']      ?? '',
            'site_email'     => $map['site_email']      ?? '',
            'site_address_lo'=> $map['site_address_lo'] ?? '',
            'office_hours_lo'=> $map['office_hours_lo'] ?? '',
            'site_facebook'  => $map['site_facebook']   ?? '',
            'site_youtube'   => $map['site_youtube']    ?? '',
            'site_line'      => $map['site_line']       ?? '',
            'site_wechat'    => $map['site_wechat']     ?? '',
        ]);
    }

    public function newsDetail(string $slug)
    {
        $news = News::with('category')
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        $news->increment('view_count');

        return response()->json([
            'id'           => $news->id,
            'slug'         => $news->slug,
            'title_lo'     => $news->title_lo,
            'title_en'     => $news->title_en,
            'title_zh'     => $news->title_zh,
            'content_lo'   => $news->content_lo,
            'content_en'   => $news->content_en,
            'content_zh'   => $news->content_zh,
            'excerpt_lo'   => $news->excerpt_lo,
            'excerpt_en'   => $news->excerpt_en,
            'excerpt_zh'   => $news->excerpt_zh,
            'thumbnail'    => $news->thumbnail ? asset('storage/' . $news->thumbnail) : null,
            'category'     => $news->category?->name_lo,
            'is_featured'  => $news->is_featured,
            'is_urgent'    => $news->is_urgent,
            'view_count'   => $news->view_count,
            'published_at' => $news->published_at?->format('d/m/Y'),
        ]);
    }

    public function page(string $slug)
    {
        $page = Page::where('slug', $slug)->published()->firstOrFail();

        return response()->json([
            'id'         => $page->id,
            'slug'       => $page->slug,
            'title'      => $page->title_lo ?: $page->title_en,
            'title_lo'   => $page->title_lo,
            'title_en'   => $page->title_en,
            'title_zh'   => $page->title_zh,
            'content'    => $page->content_lo ?: $page->content_en,
            'content_lo' => $page->content_lo,
            'content_en' => $page->content_en,
            'thumbnail'  => $page->thumbnail ? asset('storage/' . $page->thumbnail) : null,
        ]);
    }

    public function menu()
    {
        $menus = NavigationMenu::with(['children' => fn($q) => $q->active()->orderBy('sort_order')])
            ->active()
            ->topLevel()
            ->orderBy('sort_order')
            ->get();

        $format = fn($item) => [
            'id'       => $item->id,
            'label_lo' => $item->label_lo,
            'label_en' => $item->label_en,
            'label_zh' => $item->label_zh ?? null,
            'url'      => $item->url,
            'target'   => $item->target,
            'icon'     => $item->icon,
        ];

        return response()->json($menus->map(fn($m) => array_merge($format($m), [
            'items' => $m->children->map($format)->values(),
        ])));
    }
}
