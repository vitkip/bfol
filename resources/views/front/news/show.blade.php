@extends('front.layouts.app')

@php
  $L = app()->getLocale();
  $t = fn($lo,$en,$zh) => match($L){'zh'=>$zh,'en'=>$en,default=>$lo};

  $thumb = $item->thumbnail;
  if ($thumb && !\Str::startsWith($thumb, ['http','https','/storage'])) {
      $thumb = \Illuminate\Support\Facades\Storage::url($thumb);
  }
  $title   = $item->trans('title');
  $excerpt = $item->trans('excerpt');
  $content = $item->trans('content');
  $catName = $item->category?->trans('name');
@endphp

@section('title', $title.' - '.($settings->site_name_lo ?: 'BFOL'))
@section('meta_description', $excerpt ?: $title)

@push('styles')
<style>
/* ─────────────────────────────────────────────
   Reading progress bar
───────────────────────────────────────────── */
#news-reading-bar {
  position: fixed;
  top: 0; left: 0;
  height: 3px;
  width: 0%;
  z-index: 9999;
  background: linear-gradient(90deg,
    var(--color-secondary) 0%,
    var(--color-secondary-container) 60%,
    var(--color-secondary) 100%);
  background-size: 200% 100%;
  border-radius: 0 3px 3px 0;
  transition: width .1s linear;
  animation: bar-shimmer 2s linear infinite;
}
@keyframes bar-shimmer {
  0%   { background-position: 200% 0; }
  100% { background-position: -200% 0; }
}

/* ─────────────────────────────────────────────
   Hero
───────────────────────────────────────────── */
.news-hero {
  position: relative;
  overflow: hidden;
  min-height: 400px;
  display: flex;
  align-items: flex-end;
}
@media (min-width: 640px)  { .news-hero { min-height: 480px; } }
@media (min-width: 1024px) { .news-hero { min-height: 540px; } }

.news-hero__bg {
  position: absolute;
  inset: 0;
  width: 100%; height: 100%;
  object-fit: cover;
  transform: scale(1.06);
  transition: transform 10s ease;
}
.news-hero:hover .news-hero__bg { transform: scale(1); }

.news-hero__overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(
    to top,
    rgba(3,22,50,.97)  0%,
    rgba(3,22,50,.88)  30%,
    rgba(3,22,50,.55)  65%,
    rgba(3,22,50,.18)  100%
  );
}
.news-hero__no-img {
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, #031632 0%, #0d2952 60%, #1a3a6b 100%);
}
.dot-bg {
  position: absolute;
  inset: 0;
  background-image: radial-gradient(circle, rgba(255,255,255,.07) 1px, transparent 1px);
  background-size: 28px 28px;
  pointer-events: none;
}

/* ─────────────────────────────────────────────
   Prose — article body
───────────────────────────────────────────── */
.prose-news {
  font-size: 1rem;
  line-height: 1.95;
  color: #1e293b;
  word-break: break-word;
  overflow-wrap: break-word;
}
.prose-news > * + * { margin-top: 1.2em; }
.prose-news h1,.prose-news h2,.prose-news h3,
.prose-news h4,.prose-news h5,.prose-news h6 {
  font-family: var(--font-serif);
  font-weight: 700;
  color: #0f172a;
  line-height: 1.35;
  margin-top: 2em;
  margin-bottom: .55em;
}
.prose-news h2 { font-size: 1.45rem; padding-bottom: .4em; border-bottom: 1px solid #f1f5f9; }
.prose-news h3 { font-size: 1.2rem; }
.prose-news h4 { font-size: 1.05rem; }
.prose-news p  { margin: .95em 0; }
.prose-news a  {
  color: var(--color-secondary);
  text-decoration: underline;
  text-underline-offset: 3px;
  text-decoration-thickness: 1px;
  transition: color .2s;
}
.prose-news a:hover { color: var(--color-on-secondary-container); }
.prose-news ul, .prose-news ol { margin: .85em 0 .85em 1.6rem; }
.prose-news li { margin: .3em 0; }
.prose-news img {
  border-radius: 1rem;
  max-width: 100%; height: auto;
  margin: 2em auto;
  display: block;
  box-shadow: 0 4px 28px -6px rgba(0,0,0,.18);
}
.prose-news blockquote {
  position: relative;
  border-left: 4px solid var(--color-secondary);
  padding: 1em 1.25em 1em 1.5em;
  background: linear-gradient(135deg, #fffbeb, #fef9e7);
  border-radius: 0 .875rem .875rem 0;
  font-style: italic;
  margin: 1.75em 0;
  font-size: 1.05rem;
  color: #4a3800;
}
.prose-news blockquote::before {
  content: '\201C';
  position: absolute;
  top: -8px; left: 10px;
  font-size: 3.5rem;
  font-family: Georgia, serif;
  color: var(--color-secondary);
  opacity: .35;
  line-height: 1;
}
.prose-news table {
  width: 100%;
  border-collapse: collapse;
  margin: 1.5em 0;
  font-size: .875rem;
  border-radius: .875rem;
  overflow: hidden;
  box-shadow: 0 0 0 1px #e2e8f0;
}
.prose-news td,.prose-news th { border: 1px solid #e2e8f0; padding: 10px 14px; vertical-align: top; }
.prose-news th { background: #f8fafc; font-weight: 700; }
.prose-news tr:hover td { background: #fafbff; }
.prose-news pre {
  background: #1a2234;
  color: #e2e8f0;
  padding: 1.25rem 1.5rem;
  border-radius: .875rem;
  overflow-x: auto;
  font-size: .8125rem;
  line-height: 1.75;
  margin: 1.5em 0;
  box-shadow: inset 0 1px 0 rgba(255,255,255,.06);
}
.prose-news code:not(pre code) {
  background: #f1f5f9;
  color: #be123c;
  padding: 2px 6px;
  border-radius: 4px;
  font-size: .875em;
  font-family: ui-monospace, monospace;
}
.prose-news hr { border: none; border-top: 2px solid #f1f5f9; margin: 2.5em 0; }
.prose-news strong { color: #0f172a; font-weight: 700; }
.prose-news figure { margin: 2em 0; text-align: center; }
.prose-news figcaption { font-size: .8125rem; color: #64748b; margin-top: .5em; }

/* ─────────────────────────────────────────────
   Sidebar
───────────────────────────────────────────── */
.scard {
  background: #fff;
  border-radius: 1.25rem;
  border: 1px solid #f1f5f9;
  box-shadow: 0 2px 20px -4px rgba(0,0,0,.07);
  padding: 1.25rem;
  transition: box-shadow .3s ease;
}
.scard:hover { box-shadow: 0 6px 32px -8px rgba(0,0,0,.12); }
.scard-head {
  font-size: .6875rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .1em;
  color: #94a3b8;
  margin-bottom: .875rem;
  display: flex;
  align-items: center;
  gap: .5rem;
}
.scard-head i { color: var(--color-secondary); font-size: .875rem; }

/* ─────────────────────────────────────────────
   Meta row items
───────────────────────────────────────────── */
.meta-item {
  display: flex;
  align-items: center;
  gap: .75rem;
  padding: .625rem 0;
}
.meta-item + .meta-item { border-top: 1px solid #f8fafc; }
.meta-icon {
  width: 2rem; height: 2rem;
  border-radius: .625rem;
  display: flex;
  align-items: center;
  justify-content: center;
  shrink: 0;
  flex-shrink: 0;
  background: rgba(3,22,50,.06);
}

/* ─────────────────────────────────────────────
   News cards (related)
───────────────────────────────────────────── */
.news-card {
  display: block;
  border-radius: 1.125rem;
  overflow: hidden;
  background: #fff;
  border: 1px solid #f1f5f9;
  box-shadow: 0 2px 16px -4px rgba(0,0,0,.08);
  transition: transform .3s ease, box-shadow .3s ease;
  text-decoration: none;
}
.news-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 16px 40px -8px rgba(3,22,50,.16);
}
.news-card__img {
  position: relative;
  width: 100%;
  padding-top: 56.25%; /* 16:9 ratio */
  overflow: hidden;
  background: #f1f5f9;
  flex-shrink: 0;
}
.news-card__img img,
.news-card__img > div {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
}
.news-card__img img {
  object-fit: cover;
  transition: transform .55s ease;
}
.news-card:hover .news-card__img img { transform: scale(1.07); }
.line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.line-clamp-3 { display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }

/* ─────────────────────────────────────────────
   Share buttons
───────────────────────────────────────────── */
.share-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: .5rem;
  font-size: .75rem;
  font-weight: 600;
  border-radius: .75rem;
  transition: all .22s ease;
  cursor: pointer;
  border: none;
  text-decoration: none;
}

/* ─────────────────────────────────────────────
   Mobile share bar
───────────────────────────────────────────── */
.mobile-bar {
  position: fixed;
  bottom: 0; left: 0; right: 0;
  z-index: 60;
  background: rgba(255,255,255,.94);
  backdrop-filter: blur(14px);
  -webkit-backdrop-filter: blur(14px);
  border-top: 1px solid rgba(226,232,240,.8);
  padding: .625rem 1rem env(safe-area-inset-bottom, .625rem);
  display: flex;
  gap: .5rem;
  transform: translateY(0);
  transition: transform .3s ease;
  box-shadow: 0 -4px 24px -8px rgba(0,0,0,.1);
}
@media (min-width: 1024px) { .mobile-bar { display: none; } }

/* ─────────────────────────────────────────────
   Fade-up animation
───────────────────────────────────────────── */
@keyframes fadeUp {
  from { opacity: 0; transform: translateY(22px); }
  to   { opacity: 1; transform: none; }
}
.anim-1 { animation: fadeUp .6s ease .08s both; }
.anim-2 { animation: fadeUp .6s ease .18s both; }
.anim-3 { animation: fadeUp .6s ease .28s both; }
.anim-4 { animation: fadeUp .6s ease .38s both; }
.anim-5 { animation: fadeUp .6s ease .48s both; }
</style>
@endpush

@section('content')

{{-- ── Reading Progress Bar ─────────────────────────────────── --}}
<div id="news-reading-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100"
     aria-label="{{ $t('ຄວາມຄືບໜ້າການອ່ານ','Reading progress','閱讀進度') }}"></div>

{{-- ═══════════════════════════════════════════
     HERO SECTION
════════════════════════════════════════════ --}}
<header class="news-hero" itemscope itemtype="https://schema.org/NewsArticle">

  {{-- Background --}}
  @if($thumb)
    <img src="{{ $thumb }}" class="news-hero__bg" alt="" aria-hidden="true" />
    <div class="news-hero__overlay"></div>
  @else
    <div class="news-hero__no-img"></div>
  @endif
  <div class="dot-bg"></div>

  {{-- Decorative glows --}}
  <div class="absolute -top-40 -right-40 w-[32rem] h-[32rem] rounded-full pointer-events-none"
       style="background:radial-gradient(circle,rgba(197,160,33,.12),transparent 70%)"></div>
  <div class="absolute -bottom-20 -left-20 w-64 h-64 rounded-full pointer-events-none"
       style="background:radial-gradient(circle,rgba(197,160,33,.08),transparent 70%)"></div>

  {{-- Content --}}
  <div class="relative z-10 w-full max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-12 lg:pb-16">

    {{-- Breadcrumb --}}
    <nav aria-label="Breadcrumb" class="flex items-center gap-1.5 text-white/50 text-xs mb-7 anim-1">
      <a href="{{ route('front.home') }}" class="hover:text-white/80 transition-colors flex items-center gap-1.5">
        <i class="fas fa-home text-[10px]"></i>
        <span class="sr-only sm:not-sr-only">{{ $t('ໜ້າຫຼັກ','Home','首頁') }}</span>
      </a>
      <i class="fas fa-chevron-right text-[8px] opacity-40"></i>
      <a href="{{ route('front.news.index') }}" class="hover:text-white/80 transition-colors">
        {{ $t('ຂ່າວສານ','News','新聞') }}
      </a>
      @if($catName)
        <i class="fas fa-chevron-right text-[8px] opacity-40"></i>
        <span class="text-white/60 truncate max-w-[180px] sm:max-w-xs">{{ $catName }}</span>
      @endif
    </nav>

    {{-- Category badge --}}
    @if($catName)
      <div class="mb-4 anim-2">
        <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-[11px] font-bold uppercase tracking-wider
                     shadow-lg shadow-secondary/20"
              style="background:var(--color-secondary);color:#031632">
          <i class="fas fa-tag text-[9px]"></i>{{ $catName }}
        </span>
      </div>
    @endif

    {{-- Title --}}
    <h1 itemprop="headline"
        class="font-serif font-bold text-white leading-snug mb-6 max-w-4xl anim-3
               text-[1.625rem] sm:text-[2rem] md:text-[2.375rem] lg:text-[2.625rem]"
        style="text-shadow:0 2px 16px rgba(0,0,0,.35)">
      {{ $title }}
    </h1>

    {{-- Meta info --}}
    <div class="flex flex-wrap items-center gap-x-5 gap-y-2 text-white/60 text-xs anim-4">
      @if($item->published_at)
        <span class="flex items-center gap-1.5" itemprop="datePublished"
              content="{{ $item->published_at->toIso8601String() }}">
          <i class="far fa-calendar text-secondary"></i>
          {{ $item->published_at->translatedFormat('d F Y') }}
        </span>
      @endif
      @if($item->author)
        <span class="flex items-center gap-1.5" itemprop="author" itemscope
              itemtype="https://schema.org/Person">
          <i class="far fa-user text-secondary"></i>
          <span itemprop="name">{{ $item->author->name }}</span>
        </span>
      @endif
      @if($item->view_count)
        <span class="flex items-center gap-1.5">
          <i class="far fa-eye text-secondary"></i>
          {{ number_format($item->view_count) }}&nbsp;{{ $t('ການເບິ່ງ','views','次') }}
        </span>
      @endif
      <span class="flex items-center gap-1.5" id="hero-read-time">
        <i class="far fa-clock text-secondary"></i>
        <span id="hero-read-time-text" aria-live="polite">…</span>
      </span>
    </div>

    {{-- Desktop share --}}
    <div class="mt-7 hidden lg:flex items-center gap-3 anim-5">
      <span class="text-white/35 text-[10px] font-bold uppercase tracking-widest mr-0.5">
        {{ $t('ແບ່ງປັນ','Share','分享') }}
      </span>
      <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
         target="_blank" rel="noreferrer noopener"
         class="share-btn py-2.5 px-5 text-white hover:-translate-y-0.5"
         style="background:#1877f2;box-shadow:0 4px 14px rgba(24,119,242,.35)">
        <i class="fab fa-facebook-f text-xs"></i> Facebook
      </a>
      <button id="share-copy-hero"
              onclick="newsShareCopy(this)"
              title="{{ $t('ສຳເນົາລິ້ງ','Copy link','複製連結') }}"
              class="share-btn py-2.5 px-4 hover:-translate-y-0.5"
              style="background:rgba(255,255,255,.12);color:rgba(255,255,255,.8);
                     border:1px solid rgba(255,255,255,.18);backdrop-filter:blur(6px)">
        <i class="fas fa-link text-xs"></i>
        <span>{{ $t('ສຳເນົາ','Copy','複製') }}</span>
      </button>
    </div>

  </div>
</header>

{{-- ═══════════════════════════════════════════
     CONTENT + SIDEBAR
════════════════════════════════════════════ --}}
<section class="bg-slate-50 py-10 lg:py-14" id="news-body-section">
  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
    <div class="lg:grid lg:grid-cols-[1fr_320px] xl:grid-cols-[1fr_340px] gap-8 items-start">

      {{-- ════════ MAIN ARTICLE ════════ --}}
      <article class="min-w-0">

        {{-- Wide banner image --}}
        @if($thumb)
          <div class="rounded-2xl overflow-hidden aspect-[21/8] mb-0 shadow-xl shadow-slate-200/80">
            <img src="{{ $thumb }}" alt="{{ $title }}" itemprop="image"
                 class="w-full h-full object-cover" loading="eager" />
          </div>
        @endif

        {{-- Article card --}}
        <div class="bg-white rounded-2xl border border-slate-100
                    shadow-[0_4px_40px_-10px_rgba(0,0,0,.1)] overflow-hidden
                    {{ $thumb ? 'rounded-t-none' : '' }}">

          <div class="p-5 sm:p-8 md:p-10 lg:p-12" id="article-body">

            {{-- Excerpt / lead --}}
            @if($excerpt)
              <div class="relative mb-8 rounded-r-2xl overflow-hidden"
                   style="border-left:4px solid var(--color-secondary)">
                <div class="absolute inset-0"
                     style="background:linear-gradient(135deg,rgba(197,160,33,.07),rgba(197,160,33,.02))"></div>
                <div class="relative px-5 py-4">
                  <p class="text-slate-700 font-medium text-[.9375rem] leading-relaxed italic"
                     itemprop="description">{{ $excerpt }}</p>
                </div>
              </div>
            @endif

            {{-- Body content --}}
            <div class="prose-news" id="prose-content" itemprop="articleBody">
              {!! $content !!}
            </div>

          </div>

          {{-- Footer: tags + back --}}
          <div class="px-5 sm:px-8 md:px-10 lg:px-12 py-5
                      border-t border-slate-100 bg-slate-50/60
                      flex flex-wrap items-center justify-between gap-4">

            <div class="flex flex-wrap gap-2">
              @forelse($item->tags ?? [] as $tag)
                <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full
                             bg-white border border-slate-200 text-slate-600
                             text-xs font-semibold shadow-sm
                             hover:border-secondary hover:text-secondary
                             transition-colors cursor-default">
                  <span style="color:var(--color-secondary);font-size:.6875rem;font-weight:900">#</span>{{ $tag->name_lo ?? $tag->name }}
                </span>
              @empty
              @endforelse
            </div>

            <a href="{{ route('front.news.index') }}"
               class="inline-flex items-center gap-2 text-sm font-semibold text-slate-400
                      hover:text-primary transition-colors group shrink-0">
              <i class="fas fa-arrow-left text-xs group-hover:-translate-x-1 transition-transform duration-200"></i>
              {{ $t('ກັບຄືນລາຍການ','Back to news','返回列表') }}
            </a>
          </div>

        </div>
      </article>

      {{-- ════════ SIDEBAR ════════ --}}
      <aside class="mt-8 lg:mt-0 space-y-5 lg:sticky lg:top-24" aria-label="{{ $t('ຂໍ້ມູນເພີ່ມຕື່ມ','Supplementary info','補充資訊') }}">

        {{-- ── Article Info ── --}}
        <div class="scard">
          <div class="scard-head"><i class="fas fa-circle-info"></i>{{ $t('ຂໍ້ມູນຂ່າວ','Article Info','文章資訊') }}</div>

          <div>
            @if($catName)
              <div class="meta-item">
                <div class="meta-icon"><i class="fas fa-tag text-secondary text-xs"></i></div>
                <div>
                  <div class="text-[10px] text-slate-400 mb-0.5">{{ $t('ໝວດໝູ່','Category','分類') }}</div>
                  <div class="text-xs font-semibold text-slate-700">{{ $catName }}</div>
                </div>
              </div>
            @endif
            @if($item->published_at)
              <div class="meta-item">
                <div class="meta-icon"><i class="far fa-calendar text-primary text-xs"></i></div>
                <div>
                  <div class="text-[10px] text-slate-400 mb-0.5">{{ $t('ວັນທີ່ເຜີຍແຜ່','Published','發布日期') }}</div>
                  <div class="text-xs font-semibold text-slate-700">{{ $item->published_at->translatedFormat('d F Y') }}</div>
                </div>
              </div>
            @endif
            @if($item->author)
              <div class="meta-item">
                <div class="meta-icon"><i class="far fa-user text-primary text-xs"></i></div>
                <div>
                  <div class="text-[10px] text-slate-400 mb-0.5">{{ $t('ຜູ້ຂຽນ','Author','作者') }}</div>
                  <div class="text-xs font-semibold text-slate-700">{{ $item->author->name }}</div>
                </div>
              </div>
            @endif
            @if($item->view_count)
              <div class="meta-item">
                <div class="meta-icon"><i class="far fa-eye text-primary text-xs"></i></div>
                <div>
                  <div class="text-[10px] text-slate-400 mb-0.5">{{ $t('ຍອດເບິ່ງ','Views','閱覽數') }}</div>
                  <div class="text-xs font-semibold text-slate-700">{{ number_format($item->view_count) }}</div>
                </div>
              </div>
            @endif
            <div class="meta-item">
              <div class="meta-icon"><i class="far fa-clock text-primary text-xs"></i></div>
              <div>
                <div class="text-[10px] text-slate-400 mb-0.5">{{ $t('ໃຊ້ເວລາອ່ານ','Read time','閱讀時間') }}</div>
                <div class="text-xs font-semibold text-slate-700" id="sidebar-read-time">…</div>
              </div>
            </div>
          </div>
        </div>

        {{-- ── Share ── --}}
        <div class="scard">
          <div class="scard-head"><i class="fas fa-share-nodes"></i>{{ $t('ແບ່ງປັນຂ່າວ','Share Article','分享文章') }}</div>
          <div class="space-y-2">
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
               target="_blank" rel="noreferrer noopener"
               class="flex items-center gap-3 w-full px-4 py-3 rounded-xl text-white text-sm font-semibold
                      hover:-translate-y-0.5 transition-all"
               style="background:#1877f2;box-shadow:0 3px 12px rgba(24,119,242,.3)">
              <i class="fab fa-facebook-f w-4 text-center"></i> Facebook
            </a>
            <button id="share-copy-sidebar"
                    onclick="newsShareCopy(this)"
                    class="flex items-center gap-3 w-full px-4 py-3 rounded-xl
                           bg-slate-100 text-slate-700 text-sm font-semibold
                           hover:bg-slate-200 hover:-translate-y-0.5 transition-all">
              <i class="fas fa-link w-4 text-center text-slate-400"></i>
              <span id="sidebar-copy-label">{{ $t('ສຳເນົາລິ້ງ','Copy Link','複製連結') }}</span>
            </button>
          </div>
        </div>

        {{-- ── Related (sidebar compact) ── --}}
        @if($related->isNotEmpty())
          <div class="scard">
            <div class="scard-head"><i class="fas fa-newspaper"></i>{{ $t('ຂ່າວທີ່ກ່ຽວຂ້ອງ','Related News','相關新聞') }}</div>
            <div class="space-y-0.5">
              @foreach($related->take(3) as $rel)
                @php
                  $rThumb = $rel->thumbnail;
                  if ($rThumb && !\Str::startsWith($rThumb, ['http','https','/storage'])) {
                      $rThumb = \Illuminate\Support\Facades\Storage::url($rThumb);
                  }
                  $rTitle = $rel->trans('title');
                @endphp

                <a href="{{ route('front.news.show', $rel->slug) }}"
                   class="group flex gap-3 p-2 -mx-1 rounded-xl hover:bg-slate-50 transition-all">
                  <div class="w-[70px] h-[52px] rounded-lg overflow-hidden bg-slate-100 shrink-0 shadow-sm">
                    @if($rThumb)
                      <img src="{{ $rThumb }}" alt="{{ $rTitle }}" loading="lazy"
                           class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                    @else
                      <div class="w-full h-full flex items-center justify-center"
                           style="background:linear-gradient(135deg,rgba(3,22,50,.05),rgba(197,160,33,.08))">
                        <i class="fas fa-newspaper text-primary/20 text-base"></i>
                      </div>
                    @endif
                  </div>
                  <div class="min-w-0 flex-1 py-0.5">
                    <h4 class="text-xs font-semibold text-slate-700
                               group-hover:text-primary transition-colors
                               line-clamp-3 leading-relaxed mb-1">
                      {{ $rTitle }}
                    </h4>
                    @if($rel->published_at)
                      <span class="text-[10px] text-slate-400 flex items-center gap-1">
                        <i class="far fa-calendar text-[8px]"></i>
                        {{ $rel->published_at->translatedFormat('d M Y') }}
                      </span>
                    @endif
                  </div>
                </a>

                @unless($loop->last)
                  <div class="border-t border-slate-100 mx-1 my-0.5"></div>
                @endunless
              @endforeach
            </div>
          </div>
        @endif

      </aside>
    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════
     RELATED NEWS — FULL GRID
════════════════════════════════════════════ --}}
@if($related->isNotEmpty())
<section class="bg-white py-12 lg:py-16 border-t border-slate-100"
         aria-labelledby="related-heading">
  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Section header --}}
    <div class="flex items-center justify-between mb-8">
      <div class="flex items-center gap-3">
        <span class="w-1 h-8 rounded-full" style="background:var(--color-secondary)"></span>
        <h2 id="related-heading" class="text-xl sm:text-2xl font-serif font-bold text-slate-900">
          {{ $t('ຂ່າວທີ່ກ່ຽວຂ້ອງ','Related Articles','相關文章') }}
        </h2>
      </div>
      <a href="{{ route('front.news.index') }}"
         class="flex items-center gap-1.5 text-sm font-semibold text-slate-400
                hover:text-primary transition-colors group">
        {{ $t('ເບິ່ງທັງໝົດ','View all','查看全部') }}
        <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform duration-200"></i>
      </a>
    </div>

    {{-- Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
      @foreach($related as $rel)
        @php
          $rThumb = $rel->thumbnail;
          if ($rThumb && !\Str::startsWith($rThumb, ['http','https','/storage'])) {
              $rThumb = \Illuminate\Support\Facades\Storage::url($rThumb);
          }
          $rTitle  = $rel->trans('title');
          $rExcerpt = $rel->trans('excerpt');
          $rCat    = $rel->category?->trans('name');
        @endphp

        <a href="{{ route('front.news.show', $rel->slug) }}" class="news-card group">
          {{-- Image --}}
          <div class="news-card__img">
            @if($rThumb)
              <img src="{{ $rThumb }}" alt="{{ $rTitle }}" loading="lazy" />
            @else
              <div class="absolute inset-0 flex items-center justify-center"
                   style="background:linear-gradient(135deg,rgba(3,22,50,.05),rgba(197,160,33,.1))">
                <i class="fas fa-newspaper text-primary/15 text-3xl"></i>
              </div>
            @endif
          </div>

          {{-- Body --}}
          <div class="p-4 flex flex-col gap-2">
            @if($rCat)
              <span class="text-[10px] font-bold uppercase tracking-wide"
                    style="color:var(--color-secondary)">{{ $rCat }}</span>
            @endif
            <h3 class="text-sm font-bold text-slate-800 group-hover:text-primary
                       transition-colors line-clamp-2 leading-snug">
              {{ $rTitle }}
            </h3>
            @if($rExcerpt)
              <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">{{ $rExcerpt }}</p>
            @endif
            <div class="flex items-center justify-between text-[10px] text-slate-400 mt-auto pt-1.5 border-t border-slate-100">
              @if($rel->published_at)
                <span class="flex items-center gap-1">
                  <i class="far fa-calendar text-[8px]"></i>
                  {{ $rel->published_at->translatedFormat('d M Y') }}
                </span>
              @endif
              @if($rel->view_count)
                <span class="flex items-center gap-1">
                  <i class="far fa-eye text-[8px]"></i>
                  {{ number_format($rel->view_count) }}
                </span>
              @endif
            </div>
          </div>
        </a>
      @endforeach
    </div>

  </div>
</section>
@endif

{{-- ═══════════════════════════════════════════
     MOBILE SHARE BAR
════════════════════════════════════════════ --}}
<div class="mobile-bar" id="mobile-share-bar" aria-label="{{ $t('ແຖບແບ່ງປັນ','Share bar','分享欄') }}">
  <a href="{{ route('front.news.index') }}"
     class="share-btn px-3.5 py-2.5 text-slate-600 hover:text-primary hover:bg-slate-200 bg-slate-100">
    <i class="fas fa-arrow-left text-xs"></i>
  </a>
  <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
     target="_blank" rel="noreferrer noopener"
     class="share-btn flex-1 py-2.5 text-white hover:opacity-90"
     style="background:#1877f2;box-shadow:0 2px 10px rgba(24,119,242,.3)">
    <i class="fab fa-facebook-f text-xs"></i>
    <span>Facebook</span>
  </a>
  <button onclick="newsShareCopy(this)"
          class="share-btn flex-1 py-2.5 bg-slate-100 text-slate-600 hover:bg-slate-200">
    <i class="fas fa-link text-xs"></i>
    <span>{{ $t('ສຳເນົາ','Copy','複製') }}</span>
  </button>
</div>

@endsection

@push('scripts')
<script>
(function () {
  'use strict';

  /* ── Reading progress bar ─────────────────────────── */
  var bar = document.getElementById('news-reading-bar');
  var section = document.getElementById('news-body-section');
  function updateBar() {
    if (!bar || !section) return;
    var start = section.offsetTop;
    var total = section.offsetHeight - window.innerHeight;
    var pct   = total > 0
      ? Math.min(100, Math.max(0, (window.scrollY - start + 60) / total * 100))
      : 0;
    bar.style.width = pct + '%';
    bar.setAttribute('aria-valuenow', Math.round(pct));
  }
  window.addEventListener('scroll', updateBar, { passive: true });
  updateBar();

  /* ── Estimated read time ──────────────────────────── */
  (function () {
    var prose = document.getElementById('prose-content');
    if (!prose) return;
    var words = (prose.innerText || prose.textContent || '').trim().split(/\s+/).length;
    var mins  = Math.max(1, Math.round(words / 200));
    var label = '{{ $t("ນາທີ","min read","分鐘") }}';
    var txt   = mins + ' ' + label;
    ['hero-read-time-text', 'sidebar-read-time'].forEach(function (id) {
      var el = document.getElementById(id);
      if (el) el.textContent = txt;
    });
  }());

  /* ── Copy link ────────────────────────────────────── */
  window.newsShareCopy = function (btn) {
    if (!navigator.clipboard) return;
    navigator.clipboard.writeText(window.location.href).then(function () {
      var icon  = btn.querySelector('i.fas');
      var label = btn.querySelector('span');
      var origIconClass = icon  ? icon.className  : '';
      var origLabelText = label ? label.textContent : '';
      if (icon)  icon.className  = 'fas fa-check w-4 text-center';
      if (label) label.textContent = '{{ $t("ສຳເລັດ!","Copied!","已複製!") }}';
      btn.style.background = '#f0fdf4';
      btn.style.color = '#166534';
      setTimeout(function () {
        if (icon)  icon.className  = origIconClass;
        if (label) label.textContent = origLabelText;
        btn.style.background = '';
        btn.style.color = '';
      }, 2600);
    });
  };

  /* ── Scroll-triggered fade-in for below-fold cards ── */
  if ('IntersectionObserver' in window) {
    var obsEls = document.querySelectorAll('.news-card, .scard');
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting) {
          e.target.style.opacity = '1';
          e.target.style.transform = 'translateY(0)';
          io.unobserve(e.target);
        }
      });
    }, { threshold: 0.07, rootMargin: '0px 0px -40px 0px' });
    obsEls.forEach(function (el) {
      el.style.opacity = '0';
      el.style.transform = 'translateY(18px)';
      el.style.transition = 'opacity .5s ease, transform .5s ease';
      io.observe(el);
    });
  }

  /* ── Mobile share bar: hide near page bottom ─────── */
  var mobileBar = document.getElementById('mobile-share-bar');
  if (mobileBar) {
    window.addEventListener('scroll', function () {
      var nearBottom = (window.scrollY + window.innerHeight) > (document.documentElement.scrollHeight - 130);
      mobileBar.style.transform = nearBottom ? 'translateY(110%)' : 'translateY(0)';
    }, { passive: true });
  }

}());
</script>
@endpush
