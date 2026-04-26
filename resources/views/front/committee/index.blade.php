@extends('front.layouts.app')

@php
  $L  = app()->getLocale();
  $t  = fn($lo,$en,$zh) => match($L){'zh'=>$zh,'en'=>$en,default=>$lo};
  $tf = fn($m,$f) => $m->{$f.'_'.$L} ?? $m->{$f.'_lo'} ?? '';

  $allMembers  = $departments->flatMap->members->merge($ungrouped);
  $totalCount  = $allMembers->count();
  $monkCount   = $allMembers->where('gender','monk')->count();
  $maleCount   = $allMembers->where('gender','male')->count();
  $femaleCount = $allMembers->where('gender','female')->count();

  $membersJson = $allMembers->map(fn($m) => [
    'id'         => $m->id,
    'sort_order' => $m->sort_order ?? 0,
    'name'       => $tf($m,'name'),
    'title'      => $tf($m,'title'),
    'position'   => $tf($m,'position'),
    'dept_id'    => $m->department_id,
    'dept'       => $m->department
                      ? ($m->department->{'name_'.$L} ?? $m->department->name_lo)
                      : '',
    'gender'     => $m->gender ?? 'male',
    'province'   => $tf($m,'province'),
    'photo_url'  => $m->photo_url ?? '',
    'pansa'      => $m->pansa,
    'bio'        => $tf($m,'bio'),
    'education'  => $tf($m,'education'),
    'village'    => $tf($m,'birth_village'),
    'district'   => $tf($m,'district'),
    'temple'     => $tf($m,'current_temple'),
    'email'      => $m->email      ?? '',
    'phone'      => $m->phone      ?? '',
    'facebook'   => $m->facebook   ?? '',
    'dob'        => $m->date_of_birth      ? $m->date_of_birth->format('d/m/Y')      : '',
    'ordination' => $m->date_of_ordination ? $m->date_of_ordination->format('d/m/Y') : '',
    'term_start' => (string)($m->term_start ?? ''),
    'term_end'   => (string)($m->term_end   ?? ''),
  ])->values();

  $deptsJson = $departments->map(fn($d) => [
    'id'   => $d->id,
    'name' => $d->{'name_'.$L} ?? $d->name_lo,
  ])->values();
@endphp

@section('title',
  $t('ຄະນະກຳມະການ','Committee Members','委员会成员')
  .' - '.($settings->site_name_lo ?: 'BFOL'))
@section('meta_description',
  $t('ລາຍຊື່ຄະນະກຳມະການ ອພສ','BFOL Committee Members','老挝佛教协会委员会成员名单'))

@push('styles')
<style>
  @keyframes float-icon { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-10px)} }
  .float-icon { animation: float-icon 6s ease-in-out infinite; }
  .dot-grid   { background-image: radial-gradient(circle, rgba(197,160,33,.14) 1px, transparent 1px); background-size: 26px 26px; }
  [x-cloak]   { display:none !important; }
</style>
@endpush

@section('content')

{{-- ══════ HERO ══════════════════════════════════════════════════════ --}}
<section class="relative bg-primary overflow-hidden">
  <div class="absolute -top-28 -left-28 w-96 h-96 bg-secondary/10 rounded-full blur-[80px] pointer-events-none"></div>
  <div class="absolute -bottom-20 -right-20 w-72 h-72 bg-secondary/8  rounded-full blur-[60px] pointer-events-none"></div>
  <div class="absolute inset-0 dot-grid pointer-events-none"></div>
  <div class="absolute top-0    inset-x-0 h-px bg-gradient-to-r from-transparent via-secondary/50 to-transparent"></div>
  <div class="absolute bottom-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-secondary/40 to-transparent"></div>

  <div class="relative max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8
              pt-12 pb-20 md:pt-16 md:pb-24 text-center">

    @if($settings->logo_url)
    <div class="float-icon relative inline-flex items-center justify-center mb-5">
      <div class="absolute w-24 h-24 rounded-full bg-white/8 blur-xl"></div>
      <div class="relative w-20 h-20 rounded-full bg-white/10 border border-white/20
                  shadow-[0_0_24px_rgba(255,255,255,.07)] flex items-center justify-center p-2.5">
        <img src="{{ $settings->logo_url }}" alt="{{ $settings->site_name_lo }}"
             class="w-full h-full object-contain drop-shadow-lg" />
      </div>
    </div>
    @endif

    <div class="flex items-center justify-center gap-2 mb-4">
      <div class="w-8 h-px bg-gradient-to-r from-transparent to-white/25"></div>
      <span class="w-1.5 h-1.5 rotate-45 bg-white/25 inline-block"></span>
      <div class="w-16 h-px bg-white/15"></div>
      <span class="w-1.5 h-1.5 rotate-45 bg-white/25 inline-block"></span>
      <div class="w-8 h-px bg-gradient-to-l from-transparent to-white/25"></div>
    </div>

    <h1 class="font-serif text-3xl md:text-5xl font-bold text-white leading-tight mb-1">
      {{ $t('ຄະນະກຳມະການ ການຕ່າງປະເທດ','Committee Members','委员会成员') }}
    </h1>
    <p class="text-white/55 text-[11px] md:text-xs tracking-[0.28em] uppercase font-semibold mb-5">
      {{ $t('ສູນກາງອົງການພຸດທະສາສະໜາ ແຫ່ງ ສປປ ລາວ','Buddhist Fellowship of Lao','老挝佛教协会') }}
    </p>

    <div class="flex items-center justify-center gap-2 mb-5">
      <div class="w-8 h-px bg-gradient-to-r from-transparent to-white/25"></div>
      <span class="w-1 h-1 rotate-45 bg-white/35 inline-block"></span>
      <span class="w-2.5 h-2.5 rotate-45 border border-white/20 inline-block"></span>
      <div class="w-16 h-px bg-white/15"></div>
      <span class="w-2.5 h-2.5 rotate-45 border border-white/20 inline-block"></span>
      <span class="w-1 h-1 rotate-45 bg-white/35 inline-block"></span>
      <div class="w-8 h-px bg-gradient-to-l from-transparent to-white/25"></div>
    </div>

    <p class="text-white/55 max-w-xl mx-auto text-sm leading-relaxed mb-7">
      {{ $t(
        'ລາຍຊື່ຄະນະກຳມະການ ແລະ ບຸກຄະລາກອນ ທີ່ຮ່ວມຮັບຜິດຊອບ ໃນການຊີ້ນຳ ແລະ ບໍລິຫານ ອົງການ',
        'Leaders and committee members guiding and administering the organization',
        '领导和委员会成员共同负责指导和管理组织'
      ) }}
    </p>

    <div class="flex flex-wrap items-center justify-center gap-2">
      <span class="inline-flex items-center gap-2 px-4 py-1.5
                   bg-white/10 border border-white/20 rounded-full text-white text-xs font-semibold">
        <i class="fas fa-calendar-check text-[10px]"></i>
        {{ $t('ສະໄໝ','Term','任期') }} 2022 – 2026
      </span>
      <span class="text-white/25 hidden sm:inline">|</span>
      <nav class="flex items-center gap-1.5 text-xs text-white/40">
        <a href="{{ route('front.home') }}" class="hover:text-white/70 transition-colors">
          {{ $t('ໜ້າຫຼັກ','Home','首页') }}
        </a>
        <i class="fas fa-chevron-right text-[7px] opacity-40"></i>
        <span class="text-white/55">{{ $t('ກ່ຽວກັບ ອພສ','About','关于') }}</span>
        <i class="fas fa-chevron-right text-[7px] opacity-40"></i>
        <span class="text-white/70">{{ $t('ຄະນະກຳມະການ','Committee','委员会') }}</span>
      </nav>
    </div>
  </div>
</section>

{{-- ══════ MAIN (Alpine) ═════════════════════════════════════════════ --}}
<div x-data="committeeList({{ Js::from($membersJson) }}, {{ Js::from($deptsJson) }})"
     class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">

  {{-- ── STATS ─────────────────────────────────────────────────────── --}}
  <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    @foreach([
      ['icon'=>'fas fa-users',         'val'=>$totalCount,  'lo'=>'ທັງໝົດ',  'en'=>'Total',  'zh'=>'总计',
       'bg'=>'bg-primary/8','ic'=>'text-primary','nb'=>'text-primary'],
      ['icon'=>'fas fa-hands-praying', 'val'=>$monkCount,   'lo'=>'ພຣະສົງ',  'en'=>'Monks',  'zh'=>'僧侣',
       'bg'=>'bg-amber-50', 'ic'=>'text-amber-600','nb'=>'text-amber-600'],
      ['icon'=>'fas fa-person',        'val'=>$maleCount,   'lo'=>'ຊາຍ',     'en'=>'Male',   'zh'=>'男性',
       'bg'=>'bg-blue-50',  'ic'=>'text-blue-600','nb'=>'text-blue-600'],
      ['icon'=>'fas fa-person-dress',  'val'=>$femaleCount, 'lo'=>'ຍິງ',     'en'=>'Female', 'zh'=>'女性',
       'bg'=>'bg-rose-50',  'ic'=>'text-rose-500','nb'=>'text-rose-500'],
    ] as $s)
    <div class="bg-white rounded-2xl border border-slate-100
                shadow-[0_2px_16px_-4px_rgba(0,0,0,.08)]
                hover:shadow-[0_4px_24px_-4px_rgba(3,22,50,.12)]
                hover:-translate-y-0.5 transition-all duration-300 p-5 flex items-center gap-4">
      <div class="w-11 h-11 rounded-xl {{ $s['bg'] }} flex items-center justify-center shrink-0">
        <i class="{{ $s['icon'] }} {{ $s['ic'] }} text-xl"></i>
      </div>
      <div>
        <p class="text-2xl font-serif font-bold {{ $s['nb'] }} leading-none">{{ $s['val'] }}</p>
        <p class="text-xs font-medium text-slate-400 mt-0.5">
          {{ $t($s['lo'],$s['en'],$s['zh']) }}
        </p>
      </div>
    </div>
    @endforeach
  </div>

  {{-- ── SEARCH + FILTER ──────────────────────────────────────────── --}}
  <div class="bg-white rounded-2xl border border-slate-100
              shadow-[0_2px_16px_-4px_rgba(0,0,0,.07)] p-4 md:p-5">
    <div class="flex flex-col sm:flex-row gap-3">

      <div class="relative flex-1">
        <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2
                  text-slate-400 text-xs pointer-events-none"></i>
        <input x-model.debounce.250ms="search"
               type="text"
               placeholder="{{ $t('ຄົ້ນຫາຊື່, ຕຳແໜ່ງ…','Search name, position…','搜索姓名、职位…') }}"
               class="w-full pl-9 pr-4 py-2.5 text-sm rounded-xl border border-slate-200
                      bg-slate-50 focus:bg-white focus:outline-none focus:ring-2
                      focus:ring-primary/20 focus:border-primary/40 transition-all" />
      </div>

      <select x-model="deptFilter"
              class="py-2.5 pl-3 pr-8 text-sm rounded-xl border border-slate-200
                     bg-slate-50 focus:bg-white focus:outline-none focus:ring-2
                     focus:ring-primary/20 focus:border-primary/40 transition-all min-w-[160px]">
        <option value="">{{ $t('ທຸກຝ່າຍ','All Departments','所有部门') }}</option>
        @foreach($deptsJson as $d)
          <option value="{{ $d['id'] }}">{{ $d['name'] }}</option>
        @endforeach
      </select>

      <select x-model="genderFilter"
              class="py-2.5 pl-3 pr-8 text-sm rounded-xl border border-slate-200
                     bg-slate-50 focus:bg-white focus:outline-none focus:ring-2
                     focus:ring-primary/20 focus:border-primary/40 transition-all min-w-[140px]">
        <option value="">{{ $t('ທຸກປະເພດ','All Types','所有类型') }}</option>
        <option value="monk">{{ $t('ພຣະສົງ','Monk','僧侣') }}</option>
        <option value="male">{{ $t('ຊາຍ','Male','男性') }}</option>
        <option value="female">{{ $t('ຍິງ','Female','女性') }}</option>
      </select>

      <button x-show="hasFilters" x-cloak @click="clearFilters()"
              class="px-4 py-2.5 text-sm font-semibold rounded-xl border border-slate-200
                     text-slate-500 hover:bg-slate-100 hover:text-slate-700
                     transition-colors flex items-center gap-2 shrink-0">
        <i class="fas fa-times text-xs"></i>
        {{ $t('ລ້າງ','Clear','清除') }}
      </button>
    </div>

    <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-100">
      <p class="text-xs text-slate-400">
        <span class="font-semibold text-primary" x-text="filtered.length"></span>
        {{ $t(' ລາຍການ',' results',' 条结果') }}
        <template x-if="hasFilters">
          <span class="text-slate-300">
            &nbsp;/&nbsp;{{ $totalCount }} {{ $t('ທັງໝົດ','total','总计') }}
          </span>
        </template>
      </p>
    </div>
  </div>

  {{-- ── CARD GROUPS ───────────────────────────────────────────────── --}}
  <div class="space-y-12">

    <template x-for="dept in allDepts" :key="dept.id ?? '__ungrouped__'">
      <section x-show="membersForDept(dept.id).length > 0">

        {{-- Dept section header --}}
        <div class="flex items-center gap-3 mb-6">
          <div class="w-1 h-7 rounded-full shrink-0"
               :class="dept.id ? 'bg-secondary' : 'bg-slate-400'"></div>
          <h2 class="font-serif font-bold text-primary text-xl leading-tight"
              x-text="dept.name"></h2>
          <span class="text-xs font-bold px-2.5 py-0.5 rounded-full shrink-0"
                :class="dept.id ? 'bg-primary/10 text-primary' : 'bg-slate-100 text-slate-500'"
                x-text="membersForDept(dept.id).length + ' {{ $t('ທ່ານ','people','人') }}'"></span>
          <div class="flex-1 h-px bg-slate-200 hidden sm:block"></div>
        </div>

        {{-- Cards grid --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-5">
          <template x-for="m in membersForDept(dept.id)" :key="m.id">

            <article
              class="group relative flex flex-col h-full bg-white rounded-3xl
                     border border-slate-100
                     shadow-[0_4px_24px_-8px_rgba(3,22,50,0.10)]
                     hover:shadow-[0_16px_48px_-8px_rgba(3,22,50,0.18)]
                     hover:-translate-y-2 transition-all duration-300
                     overflow-hidden cursor-pointer"
              @click="openMember(m)">

              {{-- Gender colour strip --}}
              <div class="h-1.5 shrink-0 bg-gradient-to-r"
                   :class="m.gender==='monk'
                     ? 'from-amber-400 via-amber-500 to-amber-600'
                     : m.gender==='female'
                       ? 'from-rose-400 via-rose-500 to-rose-600'
                       : 'from-blue-400 via-blue-500 to-blue-600'"></div>

              {{-- Monk warm glow --}}
              <template x-if="m.gender==='monk'">
                <div class="absolute inset-0 bg-gradient-to-b from-amber-50/50
                            via-transparent to-transparent pointer-events-none"></div>
              </template>

              {{-- Body --}}
              <div class="relative flex flex-col items-center text-center
                          px-4 pt-6 pb-4 gap-3 flex-1">

                {{-- Avatar --}}
                <div class="relative shrink-0">
                  <div class="w-[80px] h-[80px] sm:w-[88px] sm:h-[88px] rounded-full p-[3px]
                               shadow-lg group-hover:scale-105 transition-transform duration-300"
                       :class="m.gender==='monk'
                         ? 'bg-gradient-to-br from-amber-400 to-amber-600'
                         : m.gender==='female'
                           ? 'bg-gradient-to-br from-rose-400 to-rose-600'
                           : 'bg-gradient-to-br from-blue-400 to-blue-600'">
                    <div class="w-full h-full rounded-full overflow-hidden"
                         :class="m.gender==='monk'
                           ? 'bg-gradient-to-br from-amber-50 to-amber-100'
                           : m.gender==='female'
                             ? 'bg-gradient-to-br from-rose-50 to-rose-100'
                             : 'bg-gradient-to-br from-blue-50 to-blue-100'">
                      <template x-if="m.photo_url">
                        <img :src="m.photo_url" :alt="m.name"
                             class="w-full h-full object-cover" loading="lazy" />
                      </template>
                      <template x-if="!m.photo_url">
                        <div class="w-full h-full flex items-center justify-center">
                          <i x-show="m.gender==='monk'"
                             class="fas fa-hands-praying text-amber-400 text-3xl" style="display:none"></i>
                          <i x-show="m.gender==='female'"
                             class="fas fa-person-dress text-rose-400 text-3xl" style="display:none"></i>
                          <i x-show="m.gender!=='monk' && m.gender!=='female'"
                             class="fas fa-person text-blue-400 text-3xl" style="display:none"></i>
                        </div>
                      </template>
                    </div>
                  </div>

                  {{-- Gender badge --}}
                  <span class="absolute -bottom-1 -right-1 w-7 h-7 rounded-full
                               border-[3px] border-white shadow-md
                               flex items-center justify-center text-white
                               group-hover:scale-110 transition-transform duration-300"
                        :class="m.gender==='monk' ? 'bg-amber-500'
                                  : m.gender==='female' ? 'bg-rose-500' : 'bg-blue-500'">
                    <i x-show="m.gender==='monk'"
                       class="fas fa-hands-praying text-[9px]" style="display:none"></i>
                    <i x-show="m.gender==='female'"
                       class="fas fa-person-dress text-[9px]" style="display:none"></i>
                    <i x-show="m.gender!=='monk' && m.gender!=='female'"
                       class="fas fa-person text-[9px]" style="display:none"></i>
                  </span>
                </div>

                {{-- Name + position --}}
                <div class="w-full space-y-1 min-w-0">
                  <h3 class="font-serif font-bold text-primary text-[14px] leading-snug
                             line-clamp-2 group-hover:text-secondary transition-colors duration-300"
                      x-text="m.name"></h3>
                  <p class="text-[10px] font-semibold text-secondary/90 line-clamp-1
                             uppercase tracking-wide"
                     x-text="m.position"></p>

                  {{-- Gender + pansa pill --}}
                  <span class="inline-flex items-center gap-1.5 px-3 py-0.5
                               rounded-full text-[10px] font-bold border"
                        :class="m.gender==='monk'
                          ? 'bg-amber-50 text-amber-800 border-amber-200'
                          : m.gender==='female'
                            ? 'bg-rose-50 text-rose-800 border-rose-200'
                            : 'bg-blue-50 text-blue-800 border-blue-200'">
                    <i x-show="m.gender==='monk'"
                       class="fas fa-hands-praying text-[8px]" style="display:none"></i>
                    <i x-show="m.gender==='female'"
                       class="fas fa-person-dress text-[8px]" style="display:none"></i>
                    <i x-show="m.gender!=='monk' && m.gender!=='female'"
                       class="fas fa-person text-[8px]" style="display:none"></i>
                    <span x-text="m.gender==='monk'
                                    ? '{{ $t('ພຣະສົງ','Monk','僧侣') }}'
                                    : m.gender==='female'
                                      ? '{{ $t('ຍິງ','Female','女性') }}'
                                      : '{{ $t('ຊາຍ','Male','男性') }}'"></span>
                    <template x-if="m.pansa">
                      <span x-text="' · ' + m.pansa + '{{ match($L){'zh'=>'安','en'=>'P',default=>'ພ'} }}.'"></span>
                    </template>
                  </span>
                </div>

                {{-- Province chip --}}
                <template x-if="m.province">
                  <p class="text-[10px] text-slate-500 flex items-center gap-1
                             font-medium bg-slate-50 px-3 py-1 rounded-full
                             border border-slate-100 -mt-1">
                    <i class="fas fa-location-dot text-[9px] text-secondary/70"></i>
                    <span x-text="m.province"></span>
                  </p>
                </template>

              </div>

              {{-- Footer CTA --}}
              <div class="mt-auto shrink-0 border-t border-slate-100 px-4 py-3
                          flex items-center justify-between bg-slate-50/60
                          group-hover:bg-primary transition-colors duration-300">
                <span class="text-[10px] font-bold uppercase tracking-widest
                             text-slate-400 group-hover:text-white/70 transition-colors duration-300">
                  {{ $t('ເບິ່ງຂໍ້ມູນ','View Profile','查看详情') }}
                </span>
                <span class="w-6 h-6 rounded-full bg-white shadow-sm
                             flex items-center justify-center text-primary
                             group-hover:translate-x-1 transition-all duration-300">
                  <i class="fas fa-chevron-right text-[8px]"></i>
                </span>
              </div>

            </article>

          </template>
        </div>

      </section>
    </template>

    {{-- Empty state --}}
    <template x-if="filtered.length === 0">
      <div class="py-24 text-center">
        <div class="flex flex-col items-center gap-4">
          <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center">
            <i class="fas fa-users-slash text-3xl text-slate-300"></i>
          </div>
          <p class="font-semibold text-slate-500 text-lg">
            {{ $t('ບໍ່ພົບຂໍ້ມູນ','No results found','未找到数据') }}
          </p>
          <button @click="clearFilters()"
                  class="text-sm text-primary font-semibold border border-primary/30
                         px-5 py-2 rounded-full hover:bg-primary/5 transition-colors">
            {{ $t('ລ້າງຕົວກອງ','Clear filters','清除筛选') }}
          </button>
        </div>
      </div>
    </template>

  </div>
</div>{{-- /committeeList --}}

{{-- ══════ MEMBER DETAIL MODAL ══════════════════════════════════════ --}}
<div x-data="committeeModal()"
     @open-member.window="open($event.detail)"
     @keydown.escape.window="close()"
     x-show="show"
     x-cloak
     x-transition:enter="transition duration-200 ease-out"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition duration-150 ease-in"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-[200] flex items-end sm:items-center justify-center
            p-0 sm:p-4 bg-black/60 backdrop-blur-sm"
     @click.self="close()">

  <div x-show="show"
       x-transition:enter="transition duration-300 ease-out"
       x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-8 sm:scale-95"
       x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
       x-transition:leave="transition duration-200 ease-in"
       x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-6 sm:scale-95"
       class="relative bg-white w-full sm:max-w-lg rounded-t-3xl sm:rounded-3xl
              shadow-2xl max-h-[88dvh] sm:max-h-[680px] overflow-hidden flex flex-col">

    {{-- Top gradient strip --}}
    <div class="h-1.5 shrink-0 rounded-t-3xl sm:rounded-t-3xl"
         :class="member.gender==='monk'
           ? 'bg-gradient-to-r from-amber-400 via-amber-500 to-amber-600'
           : member.gender==='female'
             ? 'bg-gradient-to-r from-rose-400 via-rose-500 to-rose-600'
             : 'bg-gradient-to-r from-blue-400 via-blue-500 to-blue-600'"></div>

    {{-- Mobile drag handle --}}
    <div class="sm:hidden flex justify-center py-2.5 shrink-0">
      <div class="w-10 h-1 bg-slate-200 rounded-full"></div>
    </div>

    {{-- Close --}}
    <button @click="close()"
            class="absolute top-4 right-4 z-20 w-9 h-9 rounded-full
                   bg-slate-100 hover:bg-slate-200 transition-colors cursor-pointer
                   flex items-center justify-center">
      <i class="fas fa-times text-slate-500 text-sm"></i>
    </button>

    {{-- ── Scrollable body ─────────────────────────────────────────── --}}
    <div class="overflow-y-auto flex-1">

      {{-- Profile header --}}
      <div class="px-6 pt-1 pb-5 text-center">

        {{-- Avatar --}}
        <div class="relative inline-block mb-4">
          <div class="w-24 h-24 rounded-full p-[3px] shadow-xl"
               :class="member.gender==='monk'
                 ? 'bg-gradient-to-br from-amber-400 to-amber-600'
                 : member.gender==='female'
                   ? 'bg-gradient-to-br from-rose-400 to-rose-600'
                   : 'bg-gradient-to-br from-blue-400 to-blue-600'">
            <div class="w-full h-full rounded-full overflow-hidden bg-white">
              <template x-if="member.photo_url">
                <img :src="member.photo_url" :alt="member.name"
                     class="w-full h-full object-cover" />
              </template>
              <template x-if="!member.photo_url">
                <div class="w-full h-full flex items-center justify-center"
                     :class="member.gender==='monk' ? 'bg-amber-50'
                               : member.gender==='female' ? 'bg-rose-50' : 'bg-blue-50'">
                  <i x-show="member.gender==='monk'"
                     class="fas fa-hands-praying text-amber-400 text-5xl" style="display:none"></i>
                  <i x-show="member.gender==='female'"
                     class="fas fa-person-dress text-rose-400 text-5xl" style="display:none"></i>
                  <i x-show="member.gender!=='monk' && member.gender!=='female'"
                     class="fas fa-person text-blue-400 text-5xl" style="display:none"></i>
                </div>
              </template>
            </div>
          </div>
          <span class="absolute -bottom-1 -right-1 w-8 h-8 rounded-full
                       border-[3px] border-white shadow-md
                       flex items-center justify-center text-white"
                :class="member.gender==='monk' ? 'bg-amber-500'
                          : member.gender==='female' ? 'bg-rose-500' : 'bg-blue-500'">
            <i x-show="member.gender==='monk'"
               class="fas fa-hands-praying text-[11px]" style="display:none"></i>
            <i x-show="member.gender==='female'"
               class="fas fa-person-dress text-[11px]" style="display:none"></i>
            <i x-show="member.gender!=='monk' && member.gender!=='female'"
               class="fas fa-person text-[11px]" style="display:none"></i>
          </span>
        </div>

        {{-- Name --}}
        <h3 class="font-serif text-xl font-bold text-primary leading-snug"
            x-text="(member.title ? member.title + ' ' : '') + member.name"></h3>
        <p class="text-secondary font-semibold text-sm mt-1" x-text="member.position"></p>

        {{-- Chips row --}}
        <div class="flex flex-wrap items-center justify-center gap-2 mt-3">

          <template x-if="member.dept">
            <span class="inline-flex items-center gap-1.5 px-3 py-1
                         bg-primary/8 text-primary text-xs font-semibold rounded-full">
              <i class="fas fa-sitemap text-[9px]"></i>
              <span x-text="member.dept"></span>
            </span>
          </template>

          <template x-if="member.term_start">
            <span class="inline-flex items-center gap-1.5 px-3 py-1
                         bg-slate-100 text-slate-600 text-xs font-semibold rounded-full">
              <i class="fas fa-calendar-check text-[9px]"></i>
              <span x-text="member.term_start + (member.term_end ? ' – ' + member.term_end : '')"></span>
            </span>
          </template>

          <template x-if="member.pansa">
            <span class="inline-flex items-center gap-1.5 px-3 py-1
                         bg-amber-50 border border-amber-200 text-amber-800
                         text-xs font-bold rounded-full">
              <span x-text="member.pansa + ' {{ $t('ພັນສາ','Pansa','安居') }}'"></span>
            </span>
          </template>

        </div>
      </div>

      {{-- Bio --}}
      <template x-if="member.bio">
        <div class="mx-6 mb-4 bg-slate-50 rounded-2xl px-5 py-4 border-l-2 border-primary/30">
          <p class="text-slate-600 text-sm leading-relaxed" x-text="member.bio"></p>
        </div>
      </template>

      <div class="px-6 pb-4 space-y-3">

        {{-- ── Monk info section ──────────────────────────────────── --}}
        <template x-if="member.gender==='monk' && (member.ordination || member.temple)">
          <div class="rounded-2xl border border-amber-100 bg-amber-50/50 overflow-hidden">
            <div class="px-4 py-2 bg-amber-100/60 border-b border-amber-100">
              <h4 class="text-[11px] font-bold text-amber-700 uppercase tracking-widest">
                {{ $t('ຂໍ້ມູນສົງ','Monk Information','僧侣信息') }}
              </h4>
            </div>
            <div class="p-4 space-y-3">

              <template x-if="member.ordination">
                <div class="flex items-start gap-3">
                  <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center shrink-0">
                    <i class="fas fa-calendar-check text-amber-600 text-xs"></i>
                  </div>
                  <div class="min-w-0">
                    <p class="text-[10px] text-amber-600 font-bold uppercase tracking-wide mb-0.5">
                      {{ $t('ວັນອຸປະສົມ','Ordination Date','受戒日期') }}
                    </p>
                    <p class="text-sm text-slate-700" x-text="member.ordination"></p>
                  </div>
                </div>
              </template>

              <template x-if="member.temple">
                <div class="flex items-start gap-3">
                  <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center shrink-0">
                    <i class="fas fa-place-of-worship text-amber-600 text-xs"></i>
                  </div>
                  <div class="min-w-0">
                    <p class="text-[10px] text-amber-600 font-bold uppercase tracking-wide mb-0.5">
                      {{ $t('ວັດປັດຈຸບັນ','Current Temple','现住寺院') }}
                    </p>
                    <p class="text-sm text-slate-700" x-text="member.temple"></p>
                  </div>
                </div>
              </template>

            </div>
          </div>
        </template>

        {{-- ── Personal info section ──────────────────────────────── --}}
        <template x-if="member.dob || member.education || member.village || member.district || member.province">
          <div class="rounded-2xl border border-slate-100 bg-slate-50/60 overflow-hidden">
            <div class="px-4 py-2 bg-slate-100/60 border-b border-slate-100">
              <h4 class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                {{ $t('ຂໍ້ມູນສ່ວນຕົວ','Personal Information','个人信息') }}
              </h4>
            </div>
            <div class="p-4 space-y-3">

              <template x-if="member.dob">
                <div class="flex items-start gap-3">
                  <div class="w-8 h-8 rounded-lg bg-white shadow-sm flex items-center justify-center shrink-0">
                    <i class="fas fa-cake-candles text-primary/60 text-xs"></i>
                  </div>
                  <div class="min-w-0">
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wide mb-0.5">
                      {{ $t('ວັນເດືອນປີເກີດ','Date of Birth','出生日期') }}
                    </p>
                    <p class="text-sm text-slate-700" x-text="member.dob"></p>
                  </div>
                </div>
              </template>

              <template x-if="member.education">
                <div class="flex items-start gap-3">
                  <div class="w-8 h-8 rounded-lg bg-white shadow-sm flex items-center justify-center shrink-0">
                    <i class="fas fa-graduation-cap text-primary/60 text-xs"></i>
                  </div>
                  <div class="min-w-0">
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wide mb-0.5">
                      {{ $t('ການສຶກສາ','Education','学历') }}
                    </p>
                    <p class="text-sm text-slate-700 leading-snug" x-text="member.education"></p>
                  </div>
                </div>
              </template>

              <template x-if="member.village || member.district || member.province">
                <div class="flex items-start gap-3">
                  <div class="w-8 h-8 rounded-lg bg-white shadow-sm flex items-center justify-center shrink-0">
                    <i class="fas fa-location-dot text-primary/60 text-xs"></i>
                  </div>
                  <div class="min-w-0">
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wide mb-0.5">
                      {{ $t('ທີ່ຢູ່ເກີດ','Hometown','籍贯') }}
                    </p>
                    <div class="flex flex-wrap gap-1 mt-0.5">
                      <template x-if="member.village">
                        <span class="text-xs bg-white border border-slate-200 px-2 py-0.5 rounded-full text-slate-600"
                              x-text="'{{ $t('ບ້ານ','Village','村') }}: ' + member.village"></span>
                      </template>
                      <template x-if="member.district">
                        <span class="text-xs bg-white border border-slate-200 px-2 py-0.5 rounded-full text-slate-600"
                              x-text="'{{ $t('ເມືອງ','District','县') }}: ' + member.district"></span>
                      </template>
                      <template x-if="member.province">
                        <span class="text-xs bg-white border border-slate-200 px-2 py-0.5 rounded-full text-slate-600"
                              x-text="'{{ $t('ແຂວງ','Province','省') }}: ' + member.province"></span>
                      </template>
                    </div>
                  </div>
                </div>
              </template>

            </div>
          </div>
        </template>

      </div>
    </div>{{-- /scrollable --}}

    {{-- ── Contact footer ─────────────────────────────────────────── --}}
    <div x-show="member.email || member.phone || member.facebook"
         class="px-6 pb-6 pt-4 border-t border-slate-100 shrink-0">
      <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">
        {{ $t('ຕິດຕໍ່','Contact','联系方式') }}
      </p>
      <div class="flex flex-wrap gap-2">
        <template x-if="member.email">
          <a :href="'mailto:'+member.email"
             class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-bold
                    bg-primary text-white hover:opacity-90 transition-opacity cursor-pointer">
            <i class="fas fa-envelope text-[10px]"></i>
            <span x-text="member.email"></span>
          </a>
        </template>
        <template x-if="member.phone">
          <a :href="'tel:'+member.phone"
             class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-bold
                    bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors cursor-pointer">
            <i class="fas fa-phone text-[10px]"></i>
            <span x-text="member.phone"></span>
          </a>
        </template>
        <template x-if="member.facebook">
          <a :href="member.facebook" target="_blank" rel="noreferrer"
             class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-bold
                    bg-blue-600 text-white hover:bg-blue-700 transition-colors cursor-pointer">
            <i class="fab fa-facebook-f text-[10px]"></i>
            Facebook
          </a>
        </template>
      </div>
    </div>

  </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {

  Alpine.data('committeeModal', () => ({
    show:   false,
    member: {},
    open(data) {
      this.member = data;
      this.show   = true;
      document.body.style.overflow = 'hidden';
    },
    close() {
      this.show = false;
      document.body.style.overflow = '';
    },
  }));

  Alpine.data('committeeList', (allMembers, depts) => ({
    members:      allMembers,
    departments:  depts,
    search:       '',
    deptFilter:   '',
    genderFilter: '',

    get filtered() {
      const q = this.search.toLowerCase().trim();
      return this.members.filter(m => {
        const matchSearch = !q
          || m.name.toLowerCase().includes(q)
          || (m.position || '').toLowerCase().includes(q)
          || (m.province || '').toLowerCase().includes(q);
        const matchDept   = !this.deptFilter
          || String(m.dept_id) === String(this.deptFilter);
        const matchGender = !this.genderFilter || m.gender === this.genderFilter;
        return matchSearch && matchDept && matchGender;
      });
    },

    get allDepts() {
      const hasUngrouped = this.filtered.some(m => !m.dept_id);
      if (!hasUngrouped) return this.departments;
      return [
        ...this.departments,
        { id: null, name: '{{ $t('ອື່ນໆ','Other','其他') }}' },
      ];
    },

    membersForDept(deptId) {
      if (deptId === null || deptId === undefined) {
        return this.filtered.filter(m => !m.dept_id);
      }
      return this.filtered.filter(m => String(m.dept_id) === String(deptId));
    },

    clearFilters() {
      this.search = '';
      this.deptFilter = '';
      this.genderFilter = '';
    },
    get hasFilters() {
      return !!(this.search || this.deptFilter || this.genderFilter);
    },

    openMember(m) {
      window.dispatchEvent(new CustomEvent('open-member', { detail: m }));
    },
  }));

});
</script>
@endpush
