@extends('admin.layouts.app')

@section('page_title', 'ສັນຍາ MOU')

@section('content')

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
  <div>
    <h2 class="text-base font-bold text-on-surface">ສັນຍາ MOU</h2>
    <p class="text-xs text-outline mt-0.5">{{ $mous->total() }} ລາຍການທັງໝົດ</p>
  </div>
  <a href="{{ route('admin.mou.create') }}"
     class="inline-flex items-center gap-2 text-sm font-semibold text-white primary-gradient px-4 py-2 rounded-lg hover:opacity-90 transition whitespace-nowrap">
    <i class="fas fa-plus text-xs"></i> ເພີ່ມ MOU
  </a>
</div>

@if(session('success'))
  <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 mb-4 text-sm">
    <i class="fas fa-check-circle text-green-500"></i> {{ session('success') }}
  </div>
@endif

{{-- Status chips --}}
<div class="flex flex-wrap gap-2 mb-4">
  <a href="{{ route('admin.mou.index', request()->except('status')) }}"
     class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full border transition-colors
            {{ !request('status') ? 'primary-gradient text-white border-transparent' : 'border-surface-container-high hover:bg-surface-container-low' }}">
    ທັງໝົດ <span class="opacity-75">({{ $mous->total() }})</span>
  </a>
  @foreach($statuses as $key => $meta)
    <a href="{{ route('admin.mou.index', array_merge(request()->query(), ['status' => $key])) }}"
       class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full border transition-colors
              {{ request('status') === $key ? 'primary-gradient text-white border-transparent' : 'border-surface-container-high hover:bg-surface-container-low' }}">
      {{ $meta['lo'] }}
    </a>
  @endforeach
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.mou.index') }}" class="flex flex-wrap gap-2 mb-4">
  <input type="hidden" name="status" value="{{ request('status') }}">
  <input type="text" name="search" value="{{ request('search') }}" placeholder="ຄົ້ນຫາຊື່ MOU..."
         class="flex-1 min-w-[160px] rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
  <select name="partner_id" class="rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
    <option value="">ທຸກອົງກອນ</option>
    @foreach($partners as $partner)
      <option value="{{ $partner->id }}" {{ request('partner_id') == $partner->id ? 'selected' : '' }}>
        {{ $partner->acronym ? "[{$partner->acronym}] " : '' }}{{ $partner->name_lo }}
      </option>
    @endforeach
  </select>
  <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg border border-surface-container-high hover:bg-surface-container transition-colors">
    <i class="fas fa-search text-xs"></i> ຄົ້ນຫາ
  </button>
  @if(request()->hasAny(['search','partner_id','status']))
    <a href="{{ route('admin.mou.index') }}"
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
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide">ຊື່ MOU / ອົງກອນ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide hidden md:table-cell">ວັນທີລົງນາມ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide hidden lg:table-cell">ໝົດອາຍຸ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide text-center w-28">ສະຖານະ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide text-center w-12">ເອກະສານ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide text-right">ຈັດການ</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-surface-container-high">
        @forelse($mous as $mou)
        @php
          $st = $statuses[$mou->status] ?? $statuses['active'];
          $isExpiringSoon = $mou->expiry_date && $mou->expiry_date->gt(now()) && $mou->expiry_date->lt(now()->addDays(30));
        @endphp
        <tr class="hover:bg-surface-container-low transition-colors">
          {{-- ຊື່ --}}
          <td class="px-4 py-3">
            <p class="font-semibold text-on-surface leading-tight">{{ $mou->title_lo }}</p>
            @if($mou->title_en)
              <p class="text-xs text-outline mt-0.5">{{ $mou->title_en }}</p>
            @endif
            @if($mou->partnerOrganization)
              <div class="flex items-center gap-1 mt-1">
                <i class="fas fa-building text-[9px] text-outline"></i>
                <span class="text-[11px] text-outline">
                  {{ $mou->partnerOrganization->acronym ? "[{$mou->partnerOrganization->acronym}] " : '' }}{{ $mou->partnerOrganization->name_lo }}
                </span>
              </div>
            @endif
          </td>
          {{-- ວັນລົງນາມ --}}
          <td class="px-4 py-3 hidden md:table-cell text-sm text-on-surface-variant">
            {{ $mou->signed_date->format('d/m/Y') }}
          </td>
          {{-- ໝົດອາຍຸ --}}
          <td class="px-4 py-3 hidden lg:table-cell">
            @if($mou->expiry_date)
              <span class="{{ $isExpiringSoon ? 'text-amber-600 font-semibold' : 'text-on-surface-variant' }} text-sm">
                {{ $mou->expiry_date->format('d/m/Y') }}
              </span>
              @if($isExpiringSoon)
                <p class="text-[10px] text-amber-600">ໃກ້ໝົດອາຍຸ!</p>
              @endif
            @else
              <span class="text-outline text-xs">—</span>
            @endif
          </td>
          {{-- ສະຖານະ --}}
          <td class="px-4 py-3 text-center">
            <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[11px] font-semibold rounded-full {{ $st['class'] }}">
              <i class="fas {{ $st['icon'] }} text-[8px]"></i> {{ $st['lo'] }}
            </span>
          </td>
          {{-- ເອກະສານ --}}
          <td class="px-4 py-3 text-center">
            @if($mou->document_url)
              <a href="{{ $mou->document_url }}" target="_blank"
                 class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-red-50 text-red-500 hover:bg-red-100 transition-colors">
                <i class="fas fa-file-pdf text-xs"></i>
              </a>
            @else
              <span class="text-outline text-xs">—</span>
            @endif
          </td>
          {{-- ຈັດການ --}}
          <td class="px-4 py-3 text-right">
            <div class="flex items-center justify-end gap-2">
              <a href="{{ route('admin.mou.show', $mou) }}"
                 class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:underline">
                <i class="fas fa-eye"></i><span class="hidden sm:inline">ລາຍລະອຽດ</span>
              </a>
              <a href="{{ route('admin.mou.edit', $mou) }}"
                 class="inline-flex items-center gap-1 text-xs font-semibold text-yellow-600 hover:underline">
                <i class="fas fa-edit"></i><span class="hidden sm:inline">ແກ້ໄຂ</span>
              </a>
              <form action="{{ route('admin.mou.destroy', $mou) }}" method="POST" class="inline-block"
                    onsubmit="return confirm('ລຶບ MOU «{{ $mou->title_lo }}» ແທ້ບໍ?\n\nການດຳເນີນການນີ້ບໍ່ສາມາດກູ້ຄືນໄດ້!')">
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
            <i class="fas fa-file-signature text-3xl mb-2 block opacity-30"></i>
            ຍັງບໍ່ມີ MOU
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($mous->hasPages())
    <div class="px-4 py-3 border-t border-surface-container-high">{{ $mous->links() }}</div>
  @endif
</div>

@endsection
