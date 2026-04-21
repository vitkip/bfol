@extends('admin.layouts.app')

@section('page_title', 'ໂຄງການຊ່ວຍເຫຼືອ')

@section('content')

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
  <div>
    <h2 class="text-base font-bold text-on-surface">ໂຄງການຊ່ວຍເຫຼືອ</h2>
    <p class="text-xs text-outline mt-0.5">{{ $projects->total() }} ໂຄງການທັງໝົດ</p>
  </div>
  <a href="{{ route('admin.aid-projects.create') }}"
     class="inline-flex items-center gap-2 text-sm font-semibold text-white primary-gradient px-4 py-2 rounded-lg hover:opacity-90 transition whitespace-nowrap">
    <i class="fas fa-plus text-xs"></i> ເພີ່ມໂຄງການ
  </a>
</div>

@if(session('success'))
  <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 mb-4 text-sm">
    <i class="fas fa-check-circle text-green-500"></i> {{ session('success') }}
  </div>
@endif

{{-- Type chips --}}
<div class="flex flex-wrap gap-2 mb-4">
  <a href="{{ route('admin.aid-projects.index', request()->except('type')) }}"
     class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full border transition-colors
            {{ !request('type') ? 'primary-gradient text-white border-transparent' : 'border-surface-container-high hover:bg-surface-container-low' }}">
    ທັງໝົດ
  </a>
  @foreach($types as $key => $meta)
    <a href="{{ route('admin.aid-projects.index', array_merge(request()->query(), ['type' => $key])) }}"
       class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full border transition-colors
              {{ request('type') === $key ? 'primary-gradient text-white border-transparent' : 'border-surface-container-high hover:bg-surface-container-low' }}">
      <i class="fas {{ $meta['icon'] }} text-[9px] {{ request('type') === $key ? 'text-white' : '' }}"></i>
      {{ $meta['lo'] }}
    </a>
  @endforeach
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.aid-projects.index') }}" class="flex flex-wrap gap-2 mb-4">
  <input type="hidden" name="type" value="{{ request('type') }}">
  <input type="text" name="search" value="{{ request('search') }}" placeholder="ຄົ້ນຫາຊື່, ປະເທດ..."
         class="flex-1 min-w-[160px] rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
  <select name="status" class="rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
    <option value="">ທຸກສະຖານະ</option>
    @foreach($statuses as $key => $meta)
      <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $meta['lo'] }}</option>
    @endforeach
  </select>
  <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg border border-surface-container-high hover:bg-surface-container transition-colors">
    <i class="fas fa-search text-xs"></i> ຄົ້ນຫາ
  </button>
  @if(request()->hasAny(['search','type','status']))
    <a href="{{ route('admin.aid-projects.index') }}"
       class="inline-flex items-center gap-2 px-4 py-2 text-sm rounded-lg border border-surface-container-high hover:bg-surface-container transition-colors text-outline">
      <i class="fas fa-times text-xs"></i> ລ້າງ
    </a>
  @endif
</form>

{{-- Table --}}
<div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr class="border-b border-surface-container-high bg-surface-container-low text-left">
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide">ໂຄງການ / ປະເທດ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide hidden md:table-cell text-center">ປະເພດ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide hidden lg:table-cell text-right">ງົບປະມານ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide hidden lg:table-cell">ໄລຍະ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide text-center w-28">ສະຖານະ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide text-right">ຈັດການ</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-surface-container-high">
        @forelse($projects as $proj)
        @php
          $tm = $types[$proj->type]      ?? $types['other'];
          $st = $statuses[$proj->status] ?? $statuses['planning'];
        @endphp
        <tr class="hover:bg-surface-container-low transition-colors">
          {{-- ຊື່ --}}
          <td class="px-4 py-3">
            <p class="font-semibold text-on-surface leading-tight">{{ $proj->title_lo }}</p>
            @if($proj->title_en)
              <p class="text-xs text-outline mt-0.5">{{ $proj->title_en }}</p>
            @endif
            <div class="flex items-center gap-1.5 mt-1">
              <i class="fas fa-map-marker-alt text-[9px] text-outline"></i>
              <span class="text-[11px] text-outline">{{ $proj->country }}</span>
              @if($proj->partnerOrganization)
                <span class="text-outline">·</span>
                <span class="text-[11px] text-outline">{{ $proj->partnerOrganization->acronym ?? $proj->partnerOrganization->name_lo }}</span>
              @endif
            </div>
          </td>
          {{-- ປະເພດ --}}
          <td class="px-4 py-3 hidden md:table-cell text-center">
            <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-semibold rounded-full {{ $tm['class'] }}">
              <i class="fas {{ $tm['icon'] }} text-[8px]"></i> {{ $tm['lo'] }}
            </span>
          </td>
          {{-- ງົບ --}}
          <td class="px-4 py-3 hidden lg:table-cell text-right text-xs text-on-surface-variant">
            @if($proj->budget_usd)
              <span class="font-semibold">${{ number_format($proj->budget_usd, 0) }}</span>
            @else
              <span class="text-outline">—</span>
            @endif
          </td>
          {{-- ໄລຍະ --}}
          <td class="px-4 py-3 hidden lg:table-cell text-xs text-on-surface-variant">
            @if($proj->start_date || $proj->end_date)
              <div class="space-y-0.5">
                @if($proj->start_date)
                  <div>{{ $proj->start_date->format('d/m/Y') }}</div>
                @endif
                @if($proj->end_date)
                  <div class="text-outline">→ {{ $proj->end_date->format('d/m/Y') }}</div>
                @endif
              </div>
            @else
              <span class="text-outline">—</span>
            @endif
          </td>
          {{-- ສະຖານະ --}}
          <td class="px-4 py-3 text-center">
            <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-semibold rounded-full {{ $st['class'] }}">
              <i class="fas {{ $st['icon'] }} text-[8px]"></i> {{ $st['lo'] }}
            </span>
          </td>
          {{-- ຈັດການ --}}
          <td class="px-4 py-3 text-right">
            <div class="flex items-center justify-end gap-2">
              <a href="{{ route('admin.aid-projects.show', $proj) }}"
                 class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:underline">
                <i class="fas fa-eye"></i><span class="hidden sm:inline">ລາຍລະອຽດ</span>
              </a>
              <a href="{{ route('admin.aid-projects.edit', $proj) }}"
                 class="inline-flex items-center gap-1 text-xs font-semibold text-yellow-600 hover:underline">
                <i class="fas fa-edit"></i><span class="hidden sm:inline">ແກ້ໄຂ</span>
              </a>
              <form action="{{ route('admin.aid-projects.destroy', $proj) }}" method="POST" class="inline-block"
                    onsubmit="return confirm('ລຶບໂຄງການ «{{ $proj->title_lo }}» ແທ້ບໍ?')">
                @csrf @method('DELETE')
                <button class="inline-flex items-center gap-1 text-xs font-semibold text-red-600 hover:underline">
                  <i class="fas fa-trash"></i><span class="hidden sm:inline">ລຶບ</span>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" class="text-center text-outline py-12">
            <i class="fas fa-hands-helping text-3xl mb-2 block opacity-30"></i>
            ຍັງບໍ່ມີໂຄງການ
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($projects->hasPages())
    <div class="px-4 py-3 border-t border-surface-container-high">{{ $projects->links() }}</div>
  @endif
</div>

@endsection
