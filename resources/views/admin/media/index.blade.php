@extends('admin.layouts.app')

@section('page_title', 'ສື່ທຳ')

@section('content')

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
  <div>
    <h2 class="text-base font-bold text-on-surface">ຈັດການສື່ທຳ</h2>
    <p class="text-xs text-outline mt-0.5">{{ $items->total() }} ລາຍການທັງໝົດ</p>
  </div>
  <a href="{{ route('admin.media.create') }}"
     class="inline-flex items-center gap-2 text-sm font-semibold text-white primary-gradient px-4 py-2 rounded-lg hover:opacity-90 transition whitespace-nowrap">
    <i class="fas fa-plus text-xs"></i> ເພີ່ມສື່ທຳ
  </a>
</div>

{{-- Type summary tabs --}}
@php
  $typeMap = [
    'image'    => ['label' => 'ຮູບພາບ',  'icon' => 'fa-image',        'color' => 'blue'],
    'video'    => ['label' => 'ວິດີໂອ',  'icon' => 'fa-video',        'color' => 'purple'],
    'audio'    => ['label' => 'ສຽງ',      'icon' => 'fa-music',        'color' => 'green'],
    'document' => ['label' => 'ເອກະສານ', 'icon' => 'fa-file-alt',     'color' => 'orange'],
  ];
  $colorMap = [
    'blue'   => 'bg-blue-50 text-blue-700 border-blue-200',
    'purple' => 'bg-purple-50 text-purple-700 border-purple-200',
    'green'  => 'bg-green-50 text-green-700 border-green-200',
    'orange' => 'bg-orange-50 text-orange-700 border-orange-200',
  ];
@endphp
<div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-5">
  @foreach($typeMap as $key => $t)
  <a href="{{ route('admin.media.index', ['type' => $key]) }}"
     class="flex items-center gap-3 p-3 rounded-xl border {{ request('type') === $key ? $colorMap[$t['color']] : 'bg-surface-container-lowest border-surface-container-high hover:bg-surface-container-low' }} transition-colors">
    <i class="fas {{ $t['icon'] }} text-sm w-4 text-center"></i>
    <div>
      <p class="text-xs font-semibold">{{ $t['label'] }}</p>
      <p class="text-lg font-bold leading-tight">{{ $typeCounts[$key] ?? 0 }}</p>
    </div>
  </a>
  @endforeach
</div>

{{-- Filter --}}
<form method="GET" action="{{ route('admin.media.index') }}" class="flex flex-col sm:flex-row gap-2 mb-4">
  <input type="text" name="search" value="{{ request('search') }}"
         placeholder="ຄົ້ນຫາຊື່..."
         class="flex-1 rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
  <select name="type" class="rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
    <option value="">ທຸກປະເພດ</option>
    @foreach($typeMap as $key => $t)
      <option value="{{ $key }}" {{ request('type') === $key ? 'selected' : '' }}>{{ $t['label'] }}</option>
    @endforeach
  </select>
  <select name="platform" class="rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
    <option value="">ທຸກ Platform</option>
    <option value="local"      {{ request('platform') === 'local'      ? 'selected' : '' }}>Local</option>
    <option value="youtube"    {{ request('platform') === 'youtube'    ? 'selected' : '' }}>YouTube</option>
    <option value="facebook"   {{ request('platform') === 'facebook'   ? 'selected' : '' }}>Facebook</option>
    <option value="soundcloud" {{ request('platform') === 'soundcloud' ? 'selected' : '' }}>SoundCloud</option>
    <option value="other"      {{ request('platform') === 'other'      ? 'selected' : '' }}>Other</option>
  </select>
  <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg border border-surface-container-high hover:bg-surface-container transition-colors">
    <i class="fas fa-search text-xs"></i> ຄົ້ນຫາ
  </button>
  @if(request()->hasAny(['search','type','platform']))
    <a href="{{ route('admin.media.index') }}"
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
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide w-16">Preview</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide">ຊື່ / ລາຍລະອຽດ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide w-24 text-center hidden sm:table-cell">ປະເພດ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide hidden lg:table-cell">Platform</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide hidden md:table-cell">ໝວດໝູ່</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide text-center w-24">ສະຖານະ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide text-right">ຈັດການ</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-surface-container-high">
        @forelse($items as $item)
        @php
          $thumb = $item->thumbnail_url
              ? (Str::startsWith($item->thumbnail_url,'http') ? $item->thumbnail_url : asset($item->thumbnail_url))
              : null;
          $ti = $typeMap[$item->type] ?? ['label'=>$item->type,'icon'=>'fa-file','color'=>'blue'];
        @endphp
        <tr class="hover:bg-surface-container-low transition-colors">
          {{-- Thumbnail --}}
          <td class="px-4 py-3">
            <div class="w-14 h-10 rounded-lg overflow-hidden bg-surface-container-high flex items-center justify-center flex-shrink-0 border border-surface-container-high">
              @if($thumb)
                <img src="{{ $thumb }}" class="w-full h-full object-cover" alt=""
                     onerror="this.parentElement.innerHTML='<i class=\'fas {{ $ti['icon'] }} text-outline text-sm\'></i>'">
              @elseif($item->type === 'image' && $item->file_url)
                <img src="{{ Str::startsWith($item->file_url,'http') ? $item->file_url : asset($item->file_url) }}"
                     class="w-full h-full object-cover" alt=""
                     onerror="this.parentElement.innerHTML='<i class=\'fas fa-image text-outline text-sm\'></i>'">
              @else
                <i class="fas {{ $ti['icon'] }} text-outline text-sm"></i>
              @endif
            </div>
          </td>
          {{-- Title --}}
          <td class="px-4 py-3">
            <p class="font-semibold text-on-surface leading-tight truncate max-w-xs">{{ $item->title_lo }}</p>
            @if($item->description_lo)
              <p class="text-xs text-outline mt-0.5 truncate max-w-xs">{{ Str::limit($item->description_lo, 60) }}</p>
            @endif
            @if($item->is_featured)
              <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-yellow-600 mt-0.5">
                <i class="fas fa-star text-[8px]"></i> ແນະນຳ
              </span>
            @endif
          </td>
          {{-- Type --}}
          <td class="px-4 py-3 text-center hidden sm:table-cell">
            @php
              $typeBadge = ['image'=>'bg-blue-100 text-blue-700','video'=>'bg-purple-100 text-purple-700','audio'=>'bg-green-100 text-green-700','document'=>'bg-orange-100 text-orange-700'];
            @endphp
            <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[11px] font-semibold rounded-full {{ $typeBadge[$item->type] ?? 'bg-gray-100 text-gray-600' }}">
              <i class="fas {{ $ti['icon'] }} text-[9px]"></i> {{ $ti['label'] }}
            </span>
          </td>
          {{-- Platform --}}
          <td class="px-4 py-3 hidden lg:table-cell">
            @php
              $platIcon = ['local'=>'fa-server','youtube'=>'fa-youtube','facebook'=>'fa-facebook','soundcloud'=>'fa-soundcloud','other'=>'fa-link'];
              $platColor= ['local'=>'text-gray-500','youtube'=>'text-red-500','facebook'=>'text-blue-600','soundcloud'=>'text-orange-500','other'=>'text-outline'];
            @endphp
            <span class="inline-flex items-center gap-1 text-xs {{ $platColor[$item->platform] ?? 'text-outline' }}">
              <i class="fab {{ $platIcon[$item->platform] ?? 'fa-link' }} text-[11px]"></i>
              {{ ucfirst($item->platform) }}
            </span>
          </td>
          {{-- Category --}}
          <td class="px-4 py-3 hidden md:table-cell text-xs text-outline">
            {{ $item->category?->name_lo ?? '—' }}
          </td>
          {{-- Status --}}
          <td class="px-4 py-3 text-center">
            @if($item->published_at && $item->published_at <= now())
              <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[11px] font-semibold rounded-full bg-green-100 text-green-700">
                <i class="fas fa-circle text-[5px]"></i> ເຜີຍ
              </span>
            @elseif($item->published_at)
              <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[11px] font-semibold rounded-full bg-blue-100 text-blue-600">
                <i class="fas fa-clock text-[9px]"></i> ກຳນົດ
              </span>
            @else
              <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[11px] font-semibold rounded-full bg-gray-100 text-gray-500">
                <i class="fas fa-circle text-[5px]"></i> ຮ່າງ
              </span>
            @endif
          </td>
          {{-- Actions --}}
          <td class="px-4 py-3 text-right">
            <div class="flex items-center justify-end gap-2">
              <a href="{{ route('admin.media.show', $item) }}"
                 class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:underline">
                <i class="fas fa-eye"></i><span class="hidden sm:inline">ລາຍລະອຽດ</span>
              </a>
              <a href="{{ route('admin.media.edit', $item) }}"
                 class="inline-flex items-center gap-1 text-xs font-semibold text-yellow-600 hover:underline">
                <i class="fas fa-edit"></i><span class="hidden sm:inline">ແກ້ໄຂ</span>
              </a>
              <form action="{{ route('admin.media.destroy', $item) }}" method="POST" class="inline-block"
                    onsubmit="return confirm('ທ່ານຕ້ອງການລຶບ «{{ $item->title_lo }}» ແທ້ບໍ?')">
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
          <td colspan="7" class="text-center text-outline py-12">
            <i class="fas fa-photo-video text-3xl mb-2 block opacity-30"></i>
            ຍັງບໍ່ມີສື່ທຳ
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($items->hasPages())
    <div class="px-4 py-3 border-t border-surface-container-high">{{ $items->links() }}</div>
  @endif
</div>

@endsection
