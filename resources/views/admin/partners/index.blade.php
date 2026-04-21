@extends('admin.layouts.app')

@section('page_title', 'ອົງກອນຄູ່ຮ່ວມ')

@section('content')

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
  <div>
    <h2 class="text-base font-bold text-on-surface">ອົງກອນຄູ່ຮ່ວມງານ</h2>
    <p class="text-xs text-outline mt-0.5">{{ $partners->total() }} ລາຍການທັງໝົດ</p>
  </div>
  <a href="{{ route('admin.partners.create') }}"
     class="inline-flex items-center gap-2 text-sm font-semibold text-white primary-gradient px-4 py-2 rounded-lg hover:opacity-90 transition whitespace-nowrap">
    <i class="fas fa-plus text-xs"></i> ເພີ່ມອົງກອນ
  </a>
</div>

@if(session('success'))
  <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 mb-4 text-sm">
    <i class="fas fa-check-circle text-green-500"></i> {{ session('success') }}
  </div>
@endif
@if(session('error'))
  <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-4 text-sm">
    <i class="fas fa-exclamation-circle text-red-500"></i> {{ session('error') }}
  </div>
@endif

{{-- Type chips --}}
<div class="flex flex-wrap gap-2 mb-4">
  <a href="{{ route('admin.partners.index', request()->except('type')) }}"
     class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full border transition-colors
            {{ !request('type') ? 'primary-gradient text-white border-transparent' : 'border-surface-container-high hover:bg-surface-container-low' }}">
    ທັງໝົດ
  </a>
  @foreach($types as $key => $meta)
    <a href="{{ route('admin.partners.index', array_merge(request()->query(), ['type' => $key])) }}"
       class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full border transition-colors
              {{ request('type') === $key ? 'primary-gradient text-white border-transparent' : 'border-surface-container-high hover:bg-surface-container-low' }}">
      <i class="fas {{ $meta['icon'] }} text-[9px] {{ request('type') === $key ? 'text-white' : '' }}"></i>
      {{ $meta['lo'] }}
    </a>
  @endforeach
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.partners.index') }}" class="flex flex-wrap gap-2 mb-4">
  <input type="hidden" name="type" value="{{ request('type') }}">
  <input type="text" name="search" value="{{ request('search') }}" placeholder="ຄົ້ນຫາຊື່, ຕົວຫຍໍ້..."
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
    <a href="{{ route('admin.partners.index') }}"
       class="inline-flex items-center gap-2 px-4 py-2 text-sm rounded-lg border border-surface-container-high hover:bg-surface-container transition-colors text-outline">
      <i class="fas fa-times text-xs"></i> ລ້າງ
    </a>
  @endif
</form>

{{-- Grid / Table --}}
<div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr class="border-b border-surface-container-high bg-surface-container-low text-left">
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide w-12">ໂລໂກ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide">ຊື່ / ປະເທດ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide hidden md:table-cell text-center">ປະເພດ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide hidden lg:table-cell text-center w-16">MOU</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide hidden sm:table-cell text-center w-24">ສະຖານະ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide text-right">ຈັດການ</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-surface-container-high">
        @forelse($partners as $partner)
        @php
          $tm = $types[$partner->type]    ?? $types['other'];
          $st = $statuses[$partner->status] ?? $statuses['active'];
        @endphp
        <tr class="hover:bg-surface-container-low transition-colors">
          {{-- ໂລໂກ --}}
          <td class="px-4 py-3">
            @if($partner->logo_url)
              <img src="{{ $partner->logo_url }}" alt=""
                   class="w-10 h-10 object-contain rounded-lg border border-surface-container-high bg-white"
                   onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
              <div class="w-10 h-10 rounded-lg bg-surface-container items-center justify-center hidden">
                <i class="fas fa-building text-outline text-sm"></i>
              </div>
            @else
              <div class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center">
                <i class="fas fa-building text-outline text-sm"></i>
              </div>
            @endif
          </td>
          {{-- ຊື່ --}}
          <td class="px-4 py-3">
            <div class="flex items-center gap-2">
              @if($partner->acronym)
                <span class="inline-block text-[10px] font-bold px-1.5 py-0.5 rounded bg-primary/10 text-primary flex-shrink-0">
                  {{ $partner->acronym }}
                </span>
              @endif
              <p class="font-semibold text-on-surface leading-tight">{{ $partner->name_lo }}</p>
            </div>
            @if($partner->name_en)
              <p class="text-xs text-outline mt-0.5 ml-{{ $partner->acronym ? '0' : '0' }}">{{ $partner->name_en }}</p>
            @endif
            <div class="flex items-center gap-1 mt-1">
              <span class="text-[10px] font-bold text-on-surface-variant bg-surface-container px-1.5 py-0.5 rounded font-mono">{{ $partner->country_code }}</span>
              <span class="text-[10px] text-outline">{{ $partner->country_name_lo }}</span>
            </div>
          </td>
          {{-- ປະເພດ --}}
          <td class="px-4 py-3 hidden md:table-cell text-center">
            <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-semibold rounded-full {{ $tm['class'] }}">
              <i class="fas {{ $tm['icon'] }} text-[8px]"></i> {{ $tm['lo'] }}
            </span>
          </td>
          {{-- MOU count --}}
          <td class="px-4 py-3 hidden lg:table-cell text-center">
            <a href="{{ route('admin.mou.index', ['partner_id' => $partner->id]) }}"
               class="inline-flex items-center gap-1 text-xs {{ $partner->mou_agreements_count > 0 ? 'text-primary hover:underline font-semibold' : 'text-outline' }}">
              <i class="fas fa-file-signature text-[9px]"></i>
              {{ $partner->mou_agreements_count }}
            </a>
          </td>
          {{-- ສະຖານະ --}}
          <td class="px-4 py-3 hidden sm:table-cell text-center">
            <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-semibold rounded-full {{ $st['class'] }}">
              {{ $st['lo'] }}
            </span>
          </td>
          {{-- ຈັດການ --}}
          <td class="px-4 py-3 text-right">
            <div class="flex items-center justify-end gap-2">
              <a href="{{ route('admin.partners.show', $partner) }}"
                 class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:underline">
                <i class="fas fa-eye"></i><span class="hidden sm:inline">ລາຍລະອຽດ</span>
              </a>
              <a href="{{ route('admin.partners.edit', $partner) }}"
                 class="inline-flex items-center gap-1 text-xs font-semibold text-yellow-600 hover:underline">
                <i class="fas fa-edit"></i><span class="hidden sm:inline">ແກ້ໄຂ</span>
              </a>
              <form action="{{ route('admin.partners.destroy', $partner) }}" method="POST" class="inline-block"
                    onsubmit="return confirm('ລຶບ «{{ $partner->name_lo }}» ແທ້ບໍ?')">
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
            <i class="fas fa-handshake text-3xl mb-2 block opacity-30"></i>
            ຍັງບໍ່ມີອົງກອນຄູ່ຮ່ວມ
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($partners->hasPages())
    <div class="px-4 py-3 border-t border-surface-container-high">{{ $partners->links() }}</div>
  @endif
</div>

@endsection
