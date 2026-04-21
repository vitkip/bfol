@extends('admin.layouts.app')

@section('page_title', 'ໂຄງການແລກປ່ຽນພິກຂຸ')

@section('content')

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
  <div>
    <h2 class="text-base font-bold text-on-surface">ໂຄງການແລກປ່ຽນພິກຂຸ</h2>
    <p class="text-xs text-outline mt-0.5">{{ $programs->total() }} ໂຄງການທັງໝົດ</p>
  </div>
  <a href="{{ route('admin.monk-programs.create') }}"
     class="inline-flex items-center gap-2 text-sm font-semibold text-white primary-gradient px-4 py-2 rounded-lg hover:opacity-90 transition whitespace-nowrap">
    <i class="fas fa-plus text-xs"></i> ເພີ່ມໂຄງການ
  </a>
</div>

@if(session('success'))
  <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 mb-4 text-sm">
    <i class="fas fa-check-circle text-green-500"></i> {{ session('success') }}
  </div>
@endif

{{-- Status chips --}}
<div class="flex flex-wrap gap-2 mb-4">
  <a href="{{ route('admin.monk-programs.index', request()->except('status')) }}"
     class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full border transition-colors
            {{ !request('status') ? 'primary-gradient text-white border-transparent' : 'border-surface-container-high hover:bg-surface-container-low' }}">
    ທັງໝົດ
  </a>
  @foreach($statuses as $key => $meta)
    <a href="{{ route('admin.monk-programs.index', array_merge(request()->query(), ['status' => $key])) }}"
       class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full border transition-colors
              {{ request('status') === $key ? 'primary-gradient text-white border-transparent' : 'border-surface-container-high hover:bg-surface-container-low' }}">
      <i class="fas {{ $meta['icon'] }} text-[8px] {{ request('status') === $key ? 'text-white' : '' }}"></i>
      {{ $meta['lo'] }}
    </a>
  @endforeach
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.monk-programs.index') }}" class="flex flex-wrap gap-2 mb-4">
  <input type="hidden" name="status" value="{{ request('status') }}">
  <input type="text" name="search" value="{{ request('search') }}" placeholder="ຄົ້ນຫາຊື່, ປະເທດ..."
         class="flex-1 min-w-[160px] rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
  <select name="year" class="rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
    <option value="">ທຸກປີ</option>
    @foreach($years as $y)
      <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
    @endforeach
  </select>
  <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg border border-surface-container-high hover:bg-surface-container transition-colors">
    <i class="fas fa-search text-xs"></i> ຄົ້ນຫາ
  </button>
  @if(request()->hasAny(['search','status','year']))
    <a href="{{ route('admin.monk-programs.index') }}"
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
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide hidden md:table-cell text-center w-16">ປີ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide hidden lg:table-cell">ໄລຍະສະໝັກ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide hidden md:table-cell text-center w-24">ໂຄຕ້າ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide text-center w-28">ສະຖານະ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide text-right">ຈັດການ</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-surface-container-high">
        @forelse($programs as $prog)
        @php $st = $statuses[$prog->status] ?? $statuses['draft']; @endphp
        <tr class="hover:bg-surface-container-low transition-colors">
          {{-- ຊື່ --}}
          <td class="px-4 py-3">
            <div class="flex items-center gap-2">
              @if($prog->is_featured)
                <i class="fas fa-star text-amber-400 text-[10px]" title="ໂດດເດັ່ນ"></i>
              @endif
              <p class="font-semibold text-on-surface leading-tight">{{ $prog->title_lo }}</p>
            </div>
            @if($prog->title_en)
              <p class="text-xs text-outline mt-0.5">{{ $prog->title_en }}</p>
            @endif
            <div class="flex items-center gap-1.5 mt-1">
              <i class="fas fa-map-marker-alt text-[9px] text-outline"></i>
              <span class="text-[11px] text-outline">{{ $prog->destination_country }}</span>
              @if($prog->partnerOrganization)
                <span class="text-outline">·</span>
                <span class="text-[11px] text-outline">{{ $prog->partnerOrganization->acronym ?? $prog->partnerOrganization->name_lo }}</span>
              @endif
            </div>
          </td>
          {{-- ປີ --}}
          <td class="px-4 py-3 hidden md:table-cell text-center font-bold text-on-surface-variant">
            {{ $prog->year }}
          </td>
          {{-- ໄລຍະສະໝັກ --}}
          <td class="px-4 py-3 hidden lg:table-cell text-xs text-on-surface-variant">
            @if($prog->application_open || $prog->application_deadline)
              <div class="space-y-0.5">
                @if($prog->application_open)
                  <div class="flex items-center gap-1.5">
                    <i class="fas fa-play text-[8px] text-green-500"></i>
                    {{ $prog->application_open->format('d/m/Y') }}
                  </div>
                @endif
                @if($prog->application_deadline)
                  <div class="flex items-center gap-1.5">
                    <i class="fas fa-stop text-[8px] text-red-400"></i>
                    {{ $prog->application_deadline->format('d/m/Y') }}
                  </div>
                @endif
              </div>
            @else
              <span class="text-outline">—</span>
            @endif
          </td>
          {{-- ໂຄຕ້າ --}}
          <td class="px-4 py-3 hidden md:table-cell text-center">
            @if($prog->monks_quota)
              <p class="text-sm font-bold text-on-surface">{{ $prog->monks_selected }}/{{ $prog->monks_quota }}</p>
              <div class="w-16 mx-auto h-1 rounded-full bg-surface-container-high mt-1">
                <div class="h-1 rounded-full bg-primary"
                     style="width:{{ $prog->monks_quota > 0 ? min(100, round($prog->monks_selected/$prog->monks_quota*100)) : 0 }}%"></div>
              </div>
            @else
              <span class="text-xs text-outline">—</span>
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
              <a href="{{ route('admin.monk-programs.show', $prog) }}"
                 class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:underline">
                <i class="fas fa-eye"></i><span class="hidden sm:inline">ລາຍລະອຽດ</span>
              </a>
              <a href="{{ route('admin.monk-programs.edit', $prog) }}"
                 class="inline-flex items-center gap-1 text-xs font-semibold text-yellow-600 hover:underline">
                <i class="fas fa-edit"></i><span class="hidden sm:inline">ແກ້ໄຂ</span>
              </a>
              <form action="{{ route('admin.monk-programs.destroy', $prog) }}" method="POST" class="inline-block"
                    onsubmit="return confirm('ລຶບໂຄງການ «{{ $prog->title_lo }}» ແທ້ບໍ?')">
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
            <i class="fas fa-dharmachakra text-3xl mb-2 block opacity-30"></i>
            ຍັງບໍ່ມີໂຄງການ
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($programs->hasPages())
    <div class="px-4 py-3 border-t border-surface-container-high">{{ $programs->links() }}</div>
  @endif
</div>

@endsection
