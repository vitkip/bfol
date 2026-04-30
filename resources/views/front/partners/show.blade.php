@extends('front.layouts.app')

@php
  $L  = app()->getLocale();
  $t  = fn($lo,$en,$zh) => match($L){'zh'=>$zh,'en'=>$en,default=>$lo};
  $tf = fn($field) => $partner->{$field.'_'.$L} ?? $partner->{$field.'_lo'} ?? '';

  $name    = $tf('name');
  $country = $partner->{'country_name_'.$L} ?? $partner->country_name_lo;
  $desc    = $tf('description');

  $typeLabels = [
    'buddhist_org' => $t('ອົງການທາງສາສະໜາ','Buddhist Organisation','佛教機構'),
    'government'   => $t('ລັດຖະບານ','Government','政府機構'),
    'ngo'          => $t('NGO','NGO','非政府組織'),
    'academic'     => $t('ວິຊາການ','Academic','學術機構'),
    'media'        => $t('ສື່ສານ','Media','媒體機構'),
    'un_agency'    => $t('ອົງການ UN','UN Agency','聯合國機構'),
    'other'        => $t('ອື່ນໆ','Other','其他'),
  ];
  $typeLabel = $typeLabels[$partner->type] ?? $partner->type;

  $statusColors = [
    'active'   => 'bg-emerald-100 text-emerald-700',
    'inactive' => 'bg-slate-100 text-slate-500',
    'pending'  => 'bg-amber-100 text-amber-700',
  ];
  $statusColor = $statusColors[$partner->status] ?? 'bg-slate-100 text-slate-500';
@endphp

@section('title', $name.' - '.($settings->site_name_lo ?: 'BFOL'))
@section('meta_description', $desc ? \Str::limit(strip_tags($desc), 160) : '')

@push('styles')
<style>
  .dot-pattern { background-image:radial-gradient(circle,rgba(255,255,255,.10) 1px,transparent 1px); background-size:26px 26px; }
  .section-title {
    font-size: .65rem; font-weight: 800;
    letter-spacing: .12em; text-transform: uppercase;
    color: #94a3b8;
  }
</style>
@endpush

@section('content')

{{-- ═══ HERO ═══ --}}
<section class="relative bg-primary overflow-hidden">
  <div class="dot-pattern absolute inset-0 pointer-events-none"></div>
  <div class="absolute -top-24 -right-24 w-96 h-96 bg-secondary/10 rounded-full blur-3xl pointer-events-none"></div>
  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-12 relative z-10">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-1.5 text-on-primary/50 text-xs mb-6 flex-wrap">
      <a href="{{ route('front.home') }}" class="hover:text-on-primary transition-colors">{{ $t('ໜ້າຫຼັກ','Home','首頁') }}</a>
      <i class="fas fa-chevron-right text-[8px]"></i>
      <a href="{{ route('front.partners.index') }}" class="hover:text-on-primary transition-colors">{{ $t('ຄູ່ຮ່ວມມື','Partners','合作夥伴') }}</a>
      <i class="fas fa-chevron-right text-[8px]"></i>
      <span class="text-on-primary/80">{{ $name }}</span>
    </nav>

    <div class="flex flex-col sm:flex-row items-start gap-6">
      {{-- Logo --}}
      <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl bg-white shadow-lg flex items-center justify-center p-3 shrink-0">
        @if($partner->logo_url)
          <img src="{{ $partner->logo_url }}" alt="{{ $name }}" class="max-w-full max-h-full object-contain" />
        @else
          <i class="fas fa-globe text-primary/40 text-4xl"></i>
        @endif
      </div>

      {{-- Info --}}
      <div class="flex-1">
        <div class="flex flex-wrap items-center gap-2 mb-2">
          <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-white/15 text-on-primary/80">
            {{ $typeLabel }}
          </span>
          <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $statusColor }}">
            {{ ucfirst($partner->status) }}
          </span>
        </div>

        <h1 class="text-2xl sm:text-3xl font-serif font-bold text-on-primary leading-tight mb-1">
          {{ $name }}
          @if($partner->acronym)
            <span class="text-on-primary/50 font-normal text-lg ml-1">({{ $partner->acronym }})</span>
          @endif
        </h1>

        <p class="text-on-primary/60 text-sm flex items-center gap-1.5">
          <i class="fas fa-map-marker-alt text-secondary/80 text-xs"></i>
          {{ $country }}
          @if($partner->partnership_since)
            <span class="text-on-primary/30 mx-1">·</span>
            <i class="fas fa-handshake text-secondary/80 text-xs"></i>
            {{ $t('ຮ່ວມມືຕັ້ງແຕ່','Partner since','合作始於') }} {{ $partner->partnership_since }}
          @endif
        </p>
      </div>
    </div>
  </div>
</section>

{{-- ═══ MAIN ═══ --}}
<section class="bg-slate-50/80 py-10">
  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col lg:flex-row gap-7 items-start">

      {{-- ══ LEFT SIDEBAR ══ --}}
      <aside class="w-full lg:w-[280px] xl:w-[300px] shrink-0">
        <div class="lg:sticky lg:top-[90px] flex flex-col gap-4">

          {{-- Contact info card --}}
          @if($partner->contact_person || $partner->contact_email || $partner->contact_phone || $partner->website_url)
            <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_2px_14px_-4px_rgba(0,0,0,.08)] overflow-hidden">
              <div class="px-4 pt-3 pb-2">
                <p class="section-title">{{ $t('ຂໍ້ມູນຕິດຕໍ່','Contact','聯繫方式') }}</p>
              </div>
              <div class="divide-y divide-slate-100">
                @if($partner->contact_person)
                  <div class="flex items-center gap-3 px-4 py-3">
                    <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center shrink-0">
                      <i class="far fa-user-circle text-amber-500 text-xs"></i>
                    </div>
                    <div class="min-w-0">
                      <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wide">{{ $t('ຜູ້ຕິດຕໍ່','Contact person','聯繫人') }}</p>
                      <p class="text-xs font-bold text-slate-700 truncate">{{ $partner->contact_person }}</p>
                    </div>
                  </div>
                @endif
                @if($partner->contact_email)
                  <div class="flex items-center gap-3 px-4 py-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center shrink-0">
                      <i class="far fa-envelope text-blue-500 text-xs"></i>
                    </div>
                    <div class="min-w-0">
                      <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wide">Email</p>
                      <a href="mailto:{{ $partner->contact_email }}" class="text-xs font-bold text-primary hover:underline truncate block">
                        {{ $partner->contact_email }}
                      </a>
                    </div>
                  </div>
                @endif
                @if($partner->contact_phone)
                  <div class="flex items-center gap-3 px-4 py-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center shrink-0">
                      <i class="fas fa-phone text-emerald-500 text-xs"></i>
                    </div>
                    <div class="min-w-0">
                      <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wide">{{ $t('ໂທລະສັບ','Phone','電話') }}</p>
                      <a href="tel:{{ $partner->contact_phone }}" class="text-xs font-bold text-slate-700">{{ $partner->contact_phone }}</a>
                    </div>
                  </div>
                @endif
                @if($partner->website_url)
                  <div class="px-3 py-3">
                    <a href="{{ $partner->website_url }}" target="_blank" rel="noreferrer"
                       class="flex items-center justify-center gap-2 py-2.5 rounded-xl bg-primary text-on-primary
                              text-xs font-bold hover:opacity-90 active:scale-[.98] transition-all">
                      <i class="fas fa-globe"></i>
                      {{ $t('ເວັບໄຊທາງການ','Official Website','官方網站') }}
                      <i class="fas fa-external-link-alt text-[9px]"></i>
                    </a>
                  </div>
                @endif
              </div>
            </div>
          @endif

          {{-- Quick stats --}}
          @php
            $mouCount     = $partner->mouAgreements->count();
            $programCount = $partner->monkPrograms->count();
            $projectCount = $partner->aidProjects->count();
          @endphp
          @if($mouCount + $programCount + $projectCount > 0)
            <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_2px_14px_-4px_rgba(0,0,0,.08)] p-4">
              <p class="section-title mb-3">{{ $t('ສະຫຼຸບ','Summary','概況') }}</p>
              <div class="grid grid-cols-3 gap-2 text-center">
                @if($mouCount)
                  <div class="bg-slate-50 rounded-xl p-2">
                    <p class="text-lg font-extrabold text-primary">{{ $mouCount }}</p>
                    <p class="text-[9px] text-slate-400 font-semibold leading-tight">MOU</p>
                  </div>
                @endif
                @if($programCount)
                  <div class="bg-slate-50 rounded-xl p-2">
                    <p class="text-lg font-extrabold text-emerald-600">{{ $programCount }}</p>
                    <p class="text-[9px] text-slate-400 font-semibold leading-tight">{{ $t('ແລກປ່ຽນ','Exchange','交流') }}</p>
                  </div>
                @endif
                @if($projectCount)
                  <div class="bg-slate-50 rounded-xl p-2">
                    <p class="text-lg font-extrabold text-amber-600">{{ $projectCount }}</p>
                    <p class="text-[9px] text-slate-400 font-semibold leading-tight">{{ $t('ໂຄງການ','Projects','項目') }}</p>
                  </div>
                @endif
              </div>
            </div>
          @endif

          {{-- Back --}}
          <a href="{{ route('front.partners.index') }}"
             class="flex items-center justify-center gap-2 py-2.5 rounded-xl
                    border border-slate-200 bg-white text-xs font-semibold text-slate-500
                    hover:border-primary/40 hover:text-primary hover:bg-primary/5 transition-all">
            <i class="fas fa-arrow-left text-[10px]"></i>
            {{ $t('ກັບຄືນລາຍຊື່','Back to list','返回列表') }}
          </a>
        </div>
      </aside>

      {{-- ══ RIGHT CONTENT ══ --}}
      <div class="flex-1 min-w-0 flex flex-col gap-6">

        {{-- Description --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_2px_20px_-4px_rgba(0,0,0,.08)] overflow-hidden">
          <div class="h-[3px] bg-gradient-to-r from-primary via-secondary to-primary/40"></div>
          <div class="p-6 sm:p-8">
            <h2 class="text-base font-bold text-on-surface mb-4 flex items-center gap-2">
              <i class="fas fa-info-circle text-primary text-sm"></i>
              {{ $t('ກ່ຽວກັບອົງການ','About the organisation','關於機構') }}
            </h2>
            @if($desc)
              <div class="text-sm text-slate-600 leading-relaxed whitespace-pre-line">{{ $desc }}</div>
            @else
              <p class="text-sm text-slate-400 italic">{{ $t('ຍັງບໍ່ມີຄຳອະທິບາຍ','No description available','暫無介紹') }}</p>
            @endif
          </div>
        </div>

        {{-- MOU Agreements --}}
        @if($partner->mouAgreements->isNotEmpty())
          <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_2px_20px_-4px_rgba(0,0,0,.08)] overflow-hidden">
            <div class="px-6 sm:px-8 pt-5 pb-3 border-b border-slate-100 flex items-center justify-between">
              <h2 class="text-sm font-bold text-on-surface flex items-center gap-2">
                <i class="fas fa-file-signature text-primary text-sm"></i>
                {{ $t('ສັນຍາ MOU','MOU Agreements','MOU協議') }}
                <span class="ml-1 px-2 py-0.5 rounded-full bg-primary/10 text-primary text-[10px] font-bold">{{ $partner->mouAgreements->count() }}</span>
              </h2>
              <a href="{{ route('front.mou.index') }}" class="text-[11px] text-primary hover:underline font-semibold">
                {{ $t('ເບິ່ງທັງໝົດ','View all','查看全部') }} →
              </a>
            </div>
            <div class="divide-y divide-slate-100">
              @foreach($partner->mouAgreements as $mou)
                @php
                  $mouTitle = $mou->{'title_'.$L} ?? $mou->title_lo ?? $t('ບໍ່ມີຊື່','Untitled','無標題');
                  $mouStatus = match($mou->status ?? '') {
                    'active'  => ['text' => $t('ມີຜົນ','Active','有效'), 'class' => 'bg-emerald-100 text-emerald-700'],
                    'expired' => ['text' => $t('ໝົດອາຍຸ','Expired','已過期'), 'class' => 'bg-red-100 text-red-600'],
                    default   => ['text' => $t('ລໍຖ້າ','Pending','待定'), 'class' => 'bg-amber-100 text-amber-700'],
                  };
                @endphp
                <div class="flex items-center gap-4 px-6 sm:px-8 py-3.5 hover:bg-slate-50 transition-colors">
                  <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center shrink-0">
                    <i class="fas fa-file-contract text-blue-500 text-xs"></i>
                  </div>
                  <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-slate-700 truncate">{{ $mouTitle }}</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">
                      @if($mou->signed_date) {{ $t('ລົງນາມ','Signed','簽署') }}: {{ \Carbon\Carbon::parse($mou->signed_date)->format('d/m/Y') }} @endif
                      @if($mou->expiry_date) · {{ $t('ໝົດ','Expires','到期') }}: {{ \Carbon\Carbon::parse($mou->expiry_date)->format('d/m/Y') }} @endif
                    </p>
                  </div>
                  <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $mouStatus['class'] }} shrink-0">
                    {{ $mouStatus['text'] }}
                  </span>
                </div>
              @endforeach
            </div>
          </div>
        @endif

        {{-- Monk Exchange Programs --}}
        @if($partner->monkPrograms->isNotEmpty())
          <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_2px_20px_-4px_rgba(0,0,0,.08)] overflow-hidden">
            <div class="px-6 sm:px-8 pt-5 pb-3 border-b border-slate-100 flex items-center justify-between">
              <h2 class="text-sm font-bold text-on-surface flex items-center gap-2">
                <i class="fas fa-exchange-alt text-emerald-600 text-sm"></i>
                {{ $t('ໂຄງການແລກປ່ຽນ','Exchange Programmes','交流項目') }}
                <span class="ml-1 px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-bold">{{ $partner->monkPrograms->count() }}</span>
              </h2>
              <a href="{{ route('front.monk-programs.index') }}" class="text-[11px] text-primary hover:underline font-semibold">
                {{ $t('ເບິ່ງທັງໝົດ','View all','查看全部') }} →
              </a>
            </div>
            <div class="divide-y divide-slate-100">
              @foreach($partner->monkPrograms as $prog)
                @php $progTitle = $prog->{'title_'.$L} ?? $prog->title_lo ?? '—'; @endphp
                <div class="flex items-center gap-4 px-6 sm:px-8 py-3.5 hover:bg-slate-50 transition-colors">
                  <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center shrink-0">
                    <i class="fas fa-dharmachakra text-emerald-500 text-xs"></i>
                  </div>
                  <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-slate-700 truncate">{{ $progTitle }}</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">
                      @if($prog->year) {{ $prog->year }} @endif
                      @if($prog->destination_country) · {{ $prog->destination_country }} @endif
                    </p>
                  </div>
                  @if($prog->status === 'open')
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 shrink-0">
                      {{ $t('ເປີດ','Open','開放') }}
                    </span>
                  @endif
                </div>
              @endforeach
            </div>
          </div>
        @endif

        {{-- Aid Projects --}}
        @if($partner->aidProjects->isNotEmpty())
          <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_2px_20px_-4px_rgba(0,0,0,.08)] overflow-hidden">
            <div class="px-6 sm:px-8 pt-5 pb-3 border-b border-slate-100 flex items-center justify-between">
              <h2 class="text-sm font-bold text-on-surface flex items-center gap-2">
                <i class="fas fa-hand-holding-heart text-amber-500 text-sm"></i>
                {{ $t('ໂຄງການຊ່ວຍເຫຼືອ','Aid Projects','援助項目') }}
                <span class="ml-1 px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 text-[10px] font-bold">{{ $partner->aidProjects->count() }}</span>
              </h2>
              <a href="{{ route('front.aid-projects.index') }}" class="text-[11px] text-primary hover:underline font-semibold">
                {{ $t('ເບິ່ງທັງໝົດ','View all','查看全部') }} →
              </a>
            </div>
            <div class="divide-y divide-slate-100">
              @foreach($partner->aidProjects as $proj)
                @php $projTitle = $proj->{'title_'.$L} ?? $proj->title_lo ?? '—'; @endphp
                <div class="flex items-center gap-4 px-6 sm:px-8 py-3.5 hover:bg-slate-50 transition-colors">
                  <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center shrink-0">
                    <i class="fas fa-project-diagram text-amber-500 text-xs"></i>
                  </div>
                  <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-slate-700 truncate">{{ $projTitle }}</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">
                      @if($proj->start_date) {{ \Carbon\Carbon::parse($proj->start_date)->format('Y') }} @endif
                      @if($proj->budget_usd) · ${{ number_format($proj->budget_usd) }} @endif
                    </p>
                  </div>
                  @if($proj->status === 'active')
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 shrink-0">
                      {{ $t('ດຳເນີນຢູ່','Active','進行中') }}
                    </span>
                  @endif
                </div>
              @endforeach
            </div>
          </div>
        @endif

      </div>
    </div>
  </div>
</section>

@endsection
