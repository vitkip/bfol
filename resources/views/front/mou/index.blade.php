@extends('front.layouts.app')

@php
  $L  = app()->getLocale();
  $t  = fn($lo,$en,$zh) => match($L){'zh'=>$zh,'en'=>$en,default=>$lo};
  $tf = fn($m,$f) => $m->{$f.'_'.$L} ?? $m->{$f.'_lo'} ?? '';

  $statusConfig = [
    'active'     => ['label'=>$t('ດຳເນີນຢູ່','Active','有效中'),    'bg'=>'bg-emerald-100','text'=>'text-emerald-700'],
    'expired'    => ['label'=>$t('ໝົດອາຍຸ','Expired','已到期'),      'bg'=>'bg-slate-100',  'text'=>'text-slate-500'],
    'pending'    => ['label'=>$t('ລໍຖ້າ','Pending','待簽署'),         'bg'=>'bg-amber-100',  'text'=>'text-amber-700'],
    'renewed'    => ['label'=>$t('ຕໍ່ໄດ້ໃໝ່','Renewed','已續簽'),     'bg'=>'bg-blue-100',   'text'=>'text-blue-700'],
    'terminated' => ['label'=>$t('ຍົກເລີກ','Terminated','已終止'),    'bg'=>'bg-red-100',    'text'=>'text-red-700'],
  ];
@endphp

@section('title', $t('ຂໍ້ຕົກລົງ MOU','MOU Agreements','MOU協議').' - '.($settings->site_name_lo ?: 'BFOL'))
@section('meta_description', $t('ຂໍ້ຕົກລົງ MOU ສາກົນ ຂອງ ອພສ','BFOL international MOU agreements','老撾佛協國際MOU協議'))

@push('styles')
<style>
  .dot-pattern { background-image:radial-gradient(circle,rgba(255,255,255,.12) 1px,transparent 1px); background-size:28px 28px; }
</style>
@endpush

@section('content')

{{-- ═══ HERO ═══ --}}
<section class="relative bg-primary overflow-hidden">
  <div class="dot-pattern absolute inset-0 pointer-events-none"></div>
  <div class="absolute -bottom-20 -left-20 w-72 h-72 bg-secondary/10 rounded-full blur-3xl pointer-events-none"></div>
  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-12 relative z-10">
    <div class="flex items-center gap-2 text-on-primary/60 text-xs mb-4">
      <a href="{{ route('front.home') }}" class="hover:text-on-primary transition-colors">{{ $t('ໜ້າຫຼັກ','Home','首頁') }}</a>
      <i class="fas fa-chevron-right text-[9px]"></i>
      <span class="text-on-primary/80">MOU</span>
    </div>
    <div class="flex flex-col sm:flex-row sm:items-end gap-4">
      <div class="flex-1">
        <h1 class="text-3xl sm:text-4xl font-serif font-bold text-on-primary mb-2">
          {{ $t('ຂໍ້ຕົກລົງ MOU','MOU Agreements','國際MOU協議') }}
        </h1>
        <p class="text-on-primary/70 text-sm max-w-lg">
          {{ $t('ຂໍ້ຕົກລົງຄວາມຮ່ວມມືກັບອົງການຕ່າງປະເທດ ຂອງ ອພສ','BFOL memoranda of understanding with international organisations','老撾佛協與國際組織簽署的合作備忘錄') }}
        </p>
      </div>
      <div class="flex gap-3 shrink-0">
        <div class="bg-white/10 backdrop-blur-sm rounded-2xl px-5 py-3 text-center border border-white/20">
          <p class="text-2xl font-extrabold text-on-primary">{{ $counts['active'] }}</p>
          <p class="text-[11px] text-on-primary/70">{{ $t('ດຳເນີນຢູ່','Active','有效') }}</p>
        </div>
        <div class="bg-white/10 backdrop-blur-sm rounded-2xl px-5 py-3 text-center border border-white/20">
          <p class="text-2xl font-extrabold text-on-primary">{{ $counts['all'] }}</p>
          <p class="text-[11px] text-on-primary/70">{{ $t('ທັງໝົດ','Total','總計') }}</p>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ═══ STATUS TABS ═══ --}}
<div class="bg-white border-b border-slate-100 shadow-sm">
  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center gap-2 overflow-x-auto py-3 scrollbar-hide">
      <a href="{{ route('front.mou.index', request()->except(['status','page'])) }}"
         class="flex-shrink-0 px-4 py-1.5 rounded-full text-xs font-semibold transition-colors
                {{ !request('status') ? 'bg-primary text-on-primary' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
        {{ $t('ທັງໝົດ','All','全部') }}
        <span class="ml-1 opacity-70">({{ $counts['all'] }})</span>
      </a>
      @foreach(['active','pending','renewed','expired','terminated'] as $s)
        @php $sc = $statusConfig[$s]; @endphp
        <a href="{{ route('front.mou.index', array_merge(request()->except(['status','page']), ['status'=>$s])) }}"
           class="flex-shrink-0 px-4 py-1.5 rounded-full text-xs font-semibold transition-colors
                  {{ request('status') === $s ? 'bg-primary text-on-primary' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
          {{ $sc['label'] }}
          @if(($counts[$s] ?? 0) > 0)
            <span class="ml-1 opacity-70">({{ $counts[$s] ?? 0 }})</span>
          @endif
        </a>
      @endforeach
    </div>
  </div>
</div>

{{-- ═══ LIST ═══ --}}
<section class="bg-slate-50 py-10">
  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">

    <div class="flex flex-col gap-4">
      @forelse($mous as $mou)
        @php
          $title   = $tf($mou, 'title');
          $desc    = $tf($mou, 'description');
          $scope   = $tf($mou, 'scope');
          $signers = $tf($mou, 'signers');
          $partner = $mou->partnerOrganization;
          $pName   = $partner ? ($tf($partner,'name') ?: $partner->name_lo) : '';
          $sc      = $statusConfig[$mou->status] ?? $statusConfig['active'];
        @endphp

        <div class="bg-white rounded-2xl border border-slate-100
                    shadow-[0_2px_12px_-4px_rgba(0,0,0,.08)]
                    hover:shadow-[0_4px_20px_-4px_rgba(3,22,50,.10)]
                    transition-shadow p-6 flex gap-5">

          {{-- Icon --}}
          <div class="shrink-0 w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center mt-0.5">
            <i class="fas fa-file-signature text-primary text-lg"></i>
          </div>

          {{-- Content --}}
          <div class="flex-1 min-w-0">
            <div class="flex flex-wrap items-start gap-2 mb-2">
              <h3 class="font-bold text-on-surface text-[15px] leading-snug flex-1">{{ $title }}</h3>
              <span class="shrink-0 px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $sc['bg'] }} {{ $sc['text'] }}">
                {{ $sc['label'] }}
              </span>
            </div>

            @if($pName)
              <p class="text-sm text-primary font-semibold mb-2 flex items-center gap-1.5">
                <i class="fas fa-globe text-xs text-outline"></i>
                {{ $pName }}
              </p>
            @endif

            @if($desc)
              <p class="text-sm text-on-surface-variant/80 leading-relaxed line-clamp-2 mb-3">{{ $desc }}</p>
            @endif

            @if($scope)
              <div class="bg-slate-50 rounded-lg px-3 py-2 text-xs text-on-surface-variant mb-3">
                <span class="font-semibold text-on-surface">{{ $t('ຂອບເຂດ:','Scope:','範圍：') }}</span>
                {{ $scope }}
              </div>
            @endif

            @if($signers)
              <p class="text-xs text-outline flex items-center gap-1.5 mb-2">
                <i class="fas fa-pen-nib text-[10px]"></i>
                {{ $signers }}
              </p>
            @endif

            <div class="flex flex-wrap items-center gap-4 mt-3 pt-3 border-t border-slate-100">
              <span class="text-xs text-outline flex items-center gap-1.5">
                <i class="fas fa-calendar-check text-[10px] text-secondary"></i>
                {{ $t('ລົງນາມ','Signed','簽署') }}:
                <span class="font-semibold text-on-surface-variant">{{ $mou->signed_date->translatedFormat('d M Y') }}</span>
              </span>
              @if($mou->expiry_date)
                <span class="text-xs text-outline flex items-center gap-1.5">
                  <i class="fas fa-calendar-times text-[10px] text-outline"></i>
                  {{ $t('ໝົດອາຍຸ','Expires','到期') }}:
                  <span class="font-semibold text-on-surface-variant">{{ $mou->expiry_date->translatedFormat('d M Y') }}</span>
                </span>
              @endif
              @if($mou->document_url)
                <a href="{{ $mou->document_url }}" target="_blank" rel="noreferrer"
                   class="ml-auto inline-flex items-center gap-1.5 text-xs font-bold text-primary hover:text-secondary transition-colors">
                  <i class="fas fa-download text-[10px]"></i>
                  {{ $t('ດາວໂຫຼດ','Download','下載') }}
                </a>
              @endif
            </div>
          </div>
        </div>

      @empty
        <div class="py-24 flex flex-col items-center text-center">
          <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center mb-4">
            <i class="fas fa-file-signature text-slate-300 text-3xl"></i>
          </div>
          <p class="font-semibold text-on-surface-variant">{{ $t('ຍັງບໍ່ມີຂໍ້ຕົກລົງ MOU','No MOU agreements found','暫無MOU協議') }}</p>
        </div>
      @endforelse
    </div>

    {{-- Pagination --}}
    @if($mous->hasPages())
      <div class="flex justify-center mt-10">
        <div class="flex items-center gap-1">
          @if($mous->onFirstPage())
            <span class="px-3 py-2 rounded-lg text-outline bg-white border border-slate-100 cursor-not-allowed text-sm">←</span>
          @else
            <a href="{{ $mous->previousPageUrl() }}" class="px-3 py-2 rounded-lg text-on-surface-variant bg-white border border-slate-100 hover:bg-surface-container text-sm transition-colors">←</a>
          @endif
          @foreach($mous->getUrlRange(max(1,$mous->currentPage()-2), min($mous->lastPage(),$mous->currentPage()+2)) as $page => $url)
            <a href="{{ $url }}"
               class="px-3.5 py-2 rounded-lg text-sm transition-colors
                      {{ $page === $mous->currentPage() ? 'bg-primary text-on-primary font-bold shadow-sm' : 'bg-white border border-slate-100 text-on-surface-variant hover:bg-surface-container' }}">
              {{ $page }}
            </a>
          @endforeach
          @if($mous->hasMorePages())
            <a href="{{ $mous->nextPageUrl() }}" class="px-3 py-2 rounded-lg text-on-surface-variant bg-white border border-slate-100 hover:bg-surface-container text-sm transition-colors">→</a>
          @else
            <span class="px-3 py-2 rounded-lg text-outline bg-white border border-slate-100 cursor-not-allowed text-sm">→</span>
          @endif
        </div>
      </div>
    @endif

  </div>
</section>

@endsection
