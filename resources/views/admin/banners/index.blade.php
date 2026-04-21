@extends('admin.layouts.app')

@section('page_title', 'Banners')

@section('content')

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
  <div>
    <h2 class="text-base font-bold text-on-surface">Banners</h2>
    <p class="text-xs text-outline mt-0.5">{{ $banners->total() }} Banner ທັງໝົດ</p>
  </div>
  <a href="{{ route('admin.banners.create') }}"
     class="inline-flex items-center gap-2 text-sm font-semibold text-white primary-gradient px-4 py-2 rounded-lg hover:opacity-90 transition whitespace-nowrap">
    <i class="fas fa-plus text-xs"></i> ເພີ່ມ Banner
  </a>
</div>

@if(session('success'))
  <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 mb-4 text-sm">
    <i class="fas fa-check-circle text-green-500"></i> {{ session('success') }}
  </div>
@endif

{{-- Position filter chips --}}
<div class="flex flex-wrap gap-2 mb-4">
  <a href="{{ route('admin.banners.index', request()->except('position')) }}"
     class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full border transition-colors
            {{ !request('position') ? 'primary-gradient text-white border-transparent' : 'border-surface-container-high hover:bg-surface-container-low' }}">
    ທຸກ Position
  </a>
  @foreach($positions as $key => $meta)
    <a href="{{ route('admin.banners.index', array_merge(request()->query(), ['position' => $key])) }}"
       class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full border transition-colors
              {{ request('position') === $key ? 'primary-gradient text-white border-transparent' : 'border-surface-container-high hover:bg-surface-container-low' }}">
      <i class="fas {{ $meta['icon'] }} text-[9px]"></i> {{ $meta['lo'] }}
    </a>
  @endforeach
</div>

{{-- Active filter --}}
<form method="GET" action="{{ route('admin.banners.index') }}" class="flex flex-wrap gap-2 mb-4">
  <input type="hidden" name="position" value="{{ request('position') }}">
  <select name="active" class="rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
    <option value="">ທຸກສະຖານະ</option>
    <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>ໃຊ້ງານ</option>
    <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>ປິດໃຊ້</option>
  </select>
  <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg border border-surface-container-high hover:bg-surface-container transition-colors">
    <i class="fas fa-filter text-xs"></i> ກອງ
  </button>
  @if(request()->hasAny(['position', 'active']))
    <a href="{{ route('admin.banners.index') }}"
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
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide w-8 text-center">#</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide">Banner</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide hidden md:table-cell">Position</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide hidden lg:table-cell">Style</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide text-center w-24">ສະຖານະ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide text-right">ຈັດການ</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-surface-container-high">
        @forelse($banners as $banner)
        @php
          $st = $styles[$banner->style] ?? $styles['banner-blue'];
          $po = $positions[$banner->position] ?? $positions['sidebar'];
        @endphp
        <tr class="hover:bg-surface-container-low transition-colors">
          {{-- Sort --}}
          <td class="px-4 py-3 text-center">
            <span class="text-xs font-bold text-outline">{{ $banner->sort_order }}</span>
          </td>
          {{-- Banner info --}}
          <td class="px-4 py-3">
            <div class="flex items-center gap-3">
              @if($banner->image_url)
                <img src="{{ $banner->image_url }}" alt="{{ $banner->title_lo }}"
                     class="w-14 h-9 object-cover rounded-lg border border-surface-container-high shrink-0 bg-surface-container-low">
              @else
                <div class="w-14 h-9 rounded-lg border border-surface-container-high bg-gradient-to-br {{ $st['preview'] }} shrink-0 flex items-center justify-center">
                  <i class="fas fa-ad text-white text-xs opacity-70"></i>
                </div>
              @endif
              <div class="min-w-0">
                <p class="font-semibold text-on-surface text-sm leading-tight truncate max-w-[180px]">{{ $banner->title_lo }}</p>
                @if($banner->title_en)
                  <p class="text-xs text-outline mt-0.5 truncate max-w-[180px]">{{ $banner->title_en }}</p>
                @endif
                @if($banner->btn_url)
                  <p class="text-[10px] text-primary mt-0.5 truncate max-w-[180px]">
                    <i class="fas fa-link text-[8px]"></i> {{ $banner->btn_url }}
                  </p>
                @endif
              </div>
            </div>
          </td>
          {{-- Position --}}
          <td class="px-4 py-3 hidden md:table-cell">
            <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-semibold rounded-full bg-surface-container text-on-surface-variant">
              <i class="fas {{ $po['icon'] }} text-[8px]"></i> {{ $po['lo'] }}
            </span>
          </td>
          {{-- Style --}}
          <td class="px-4 py-3 hidden lg:table-cell">
            <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-semibold rounded-full {{ $st['bg'] }} {{ $st['text'] }}">
              {{ $st['lo'] }}
            </span>
          </td>
          {{-- Status --}}
          <td class="px-4 py-3 text-center">
            @if($banner->is_active)
              <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-semibold rounded-full bg-green-100 text-green-700">
                <i class="fas fa-circle text-[6px]"></i> ໃຊ້ງານ
              </span>
            @else
              <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-semibold rounded-full bg-gray-100 text-gray-500">
                <i class="fas fa-circle text-[6px]"></i> ປິດ
              </span>
            @endif
          </td>
          {{-- Actions --}}
          <td class="px-4 py-3 text-right">
            <div class="flex items-center justify-end gap-2">
              <a href="{{ route('admin.banners.show', $banner) }}"
                 class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:underline">
                <i class="fas fa-eye"></i><span class="hidden sm:inline">ລາຍລະອຽດ</span>
              </a>
              <a href="{{ route('admin.banners.edit', $banner) }}"
                 class="inline-flex items-center gap-1 text-xs font-semibold text-yellow-600 hover:underline">
                <i class="fas fa-edit"></i><span class="hidden sm:inline">ແກ້ໄຂ</span>
              </a>
              <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" class="inline-block"
                    onsubmit="return confirm('ລຶບ Banner «{{ $banner->title_lo }}» ແທ້ບໍ?')">
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
            <i class="fas fa-ad text-3xl mb-2 block opacity-30"></i>
            ຍັງບໍ່ມີ Banner
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($banners->hasPages())
    <div class="px-4 py-3 border-t border-surface-container-high">{{ $banners->links() }}</div>
  @endif
</div>

@endsection
