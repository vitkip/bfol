@extends('front.layouts.app')

@php
  $L  = app()->getLocale();
  $t  = fn($lo,$en,$zh) => match($L){'zh'=>$zh,'en'=>$en,default=>$lo};
  $tf = fn($m,$f) => $m->{$f.'_'.$L} ?? $m->{$f.'_lo'} ?? '';
  $title = $tf($album,'title');
@endphp

@section('title', $title.' - '.($settings->site_name_lo ?: 'BFOL'))
@section('meta_description', $tf($album,'description') ?: $title)
@if($album->cover_image)
  @section('og_image', $album->cover_image)
@endif

@push('styles')
<style>
  .dot-pattern { background-image:radial-gradient(circle,rgba(255,255,255,.12) 1px,transparent 1px); background-size:28px 28px; }
  [x-cloak] { display:none !important; }

  /* Masonry-like grid */
  .photo-grid { columns: 2; gap: 12px; }
  @media(min-width:640px)  { .photo-grid { columns: 3; } }
  @media(min-width:1024px) { .photo-grid { columns: 4; } }
  .photo-item { break-inside: avoid; margin-bottom: 12px; }
  .photo-item img { display: block; width: 100%; border-radius: .75rem; cursor: zoom-in;
                    transition: transform .35s ease, box-shadow .35s ease; }
  .photo-item:hover img { transform: scale(1.02); box-shadow: 0 8px 30px rgba(0,0,0,.18); }

  /* Lightbox */
  #lightbox { position:fixed; inset:0; z-index:9999; display:flex; align-items:center;
              justify-content:center; background:rgba(0,0,0,.92); backdrop-filter:blur(6px); }
  #lightbox img { max-width:90vw; max-height:88vh; object-fit:contain; border-radius:6px;
                  box-shadow:0 24px 80px rgba(0,0,0,.6); }
</style>
@endpush

@section('content')

{{-- ═══ HERO ═══ --}}
<section class="relative bg-primary overflow-hidden">
  <div class="dot-pattern absolute inset-0 pointer-events-none"></div>
  <div class="absolute -bottom-24 -right-24 w-80 h-80 bg-secondary/10 rounded-full blur-3xl pointer-events-none"></div>

  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-12 relative z-10">

    {{-- breadcrumb --}}
    <div class="flex items-center gap-2 text-on-primary/60 text-xs mb-4">
      <a href="{{ route('front.home') }}" class="hover:text-on-primary transition-colors">{{ $t('ໜ້າຫຼັກ','Home','首頁') }}</a>
      <i class="fas fa-chevron-right text-[9px]"></i>
      <a href="{{ route('front.gallery.index') }}" class="hover:text-on-primary transition-colors">{{ $t('ຄັງຮູບ','Gallery','相冊') }}</a>
      <i class="fas fa-chevron-right text-[9px]"></i>
      <span class="text-on-primary/90 line-clamp-1 max-w-[200px]">{{ $title }}</span>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-end gap-5">
      <div class="flex-1">
        <h1 class="text-3xl sm:text-4xl font-serif font-bold text-on-primary mb-2">
          {{ $title }}
        </h1>
        @if($tf($album,'description'))
          <p class="text-on-primary/70 text-sm max-w-xl">{{ $tf($album,'description') }}</p>
        @endif
        @if($album->event)
          <div class="mt-3 inline-flex items-center gap-1.5 text-xs text-secondary bg-white/10 px-3 py-1.5 rounded-full border border-white/20">
            <i class="fas fa-calendar-alt text-[10px]"></i>
            {{ $tf($album->event,'title') }}
          </div>
        @endif
      </div>
      <div class="flex items-center gap-3 shrink-0">
        <div class="bg-white/10 backdrop-blur-sm rounded-2xl px-5 py-3 text-center border border-white/20">
          <p class="text-2xl font-extrabold text-on-primary">{{ $album->images->count() }}</p>
          <p class="text-[11px] text-on-primary/70 mt-0.5">{{ $t('ຮູບ','Photos','照片') }}</p>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ═══ GALLERY ═══ --}}
<section class="bg-surface py-10"
         x-data="{
           cur: null,
           idx: 0,
           imgs: {{ $album->images->pluck('image_url')->toJson() }},
           captions: {{ $album->images->pluck('caption_'.$L)->toJson() }},
           open(i)  { this.idx = i; this.cur = this.imgs[i]; document.body.style.overflow='hidden'; },
           close()  { this.cur = null; document.body.style.overflow=''; },
           prev()   { this.idx = (this.idx - 1 + this.imgs.length) % this.imgs.length; this.cur = this.imgs[this.idx]; },
           next()   { this.idx = (this.idx + 1) % this.imgs.length; this.cur = this.imgs[this.idx]; },
         }"
         @keydown.escape.window="close()"
         @keydown.arrow-left.window="cur && prev()"
         @keydown.arrow-right.window="cur && next()">

  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">

    @if($album->images->isEmpty())
      <div class="text-center py-20 text-on-surface-variant">
        <i class="fas fa-images text-5xl opacity-20 mb-4"></i>
        <p class="text-lg font-semibold">{{ $t('ຍັງບໍ່ມີຮູບ','No photos yet','暫無照片') }}</p>
      </div>
    @else
      {{-- Photo grid --}}
      <div class="photo-grid">
        @foreach($album->images as $i => $img)
          <div class="photo-item" @click="open({{ $i }})">
            <img src="{{ $img->image_url }}"
                 alt="{{ $img->{'caption_'.$L} ?: $title }}"
                 loading="lazy" />
            @if($img->{'caption_'.$L})
              <p class="text-[11px] text-on-surface-variant mt-1 px-1 line-clamp-1">
                {{ $img->{'caption_'.$L} }}
              </p>
            @endif
          </div>
        @endforeach
      </div>
    @endif

    {{-- Back link --}}
    <div class="mt-10 text-center">
      <a href="{{ route('front.gallery.index') }}"
         class="inline-flex items-center gap-2 text-sm font-semibold text-primary
                hover:text-secondary transition-colors">
        <i class="fas fa-arrow-left text-xs"></i>
        {{ $t('ກັບໄປຄັງຮູບ','Back to Gallery','返回相冊') }}
      </a>
    </div>
  </div>

  {{-- ── Lightbox ── --}}
  <div id="lightbox" x-show="cur" x-cloak @click.self="close()">

    {{-- Close --}}
    <button @click="close()"
            class="absolute top-4 right-4 z-10 w-10 h-10 rounded-full bg-white/10 hover:bg-white/20
                   flex items-center justify-center text-white transition-colors">
      <i class="fas fa-times"></i>
    </button>

    {{-- Prev --}}
    <button @click.stop="prev()"
            class="absolute left-4 z-10 w-11 h-11 rounded-full bg-white/10 hover:bg-white/20
                   flex items-center justify-center text-white transition-colors">
      <i class="fas fa-chevron-left"></i>
    </button>

    {{-- Image --}}
    <div class="flex flex-col items-center gap-3 px-16">
      <img :src="cur" :alt="captions[idx] || ''" />
      <p x-show="captions[idx]" x-text="captions[idx]"
         class="text-white/70 text-sm text-center max-w-lg"></p>
      <p class="text-white/40 text-xs" x-text="(idx+1)+' / '+imgs.length"></p>
    </div>

    {{-- Next --}}
    <button @click.stop="next()"
            class="absolute right-4 z-10 w-11 h-11 rounded-full bg-white/10 hover:bg-white/20
                   flex items-center justify-center text-white transition-colors">
      <i class="fas fa-chevron-right"></i>
    </button>
  </div>

</section>

@endsection
