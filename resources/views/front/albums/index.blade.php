@extends('front.layouts.app')

@php
  $L  = app()->getLocale();
  $t  = fn($lo,$en,$zh) => match($L){'zh'=>$zh,'en'=>$en,default=>$lo};
  $tf = fn($m,$f) => $m->{$f.'_'.$L} ?? $m->{$f.'_lo'} ?? '';
@endphp

@section('title', $t('ຄັງຮູບ','Photo Gallery','相冊').' - '.($settings->site_name_lo ?: 'BFOL'))
@section('meta_description', $t('ຄັງຮູບພາບ ກິດຈະກຳ ແລະ ງານຕ່າງໆ ຂອງ ອພສ','BFOL photo albums and event galleries','老撾佛協活動相冊與圖片庫'))

@push('styles')
<style>
  .dot-pattern { background-image:radial-gradient(circle,rgba(255,255,255,.12) 1px,transparent 1px); background-size:28px 28px; }
  .album-card:hover .album-thumb { transform:scale(1.07); }
  .album-thumb { transition:transform .5s cubic-bezier(.25,.46,.45,.94); }
  [x-cloak] { display:none !important; }
</style>
@endpush

@section('content')

{{-- ═══ HERO ═══ --}}
<section class="relative bg-primary overflow-hidden">
  <div class="dot-pattern absolute inset-0 pointer-events-none"></div>
  <div class="absolute -top-32 -right-32 w-96 h-96 bg-secondary/10 rounded-full blur-3xl pointer-events-none"></div>
  <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>

  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-14 relative z-10">

    {{-- breadcrumb --}}
    <div class="flex items-center gap-2 text-on-primary/60 text-xs mb-5">
      <a href="{{ route('front.home') }}" class="hover:text-on-primary transition-colors">{{ $t('ໜ້າຫຼັກ','Home','首頁') }}</a>
      <i class="fas fa-chevron-right text-[9px]"></i>
      <span class="text-on-primary/90">{{ $t('ຄັງຮູບ','Gallery','相冊') }}</span>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-end gap-6">
      <div class="flex-1">
        <span class="inline-flex items-center gap-1.5 text-xs font-bold text-secondary uppercase tracking-widest mb-3">
          <span class="w-1.5 h-1.5 rounded-full bg-secondary inline-block"></span>
          {{ $t('ຄັງຮູບ ສ.ຊ.ພ.ລ','BFOL Photo Gallery','老撾佛協相冊') }}
        </span>
        <h1 class="text-3xl sm:text-4xl font-serif font-bold text-on-primary mb-2">
          {{ $t('ຄັງຮູບ ກິດຈະກຳ','Photo Albums','活動相冊') }}
        </h1>
        <p class="text-on-primary/70 text-sm max-w-lg">
          {{ $t('ຮູບພາບ ຈາກກິດຈະກຳ, ພິທີ ແລະ ງານຕ່າງໆ ຂອງ ສ.ຊ.ພ.ລ',
                'Photos from BFOL events, ceremonies and activities',
                '老撾佛協各類活動、典禮及工作的照片集錦') }}
        </p>
      </div>
      <div class="bg-white/10 backdrop-blur-sm rounded-2xl px-6 py-3 text-center border border-white/20 shrink-0">
        <p class="text-3xl font-extrabold text-on-primary">{{ $albums->total() }}</p>
        <p class="text-[11px] text-on-primary/70 mt-0.5">{{ $t('ອາລ໌ບໍ້','Albums','相冊') }}</p>
      </div>
    </div>
  </div>
</section>

{{-- ═══ GRID ═══ --}}
<section class="bg-surface py-12">
  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">

    @if($albums->isEmpty())
      <div class="text-center py-24 text-on-surface-variant">
        <i class="fas fa-images text-5xl opacity-20 mb-4"></i>
        <p class="text-lg font-semibold">{{ $t('ຍັງບໍ່ມີອາລ໌ບໍ້','No albums yet','暫無相冊') }}</p>
      </div>
    @else
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
        @foreach($albums as $album)
          @php
            $cover = $album->cover_image ?? $album->images->first()?->image_url;
          @endphp
          <a href="{{ route('front.gallery.show', $album) }}"
             class="album-card group bg-surface-container-lowest rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1">

            {{-- Thumbnail --}}
            <div class="relative overflow-hidden aspect-[4/3] bg-surface-container">
              @if($cover)
                <img src="{{ $cover }}" alt="{{ $tf($album,'title') }}"
                     class="album-thumb w-full h-full object-cover" loading="lazy" />
              @else
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary/5 to-secondary/10">
                  <i class="fas fa-images text-4xl text-primary/30"></i>
                </div>
              @endif

              {{-- Overlay --}}
              <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent
                          opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

              {{-- Image count badge --}}
              <div class="absolute bottom-2.5 right-2.5 flex items-center gap-1
                          bg-black/60 backdrop-blur-sm text-white text-xs font-bold px-2.5 py-1 rounded-full">
                <i class="fas fa-image text-[10px]"></i>
                {{ $album->images_count }}
              </div>

              {{-- Hover: view icon --}}
              <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <div class="w-12 h-12 rounded-full bg-white/90 flex items-center justify-center shadow-lg">
                  <i class="fas fa-expand text-primary text-base"></i>
                </div>
              </div>
            </div>

            {{-- Info --}}
            <div class="p-4">
              <h3 class="font-bold text-sm text-on-surface leading-snug line-clamp-2 group-hover:text-primary transition-colors">
                {{ $tf($album,'title') }}
              </h3>
              @if($tf($album,'description'))
                <p class="text-xs text-on-surface-variant mt-1.5 line-clamp-2 leading-relaxed">
                  {{ $tf($album,'description') }}
                </p>
              @endif
              <div class="mt-3 flex items-center justify-between">
                <span class="text-[11px] text-outline">
                  {{ $album->created_at->translatedFormat('d M Y') }}
                </span>
                <span class="text-xs font-semibold text-primary flex items-center gap-1
                             group-hover:gap-2 transition-all duration-200">
                  {{ $t('ເບິ່ງ','View','查看') }}
                  <i class="fas fa-arrow-right text-[10px]"></i>
                </span>
              </div>
            </div>
          </a>
        @endforeach
      </div>

      {{-- Pagination --}}
      @if($albums->hasPages())
        <div class="mt-10">{{ $albums->links() }}</div>
      @endif
    @endif
  </div>
</section>

@endsection
