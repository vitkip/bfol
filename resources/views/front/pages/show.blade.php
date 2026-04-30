@extends('front.layouts.app')

@php
  $L = app()->getLocale();
  $t = fn($lo,$en,$zh) => match($L){'zh'=>$zh,'en'=>$en,default=>$lo};

  $title   = $page->{'title_'.$L}   ?? $page->title_lo ?? '';
  $content = $page->{'content_'.$L} ?? $page->content_lo ?? '';
  $metaT   = $page->{'meta_title_'.$L} ?? $page->{'meta_title_lo'} ?? $title;

  $thumb = $page->thumbnail;
  if ($thumb && !\Str::startsWith($thumb, ['http','https','/storage'])) {
      $thumb = \Illuminate\Support\Facades\Storage::url($thumb);
  }

  $crumbs = [['label' => $t('ໜ້າຫຼັກ','Home','首頁'), 'url' => route('front.home')]];
  if ($page->parent_slug) {
      $parent = \App\Models\Page::published()->where('slug', $page->parent_slug)->first();
      if ($parent) {
          $crumbs[] = [
              'label' => $parent->{'title_'.$L} ?? $parent->title_lo,
              'url'   => route('front.page.show', $parent->slug),
          ];
      }
  }
  $crumbs[] = ['label' => $title, 'url' => null];

  $hasCover = !empty($thumb);

  // Estimate reading time
  $wordCount = $content ? str_word_count(strip_tags($content)) : 0;
  $readMins  = max(1, (int) ceil($wordCount / 180));
@endphp

@section('title', ($metaT ?: $title).' - '.($settings->site_name_lo ?: 'BFOL'))
@section('meta_description', $page->meta_description ?: '')

@push('styles')
<style>
  /* Dot pattern for hero */
  .dot-pattern {
    background-image: radial-gradient(circle, rgba(255,255,255,.10) 1px, transparent 1px);
    background-size: 26px 26px;
  }

  /* ── Rich-text typography ─────────────────────── */
  .prose-page {
    font-size: .9375rem;
    line-height: 1.95;
    color: #1e293b;
  }
  .prose-page h1,.prose-page h2,.prose-page h3,.prose-page h4 {
    font-family: 'Phetsarath OT','Noto Serif Lao',serif;
    font-weight: 700; color: #0f172a;
    margin: 1.75em 0 .6em; line-height: 1.35;
  }
  .prose-page h1 { font-size: 1.55rem; }
  .prose-page h2 {
    font-size: 1.25rem;
    border-bottom: 2px solid #e2e8f0;
    padding-bottom: .35em;
  }
  .prose-page h3 { font-size: 1.1rem; }
  .prose-page h4 { font-size: 1rem; }
  .prose-page p  { margin: .9em 0; }
  .prose-page ul,.prose-page ol { margin: .85em 0 .85em 1.5rem; }
  .prose-page li { margin: .3em 0; }
  .prose-page a  { color: var(--color-primary,#00488d); text-decoration: underline; text-underline-offset: 3px; }
  .prose-page a:hover { opacity: .8; }
  .prose-page img {
    border-radius: .75rem;
    max-width: 100%; height: auto;
    margin: 1.6em auto; display: block;
    box-shadow: 0 4px 28px -8px rgba(0,0,0,.15);
  }
  .prose-page blockquote {
    border-left: 4px solid #f59e0b;
    padding: .75em 1.25em;
    background: #fefce8;
    border-radius: 0 .6rem .6rem 0;
    font-style: italic; margin: 1.4em 0; color: #78350f;
  }
  .prose-page table { width:100%; border-collapse:collapse; margin:1.4em 0; font-size:.875rem; }
  .prose-page th {
    background:#f1f5f9; font-weight:700;
    text-align:left; padding:.6em 1em; border:1px solid #e2e8f0;
  }
  .prose-page td { padding:.55em 1em; border:1px solid #e2e8f0; vertical-align:top; }
  .prose-page tr:nth-child(even) td { background:#f8fafc; }
  .prose-page hr { border:none; border-top:2px solid #e2e8f0; margin:2.2em 0; }
  .prose-page pre {
    background:#0f172a; color:#e2e8f0;
    border-radius:.6rem; padding:1.25em;
    overflow-x:auto; font-size:.8rem; margin:1.4em 0;
  }

  /* ── Sidebar image ─────────────────────────────── */
  /* Fixed 3:2 aspect ratio so any image looks consistent */
  .sidebar-thumb {
    position: relative;
    padding-top: 66.667%;   /* 3:2 */
    overflow: hidden;
  }
  .sidebar-thumb img {
    position: absolute;
    inset: 0; width: 100%; height: 100%;
    object-fit: cover;
    transition: transform .5s ease;
  }
  .sidebar-thumb:hover img { transform: scale(1.04); }

  /* ── Misc ──────────────────────────────────────── */
  [x-cloak] { display: none !important; }
  .img-zoomable { cursor: zoom-in; }

  #read-progress {
    position: fixed; top: 0; left: 0; height: 3px;
    background: linear-gradient(90deg, #00488d, #f59e0b, #00488d);
    background-size: 200% 100%;
    z-index: 9999; transition: width .12s linear; pointer-events: none;
    animation: shiftGrad 3s linear infinite;
  }
  @keyframes shiftGrad { to { background-position: -200% 0; } }
</style>
@endpush

@section('content')

<div id="read-progress" style="width:0%"></div>

{{-- ══════════════════════════════════════════════════
     HERO
══════════════════════════════════════════════════ --}}
<section class="relative bg-primary overflow-hidden min-h-[220px] flex items-end">
  <div class="dot-pattern absolute inset-0 pointer-events-none"></div>

  @if($hasCover)
    <div class="absolute inset-0">
      <img src="{{ $thumb }}" class="w-full h-full object-cover opacity-[.14]" alt="" />
    </div>
    <div class="absolute inset-0 bg-gradient-to-br from-primary/70 via-primary/85 to-primary"></div>
  @else
    <div class="absolute -top-32 -right-32 w-[420px] h-[420px] bg-secondary/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>
  @endif

  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-14 relative z-10 w-full">

    {{-- Breadcrumb --}}
    <nav class="flex items-center flex-wrap gap-1.5 text-on-primary/45 text-[11px] mb-5" aria-label="breadcrumb">
      @foreach($crumbs as $i => $crumb)
        @if($i > 0)<i class="fas fa-chevron-right text-[7px] opacity-40"></i>@endif
        @if($crumb['url'])
          <a href="{{ $crumb['url'] }}" class="hover:text-on-primary/80 transition-colors">{{ $crumb['label'] }}</a>
        @else
          <span class="text-on-primary/75 font-semibold">{{ $crumb['label'] }}</span>
        @endif
      @endforeach
    </nav>

    <div class="max-w-3xl">
      <h1 class="text-[1.9rem] sm:text-[2.4rem] lg:text-[2.7rem] font-serif font-bold text-on-primary leading-tight">
        {{ $title }}
      </h1>
    </div>
  </div>
</section>

{{-- ══════════════════════════════════════════════════
     MAIN LAYOUT
══════════════════════════════════════════════════ --}}
<section class="bg-slate-50/80 py-10">
  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">

    @if($hasCover)
      {{-- ── TWO-COLUMN (image LEFT, content RIGHT on lg+) ── --}}
      <div class="flex flex-col lg:flex-row gap-7 items-start">

        {{-- ══ LEFT SIDEBAR ══ --}}
        <aside class="w-full lg:w-[300px] xl:w-[310px] shrink-0">
          <div class="lg:sticky lg:top-[90px] flex flex-col gap-4">

            {{-- ① Info card (date + author + reading time) --}}
            @if($page->updated_at || $page->author || $wordCount > 60)
              <div class="bg-white rounded-2xl border border-slate-100
                          shadow-[0_2px_14px_-4px_rgba(0,0,0,.08)] overflow-hidden">
                <div class="px-4 pt-3 pb-2">
                  <p class="text-[10px] font-black text-slate-400 uppercase tracking-[.12em]">
                    {{ $t('ລາຍລະອຽດ','Details','詳情') }}
                  </p>
                </div>
                <div class="divide-y divide-slate-100">
                  @if($page->updated_at)
                    <div class="flex items-center gap-3 px-4 py-3">
                      <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center shrink-0">
                        <i class="far fa-calendar-check text-blue-500 text-xs"></i>
                      </div>
                      <div>
                        <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wide">{{ $t('ອັບເດດລ່າສຸດ','Last updated','最後更新') }}</p>
                        <p class="text-xs font-bold text-slate-700">{{ $page->updated_at->translatedFormat('d F Y') }}</p>
                      </div>
                    </div>
                  @endif
                  @if($page->author)
                    <div class="flex items-center gap-3 px-4 py-3">
                      <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center shrink-0">
                        <i class="far fa-user-circle text-amber-500 text-xs"></i>
                      </div>
                      <div>
                        <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wide">{{ $t('ຜູ້ຂຽນ','Author','作者') }}</p>
                        <p class="text-xs font-bold text-slate-700">{{ $page->author->full_name_lo ?? $page->author->name ?? '' }}</p>
                      </div>
                    </div>
                  @endif
                  @if($wordCount > 60)
                    <div class="flex items-center gap-3 px-4 py-3">
                      <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center shrink-0">
                        <i class="far fa-clock text-emerald-500 text-xs"></i>
                      </div>
                      <div>
                        <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wide">{{ $t('ເວລາອ່ານ','Reading time','閱讀時間') }}</p>
                        <p class="text-xs font-bold text-slate-700">~{{ $readMins }} {{ $t('ນາທີ','min','分鐘') }}</p>
                      </div>
                    </div>
                  @endif
                </div>
              </div>
            @endif

            {{-- ③ Share card --}}
            <div class="bg-white rounded-2xl border border-slate-100
                        shadow-[0_2px_14px_-4px_rgba(0,0,0,.08)] overflow-hidden">
              <div class="px-4 pt-3 pb-2">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[.12em]">
                  {{ $t('ແຊຣ໌','Share','分享') }}
                </p>
              </div>
              <div class="px-3 pb-3 flex flex-col gap-2">
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                   target="_blank" rel="noreferrer"
                   class="flex items-center justify-center gap-2 py-2.5 rounded-xl
                          bg-[#1877F2] text-white text-xs font-bold
                          hover:bg-[#1568d3] active:scale-[.98] transition-all">
                  <i class="fab fa-facebook-f"></i> Facebook
                </a>
                <button id="copy-btn"
                        onclick="
                          navigator.clipboard.writeText('{{ request()->url() }}').then(()=>{
                            const b=document.getElementById('copy-btn');
                            b.innerHTML='<i class=\'fas fa-check\'></i>&nbsp;{{ $t('ຄັດລອກແລ້ວ!','Copied!','已複製！') }}';
                            b.classList.add('bg-emerald-50','text-emerald-700','border-emerald-200');
                            b.classList.remove('bg-slate-100','text-slate-600');
                            setTimeout(()=>{
                              b.innerHTML='<i class=\'fas fa-link\'></i>&nbsp;{{ $t('ຄັດລອກ URL','Copy link','複製連結') }}';
                              b.classList.remove('bg-emerald-50','text-emerald-700','border-emerald-200');
                              b.classList.add('bg-slate-100','text-slate-600');
                            },2500);
                          })"
                        class="flex items-center justify-center gap-2 py-2.5 rounded-xl border border-slate-200
                               bg-slate-100 text-slate-600 text-xs font-bold
                               hover:bg-slate-200 active:scale-[.98] transition-all cursor-pointer">
                  <i class="fas fa-link"></i>&nbsp;{{ $t('ຄັດລອກ URL','Copy link','複製連結') }}
                </button>
              </div>
            </div>

            {{-- ④ Back link --}}
            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('front.home') }}"
               class="flex items-center justify-center gap-2 py-2.5 rounded-xl
                      border border-slate-200 bg-white text-xs font-semibold text-slate-500
                      hover:border-primary/40 hover:text-primary hover:bg-primary/5
                      active:scale-[.98] transition-all">
              <i class="fas fa-arrow-left text-[10px]"></i>
              {{ $t('ກັບຄືນໜ້າກ່ອນ','Go back','返回上頁') }}
            </a>

          </div>
        </aside>

        {{-- ══ RIGHT: Article content ══ --}}
        <div class="flex-1 min-w-0">

          <article class="bg-white rounded-2xl border border-slate-100
                          shadow-[0_3px_24px_-6px_rgba(0,0,0,.10)] overflow-hidden">
            {{-- Cover image spanning full width of the card --}}
            <div class="aspect-[16/7] overflow-hidden">
              <img src="{{ $thumb }}" alt="{{ $title }}" class="w-full h-full object-cover" />
            </div>

            {{-- Accent stripe below image --}}
            <div class="h-[3px] bg-gradient-to-r from-primary via-secondary to-primary/40"></div>

            <div id="article-body" class="p-6 sm:p-8 lg:p-10 xl:p-12">
              @if(!$content)
                <div class="py-24 text-center">
                  <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-file-alt text-slate-300 text-2xl"></i>
                  </div>
                  <p class="text-slate-400 text-sm">{{ $t('ຍັງບໍ່ມີເນື້ອຫາ','Content coming soon','內容即將推出') }}</p>
                </div>
              @else
                <div class="prose-page">
                  {!! $content !!}
                </div>
              @endif
            </div>
          </article>

        </div>

      </div>

    @else
      {{-- ── SINGLE COLUMN (no thumbnail) ── --}}
      <div class="max-w-3xl mx-auto">

        <article class="bg-white rounded-2xl border border-slate-100
                        shadow-[0_3px_24px_-6px_rgba(0,0,0,.10)] overflow-hidden">
          <div class="h-[3px] bg-gradient-to-r from-primary via-secondary to-primary/40"></div>

          <div id="article-body" class="p-6 sm:p-8 lg:p-10">
            @if(!$content)
              <div class="py-24 text-center">
                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4">
                  <i class="fas fa-file-alt text-slate-300 text-2xl"></i>
                </div>
                <p class="text-slate-400 text-sm">{{ $t('ຍັງບໍ່ມີເນື້ອຫາ','Content coming soon','內容即將推出') }}</p>
              </div>
            @else
              <div class="prose-page">
                {!! $content !!}
              </div>
            @endif
          </div>
        </article>

        <div class="flex items-center justify-between mt-5 gap-3 flex-wrap">
          <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('front.home') }}"
             class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500
                    hover:text-primary transition-colors">
            <i class="fas fa-arrow-left text-xs"></i>
            {{ $t('ກັບຄືນ','Go back','返回') }}
          </a>
          <div class="flex items-center gap-2">
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
               target="_blank" rel="noreferrer"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#1877F2]
                      text-white text-xs font-bold hover:bg-[#1568d3] transition-colors">
              <i class="fab fa-facebook-f"></i> Share
            </a>
            <button onclick="navigator.clipboard.writeText('{{ request()->url() }}').then(()=>alert('{{ $t('ຄັດລອກ URL ແລ້ວ!','URL copied!','已複製！') }}'))"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-100
                           text-slate-600 text-xs font-bold hover:bg-slate-200 transition-colors cursor-pointer">
              <i class="fas fa-link"></i> Copy
            </button>
          </div>
        </div>

        @if($page->updated_at)
          <p class="text-center text-xs text-slate-400 mt-5">
            {{ $t('ອັບເດດລ່າສຸດ','Last updated','最後更新') }}:
            {{ $page->updated_at->translatedFormat('d F Y') }}
          </p>
        @endif
      </div>
    @endif

  </div>
</section>

@endsection

@push('scripts')
<script>
  /* Reading progress */
  window.addEventListener('scroll', () => {
    const total = Math.max(document.body.scrollHeight, document.documentElement.scrollHeight)
                  - document.documentElement.clientHeight;
    const pct = total > 0 ? (window.scrollY / total * 100).toFixed(1) : 100;
    document.getElementById('read-progress').style.width = pct + '%';
  }, { passive: true });

  /* Click-to-zoom images inside prose */
  document.querySelectorAll('#article-body img').forEach(img => {
    img.classList.add('img-zoomable');
    img.addEventListener('click', () => {
      const ov = document.createElement('div');
      ov.className = 'fixed inset-0 z-[9999] bg-black/85 flex items-center justify-center p-4 cursor-zoom-out';
      ov.innerHTML = `<img src="${img.src}" class="max-w-full max-h-full object-contain rounded-xl shadow-2xl" />`;
      ov.addEventListener('click', () => ov.remove());
      document.body.appendChild(ov);
    });
  });
</script>
@endpush
