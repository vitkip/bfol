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
  .prose-bfol { font-size:.9375rem; line-height:1.8; color:#1e293b; }
  .prose-bfol h2,.prose-bfol h3,.prose-bfol h4 { font-family:inherit; font-weight:700; color:#0f172a; margin:1.5em 0 .6em; }
  .prose-bfol h2 { font-size:1.35rem; }
  .prose-bfol h3 { font-size:1.1rem; }
  .prose-bfol p { margin:.9em 0; }
  .prose-bfol ul,.prose-bfol ol { margin:.8em 0 .8em 1.5rem; }
  .prose-bfol li { margin:.3em 0; }
  .prose-bfol a { color:var(--color-primary); text-decoration:underline; }
  .prose-bfol img { border-radius:.75rem; max-width:100%; height:auto; margin:1.5em 0; }
  .prose-bfol blockquote { border-left:4px solid var(--color-secondary); padding:.6em 1em; background:#fefce8; border-radius:0 .5rem .5rem 0; font-style:italic; margin:1.2em 0; }
  .dot-pattern { background-image:radial-gradient(circle,rgba(255,255,255,.12) 1px,transparent 1px); background-size:28px 28px; }
</style>
@endpush

@section('content')

{{-- ═══ HERO ═══ --}}
<section class="relative bg-primary overflow-hidden">
  <div class="dot-pattern absolute inset-0 pointer-events-none"></div>
  @if($thumb)
    <div class="absolute inset-0 opacity-15">
      <img src="{{ $thumb }}" class="w-full h-full object-cover" alt="" />
    </div>
    <div class="absolute inset-0 bg-gradient-to-t from-primary via-primary/70 to-primary/30"></div>
  @endif
  <div class="absolute -top-24 -right-24 w-80 h-80 bg-secondary/10 rounded-full blur-3xl pointer-events-none"></div>

  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-12 relative z-10">
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-on-primary/60 text-xs mb-5">
      <a href="{{ route('front.home') }}" class="hover:text-on-primary transition-colors">{{ $t('ໜ້າຫຼັກ','Home','首頁') }}</a>
      <i class="fas fa-chevron-right text-[9px]"></i>
      <a href="{{ route('front.news.index') }}" class="hover:text-on-primary transition-colors">{{ $t('ຂ່າວສານ','News','新聞') }}</a>
      <i class="fas fa-chevron-right text-[9px]"></i>
      <span class="text-on-primary/80 truncate max-w-[240px]">{{ $title }}</span>
    </div>

    <div class="max-w-3xl">
      @if($catName)
        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-secondary/90 text-on-secondary
                     text-[10px] font-bold rounded-full mb-4">
          <i class="fas fa-tag text-[8px]"></i>{{ $catName }}
        </span>
      @endif
      <h1 class="text-2xl sm:text-3xl md:text-4xl font-serif font-bold text-on-primary leading-snug mb-4">
        {{ $title }}
      </h1>
      <div class="flex flex-wrap items-center gap-4 text-on-primary/60 text-xs">
        @if($item->published_at)
          <span class="flex items-center gap-1.5">
            <i class="far fa-calendar text-secondary text-sm"></i>
            {{ $item->published_at->translatedFormat('d F Y') }}
          </span>
        @endif
        @if($item->view_count)
          <span class="flex items-center gap-1.5">
            <i class="far fa-eye text-secondary text-sm"></i>
            {{ number_format($item->view_count) }} {{ $t('ການເບິ່ງ','views','次') }}
          </span>
        @endif
        @if($item->author)
          <span class="flex items-center gap-1.5">
            <i class="far fa-user text-secondary text-sm"></i>
            {{ $item->author->name }}
          </span>
        @endif
      </div>
    </div>
  </div>
</section>

{{-- ═══ CONTENT ═══ --}}
<section class="bg-slate-50 py-10">
  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">

      <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_2px_24px_-6px_rgba(0,0,0,.1)] overflow-hidden">
        {{-- Hero image --}}
        @if($thumb)
          <div class="w-full aspect-[16/9] overflow-hidden">
            <img src="{{ $thumb }}" alt="{{ $title }}" class="w-full h-full object-cover" />
          </div>
        @endif

        {{-- Article body --}}
        <div class="p-6 md:p-10">
          @if($excerpt)
            <p class="text-on-surface-variant font-medium text-sm leading-relaxed mb-6 border-l-4 border-secondary pl-4
                       bg-secondary/5 py-3 pr-4 rounded-r-xl">
              {{ $excerpt }}
            </p>
          @endif

          <div class="prose-bfol">
            {!! $content !!}
          </div>
        </div>

        {{-- Tags --}}
        @if($item->tags?->isNotEmpty())
          <div class="px-6 md:px-10 pb-6 flex flex-wrap gap-2">
            @foreach($item->tags as $tag)
              <span class="px-3 py-1 bg-surface-container-low text-on-surface-variant text-xs font-semibold rounded-full border border-surface-container-high">
                #{{ $tag->name_lo ?? $tag->name }}
              </span>
            @endforeach
          </div>
        @endif

        {{-- Footer --}}
        <div class="px-6 md:px-10 py-4 border-t border-slate-100 bg-slate-50/70 flex items-center justify-between gap-4">
          <a href="{{ route('front.news.index') }}"
             class="inline-flex items-center gap-2 text-sm font-semibold text-on-surface-variant
                    hover:text-primary transition-colors">
            <i class="fas fa-arrow-left text-xs"></i>
            {{ $t('ກັບຄືນ','Back to news','返回') }}
          </a>
          <div class="flex items-center gap-2">
            <span class="text-[10px] font-bold text-outline uppercase tracking-wide">Share:</span>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
               target="_blank" rel="noreferrer"
               class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700 transition-colors text-sm">
              <i class="fab fa-facebook-f text-xs"></i>
            </a>
          </div>
        </div>
      </div>

      {{-- Related --}}
      @if($related->isNotEmpty())
        <div class="mt-10">
          <h3 class="font-serif font-bold text-on-surface text-lg mb-5 flex items-center gap-2">
            <i class="fas fa-newspaper text-secondary text-sm"></i>
            {{ $t('ຂ່າວທີ່ກ່ຽວຂ້ອງ','Related News','相關新聞') }}
          </h3>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach($related as $rel)
              @php
                $rThumb = $rel->thumbnail;
                if ($rThumb && !\Str::startsWith($rThumb, ['http','https','/storage'])) {
                    $rThumb = \Illuminate\Support\Facades\Storage::url($rThumb);
                }
              @endphp
              <a href="{{ route('front.news.show', $rel->slug) }}"
                 class="group flex gap-4 bg-white rounded-2xl border border-slate-100 p-4
                        hover:shadow-md hover:border-primary/20 hover:-translate-y-0.5 transition-all duration-300">
                <div class="w-20 h-20 rounded-xl overflow-hidden bg-slate-100 shrink-0">
                  @if($rThumb)
                    <img src="{{ $rThumb }}" alt="{{ $rel->trans('title') }}"
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                  @else
                    <div class="w-full h-full bg-primary/8 flex items-center justify-center">
                      <i class="fas fa-newspaper text-primary/20 text-xl"></i>
                    </div>
                  @endif
                </div>
                <div class="min-w-0 flex-1">
                  <h4 class="text-sm font-bold text-on-surface group-hover:text-primary transition-colors line-clamp-2 mb-1.5">
                    {{ $rel->trans('title') }}
                  </h4>
                  @if($rel->published_at)
                    <span class="text-[10px] text-outline flex items-center gap-1">
                      <i class="far fa-calendar text-[9px]"></i>
                      {{ $rel->published_at->translatedFormat('d M Y') }}
                    </span>
                  @endif
                </div>
              </a>
            @endforeach
          </div>
        </div>
      @endif

    </div>
  </div>
</section>

@endsection
