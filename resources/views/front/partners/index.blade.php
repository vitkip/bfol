@extends('front.layouts.app')

@php
  $L  = app()->getLocale();
  $t  = fn($lo,$en,$zh) => match($L){'zh'=>$zh,'en'=>$en,default=>$lo};
  $tf = fn($m,$f) => $m->{$f.'_'.$L} ?? $m->{$f.'_lo'} ?? '';

  $typeLabels = [
    'buddhist_org' => $t('ອົງການທາງສາສະໜາ','Buddhist Organisation','佛教機構'),
    'government'   => $t('ລັດຖະບານ','Government','政府機構'),
    'ngo'          => $t('NGO','NGO','非政府組織'),
    'academic'     => $t('ວິຊາການ','Academic','學術機構'),
    'media'        => $t('ສື່ສານ','Media','媒體機構'),
    'un_agency'    => $t('ອົງການ UN','UN Agency','聯合國機構'),
    'other'        => $t('ອື່ນໆ','Other','其他'),
  ];
@endphp

@section('title', $t('ຄູ່ຮ່ວມມືສາກົນ','International Partners','國際合作夥伴').' - '.($settings->site_name_lo ?: 'BFOL'))
@section('meta_description', $t('ລາຍຊື່ຄູ່ຮ່ວມມື ສາກົນ ຂອງ ອພສ','BFOL international partner organisations','老撾佛協國際合作夥伴名單'))

@push('styles')
<style>
  .dot-pattern { background-image:radial-gradient(circle,rgba(255,255,255,.12) 1px,transparent 1px); background-size:28px 28px; }
  .card-hover:hover .card-logo { transform:scale(1.05); }
  .card-logo { transition:transform .4s ease; }
</style>
@endpush

@section('content')

{{-- ═══ HERO ═══ --}}
<section class="relative bg-primary overflow-hidden">
  <div class="dot-pattern absolute inset-0 pointer-events-none"></div>
  <div class="absolute -top-24 -right-24 w-96 h-96 bg-secondary/10 rounded-full blur-3xl pointer-events-none"></div>
  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-12 relative z-10">
    <div class="flex items-center gap-2 text-on-primary/60 text-xs mb-4">
      <a href="{{ route('front.home') }}" class="hover:text-on-primary transition-colors">{{ $t('ໜ້າຫຼັກ','Home','首頁') }}</a>
      <i class="fas fa-chevron-right text-[9px]"></i>
      <span class="text-on-primary/80">{{ $t('ຄູ່ຮ່ວມມືສາກົນ','International Partners','國際合作夥伴') }}</span>
    </div>
    <div class="flex flex-col sm:flex-row sm:items-end gap-4">
      <div class="flex-1">
        <h1 class="text-3xl sm:text-4xl font-serif font-bold text-on-primary mb-2">
          {{ $t('ຄູ່ຮ່ວມມືສາກົນ','International Partners','國際合作夥伴') }}
        </h1>
        <p class="text-on-primary/70 text-sm max-w-lg">
          {{ $t('ອົງການ ແລະ ສະຖາບັນ ທີ່ ອພສ ຮ່ວມມືດ້ວຍ ໃນລະດັບສາກົນ','Organisations and institutions cooperating with BFOL internationally','與老撾佛協開展國際合作的組織和機構') }}
        </p>
      </div>
      <div class="bg-white/10 backdrop-blur-sm rounded-2xl px-6 py-4 text-center border border-white/20 shrink-0">
        <p class="text-3xl font-extrabold text-on-primary">{{ $totalCount }}</p>
        <p class="text-xs text-on-primary/70 mt-0.5">{{ $t('ຄູ່ຮ່ວມມືທັງໝົດ','Total Partners','合作夥伴總數') }}</p>
      </div>
    </div>
  </div>
</section>

{{-- ═══ FILTERS ═══ --}}
<div class="bg-white border-b border-slate-100 shadow-sm sticky top-[56px] sm:top-[64px] z-40">
  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center gap-2 overflow-x-auto py-3 scrollbar-hide">

      {{-- Type filter --}}
      <a href="{{ route('front.partners.index', request()->except(['type','country','page'])) }}"
         class="flex-shrink-0 px-4 py-1.5 rounded-full text-xs font-semibold transition-colors
                {{ !request('type') && !request('country') ? 'bg-primary text-on-primary' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
        {{ $t('ທັງໝົດ','All','全部') }}
      </a>

      @foreach($typeLabels as $key => $label)
        <a href="{{ route('front.partners.index', array_merge(request()->except(['type','page']), ['type' => $key])) }}"
           class="flex-shrink-0 px-4 py-1.5 rounded-full text-xs font-semibold transition-colors
                  {{ request('type') === $key ? 'bg-primary text-on-primary' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
          {{ $label }}
        </a>
      @endforeach

      @if($countries->count() > 1)
        <div class="w-px h-5 bg-slate-200 mx-1 shrink-0"></div>
        @foreach($countries as $c)
          @php
            $cName = $c->{'country_name_'.$L} ?? $c->country_name_lo;
          @endphp
          <a href="{{ route('front.partners.index', array_merge(request()->except(['country','page']), ['country' => $c->country_code])) }}"
             class="flex-shrink-0 px-4 py-1.5 rounded-full text-xs font-semibold transition-colors
                    {{ request('country') === $c->country_code ? 'bg-secondary/90 text-on-secondary' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
            {{ $cName }}
          </a>
        @endforeach
      @endif

    </div>
  </div>
</div>

{{-- ═══ GRID ═══ --}}
<section class="bg-slate-50 py-10">
  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
      @forelse($partners as $partner)
        @php
          $name    = $tf($partner, 'name');
          $country = $partner->{'country_name_'.$L} ?? $partner->country_name_lo;
          $desc    = $tf($partner, 'description');
          $typeL   = $typeLabels[$partner->type] ?? $partner->type;
        @endphp

        <article class="card-hover bg-white rounded-2xl border border-slate-100
                        shadow-[0_2px_12px_-4px_rgba(0,0,0,.08)]
                        hover:shadow-[0_6px_24px_-4px_rgba(3,22,50,.13)]
                        hover:-translate-y-0.5 transition-all duration-300 overflow-hidden flex flex-col
                        cursor-pointer"
                 onclick="window.location='{{ route('front.partners.show', $partner->id) }}'"
                 role="link">

          {{-- Logo / top area --}}
          <div class="h-32 bg-gradient-to-br from-slate-50 to-slate-100 flex items-center justify-center p-5 border-b border-slate-100 relative overflow-hidden">
            @if($partner->logo_url)
              <img src="{{ $partner->logo_url }}" alt="{{ $name }}"
                   class="card-logo max-h-20 max-w-[140px] object-contain" loading="lazy" />
            @else
              <div class="w-16 h-16 rounded-xl bg-primary/10 flex items-center justify-center">
                <i class="fas fa-globe text-primary/40 text-2xl"></i>
              </div>
            @endif
            {{-- Country flag placeholder --}}
            <span class="absolute top-3 right-3 text-xs font-bold text-slate-400 bg-white px-2 py-0.5 rounded-full border border-slate-200">
              {{ strtoupper($partner->country_code) }}
            </span>
          </div>

          {{-- Body --}}
          <div class="p-4 flex flex-col flex-1">
            {{-- Type badge --}}
            <span class="inline-block mb-2 text-[10px] font-bold text-primary bg-primary/8 px-2 py-0.5 rounded-full w-fit">
              {{ $typeL }}
            </span>

            <h3 class="font-bold text-on-surface text-sm leading-snug mb-1">
              {{ $name }}
              @if($partner->acronym)
                <span class="text-outline font-normal text-xs ml-1">({{ $partner->acronym }})</span>
              @endif
            </h3>

            <p class="text-xs text-outline flex items-center gap-1 mb-2">
              <i class="fas fa-map-marker-alt text-[9px] shrink-0"></i>
              {{ $country }}
            </p>

            @if($desc)
              <p class="text-[12px] text-on-surface-variant/70 line-clamp-3 leading-relaxed mb-3 flex-1">{{ $desc }}</p>
            @else
              <div class="flex-1"></div>
            @endif

            {{-- Footer --}}
            <div class="flex items-center justify-between pt-3 border-t border-slate-100 mt-auto">
              @if($partner->partnership_since)
                <span class="text-[10px] text-outline">
                  <i class="fas fa-handshake text-[9px] mr-0.5"></i>
                  {{ $partner->partnership_since }}
                </span>
              @else
                <span></span>
              @endif

              @if($partner->website_url)
                <a href="{{ $partner->website_url }}" target="_blank" rel="noreferrer"
                   onclick="event.stopPropagation()"
                   class="inline-flex items-center gap-1 text-[11px] font-semibold text-primary hover:text-secondary transition-colors">
                  {{ $t('ເວັບໄຊ','Website','官網') }}
                  <i class="fas fa-external-link-alt text-[9px]"></i>
                </a>
              @else
                <a href="{{ route('front.partners.show', $partner->id) }}"
                   onclick="event.stopPropagation()"
                   class="inline-flex items-center gap-1 text-[11px] font-semibold text-primary hover:text-secondary transition-colors">
                  {{ $t('ລາຍລະອຽດ','Details','詳情') }}
                  <i class="fas fa-arrow-right text-[9px]"></i>
                </a>
              @endif
            </div>
          </div>
        </article>

      @empty
        <div class="col-span-full py-24 flex flex-col items-center text-center">
          <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center mb-4">
            <i class="fas fa-handshake text-slate-300 text-3xl"></i>
          </div>
          <p class="font-semibold text-on-surface-variant">{{ $t('ຍັງບໍ່ມີຂໍ້ມູນ','No partners found','暫無數據') }}</p>
        </div>
      @endforelse
    </div>

    {{-- Pagination --}}
    @if($partners->hasPages())
      <div class="flex justify-center mt-10">
        <div class="flex items-center gap-1">
          @if($partners->onFirstPage())
            <span class="px-3 py-2 rounded-lg text-outline bg-white border border-slate-100 cursor-not-allowed text-sm">←</span>
          @else
            <a href="{{ $partners->previousPageUrl() }}" class="px-3 py-2 rounded-lg text-on-surface-variant bg-white border border-slate-100 hover:bg-surface-container text-sm transition-colors">←</a>
          @endif
          @foreach($partners->getUrlRange(max(1,$partners->currentPage()-2), min($partners->lastPage(),$partners->currentPage()+2)) as $page => $url)
            <a href="{{ $url }}"
               class="px-3.5 py-2 rounded-lg text-sm transition-colors
                      {{ $page === $partners->currentPage() ? 'bg-primary text-on-primary font-bold shadow-sm' : 'bg-white border border-slate-100 text-on-surface-variant hover:bg-surface-container' }}">
              {{ $page }}
            </a>
          @endforeach
          @if($partners->hasMorePages())
            <a href="{{ $partners->nextPageUrl() }}" class="px-3 py-2 rounded-lg text-on-surface-variant bg-white border border-slate-100 hover:bg-surface-container text-sm transition-colors">→</a>
          @else
            <span class="px-3 py-2 rounded-lg text-outline bg-white border border-slate-100 cursor-not-allowed text-sm">→</span>
          @endif
        </div>
      </div>
    @endif

  </div>
</section>

@endsection
