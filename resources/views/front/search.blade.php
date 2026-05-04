@extends('front.layouts.app')

@php
  $L = app()->getLocale();
  $t = fn($lo,$en,$zh) => match($L){'zh'=>$zh,'en'=>$en,default=>$lo};

  // Type config: label, icon, badge colours, url resolver
  $typeConfig = [
    'news'    => ['label'=>$t('ຂ່າວ','News','新聞'),             'icon'=>'fas fa-newspaper',          'bg'=>'bg-blue-100',    'text'=>'text-blue-700'],
    'event'   => ['label'=>$t('ກິດຈະກຳ','Events','活動'),         'icon'=>'far fa-calendar-alt',        'bg'=>'bg-violet-100',  'text'=>'text-violet-700'],
    'page'    => ['label'=>$t('ໜ້າ','Page','頁面'),               'icon'=>'fas fa-file-signature',      'bg'=>'bg-emerald-100', 'text'=>'text-emerald-700'],
    'partner' => ['label'=>$t('ຄູ່ຮ່ວມ','Partner','合作夥伴'),    'icon'=>'fas fa-globe',               'bg'=>'bg-cyan-100',    'text'=>'text-cyan-700'],
    'mou'     => ['label'=>'MOU',                                  'icon'=>'fas fa-file-contract',       'bg'=>'bg-indigo-100',  'text'=>'text-indigo-700'],
    'aid'     => ['label'=>$t('ໂຄງການ','Aid Project','援助'),     'icon'=>'fas fa-hand-holding-heart',  'bg'=>'bg-amber-100',   'text'=>'text-amber-700'],
    'program' => ['label'=>$t('ແລກປ່ຽນ','Exchange','交流'),       'icon'=>'fas fa-exchange-alt',        'bg'=>'bg-rose-100',    'text'=>'text-rose-700'],
  ];

  // Count by type
  $counts = $results->groupBy('type')->map->count();

  // Active filter tab
  $filterType = request('type', '');
  $filtered   = $filterType ? $results->filter(fn($r) => $r['type'] === $filterType)->values() : $results;

  // Keyword highlight helper (safe, strips tags first)
  $highlight = function(string $text, string $q): string {
    if (!$q || !$text) return e($text);
    return preg_replace(
      '/('.preg_quote(e($q), '/').')/iu',
      '<mark class="bg-yellow-100 text-yellow-900 rounded px-0.5 not-italic">$1</mark>',
      e($text)
    );
  };

  // Build result meta for each item
  $getMeta = function(array $r) use ($L, $typeConfig): array {
    $item = $r['item'];
    $type = $r['type'];
    $cfg  = $typeConfig[$type];

    $title = $item->{"title_{$L}"} ?? $item->{"name_{$L}"}
          ?? $item->title_lo       ?? $item->name_lo ?? '—';

    $desc = strip_tags(
      $item->{"description_{$L}"} ?? $item->{"content_{$L}"}
      ?? $item->{"excerpt_{$L}"}  ?? $item->{"scope_{$L}"} ?? ''
    );

    $url = match($type) {
      'news'    => route('front.news.show',    $item->slug),
      'event'   => route('front.events.show',  $item->slug),
      'page'    => route('front.page.show',    $item->slug),
      'partner' => route('front.partners.show',$item->id),
      'mou'     => route('front.mou.index'),
      'aid'     => route('front.aid-projects.index'),
      'program' => route('front.monk-programs.index'),
      default   => '#',
    };

    $meta = match($type) {
      'news'    => $item->published_at?->translatedFormat('d F Y'),
      'partner' => ($item->{"country_name_{$L}"} ?? $item->country_name_lo ?? '').($item->partnership_since ? ' · '.$item->partnership_since : ''),
      'mou'     => $item->signed_date ? \Carbon\Carbon::parse($item->signed_date)->format('d/m/Y') : '',
      'aid'     => $item->start_date ? \Carbon\Carbon::parse($item->start_date)->format('Y') : '',
      'program' => $item->year ?? ($item->destination_country ?? ''),
      default   => '',
    };

    return compact('title','desc','url','meta','cfg','type');
  };
@endphp

@section('title', $t('ຄົ້ນຫາ','Search','搜索').' "'.$q.'" - '.($settings->site_name_lo ?: 'BFOL'))

@push('styles')
<style>
  .dot-pattern { background-image:radial-gradient(circle,rgba(255,255,255,.12) 1px,transparent 1px); background-size:28px 28px; }
  mark { font-style: normal; }
</style>
@endpush

@section('content')

{{-- ═══ HERO + SEARCH BAR ═══ --}}
<section class="relative bg-primary overflow-hidden">
  <div class="dot-pattern absolute inset-0 pointer-events-none"></div>
  <div class="absolute -top-24 -right-24 w-80 h-80 bg-secondary/10 rounded-full blur-3xl pointer-events-none"></div>

  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-12 relative z-10 text-center">
    <h1 class="text-3xl sm:text-4xl font-serif font-bold text-on-primary mb-2">
      {{ $t('ຜົນການຄົ້ນຫາ','Search Results','搜索結果') }}
    </h1>

    @if($q)
      <p class="text-on-primary/70 text-sm mb-6">
        {{ $t('ພົບ','Found','找到') }}
        <strong class="text-secondary">{{ $total }}</strong>
        {{ $t('ຜົນ ສຳລັບ','results for','條結果') }}
        "<em class="text-secondary not-italic font-semibold">{{ $q }}</em>"
      </p>
    @else
      <p class="text-on-primary/60 text-sm mb-6">{{ $t('ກະລຸນາປ້ອນຄຳຄົ້ນຫາ','Enter a search term above','請輸入搜索詞') }}</p>
    @endif

    {{-- Search box --}}
    <form action="{{ route('front.search') }}" method="GET" class="relative max-w-xl mx-auto">
      <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-outline text-sm pointer-events-none"></i>
      <input type="text" name="q" value="{{ $q }}" autofocus
             placeholder="{{ $t('ຄົ້ນຫາ...','Search...','搜索...') }}"
             class="w-full pl-10 pr-28 py-3 bg-white rounded-xl text-sm text-on-surface
                    focus:outline-none focus:ring-2 focus:ring-primary/30 transition" />
      <button type="submit"
              class="absolute right-1.5 top-1.5 px-4 py-2 bg-primary text-on-primary
                     text-xs font-bold rounded-lg hover:bg-secondary hover:text-on-secondary transition-colors">
        {{ $t('ຄົ້ນຫາ','Search','搜索') }}
      </button>
    </form>
  </div>
</section>

{{-- ═══ RESULTS ═══ --}}
<section class="bg-slate-50 py-10">
  <div class="max-w-[900px] mx-auto px-4 sm:px-6 lg:px-8">

    @if($total > 0)

      {{-- Type filter tabs --}}
      <div class="flex items-center gap-2 flex-wrap mb-6">
        <a href="{{ route('front.search', ['q' => $q]) }}"
           class="px-3.5 py-1.5 rounded-full text-xs font-bold transition-colors
                  {{ !$filterType ? 'bg-primary text-on-primary' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-100' }}">
          {{ $t('ທັງໝົດ','All','全部') }}
          <span class="ml-1 opacity-70">{{ $total }}</span>
        </a>
        @foreach($typeConfig as $key => $cfg)
          @if(($counts[$key] ?? 0) > 0)
            <a href="{{ route('front.search', ['q' => $q, 'type' => $key]) }}"
               class="px-3.5 py-1.5 rounded-full text-xs font-bold transition-colors
                      {{ $filterType === $key ? 'bg-primary text-on-primary' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-100' }}">
              <i class="{{ $cfg['icon'] }} text-[9px] mr-1"></i>
              {{ $cfg['label'] }}
              <span class="ml-1 opacity-70">{{ $counts[$key] }}</span>
            </a>
          @endif
        @endforeach
      </div>

      {{-- Result cards --}}
      <div class="flex flex-col gap-3">
        @foreach($filtered as $result)
          @php $m = $getMeta($result); @endphp
          <a href="{{ $m['url'] }}"
             class="group bg-white rounded-2xl border border-slate-100
                    shadow-[0_2px_12px_-4px_rgba(0,0,0,.07)]
                    hover:shadow-[0_6px_24px_-4px_rgba(3,22,50,.12)]
                    hover:-translate-y-0.5 hover:border-primary/20
                    transition-all duration-300 p-5 flex items-start gap-4">

            {{-- Icon --}}
            <div class="w-10 h-10 rounded-xl {{ $m['cfg']['bg'] }} flex items-center justify-center shrink-0 mt-0.5
                        group-hover:scale-105 transition-transform">
              <i class="{{ $m['cfg']['icon'] }} {{ $m['cfg']['text'] }} text-sm"></i>
            </div>

            <div class="flex-1 min-w-0">
              {{-- Badge + meta --}}
              <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                <span class="px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider rounded-md
                             {{ $m['cfg']['bg'] }} {{ $m['cfg']['text'] }}">
                  {{ $m['cfg']['label'] }}
                </span>
                @if($m['meta'])
                  <span class="text-[11px] text-slate-400">{{ $m['meta'] }}</span>
                @endif
              </div>

              {{-- Title with keyword highlight --}}
              <h3 class="font-bold text-on-surface text-sm leading-snug mb-1.5
                          group-hover:text-primary transition-colors line-clamp-2">
                {!! $highlight($m['title'], $q) !!}
              </h3>

              {{-- Snippet with highlight --}}
              @if($m['desc'])
                <p class="text-[12px] text-slate-500 line-clamp-2 leading-relaxed">
                  {!! $highlight(\Str::limit($m['desc'], 200), $q) !!}
                </p>
              @endif

              <span class="inline-flex items-center gap-1 mt-2 text-xs font-bold text-primary
                           group-hover:text-secondary transition-colors">
                {{ $t('ເບິ່ງລາຍລະອຽດ','View details','查看詳情') }}
                <i class="fas fa-arrow-right text-[9px] group-hover:translate-x-0.5 transition-transform"></i>
              </span>
            </div>
          </a>
        @endforeach
      </div>

    @elseif($q)
      {{-- No results --}}
      <div class="py-24 flex flex-col items-center text-center">
        <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center mb-4">
          <i class="fas fa-search text-slate-300 text-3xl"></i>
        </div>
        <p class="font-semibold text-on-surface-variant mb-1">
          {{ $t('ບໍ່ພົບຜົນ','No results found','未找到結果') }}
        </p>
        <p class="text-sm text-outline mb-6">
          {{ $t('ລອງຄຳຄົ້ນຫາອື່ນ','Try different keywords','請嘗試不同的關鍵詞') }}
        </p>
        <div class="flex gap-2 flex-wrap justify-center">
          @foreach([
            [route('front.news.index'),         'fas fa-newspaper',         $t('ຂ່າວ','News','新聞')],
            [route('front.partners.index'),     'fas fa-globe',             $t('ຄູ່ຮ່ວມ','Partners','夥伴')],
            [route('front.monk-programs.index'),'fas fa-exchange-alt',      $t('ແລກປ່ຽນ','Exchange','交流')],
            [route('front.mou.index'),          'fas fa-file-contract',     'MOU'],
          ] as [$url, $icon, $label])
            <a href="{{ $url }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-slate-200
                      text-on-surface-variant text-xs font-semibold rounded-xl hover:bg-primary
                      hover:text-on-primary hover:border-primary transition-colors">
              <i class="{{ $icon }} text-[10px]"></i>{{ $label }}
            </a>
          @endforeach
        </div>
      </div>
    @endif

  </div>
</section>

@endsection
