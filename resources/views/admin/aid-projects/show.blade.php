@extends('admin.layouts.app')

@section('page_title', $project->title_lo)

@section('content')
<div class="max-w-4xl mx-auto space-y-5">

  {{-- Breadcrumb --}}
  <div class="flex items-center gap-2 text-xs text-outline">
    <a href="{{ route('admin.aid-projects.index') }}" class="hover:text-primary">ໂຄງການຊ່ວຍເຫຼືອ</a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <span class="truncate max-w-[200px]">{{ $project->title_lo }}</span>
  </div>

  {{-- Header card --}}
  <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
      <div class="flex-1 min-w-0">
        {{-- Type + Status badges --}}
        <div class="flex flex-wrap items-center gap-2 mb-3">
          @php $tm = $types[$project->type] ?? $types['other']; @endphp
          <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full {{ $tm['class'] }}">
            <i class="fas {{ $tm['icon'] }} text-[9px]"></i> {{ $tm['lo'] }}
          </span>
          @php $st = $statuses[$project->status] ?? $statuses['planning']; @endphp
          <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full {{ $st['class'] }}">
            <i class="fas {{ $st['icon'] }} text-[9px]"></i> {{ $st['lo'] }}
          </span>
        </div>

        <h1 class="text-lg font-bold text-on-surface leading-snug">{{ $project->title_lo }}</h1>
        @if($project->title_en)
          <p class="text-sm text-outline mt-0.5">{{ $project->title_en }}</p>
        @endif
        @if($project->title_zh)
          <p class="text-sm text-outline mt-0.5">{{ $project->title_zh }}</p>
        @endif

        <div class="flex flex-wrap items-center gap-x-4 gap-y-1.5 mt-3 text-xs text-on-surface-variant">
          <span class="flex items-center gap-1.5">
            <i class="fas fa-map-marker-alt text-outline text-[10px]"></i>
            {{ $project->country }}
          </span>
          @if($project->partnerOrganization)
            <span class="flex items-center gap-1.5">
              <i class="fas fa-handshake text-outline text-[10px]"></i>
              <a href="{{ route('admin.partners.show', $project->partnerOrganization) }}" class="hover:text-primary">
                {{ $project->partnerOrganization->acronym ? "[{$project->partnerOrganization->acronym}] " : '' }}{{ $project->partnerOrganization->name_lo }}
              </a>
            </span>
          @endif
          @if($project->budget_usd)
            <span class="flex items-center gap-1.5">
              <i class="fas fa-dollar-sign text-outline text-[10px]"></i>
              <span class="font-semibold">${{ number_format($project->budget_usd, 0) }}</span>
            </span>
          @endif
        </div>
      </div>

      {{-- Actions --}}
      <div class="flex items-center gap-2 shrink-0">
        <a href="{{ route('admin.aid-projects.edit', $project) }}"
           class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg border border-surface-container-high text-sm font-semibold hover:bg-surface-container transition-colors">
          <i class="fas fa-edit text-xs text-yellow-600"></i> ແກ້ໄຂ
        </a>
        <form action="{{ route('admin.aid-projects.destroy', $project) }}" method="POST"
              onsubmit="return confirm('ລຶບໂຄງການ «{{ $project->title_lo }}» ແທ້ບໍ?')">
          @csrf @method('DELETE')
          <button type="submit"
                  class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg border border-red-200 text-sm font-semibold text-red-600 hover:bg-red-50 transition-colors">
            <i class="fas fa-trash text-xs"></i> ລຶບ
          </button>
        </form>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Left: Description + Report --}}
    <div class="lg:col-span-2 space-y-5">

      {{-- Date range --}}
      @if($project->start_date || $project->end_date)
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
        <h3 class="font-bold text-sm text-on-surface mb-3 flex items-center gap-2">
          <i class="fas fa-calendar-alt text-primary text-xs"></i> ໄລຍະໂຄງການ
        </h3>
        <div class="flex items-center gap-3">
          @if($project->start_date)
            <div class="text-center">
              <p class="text-[10px] text-outline uppercase tracking-wide mb-0.5">ເລີ່ມ</p>
              <p class="text-sm font-semibold text-on-surface">{{ $project->start_date->format('d/m/Y') }}</p>
            </div>
          @endif
          @if($project->start_date && $project->end_date)
            <div class="flex-1 h-px bg-surface-container-high relative">
              <i class="fas fa-arrow-right absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-[10px] text-outline bg-surface-container-lowest px-1"></i>
            </div>
          @endif
          @if($project->end_date)
            <div class="text-center">
              <p class="text-[10px] text-outline uppercase tracking-wide mb-0.5">ສຸດ</p>
              <p class="text-sm font-semibold text-on-surface">{{ $project->end_date->format('d/m/Y') }}</p>
            </div>
          @endif
          @if($project->start_date && $project->end_date)
            @php
              $days = $project->start_date->diffInDays($project->end_date);
              $years = floor($days / 365);
              $months = floor(($days % 365) / 30);
            @endphp
            <div class="ml-auto text-right">
              <p class="text-[10px] text-outline">ໄລຍະທັງໝົດ</p>
              <p class="text-xs font-semibold text-on-surface-variant">
                @if($years > 0) {{ $years }} ປີ @endif
                @if($months > 0) {{ $months }} ເດືອນ @endif
                @if($years == 0 && $months == 0) {{ $days }} ວັນ @endif
              </p>
            </div>
          @endif
        </div>
      </div>
      @endif

      {{-- Report URL --}}
      @if($project->report_url)
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
        <h3 class="font-bold text-sm text-on-surface mb-3 flex items-center gap-2">
          <i class="fas fa-file-alt text-primary text-xs"></i> ລາຍງານໂຄງການ
        </h3>
        <a href="{{ $project->report_url }}" target="_blank" rel="noopener noreferrer"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-blue-50 border border-blue-200 text-blue-700 text-sm font-semibold hover:bg-blue-100 transition-colors">
          <i class="fas fa-external-link-alt text-xs"></i> ເປີດລາຍງານ
          <span class="text-xs font-normal text-blue-500 truncate max-w-[220px]">{{ $project->report_url }}</span>
        </a>
      </div>
      @endif

      {{-- Description --}}
      @if($project->description_lo || $project->description_en || $project->description_zh)
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5"
           x-data="{ lang: '{{ $project->description_lo ? 'lo' : ($project->description_en ? 'en' : 'zh') }}' }">
        <div class="flex items-center justify-between mb-4">
          <h3 class="font-bold text-sm text-on-surface flex items-center gap-2">
            <i class="fas fa-align-left text-primary text-xs"></i> ລາຍລະອຽດ
          </h3>
          <div class="flex gap-1">
            @if($project->description_lo)
              <button @click="lang='lo'" :class="lang==='lo' ? 'primary-gradient text-white' : 'border border-surface-container-high hover:bg-surface-container'"
                      class="px-2.5 py-1 rounded text-[11px] font-semibold transition-colors">LO</button>
            @endif
            @if($project->description_en)
              <button @click="lang='en'" :class="lang==='en' ? 'primary-gradient text-white' : 'border border-surface-container-high hover:bg-surface-container'"
                      class="px-2.5 py-1 rounded text-[11px] font-semibold transition-colors">EN</button>
            @endif
            @if($project->description_zh)
              <button @click="lang='zh'" :class="lang==='zh' ? 'primary-gradient text-white' : 'border border-surface-container-high hover:bg-surface-container'"
                      class="px-2.5 py-1 rounded text-[11px] font-semibold transition-colors">ZH</button>
            @endif
          </div>
        </div>

        @if($project->description_lo)
          <div x-show="lang==='lo'" class="text-sm text-on-surface-variant leading-relaxed whitespace-pre-wrap">{{ $project->description_lo }}</div>
        @endif
        @if($project->description_en)
          <div x-show="lang==='en'" class="text-sm text-on-surface-variant leading-relaxed whitespace-pre-wrap">{{ $project->description_en }}</div>
        @endif
        @if($project->description_zh)
          <div x-show="lang==='zh'" class="text-sm text-on-surface-variant leading-relaxed whitespace-pre-wrap">{{ $project->description_zh }}</div>
        @endif
      </div>
      @endif

    </div>

    {{-- Right sidebar --}}
    <div class="space-y-5">

      {{-- Partner org card --}}
      @if($project->partnerOrganization)
      @php $partner = $project->partnerOrganization; @endphp
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
        <h3 class="font-bold text-sm text-on-surface mb-3 flex items-center gap-2">
          <i class="fas fa-handshake text-primary text-xs"></i> ອົງກອນຄູ່ຮ່ວມ
        </h3>
        <div class="flex items-start gap-3">
          @if($partner->logo_url)
            <img src="{{ $partner->logo_url }}" alt="{{ $partner->name_lo }}"
                 class="w-10 h-10 rounded-lg object-contain border border-surface-container-high bg-white shrink-0">
          @else
            <div class="w-10 h-10 rounded-lg bg-surface-container-low border border-surface-container-high flex items-center justify-center shrink-0">
              <i class="fas fa-building text-outline text-sm"></i>
            </div>
          @endif
          <div class="min-w-0">
            @if($partner->acronym)
              <span class="inline-block text-[10px] font-bold px-1.5 py-0.5 rounded bg-primary/10 text-primary mb-1">{{ $partner->acronym }}</span>
            @endif
            <p class="text-sm font-semibold text-on-surface leading-tight">{{ $partner->name_lo }}</p>
            @if($partner->country)
              <p class="text-xs text-outline mt-0.5">{{ $partner->country }}</p>
            @endif
            <a href="{{ route('admin.partners.show', $partner) }}"
               class="text-[11px] text-primary hover:underline mt-1 inline-block">ລາຍລະອຽດ →</a>
          </div>
        </div>
      </div>
      @endif

      {{-- Budget --}}
      @if($project->budget_usd)
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
        <h3 class="font-bold text-sm text-on-surface mb-3 flex items-center gap-2">
          <i class="fas fa-dollar-sign text-primary text-xs"></i> ງົບປະມານ
        </h3>
        <p class="text-2xl font-bold text-on-surface">${{ number_format($project->budget_usd, 0) }}</p>
        <p class="text-xs text-outline mt-0.5">USD</p>
      </div>
      @endif

      {{-- System info --}}
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
        <h3 class="font-bold text-sm text-on-surface mb-3 flex items-center gap-2">
          <i class="fas fa-info-circle text-primary text-xs"></i> ຂໍ້ມູນລະບົບ
        </h3>
        <dl class="space-y-2.5">
          <div>
            <dt class="text-[10px] text-outline uppercase tracking-wide">ສ້າງໂດຍ</dt>
            <dd class="text-xs text-on-surface-variant mt-0.5">{{ $project->author?->name ?? '—' }}</dd>
          </div>
          <div>
            <dt class="text-[10px] text-outline uppercase tracking-wide">ສ້າງວັນທີ</dt>
            <dd class="text-xs text-on-surface-variant mt-0.5">{{ $project->created_at->format('d/m/Y H:i') }}</dd>
          </div>
          <div>
            <dt class="text-[10px] text-outline uppercase tracking-wide">ອັບເດດລ່າສຸດ</dt>
            <dd class="text-xs text-on-surface-variant mt-0.5">{{ $project->updated_at->format('d/m/Y H:i') }}</dd>
          </div>
        </dl>
      </div>

      {{-- Back link --}}
      <a href="{{ route('admin.aid-projects.index') }}"
         class="flex items-center gap-2 text-sm text-on-surface-variant hover:text-primary transition-colors">
        <i class="fas fa-arrow-left text-xs"></i> ກັບໄປລາຍການ
      </a>
    </div>

  </div>

</div>
@endsection
