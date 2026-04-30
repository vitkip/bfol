@extends('front.layouts.app')

@php
  $L  = app()->getLocale();
  $t  = fn($lo,$en,$zh) => match($L){'zh'=>$zh,'en'=>$en,default=>$lo};
  $tf = fn($m,$f) => $m->{$f.'_'.$L} ?? $m->{$f.'_lo'} ?? '';

  $statusConfig = [
    'open'      => ['label'=>$t('ເປີດຮັບສະໝັກ','Open','開放申請'),      'bg'=>'bg-emerald-100','text'=>'text-emerald-700','border'=>'border-emerald-200'],
    'ongoing'   => ['label'=>$t('ກຳລັງດຳເນີນ','Ongoing','進行中'),       'bg'=>'bg-blue-100',   'text'=>'text-blue-700',  'border'=>'border-blue-200'],
    'closed'    => ['label'=>$t('ປິດຮັບສະໝັກ','Closed','已截止'),         'bg'=>'bg-slate-100',  'text'=>'text-slate-500', 'border'=>'border-slate-200'],
    'completed' => ['label'=>$t('ສຳເລັດແລ້ວ','Completed','已完成'),       'bg'=>'bg-purple-100', 'text'=>'text-purple-700','border'=>'border-purple-200'],
    'cancelled' => ['label'=>$t('ຍົກເລີກ','Cancelled','已取消'),           'bg'=>'bg-red-100',    'text'=>'text-red-700',   'border'=>'border-red-200'],
  ];
@endphp

@section('title', $t('ໂຄງການແລກປ່ຽນພຣະ','Monk Exchange Programmes','僧侶交流項目').' - '.($settings->site_name_lo ?: 'BFOL'))
@section('meta_description', $t('ໂຄງການແລກປ່ຽນພຣະ ຂອງ ອພສ','BFOL monk exchange programmes','老撾佛協僧侶交流項目'))

@push('styles')
<style>
  .dot-pattern { background-image:radial-gradient(circle,rgba(255,255,255,.12) 1px,transparent 1px); background-size:28px 28px; }
  .prog-open { box-shadow: 0 0 0 2px #d1fae5; }
</style>
@endpush

@section('content')

{{-- ═══ HERO ═══ --}}
<section class="relative bg-primary overflow-hidden">
  <div class="dot-pattern absolute inset-0 pointer-events-none"></div>
  <div class="absolute -top-28 -left-28 w-96 h-96 bg-secondary/10 rounded-full blur-3xl pointer-events-none"></div>
  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-12 relative z-10">
    <div class="flex items-center gap-2 text-on-primary/60 text-xs mb-4">
      <a href="{{ route('front.home') }}" class="hover:text-on-primary transition-colors">{{ $t('ໜ້າຫຼັກ','Home','首頁') }}</a>
      <i class="fas fa-chevron-right text-[9px]"></i>
      <span class="text-on-primary/80">{{ $t('ແລກປ່ຽນ ສາກົນ','Exchange','國際交流') }}</span>
    </div>
    <div class="flex flex-col sm:flex-row sm:items-end gap-4">
      <div class="flex-1">
        <h1 class="text-3xl sm:text-4xl font-serif font-bold text-on-primary mb-2">
          {{ $t('ໂຄງການແລກປ່ຽນພຣະ','Monk Exchange Programmes','僧侶交流項目') }}
        </h1>
        <p class="text-on-primary/70 text-sm max-w-lg">
          {{ $t('ໂຄງການ ສົ່ງ ແລະ ຮັບ ພຣະ ໄປ ຮຽນ ຢູ່ ຕ່າງປະເທດ','International monk exchange and study programmes supported by BFOL','老撾佛協支持的僧侶出國交流和學習項目') }}
        </p>
      </div>
      <div class="flex gap-3 shrink-0">
        @if($counts['open'] > 0)
          <div class="bg-emerald-500/20 backdrop-blur-sm rounded-2xl px-5 py-3 text-center border border-emerald-400/30">
            <p class="text-2xl font-extrabold text-on-primary">{{ $counts['open'] }}</p>
            <p class="text-[11px] text-on-primary/80">{{ $t('ເປີດຮັບ','Open','開放') }}</p>
          </div>
        @endif
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
      <a href="{{ route('front.monk-programs.index', request()->except(['status','page'])) }}"
         class="flex-shrink-0 px-4 py-1.5 rounded-full text-xs font-semibold transition-colors
                {{ !request('status') ? 'bg-primary text-on-primary' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
        {{ $t('ທັງໝົດ','All','全部') }}
      </a>
      @foreach(['open','ongoing','closed','completed','cancelled'] as $s)
        @php $sc = $statusConfig[$s]; @endphp
        <a href="{{ route('front.monk-programs.index', array_merge(request()->except(['status','page']), ['status'=>$s])) }}"
           class="flex-shrink-0 px-4 py-1.5 rounded-full text-xs font-semibold transition-colors
                  {{ request('status') === $s ? 'bg-primary text-on-primary' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
          {{ $sc['label'] }}
          @if($counts[$s] ?? 0)
            <span class="ml-1 opacity-70">({{ $counts[$s] }})</span>
          @endif
        </a>
      @endforeach
    </div>
  </div>
</div>

{{-- ═══ PROGRAMMES ═══ --}}
<section class="bg-slate-50 py-10">
  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
      @forelse($programs as $prog)
        @php
          $title    = $tf($prog, 'title');
          $desc     = $tf($prog, 'description');
          $reqs     = $tf($prog, 'requirements');
          $partner  = $prog->partnerOrganization;
          $pName    = $partner ? ($tf($partner,'name') ?: $partner->name_lo) : '';
          $sc       = $statusConfig[$prog->status] ?? $statusConfig['closed'];
          $isOpen   = $prog->status === 'open';
        @endphp

        <article class="bg-white rounded-2xl border border-slate-100
                        {{ $isOpen ? 'ring-2 ring-emerald-200' : '' }}
                        shadow-[0_2px_12px_-4px_rgba(0,0,0,.08)]
                        hover:shadow-[0_6px_24px_-4px_rgba(3,22,50,.13)]
                        hover:-translate-y-0.5 transition-all duration-300 overflow-hidden flex flex-col">

          {{-- Top bar --}}
          <div class="flex items-start justify-between p-5 pb-3 gap-3">
            <div class="flex items-center gap-3 min-w-0">
              <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center shrink-0">
                <i class="fas fa-dharmachakra text-primary text-xl"></i>
              </div>
              <div class="min-w-0">
                <h3 class="font-bold text-on-surface text-sm leading-snug line-clamp-2">{{ $title }}</h3>
                @if($prog->year)
                  <p class="text-xs text-outline mt-0.5">{{ $t('ສົກ','Year','年度') }} {{ $prog->year }}</p>
                @endif
              </div>
            </div>
            <span class="shrink-0 mt-0.5 px-2.5 py-1 rounded-full text-[10px] font-bold {{ $sc['bg'] }} {{ $sc['text'] }}">
              {{ $sc['label'] }}
            </span>
          </div>

          <div class="px-5 pb-5 flex flex-col flex-1">
            @if($pName)
              <p class="text-xs text-primary font-semibold mb-3 flex items-center gap-1.5">
                <i class="fas fa-globe text-[10px] text-outline"></i>
                {{ $pName }}
                @if($prog->destination_country)
                  <span class="text-outline font-normal">· {{ $prog->destination_country }}</span>
                @endif
              </p>
            @endif

            @if($desc)
              <p class="text-[12px] text-on-surface-variant/80 line-clamp-3 leading-relaxed mb-3">{{ $desc }}</p>
            @endif

            @if($reqs)
              <div class="bg-amber-50 border border-amber-100 rounded-xl px-3 py-2.5 mb-3">
                <p class="text-[10px] font-bold text-amber-700 mb-1 flex items-center gap-1">
                  <i class="fas fa-list-check text-[9px]"></i>
                  {{ $t('ເງື່ອນໄຂ','Requirements','申請條件') }}
                </p>
                <p class="text-[11px] text-amber-800/80 line-clamp-2 leading-relaxed">{{ $reqs }}</p>
              </div>
            @endif

            {{-- Info grid --}}
            <div class="grid grid-cols-2 gap-x-4 gap-y-2 mt-auto pt-3 border-t border-slate-100">
              @if($prog->monks_quota)
                <div class="text-xs">
                  <p class="text-outline">{{ $t('ຈຳນວນ','Quota','名額') }}</p>
                  <p class="font-semibold text-on-surface-variant">{{ $prog->monks_quota }} {{ $t('ທ່ານ','monks','名') }}</p>
                </div>
              @endif
              @if($prog->monks_selected)
                <div class="text-xs">
                  <p class="text-outline">{{ $t('ໄດ້ຮັບ','Selected','已選') }}</p>
                  <p class="font-semibold text-on-surface-variant">{{ $prog->monks_selected }} {{ $t('ທ່ານ','monks','名') }}</p>
                </div>
              @endif
              @if($prog->application_deadline)
                <div class="text-xs">
                  <p class="text-outline">{{ $t('ກຳໜົດ','Deadline','截止') }}</p>
                  <p class="font-semibold {{ $isOpen ? 'text-emerald-600' : 'text-on-surface-variant' }}">
                    {{ $prog->application_deadline->translatedFormat('d M Y') }}
                  </p>
                </div>
              @endif
              @if($prog->program_start)
                <div class="text-xs">
                  <p class="text-outline">{{ $t('ໂຄງການເລີ່ມ','Prog. Start','項目開始') }}</p>
                  <p class="font-semibold text-on-surface-variant">{{ $prog->program_start->translatedFormat('M Y') }}</p>
                </div>
              @endif
            </div>

            {{-- CTA --}}
            @if($isOpen && ($prog->application_url || $prog->contact_email))
              <div class="mt-4 flex gap-2">
                @if($prog->application_url)
                  <a href="{{ $prog->application_url }}" target="_blank" rel="noreferrer"
                     class="flex-1 text-center py-2.5 bg-primary text-on-primary text-xs font-bold rounded-xl
                            hover:bg-primary-container transition-colors">
                    <i class="fas fa-paper-plane mr-1.5"></i>
                    {{ $t('ສະໝັກໄດ້ເລີຍ','Apply Now','立即申請') }}
                  </a>
                @endif
                @if($prog->contact_email)
                  <a href="mailto:{{ $prog->contact_email }}"
                     class="px-4 py-2.5 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl
                            hover:bg-slate-200 transition-colors flex items-center gap-1.5">
                    <i class="fas fa-envelope text-[10px]"></i>
                    {{ $t('ຕິດຕໍ່','Contact','聯繫') }}
                  </a>
                @endif
              </div>
            @endif
          </div>
        </article>

      @empty
        <div class="col-span-full py-24 flex flex-col items-center text-center">
          <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center mb-4">
            <i class="fas fa-dharmachakra text-slate-300 text-3xl"></i>
          </div>
          <p class="font-semibold text-on-surface-variant">{{ $t('ຍັງບໍ່ມີໂຄງການ','No programmes found','暫無項目') }}</p>
        </div>
      @endforelse
    </div>

    {{-- Pagination --}}
    @if($programs->hasPages())
      <div class="flex justify-center mt-10">
        <div class="flex items-center gap-1">
          @if($programs->onFirstPage())
            <span class="px-3 py-2 rounded-lg text-outline bg-white border border-slate-100 cursor-not-allowed text-sm">←</span>
          @else
            <a href="{{ $programs->previousPageUrl() }}" class="px-3 py-2 rounded-lg text-on-surface-variant bg-white border border-slate-100 hover:bg-surface-container text-sm transition-colors">←</a>
          @endif
          @foreach($programs->getUrlRange(max(1,$programs->currentPage()-2), min($programs->lastPage(),$programs->currentPage()+2)) as $page => $url)
            <a href="{{ $url }}"
               class="px-3.5 py-2 rounded-lg text-sm transition-colors
                      {{ $page === $programs->currentPage() ? 'bg-primary text-on-primary font-bold shadow-sm' : 'bg-white border border-slate-100 text-on-surface-variant hover:bg-surface-container' }}">{{ $page }}</a>
          @endforeach
          @if($programs->hasMorePages())
            <a href="{{ $programs->nextPageUrl() }}" class="px-3 py-2 rounded-lg text-on-surface-variant bg-white border border-slate-100 hover:bg-surface-container text-sm transition-colors">→</a>
          @else
            <span class="px-3 py-2 rounded-lg text-outline bg-white border border-slate-100 cursor-not-allowed text-sm">→</span>
          @endif
        </div>
      </div>
    @endif

  </div>
</section>

@endsection
