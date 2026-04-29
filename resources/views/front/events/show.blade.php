@extends('front.layouts.app')

@php
  $L = app()->getLocale();
  $t = fn($lo,$en,$zh) => match($L){'zh'=>$zh,'en'=>$en,default=>$lo};
  $tf = fn($m,$f) => $m->{$f.'_'.$L} ?? $m->{$f.'_lo'} ?? '';

  $thumb = $item->thumbnail;
  if ($thumb && !\Str::startsWith($thumb, ['http','https','/storage'])) {
      $thumb = \Illuminate\Support\Facades\Storage::url($thumb);
  }
  $title    = $item->trans('title');
  $excerpt  = $item->trans('excerpt');
  $desc     = $item->trans('description');
  $catName  = $item->category?->trans('name');
  $location = $tf($item,'location');
  $org      = $item->trans('organizer');

  $statusConfig = [
    'upcoming'  => ['label'=>$t('ກຳລັງຈະມາ','Upcoming','即將舉行'),  'bg'=>'bg-emerald-100','text'=>'text-emerald-700'],
    'ongoing'   => ['label'=>$t('ກຳລັງດຳເນີນ','Ongoing','進行中'),    'bg'=>'bg-blue-100',   'text'=>'text-blue-700'],
    'past'      => ['label'=>$t('ສຳເລັດ','Past','已結束'),             'bg'=>'bg-slate-100',  'text'=>'text-slate-600'],
    'cancelled' => ['label'=>$t('ຍົກເລີກ','Cancelled','已取消'),       'bg'=>'bg-red-100',    'text'=>'text-red-700'],
  ];
  $sc = $statusConfig[$item->status] ?? $statusConfig['past'];
@endphp

@section('title', $title.' - '.($settings->site_name_lo ?: 'BFOL'))
@section('meta_description', $excerpt ?: $title)

@push('styles')
<style>
  .prose-bfol { font-size:.9375rem; line-height:1.8; color:#1e293b; }
  .prose-bfol h2,.prose-bfol h3 { font-family:inherit; font-weight:700; color:#0f172a; margin:1.5em 0 .6em; }
  .prose-bfol h2 { font-size:1.35rem; } .prose-bfol h3 { font-size:1.1rem; }
  .prose-bfol p { margin:.9em 0; }
  .prose-bfol ul,.prose-bfol ol { margin:.8em 0 .8em 1.5rem; }
  .prose-bfol li { margin:.3em 0; }
  .prose-bfol a { color:var(--color-primary); text-decoration:underline; }
  .prose-bfol img { border-radius:.75rem; max-width:100%; height:auto; margin:1.5em 0; }
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
    <div class="flex items-center gap-2 text-on-primary/60 text-xs mb-5">
      <a href="{{ route('front.home') }}" class="hover:text-on-primary transition-colors">{{ $t('ໜ້າຫຼັກ','Home','首頁') }}</a>
      <i class="fas fa-chevron-right text-[9px]"></i>
      <a href="{{ route('front.events.index') }}" class="hover:text-on-primary transition-colors">{{ $t('ກິດຈະກຳ','Events','活動') }}</a>
      <i class="fas fa-chevron-right text-[9px]"></i>
      <span class="text-on-primary/80 truncate max-w-[240px]">{{ $title }}</span>
    </div>

    <div class="max-w-3xl">
      <div class="flex flex-wrap items-center gap-2 mb-3">
        @if($catName)
          <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-secondary/90 text-on-secondary text-[10px] font-bold rounded-full">
            <i class="fas fa-tag text-[8px]"></i>{{ $catName }}
          </span>
        @endif
        <span class="inline-flex px-3 py-1 rounded-full text-[10px] font-bold {{ $sc['bg'] }} {{ $sc['text'] }}">
          {{ $sc['label'] }}
        </span>
      </div>
      <h1 class="text-2xl sm:text-3xl md:text-4xl font-serif font-bold text-on-primary leading-snug mb-4">
        {{ $title }}
      </h1>
      <div class="flex flex-wrap items-center gap-4 text-on-primary/60 text-xs">
        @if($item->start_date)
          <span class="flex items-center gap-1.5">
            <i class="far fa-calendar text-secondary text-sm"></i>
            {{ $item->start_date->translatedFormat('d F Y') }}
            @if($item->end_date && $item->end_date != $item->start_date)
              — {{ $item->end_date->translatedFormat('d F Y') }}
            @endif
          </span>
        @endif
        @if($location)
          <span class="flex items-center gap-1.5">
            <i class="fas fa-map-marker-alt text-secondary text-sm"></i>{{ $location }}
          </span>
        @endif
        @if($item->view_count)
          <span class="flex items-center gap-1.5">
            <i class="far fa-eye text-secondary text-sm"></i>{{ number_format($item->view_count) }}
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

        {{-- Event meta grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 p-6 md:p-8 bg-slate-50/60 border-b border-slate-100">
          {{-- Date --}}
          @if($item->start_date)
            <div class="flex items-start gap-3">
              <div class="w-9 h-9 rounded-lg bg-primary/8 flex items-center justify-center shrink-0">
                <i class="far fa-calendar text-primary text-sm"></i>
              </div>
              <div>
                <p class="text-[10px] font-bold text-outline uppercase tracking-wide mb-0.5">{{ $t('ວັນທີ','Date','日期') }}</p>
                <p class="text-sm font-semibold text-on-surface">
                  {{ $item->start_date->translatedFormat('D, d M Y') }}
                  @if($item->end_date && $item->end_date != $item->start_date)
                    <br><span class="text-on-surface-variant font-normal">— {{ $item->end_date->translatedFormat('D, d M Y') }}</span>
                  @endif
                </p>
                @if($item->start_time)
                  <p class="text-xs text-outline mt-0.5">
                    {{ $item->start_time }}@if($item->end_time) – {{ $item->end_time }}@endif
                  </p>
                @endif
              </div>
            </div>
          @endif

          {{-- Location --}}
          @if($location || $item->country)
            <div class="flex items-start gap-3">
              <div class="w-9 h-9 rounded-lg bg-primary/8 flex items-center justify-center shrink-0">
                <i class="fas fa-map-marker-alt text-primary text-sm"></i>
              </div>
              <div>
                <p class="text-[10px] font-bold text-outline uppercase tracking-wide mb-0.5">{{ $t('ສະຖານທີ','Location','地點') }}</p>
                <p class="text-sm font-semibold text-on-surface">{{ $location }}</p>
                @if($item->country)
                  <p class="text-xs text-outline mt-0.5">{{ $item->country }}</p>
                @endif
              </div>
            </div>
          @endif

          {{-- Organizer --}}
          @if($org)
            <div class="flex items-start gap-3">
              <div class="w-9 h-9 rounded-lg bg-primary/8 flex items-center justify-center shrink-0">
                <i class="fas fa-building text-primary text-sm"></i>
              </div>
              <div>
                <p class="text-[10px] font-bold text-outline uppercase tracking-wide mb-0.5">{{ $t('ຜູ້ຈັດ','Organizer','主辦方') }}</p>
                <p class="text-sm font-semibold text-on-surface">{{ $org }}</p>
              </div>
            </div>
          @endif

          {{-- Registration --}}
          @if($item->registration_deadline || $item->max_participants)
            <div class="flex items-start gap-3">
              <div class="w-9 h-9 rounded-lg bg-primary/8 flex items-center justify-center shrink-0">
                <i class="fas fa-user-plus text-primary text-sm"></i>
              </div>
              <div>
                <p class="text-[10px] font-bold text-outline uppercase tracking-wide mb-0.5">{{ $t('ການລົງທະບຽນ','Registration','報名') }}</p>
                @if($item->registration_deadline)
                  <p class="text-sm font-semibold text-on-surface">
                    {{ $t('ໝົດເຂດ','Deadline','截止') }}: <span class="text-red-500">{{ \Carbon\Carbon::parse($item->registration_deadline)->translatedFormat('d M Y') }}</span>
                  </p>
                @endif
                @if($item->max_participants)
                  <p class="text-xs text-outline mt-0.5">{{ $t('ຈຳນວນ','Capacity','容量') }}: {{ $item->max_participants }}</p>
                @endif
              </div>
            </div>
          @endif
        </div>

        {{-- Description --}}
        @if($desc)
          <div class="p-6 md:p-10 prose-bfol">
            {!! $desc !!}
          </div>
        @endif

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
        <div class="px-6 md:px-10 py-4 border-t border-slate-100 bg-slate-50/70 flex items-center justify-between gap-4 flex-wrap">
          <a href="{{ route('front.events.index') }}"
             class="inline-flex items-center gap-2 text-sm font-semibold text-on-surface-variant hover:text-primary transition-colors">
            <i class="fas fa-arrow-left text-xs"></i>
            {{ $t('ກັບຄືນ','Back','返回') }}
          </a>
          @if($item->registration_url)
            <a href="{{ $item->registration_url }}" target="_blank" rel="noreferrer"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-on-primary font-bold text-sm rounded-xl
                      hover:bg-secondary hover:text-on-secondary transition-colors">
              <i class="fas fa-user-plus text-xs"></i>
              {{ $t('ລົງທະບຽນ','Register','報名') }}
              <i class="fas fa-external-link-alt text-[10px]"></i>
            </a>
          @endif
        </div>
      </div>

    </div>
  </div>
</section>

@endsection
