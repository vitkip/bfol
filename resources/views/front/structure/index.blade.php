@extends('front.layouts.app')

@php
  $L  = app()->getLocale();
  $t  = fn($lo,$en,$zh) => match($L){'zh'=>$zh,'en'=>$en,default=>$lo};
  $tf = fn($m,$f) => $m->{$f.'_'.$L} ?? $m->{$f.'_lo'} ?? '';

  // Dept palette — one colour per department group
  $deptColors = [
    0 => ['ring'=>'ring-primary/60',    'bg'=>'bg-primary/10',    'text'=>'text-primary',    'bar'=>'bg-primary'],
    1 => ['ring'=>'ring-secondary/60',  'bg'=>'bg-secondary/10',  'text'=>'text-secondary',  'bar'=>'bg-secondary'],
    2 => ['ring'=>'ring-emerald-400/60','bg'=>'bg-emerald-50',    'text'=>'text-emerald-700','bar'=>'bg-emerald-500'],
    3 => ['ring'=>'ring-violet-400/60', 'bg'=>'bg-violet-50',     'text'=>'text-violet-700', 'bar'=>'bg-violet-500'],
  ];

  $termLabel = '';
  if ($president) {
    $ts = $president->term_start ?? ''; $te = $president->term_end ?? '';
    $termLabel = ($ts && $te) ? $ts.'–'.$te : ($ts ?: '');
  }
@endphp

@section('title', $t('ໂຄງສ້າງອົງການ','Organizational Structure','組織架構').' - '.($settings->site_name_lo ?: 'BFOL'))
@section('meta_description', $t('ໂຄງສ້າງອົງການຄະນະກຳມະການ ອພສ','BFOL Committee Organizational Structure','老撾佛教協會委員會組織架構'))

@push('styles')
<style>
  /* ─── CSS Org-Chart Tree ─── */
  .org-chart { display:flex; justify-content:center; }
  .org-chart ul {
    padding-top:40px;
    position:relative;
    display:flex;
    justify-content:center;
    flex-wrap:nowrap;
    gap:0;
    margin:0;
    padding-inline-start:0;
  }
  /* Vertical stem from parent ul down to horizontal bar */
  .org-chart ul::before {
    content:'';
    position:absolute;
    top:0; left:50%;
    transform:translateX(-50%);
    width:2px;
    height:40px;
    background:linear-gradient(to bottom,#c5a021,#c5a021);
  }
  .org-chart li {
    display:flex;
    flex-direction:column;
    align-items:center;
    list-style:none;
    position:relative;
    padding:40px 12px 0;
  }
  /* Horizontal connectors + vertical center stem */
  .org-chart li::before,
  .org-chart li::after {
    content:'';
    position:absolute;
    top:0;
    border-top:2px solid #c5a021;
    width:50%;
    height:40px;
  }
  .org-chart li::before { right:50%; }
  /* ::after also draws the vertical stem at center (left edge of right half = center of li) */
  .org-chart li::after  { left:50%; border-left:2px solid #c5a021; }
  /* First child: no left horizontal bar */
  .org-chart li:first-child::before { border-top:none; }
  /* Last child: no right horizontal bar and no duplicate vertical from ::after
     (::before border-right already provides the center vertical with rounded corner) */
  .org-chart li:last-child::after   { border-top:none; border-left:none; }
  .org-chart li:last-child::before  { border-right:2px solid #c5a021; border-radius:0 6px 0 0; }
  .org-chart li:first-child::after  { border-radius:6px 0 0 0; }
  /* Only child: hide horizontal, keep vertical stem via background fill */
  .org-chart li:only-child::before  { display:none; }
  .org-chart li:only-child::after   {
    border-top:none; border-left:none;
    width:2px; left:50%; transform:translateX(-50%);
    background:#c5a021;
  }

  /* ─── Pair grid: 2-column member list going downward ─── */
  .pair-wrap {
    padding-top:30px;
    position:relative;
    display:flex;
    flex-direction:column;
    align-items:center;
  }
  .pair-wrap::before {
    content:'';
    position:absolute;
    top:0; left:50%;
    transform:translateX(-50%);
    width:2px; height:30px;
    background:#c5a021;
  }
  .pair-grid {
    display:grid;
    grid-template-columns:repeat(2,minmax(0,1fr));
    gap:12px 10px;
    justify-items:center;
  }

  /* ─── Dot background ─── */
  .dot-pattern {
    background-image:radial-gradient(circle,rgba(255,255,255,.12) 1px,transparent 1px);
    background-size:28px 28px;
  }

  /* ─── Mobile card tree ─── */
  [x-cloak] { display:none !important; }
</style>
@endpush

@section('content')

{{-- ═══ HERO ═══ --}}
<section class="relative bg-primary overflow-hidden">
  <div class="dot-pattern absolute inset-0 pointer-events-none"></div>
  <div class="absolute -top-32 -right-32 w-96 h-96 bg-secondary/10 rounded-full blur-3xl pointer-events-none"></div>

  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-14 relative z-10">
    <div class="flex flex-col md:flex-row items-center gap-8">
      <div class="flex-1 text-center md:text-left">
        <span class="inline-flex items-center gap-1.5 text-xs font-bold text-secondary uppercase tracking-widest mb-4">
          <span class="w-1.5 h-1.5 rounded-full bg-secondary inline-block"></span>
          {{ $t('ສ.ຊ.ພ.ລ — ວາລະ '.$termLabel,'BFOL — Tenure '.$termLabel,'老撾佛協 — '.$termLabel.'屆') }}
        </span>
        <h1 class="text-3xl sm:text-4xl font-serif font-bold text-on-primary mb-3">
          {{ $t('ໂຄງສ້າງອົງການ','Organizational Structure','組織架構') }}
        </h1>
        <p class="text-on-primary/70 text-sm max-w-lg">
          {{ $t(
            'ໂຄງສ້າງຄະນະກຳມະການສະຫາຍທັມ ແລະ ການຕ່າງປະເທດ ຂອງ ສະຫະມາຄົມພຸດທະສາສາສ໌ ລາວ',
            'Committee and international affairs organizational structure of the Buddhist Fellowship of Lao PDR',
            '老撾佛教協會委員會及對外事務組織架構'
          ) }}
        </p>
      </div>

      {{-- Stats --}}
      <div class="flex flex-wrap justify-center gap-3">
        <div class="flex items-center gap-2 bg-white/10 border border-white/15 rounded-xl px-4 py-2.5">
          <i class="fas fa-users text-secondary text-base"></i>
          <div class="leading-tight">
            <div class="text-on-primary font-bold text-xl leading-none">{{ $totalMembers }}</div>
            <div class="text-on-primary/60 text-[11px]">{{ $t('ສະມາຊິກ','Members','成員') }}</div>
          </div>
        </div>
        <div class="flex items-center gap-2 bg-white/10 border border-white/15 rounded-xl px-4 py-2.5">
          <i class="fas fa-sitemap text-secondary text-base"></i>
          <div class="leading-tight">
            <div class="text-on-primary font-bold text-xl leading-none">{{ $deptGroups->count() }}</div>
            <div class="text-on-primary/60 text-[11px]">{{ $t('ຝ່າຍ','Departments','部門') }}</div>
          </div>
        </div>
        @if($termLabel)
          <div class="flex items-center gap-2 bg-white/10 border border-white/15 rounded-xl px-4 py-2.5">
            <i class="fas fa-calendar-check text-secondary text-base"></i>
            <div class="leading-tight">
              <div class="text-on-primary font-bold text-xl leading-none">{{ $termLabel }}</div>
              <div class="text-on-primary/60 text-[11px]">{{ $t('ວາລະ','Tenure','任期') }}</div>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
</section>

{{-- ═══ ORG CHART (Desktop lg+) ═══ --}}
<section class="hidden lg:block bg-slate-50 py-16 overflow-x-auto">
  <div class="min-w-[900px] px-8">

    <div class="org-chart select-none">
      <ul>
        <li>

          {{-- ══ LEVEL 1: PRESIDENT ══ --}}
          @if($president)
            @php
              $pName = $tf($president,'name') ?: $president->name_lo;
              $pPos  = $tf($president,'position') ?: $president->position_lo;
              $pDept = $president->department ? $tf($president->department,'name') : '';
            @endphp
            <div class="flex flex-col items-center group">
              {{-- Photo --}}
              <div class="relative mb-3">
                <div class="w-28 h-28 rounded-full ring-4 ring-amber-400 ring-offset-4 ring-offset-slate-50
                            shadow-xl shadow-amber-200 overflow-hidden bg-white
                            group-hover:ring-secondary transition-all duration-300">
                  @if($president->photo_url)
                    <img src="{{ $president->photo_url }}" alt="{{ $pName }}" class="w-full h-full object-cover" />
                  @else
                    <div class="w-full h-full bg-gradient-to-br from-primary to-primary-container
                                flex items-center justify-center">
                      <i class="fas fa-hands-praying text-on-primary text-4xl"></i>
                    </div>
                  @endif
                </div>
                {{-- Crown badge --}}
                <div class="absolute -top-1 left-1/2 -translate-x-1/2 bg-amber-400 text-white
                            text-[9px] font-black px-2 py-0.5 rounded-full shadow-md whitespace-nowrap">
                  <i class="fas fa-star text-[7px] mr-0.5"></i>{{ $t('ປະທານ','President','主席') }}
                </div>
              </div>

              {{-- Info card --}}
              <div class="bg-white rounded-2xl shadow-lg shadow-primary/10 border border-amber-100
                          px-5 py-3.5 text-center min-w-[200px] max-w-[240px]
                          group-hover:shadow-xl group-hover:border-amber-200 transition-all duration-300">
                <p class="font-bold text-on-surface text-sm leading-snug">{{ $pName }}</p>
                <p class="text-primary text-xs font-semibold mt-1">{{ $pPos }}</p>
                @if($pDept)
                  <span class="inline-flex items-center gap-1 mt-2 px-2 py-0.5 rounded-full
                               bg-amber-50 text-amber-700 text-[10px] font-medium border border-amber-200">
                    <i class="fas fa-layer-group text-[8px]"></i>{{ $pDept }}
                  </span>
                @endif
                @if($president->pansa)
                  <div class="text-[10px] text-on-surface-variant/60 mt-1.5">
                    <i class="fas fa-dharmachakra text-[8px] mr-0.5 text-amber-500"></i>
                    {{ $president->pansa }} {{ $t('ພັນສາ','Pansa','安居') }}
                  </div>
                @endif
              </div>
            </div>
          @endif

          {{-- ══ LEVEL 2: DEPT HEADS ══ --}}
          <ul>
            @foreach($deptGroups as $gi => $grp)
              @php
                $col  = $deptColors[$gi % count($deptColors)];
                $head = $grp['head'];
                $dept = $grp['dept'];
                $hName = $tf($head,'name') ?: $head->name_lo;
                $hPos  = $tf($head,'position') ?: $head->position_lo;
                $dName = $tf($dept,'name') ?: $dept->name_lo;
              @endphp
              <li>
                {{-- Dept head card --}}
                <div class="flex flex-col items-center group">
                  {{-- Dept colour bar --}}
                  <div class="w-1 h-5 {{ $col['bar'] }} rounded-full mb-2 opacity-60"></div>

                  {{-- Photo --}}
                  <div class="w-20 h-20 rounded-full ring-2 {{ $col['ring'] }} ring-offset-2 ring-offset-slate-50
                              shadow-md overflow-hidden bg-white mb-2.5
                              group-hover:scale-105 transition-transform duration-300">
                    @if($head->photo_url)
                      <img src="{{ $head->photo_url }}" alt="{{ $hName }}" class="w-full h-full object-cover" />
                    @else
                      <div class="w-full h-full {{ $col['bg'] }} flex items-center justify-center">
                        @if($head->gender === 'monk')
                          <i class="fas fa-hands-praying {{ $col['text'] }} text-2xl"></i>
                        @elseif($head->gender === 'female')
                          <i class="fas fa-person-dress {{ $col['text'] }} text-2xl"></i>
                        @else
                          <i class="fas fa-person {{ $col['text'] }} text-2xl"></i>
                        @endif
                      </div>
                    @endif
                  </div>

                  {{-- Info card --}}
                  <div class="bg-white rounded-xl shadow-md border {{ str_replace('ring-','border-',$col['ring']) }}
                              px-4 py-3 text-center w-48
                              group-hover:shadow-lg transition-shadow duration-300">
                    {{-- Dept label --}}
                    <div class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full {{ $col['bg'] }} {{ $col['text'] }}
                                text-[10px] font-bold mb-2 border {{ str_replace('ring-','border-',$col['ring']) }}">
                      {{ $dName }}
                    </div>
                    <p class="font-bold text-on-surface text-xs leading-snug">{{ $hName }}</p>
                    <p class="{{ $col['text'] }} text-[11px] font-medium mt-0.5">{{ $hPos }}</p>
                  </div>
                </div>

                {{-- ══ LEVEL 3: SUB-DEPTS + DIRECT MEMBERS ══ --}}
                @if($grp['subGroups']->isNotEmpty() || $grp['members']->isNotEmpty())
                  <ul>

                    {{-- Sub-department branches --}}
                    @foreach($grp['subGroups'] as $sg)
                      @if($sg['head'])
                        @php
                          $sgHead  = $sg['head'];
                          $sgDept  = $sg['dept'];
                          $sgHName = $tf($sgHead,'name') ?: $sgHead->name_lo;
                          $sgHPos  = $tf($sgHead,'position') ?: $sgHead->position_lo;
                          $sgDName = $tf($sgDept,'name') ?: $sgDept->name_lo;
                        @endphp
                        <li>
                          <div class="flex flex-col items-center group cursor-default">
                            {{-- Sub-dept badge --}}
                            <div class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full {{ $col['bg'] }} {{ $col['text'] }}
                                        text-[9px] font-bold mb-1.5 border {{ str_replace('ring-','border-',$col['ring']) }}">
                              <i class="fas fa-layer-group text-[7px]"></i>{{ $sgDName }}
                            </div>
                            {{-- Photo --}}
                            <div class="w-14 h-14 rounded-full ring-2 {{ $col['ring'] }} ring-offset-1 ring-offset-slate-50
                                        shadow-md overflow-hidden bg-white mb-2
                                        group-hover:scale-105 transition-transform duration-300">
                              @if($sgHead->photo_url)
                                <img src="{{ $sgHead->photo_url }}" alt="{{ $sgHName }}" class="w-full h-full object-cover" />
                              @else
                                <div class="w-full h-full {{ $col['bg'] }} flex items-center justify-center">
                                  @if($sgHead->gender === 'monk')
                                    <i class="fas fa-hands-praying {{ $col['text'] }} text-xl"></i>
                                  @elseif($sgHead->gender === 'female')
                                    <i class="fas fa-person-dress {{ $col['text'] }} text-xl"></i>
                                  @else
                                    <i class="fas fa-person {{ $col['text'] }} text-xl"></i>
                                  @endif
                                </div>
                              @endif
                            </div>
                            {{-- Info card --}}
                            <div class="bg-white rounded-xl border {{ str_replace('ring-','border-',$col['ring']) }} shadow-sm
                                        px-3 py-2 text-center w-[140px]
                                        group-hover:shadow-md transition-shadow duration-200">
                              <p class="text-[10px] font-bold text-on-surface leading-snug line-clamp-2">{{ $sgHName }}</p>
                              <p class="{{ $col['text'] }} text-[9px] font-medium mt-0.5 leading-tight line-clamp-1">{{ $sgHPos }}</p>
                            </div>
                          </div>

                          {{-- LEVEL 4: sub-dept members — 2-column pair grid ↓ --}}
                          @if($sg['members']->isNotEmpty())
                            <div class="pair-wrap">
                              <div class="pair-grid">
                                @foreach($sg['members'] as $mem)
                                  @php
                                    $mName = $tf($mem,'name') ?: $mem->name_lo;
                                    $mPos  = $tf($mem,'position') ?: $mem->position_lo;
                                  @endphp
                                  <div class="flex flex-col items-center group cursor-default">
                                    <div class="w-12 h-12 rounded-full ring-1 ring-slate-200 ring-offset-1 ring-offset-slate-50
                                                overflow-hidden bg-white shadow-sm mb-1.5
                                                group-hover:ring-2 group-hover:{{ $col['ring'] }} group-hover:scale-105
                                                transition-all duration-200">
                                      @if($mem->photo_url)
                                        <img src="{{ $mem->photo_url }}" alt="{{ $mName }}" class="w-full h-full object-cover" />
                                      @else
                                        <div class="w-full h-full bg-slate-100 flex items-center justify-center">
                                          @if($mem->gender === 'monk')
                                            <i class="fas fa-hands-praying text-slate-400 text-base"></i>
                                          @elseif($mem->gender === 'female')
                                            <i class="fas fa-person-dress text-slate-400 text-base"></i>
                                          @else
                                            <i class="fas fa-person text-slate-400 text-base"></i>
                                          @endif
                                        </div>
                                      @endif
                                    </div>
                                    <div class="bg-white rounded-lg border border-slate-100 shadow-sm px-2 py-1 text-center w-[110px]
                                                group-hover:border-slate-200 group-hover:shadow-md transition-all duration-200">
                                      <p class="text-[9px] font-bold text-on-surface leading-tight line-clamp-2">{{ $mName }}</p>
                                      <p class="text-[8px] text-on-surface-variant/70 mt-0.5 leading-tight line-clamp-1">{{ $mPos }}</p>
                                    </div>
                                  </div>
                                @endforeach
                              </div>
                            </div>
                          @endif
                        </li>
                      @endif
                    @endforeach

                    {{-- Direct members of this dept — 2-column pair grid ↓ --}}
                    @if($grp['members']->isNotEmpty())
                      <li>
                        <div class="pair-wrap">
                          <div class="pair-grid">
                            @foreach($grp['members'] as $mem)
                              @php
                                $mName = $tf($mem,'name') ?: $mem->name_lo;
                                $mPos  = $tf($mem,'position') ?: $mem->position_lo;
                              @endphp
                              <div class="flex flex-col items-center group cursor-default">
                                <div class="w-14 h-14 rounded-full ring-1 ring-slate-300 ring-offset-1 ring-offset-slate-50
                                            overflow-hidden bg-white shadow-sm mb-1.5
                                            group-hover:ring-2 group-hover:{{ $col['ring'] }} group-hover:scale-105
                                            transition-all duration-200">
                                  @if($mem->photo_url)
                                    <img src="{{ $mem->photo_url }}" alt="{{ $mName }}" class="w-full h-full object-cover" />
                                  @else
                                    <div class="w-full h-full bg-slate-100 flex items-center justify-center">
                                      @if($mem->gender === 'monk')
                                        <i class="fas fa-hands-praying text-slate-400 text-lg"></i>
                                      @elseif($mem->gender === 'female')
                                        <i class="fas fa-person-dress text-slate-400 text-lg"></i>
                                      @else
                                        <i class="fas fa-person text-slate-400 text-lg"></i>
                                      @endif
                                    </div>
                                  @endif
                                </div>
                                <div class="bg-white rounded-lg border border-slate-100 shadow-sm px-2.5 py-1.5 text-center w-[120px]
                                            group-hover:border-slate-200 group-hover:shadow-md transition-all duration-200">
                                  <p class="text-[10px] font-bold text-on-surface leading-tight line-clamp-2">{{ $mName }}</p>
                                  <p class="text-[9px] text-on-surface-variant/70 mt-0.5 leading-tight line-clamp-1">{{ $mPos }}</p>
                                </div>
                              </div>
                            @endforeach
                          </div>
                        </div>
                      </li>
                    @endif

                  </ul>
                @endif

              </li>
            @endforeach
          </ul>

        </li>
      </ul>
    </div>

    {{-- Legend --}}
    <div class="flex flex-wrap justify-center gap-4 mt-12">
      @foreach($deptGroups as $gi => $grp)
        @php $col = $deptColors[$gi % count($deptColors)]; @endphp
        <div class="flex items-center gap-2 px-4 py-2 bg-white rounded-full border border-slate-100 shadow-sm">
          <div class="w-2.5 h-2.5 rounded-full {{ $col['bar'] }}"></div>
          <span class="text-xs font-medium text-on-surface">{{ $tf($grp['dept'],'name') }}</span>
        </div>
      @endforeach
    </div>

  </div>
</section>

{{-- ═══ MOBILE / TABLET (< lg) ═══ --}}
<section class="lg:hidden bg-slate-50 py-10"
         x-data="{ openDept: null }">
  <div class="max-w-[600px] mx-auto px-4">

    {{-- President card (mobile) --}}
    @if($president)
      @php
        $pName = $tf($president,'name') ?: $president->name_lo;
        $pPos  = $tf($president,'position') ?: $president->position_lo;
      @endphp
      <div class="flex flex-col items-center mb-8">
        <div class="relative mb-3">
          <div class="w-24 h-24 rounded-full ring-4 ring-amber-400 ring-offset-2 overflow-hidden bg-white shadow-xl">
            @if($president->photo_url)
              <img src="{{ $president->photo_url }}" alt="{{ $pName }}" class="w-full h-full object-cover" />
            @else
              <div class="w-full h-full bg-primary flex items-center justify-center">
                <i class="fas fa-hands-praying text-on-primary text-3xl"></i>
              </div>
            @endif
          </div>
          <div class="absolute -top-1 left-1/2 -translate-x-1/2 bg-amber-400 text-white
                      text-[9px] font-black px-2 py-0.5 rounded-full whitespace-nowrap">
            <i class="fas fa-star text-[7px] mr-0.5"></i>{{ $t('ປະທານ','President','主席') }}
          </div>
        </div>
        <div class="bg-white rounded-2xl shadow-lg border border-amber-100 px-5 py-3 text-center w-full max-w-xs">
          <p class="font-bold text-on-surface">{{ $pName }}</p>
          <p class="text-primary text-sm font-semibold mt-0.5">{{ $pPos }}</p>
        </div>
      </div>

      {{-- Vertical line connector --}}
      <div class="flex justify-center mb-2">
        <div class="w-0.5 h-8 bg-amber-400"></div>
      </div>
    @endif

    {{-- Dept accordion (mobile) --}}
    <div class="flex flex-col gap-3">
      @foreach($deptGroups as $gi => $grp)
        @php
          $col   = $deptColors[$gi % count($deptColors)];
          $head  = $grp['head'];
          $dept  = $grp['dept'];
          $hName = $tf($head,'name') ?: $head->name_lo;
          $hPos  = $tf($head,'position') ?: $head->position_lo;
          $dName = $tf($dept,'name') ?: $dept->name_lo;
          $dKey  = 'dept-'.$gi;
          // Total count excluding the head shown in the header row
          $mobileCount = $grp['members']->count()
            + $grp['subGroups']->sum(fn($sg) => ($sg['head'] ? 1 : 0) + $sg['members']->count());
        @endphp

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
          {{-- Dept head row (clickable) --}}
          <button type="button"
                  @click="openDept = openDept === '{{ $dKey }}' ? null : '{{ $dKey }}'"
                  class="w-full flex items-center gap-3 px-4 py-4 text-left cursor-pointer">
            {{-- Colour strip --}}
            <div class="w-1 self-stretch {{ $col['bar'] }} rounded-full shrink-0"></div>
            {{-- Photo --}}
            <div class="w-12 h-12 rounded-full ring-2 {{ $col['ring'] }} overflow-hidden bg-white shrink-0">
              @if($head->photo_url)
                <img src="{{ $head->photo_url }}" alt="{{ $hName }}" class="w-full h-full object-cover" />
              @else
                <div class="w-full h-full {{ $col['bg'] }} flex items-center justify-center">
                  <i class="fas fa-person {{ $col['text'] }} text-xl"></i>
                </div>
              @endif
            </div>
            {{-- Info --}}
            <div class="flex-1 min-w-0">
              <div class="text-[10px] font-bold {{ $col['text'] }} uppercase tracking-wide mb-0.5">{{ $dName }}</div>
              <p class="font-bold text-on-surface text-sm leading-snug truncate">{{ $hName }}</p>
              <p class="text-xs text-on-surface-variant/70 truncate">{{ $hPos }}</p>
            </div>
            {{-- Toggle --}}
            <div class="flex items-center gap-1 shrink-0">
              @if($mobileCount > 0)
                <span class="text-[10px] text-on-surface-variant/60">+{{ $mobileCount }}</span>
              @endif
              <i class="fas fa-chevron-down text-xs text-on-surface-variant/50 transition-transform duration-300"
                 :class="openDept === '{{ $dKey }}' ? 'rotate-180' : ''"></i>
            </div>
          </button>

          {{-- Expanded content --}}
          <div x-show="openDept === '{{ $dKey }}'"
               x-transition:enter="transition ease-out duration-200"
               x-transition:enter-start="opacity-0 -translate-y-2"
               x-transition:enter-end="opacity-100 translate-y-0"
               x-transition:leave="transition ease-in duration-150"
               x-transition:leave-end="opacity-0"
               style="display:none">

            @if($grp['subGroups']->isNotEmpty() || $grp['members']->isNotEmpty())
              <div class="border-t border-slate-100">

                {{-- Sub-departments --}}
                @foreach($grp['subGroups'] as $sg)
                  @if($sg['head'])
                    @php
                      $sgHead  = $sg['head'];
                      $sgDept  = $sg['dept'];
                      $sgHName = $tf($sgHead,'name') ?: $sgHead->name_lo;
                      $sgHPos  = $tf($sgHead,'position') ?: $sgHead->position_lo;
                      $sgDName = $tf($sgDept,'name') ?: $sgDept->name_lo;
                    @endphp
                    {{-- Sub-dept label --}}
                    <div class="px-4 pt-3 pb-1 flex items-center gap-2">
                      <div class="w-0.5 h-3 rounded-full {{ $col['bar'] }} opacity-70"></div>
                      <span class="text-[10px] font-bold {{ $col['text'] }} uppercase tracking-wide">{{ $sgDName }}</span>
                    </div>
                    {{-- Sub-dept head --}}
                    <div class="px-4 py-2 flex items-center gap-3">
                      <div class="w-9 h-9 rounded-full ring-2 {{ $col['ring'] }} overflow-hidden bg-slate-50 shrink-0">
                        @if($sgHead->photo_url)
                          <img src="{{ $sgHead->photo_url }}" alt="{{ $sgHName }}" class="w-full h-full object-cover" />
                        @else
                          <div class="w-full h-full {{ $col['bg'] }} flex items-center justify-center">
                            @if($sgHead->gender === 'monk')
                              <i class="fas fa-hands-praying {{ $col['text'] }} text-sm"></i>
                            @elseif($sgHead->gender === 'female')
                              <i class="fas fa-person-dress {{ $col['text'] }} text-sm"></i>
                            @else
                              <i class="fas fa-person {{ $col['text'] }} text-sm"></i>
                            @endif
                          </div>
                        @endif
                      </div>
                      <div class="min-w-0 flex-1">
                        <p class="text-sm font-semibold text-on-surface leading-tight truncate">{{ $sgHName }}</p>
                        <p class="text-[11px] text-on-surface-variant/70 truncate">{{ $sgHPos }}</p>
                      </div>
                      <span class="text-[9px] px-1.5 py-0.5 rounded-full {{ $col['bg'] }} {{ $col['text'] }} font-bold border {{ str_replace('ring-','border-',$col['ring']) }} shrink-0">
                        {{ $t('ຫົວໜ້າ','Head','負責人') }}
                      </span>
                    </div>
                    {{-- Sub-dept members (indented) --}}
                    @foreach($sg['members'] as $mem)
                      @php
                        $mName = $tf($mem,'name') ?: $mem->name_lo;
                        $mPos  = $tf($mem,'position') ?: $mem->position_lo;
                      @endphp
                      <div class="pl-12 pr-4 py-1.5 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full ring-1 ring-slate-200 overflow-hidden bg-slate-50 shrink-0">
                          @if($mem->photo_url)
                            <img src="{{ $mem->photo_url }}" alt="{{ $mName }}" class="w-full h-full object-cover" />
                          @else
                            <div class="w-full h-full bg-slate-100 flex items-center justify-center">
                              <i class="fas fa-person text-slate-400 text-xs"></i>
                            </div>
                          @endif
                        </div>
                        <div class="min-w-0 flex-1">
                          <p class="text-xs font-semibold text-on-surface leading-tight truncate">{{ $mName }}</p>
                          <p class="text-[11px] text-on-surface-variant/60 truncate">{{ $mPos }}</p>
                        </div>
                      </div>
                    @endforeach
                  @endif
                @endforeach

                {{-- Direct members of this dept --}}
                @if($grp['members']->isNotEmpty())
                  @if($grp['subGroups']->isNotEmpty())
                    <div class="px-4 pt-3 pb-1 flex items-center gap-2 mt-1 border-t border-slate-50">
                      <span class="text-[10px] text-on-surface-variant/50 uppercase tracking-wide">
                        {{ $t('ສະມາຊິກ','Members','成員') }}
                      </span>
                    </div>
                  @endif
                  <div class="px-4 pb-4 {{ $grp['subGroups']->isNotEmpty() ? 'pt-1' : 'pt-3' }} flex flex-col gap-2">
                    @foreach($grp['members'] as $mem)
                      @php
                        $mName = $tf($mem,'name') ?: $mem->name_lo;
                        $mPos  = $tf($mem,'position') ?: $mem->position_lo;
                      @endphp
                      <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full ring-1 ring-slate-200 overflow-hidden bg-slate-50 shrink-0">
                          @if($mem->photo_url)
                            <img src="{{ $mem->photo_url }}" alt="{{ $mName }}" class="w-full h-full object-cover" />
                          @else
                            <div class="w-full h-full bg-slate-100 flex items-center justify-center">
                              <i class="fas fa-person text-slate-400 text-sm"></i>
                            </div>
                          @endif
                        </div>
                        <div class="min-w-0 flex-1">
                          <p class="text-sm font-semibold text-on-surface leading-tight truncate">{{ $mName }}</p>
                          <p class="text-[11px] text-on-surface-variant/70 truncate">{{ $mPos }}</p>
                        </div>
                      </div>
                    @endforeach
                  </div>
                @endif

              </div>
            @else
              <div class="border-t border-slate-100 px-4 py-3 text-xs text-on-surface-variant/50 text-center">
                {{ $t('ບໍ່ມີສະມາຊິກ','No members','暫無成員') }}
              </div>
            @endif

          </div>
        </div>

      @endforeach
    </div>

    {{-- Link to full committee page --}}
    <div class="mt-8 text-center">
      <a href="{{ route('front.committee') }}"
         class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-on-primary text-sm font-bold rounded-xl
                hover:bg-secondary hover:text-on-secondary transition-all">
        <i class="fas fa-users text-xs"></i>
        {{ $t('ເບິ່ງລາຍຊື່ທັງໝົດ','View Full Committee','查看完整委員會') }}
      </a>
    </div>

  </div>
</section>

{{-- ═══ CTA strip ═══ --}}
<section class="bg-primary py-10">
  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
    <div>
      <p class="text-on-primary font-bold text-base">
        {{ $t('ຕ້ອງການລາຍລະອຽດເພີ່ມຕື່ມ?','Want more details?','需要更多詳情？') }}
      </p>
      <p class="text-on-primary/60 text-sm">
        {{ $t('ເບິ່ງຂໍ້ມູນສ່ວນຕົວ ຂອງຄະນະກຳມະການແຕ່ລະທ່ານ','View individual committee member profiles','查看各委員會成員的個人資料') }}
      </p>
    </div>
    <div class="flex gap-3 flex-wrap justify-center">
      <a href="{{ route('front.committee') }}"
         class="flex items-center gap-2 px-6 py-3 bg-secondary text-on-secondary font-bold rounded-xl text-sm
                hover:bg-secondary-container hover:text-on-secondary-container transition-all hover:-translate-y-0.5 shadow-md">
        <i class="fas fa-id-card text-xs"></i>
        {{ $t('ຄະນະກຳມະການ','Committee','委員會') }}
      </a>
      <a href="{{ route('front.contact') }}"
         class="flex items-center gap-2 px-6 py-3 bg-white/10 border border-white/20 text-on-primary font-semibold rounded-xl text-sm
                hover:bg-white/20 transition-all">
        <i class="fas fa-envelope text-xs"></i>
        {{ $t('ຕິດຕໍ່ພວກເຮົາ','Contact Us','聯繫我們') }}
      </a>
    </div>
  </div>
</section>

@endsection
