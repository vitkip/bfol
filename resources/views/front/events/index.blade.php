@extends('front.layouts.app')

@php
  $L = app()->getLocale();
  $t = fn($lo,$en,$zh) => match($L){'zh'=>$zh,'en'=>$en,default=>$lo};
  $tf = fn($m,$f) => $m->{$f.'_'.$L} ?? $m->{$f.'_lo'} ?? '';

  $statusConfig = [
    'upcoming' => ['label'=>$t('ກຳລັງຈະມາ','Upcoming','即將舉行'), 'bg'=>'bg-emerald-100','text'=>'text-emerald-700'],
    'ongoing'  => ['label'=>$t('ກຳລັງດຳເນີນ','Ongoing','進行中'),    'bg'=>'bg-blue-100',   'text'=>'text-blue-700'],
    'past'     => ['label'=>$t('ສຳເລັດແລ້ວ','Past','已結束'),         'bg'=>'bg-slate-100',  'text'=>'text-slate-600'],
    'cancelled'=> ['label'=>$t('ຍົກເລີກ','Cancelled','已取消'),        'bg'=>'bg-red-100',    'text'=>'text-red-700'],
  ];
@endphp

@section('title', $t('ກິດຈະກຳ','Events','活動').' - '.($settings->site_name_lo ?: 'BFOL'))
@section('meta_description', $t('ກິດຈະກຳ ແລະ ງານຕ່າງໆ ຂອງ ສ.ຊ.ພ.ລ','BFOL events and activities','老撾佛協活動'))

@push('styles')
<style>
  .dot-pattern { background-image:radial-gradient(circle,rgba(255,255,255,.12) 1px,transparent 1px); background-size:28px 28px; }
  .card-hover:hover .thumb-img { transform:scale(1.06); }
  .thumb-img { transition:transform .5s ease; }
</style>
@endpush

@section('content')

{{-- ═══ HERO ═══ --}}
<section class="relative bg-primary overflow-hidden">
  <div class="dot-pattern absolute inset-0 pointer-events-none"></div>
  <div class="absolute -top-24 -right-24 w-80 h-80 bg-secondary/10 rounded-full blur-3xl pointer-events-none"></div>
  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-12 relative z-10">
    <div class="flex items-center gap-2 text-on-primary/60 text-xs mb-4">
      <a href="{{ route('front.home') }}" class="hover:text-on-primary transition-colors">{{ $t('ໜ້າຫຼັກ','Home','首頁') }}</a>
      <i class="fas fa-chevron-right text-[9px]"></i>
      <span class="text-on-primary/80">{{ $t('ກິດຈະກຳ','Events','活動') }}</span>
    </div>
    <h1 class="text-3xl sm:text-4xl font-serif font-bold text-on-primary mb-2">
      {{ $t('ກິດຈະກຳ ແລະ ງານ','Events & Activities','活動') }}
    </h1>
    <p class="text-on-primary/70 text-sm max-w-lg">
      {{ $t('ກິດຈະກຳ ທີ່ ສ.ຊ.ພ.ລ ຈັດຂຶ້ນ ຫຼືໃຫ້ການສະໜັບສະໜູນ','Events organized or supported by BFOL','老撾佛協舉辦或支持的活動') }}
    </p>
  </div>
</section>

{{-- ═══ FILTERS ═══ --}}
<div class="bg-white border-b border-slate-100 shadow-sm">
  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center gap-2 overflow-x-auto py-3 scrollbar-hide">
      {{-- Status tabs --}}
      @foreach(['' => $t('ທັງໝົດ','All','全部'), 'upcoming' => $t('ກຳລັງຈະມາ','Upcoming','即將'), 'ongoing' => $t('ກຳລັງດຳເນີນ','Ongoing','進行中'), 'past' => $t('ຜ່ານມາ','Past','已過')] as $s => $sl)
        <a href="{{ route('front.events.index', array_merge(request()->except(['status','page']), $s ? ['status'=>$s] : [])) }}"
           class="flex-shrink-0 px-4 py-1.5 rounded-full text-xs font-semibold transition-colors
                  {{ request('status','') === $s ? 'bg-primary text-on-primary' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
          {{ $sl }}
        </a>
      @endforeach

      @if($categories->isNotEmpty())
        <div class="w-px h-5 bg-slate-200 mx-1 shrink-0"></div>
        <a href="{{ route('front.events.index', request()->except(['category','page'])) }}"
           class="flex-shrink-0 px-4 py-1.5 rounded-full text-xs font-semibold transition-colors
                  {{ !request('category') ? 'bg-secondary/90 text-on-secondary' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
          {{ $t('ທຸກໝວດ','All Categories','所有分類') }}
        </a>
        @foreach($categories as $cat)
          <a href="{{ route('front.events.index', array_merge(request()->except(['category','page']), ['category' => $cat->slug])) }}"
             class="flex-shrink-0 px-4 py-1.5 rounded-full text-xs font-semibold transition-colors
                    {{ request('category') === $cat->slug ? 'bg-secondary/90 text-on-secondary' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
            {{ $cat->trans('name') }}
          </a>
        @endforeach
      @endif
    </div>
  </div>
</div>

{{-- ═══ EVENTS GRID ═══ --}}
<section class="bg-slate-50 py-10">
  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
      @forelse($events as $item)
        @php
          $thumb = $item->thumbnail;
          if ($thumb && !\Str::startsWith($thumb, ['http','https','/storage'])) {
              $thumb = \Illuminate\Support\Facades\Storage::url($thumb);
          }
          $title    = $item->trans('title');
          $excerpt  = $item->trans('excerpt');
          $location = $tf($item,'location');
          $catName  = $item->category?->trans('name');
          $sc       = $statusConfig[$item->status] ?? $statusConfig['past'];
        @endphp

        <article class="card-hover group bg-white rounded-2xl border border-slate-100
                        shadow-[0_2px_12px_-4px_rgba(0,0,0,.08)]
                        hover:shadow-[0_6px_24px_-4px_rgba(3,22,50,.13)]
                        hover:-translate-y-0.5 transition-all duration-300 overflow-hidden flex flex-col">
          {{-- Thumbnail --}}
          <a href="{{ route('front.events.show', $item->slug) }}"
             class="relative h-52 bg-slate-100 overflow-hidden block shrink-0">
            @if($thumb)
              <img src="{{ $thumb }}" alt="{{ $title }}"
                   class="thumb-img w-full h-full object-cover" loading="lazy" />
            @else
              <div class="w-full h-full bg-primary/8 flex items-center justify-center">
                <i class="fas fa-calendar-alt text-primary/20 text-5xl"></i>
              </div>
            @endif
            {{-- Status badge --}}
            <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[10px] font-bold {{ $sc['bg'] }} {{ $sc['text'] }}">
              {{ $sc['label'] }}
            </span>
            @if($catName)
              <span class="absolute bottom-3 left-3 px-2.5 py-1 bg-primary/85 backdrop-blur-sm
                           text-on-primary text-[10px] font-bold rounded-lg">
                {{ $catName }}
              </span>
            @endif
          </a>

          {{-- Body --}}
          <div class="p-5 flex flex-col flex-1">
            <div class="flex items-center gap-3 text-[10px] text-outline mb-2.5 flex-wrap">
              @if($item->start_date)
                <span class="flex items-center gap-1">
                  <i class="far fa-calendar text-secondary"></i>
                  {{ $item->start_date->translatedFormat('d M Y') }}
                </span>
              @endif
              @if($location)
                <span class="flex items-center gap-1 truncate max-w-[140px]">
                  <i class="fas fa-map-marker-alt text-secondary"></i>
                  {{ $location }}
                </span>
              @endif
            </div>

            <h3 class="font-bold text-on-surface text-sm leading-snug line-clamp-3 mb-2
                       group-hover:text-primary transition-colors flex-1">
              <a href="{{ route('front.events.show', $item->slug) }}">{{ $title }}</a>
            </h3>

            @if($excerpt)
              <p class="text-[12px] text-on-surface-variant/70 line-clamp-2 leading-relaxed mb-3">{{ $excerpt }}</p>
            @endif

            <a href="{{ route('front.events.show', $item->slug) }}"
               class="inline-flex items-center gap-1.5 text-xs font-bold text-primary
                      hover:text-secondary transition-colors mt-auto pt-3 border-t border-slate-100">
              {{ $t('ລາຍລະອຽດ','Details','詳情') }}
              <i class="fas fa-arrow-right text-[10px] group-hover:translate-x-0.5 transition-transform"></i>
            </a>
          </div>
        </article>

      @empty
        <div class="col-span-full py-24 flex flex-col items-center text-center">
          <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center mb-4">
            <i class="fas fa-calendar-times text-slate-300 text-3xl"></i>
          </div>
          <p class="font-semibold text-on-surface-variant">{{ $t('ຍັງບໍ່ມີກິດຈະກຳ','No events found','暫無活動') }}</p>
        </div>
      @endforelse
    </div>

    {{-- Pagination --}}
    @if($events->hasPages())
      <div class="flex justify-center mt-10">
        <div class="flex items-center gap-1">
          @if($events->onFirstPage())
            <span class="px-3 py-2 rounded-lg text-outline bg-white border border-slate-100 cursor-not-allowed text-sm">←</span>
          @else
            <a href="{{ $events->previousPageUrl() }}" class="px-3 py-2 rounded-lg text-on-surface-variant bg-white border border-slate-100 hover:bg-surface-container text-sm transition-colors">←</a>
          @endif
          @foreach($events->getUrlRange(max(1,$events->currentPage()-2), min($events->lastPage(),$events->currentPage()+2)) as $page => $url)
            <a href="{{ $url }}"
               class="px-3.5 py-2 rounded-lg text-sm transition-colors
                      {{ $page === $events->currentPage()
                         ? 'bg-primary text-on-primary font-bold shadow-sm'
                         : 'bg-white border border-slate-100 text-on-surface-variant hover:bg-surface-container' }}">{{ $page }}</a>
          @endforeach
          @if($events->hasMorePages())
            <a href="{{ $events->nextPageUrl() }}" class="px-3 py-2 rounded-lg text-on-surface-variant bg-white border border-slate-100 hover:bg-surface-container text-sm transition-colors">→</a>
          @else
            <span class="px-3 py-2 rounded-lg text-outline bg-white border border-slate-100 cursor-not-allowed text-sm">→</span>
          @endif
        </div>
      </div>
    @endif

  </div>
</section>

@endsection
