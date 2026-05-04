@extends('admin.layouts.app')

@section('page_title', 'ອາລ໌ບໍ້ຮູບ')

@section('content')

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
  <div>
    <h2 class="text-base font-bold text-on-surface">ຈັດການອາລ໌ບໍ້ຮູບ</h2>
    <p class="text-xs text-outline mt-0.5">{{ $albums->total() }} ອາລ໌ບໍ້ທັງໝົດ</p>
  </div>
  <a href="{{ route('admin.albums.create') }}"
     class="inline-flex items-center gap-2 text-sm font-semibold text-white primary-gradient px-4 py-2 rounded-lg hover:opacity-90 transition whitespace-nowrap">
    <i class="fas fa-plus text-xs"></i> ສ້າງອາລ໌ບໍ້
  </a>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.albums.index') }}" class="flex flex-wrap gap-2 mb-4">
  <input type="text" name="search" value="{{ request('search') }}" placeholder="ຄົ້ນຫາຊື່..."
         class="flex-1 min-w-[180px] rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
  <select name="visibility" class="rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
    <option value="">ທຸກ</option>
    <option value="public"  {{ request('visibility') === 'public'  ? 'selected' : '' }}>ສາທາລະນະ</option>
    <option value="private" {{ request('visibility') === 'private' ? 'selected' : '' }}>ສ່ວນຕົວ</option>
  </select>
  <button type="submit" class="px-4 py-2 text-sm font-semibold rounded-lg border border-surface-container-high hover:bg-surface-container transition-colors flex items-center gap-2">
    <i class="fas fa-search text-xs"></i> ຄົ້ນຫາ
  </button>
  @if(request()->hasAny(['search','visibility']))
    <a href="{{ route('admin.albums.index') }}"
       class="px-4 py-2 text-sm rounded-lg border border-surface-container-high hover:bg-surface-container transition-colors text-outline flex items-center gap-2">
      <i class="fas fa-times text-xs"></i> ລ້າງ
    </a>
  @endif
</form>

@if($albums->isEmpty())
  <div class="bg-surface-container-lowest rounded-xl p-12 text-center text-outline">
    <i class="fas fa-images text-4xl mb-3 opacity-30"></i>
    <p class="text-sm font-semibold">ຍັງບໍ່ມີອາລ໌ບໍ້ຮູບ</p>
    <a href="{{ route('admin.albums.create') }}" class="mt-3 inline-flex items-center gap-2 text-sm text-primary hover:underline">
      <i class="fas fa-plus text-xs"></i> ສ້າງອາລ໌ບໍ້ທຳອິດ
    </a>
  </div>
@else
  <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
    @foreach($albums as $album)
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] overflow-hidden group">
        {{-- Cover --}}
        <div class="relative aspect-[4/3] bg-surface-container overflow-hidden">
          @if($album->cover_image)
            <img src="{{ $album->cover_image }}" alt="{{ $album->title_lo }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
          @else
            <div class="w-full h-full flex items-center justify-center text-outline/30">
              <i class="fas fa-images text-3xl"></i>
            </div>
          @endif
          {{-- image count badge --}}
          <span class="absolute bottom-2 right-2 bg-black/60 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
            <i class="fas fa-image text-[8px] mr-0.5"></i>{{ $album->images_count }}
          </span>
          {{-- visibility badge --}}
          @if(!$album->is_public)
            <span class="absolute top-2 left-2 bg-slate-700/80 text-white text-[9px] px-1.5 py-0.5 rounded">
              <i class="fas fa-lock text-[8px]"></i>
            </span>
          @endif
        </div>

        {{-- Info --}}
        <div class="p-3">
          <p class="text-xs font-semibold text-on-surface line-clamp-2 leading-snug mb-2">{{ $album->title_lo }}</p>
          @if($album->title_en)
            <p class="text-[10px] text-outline line-clamp-1">{{ $album->title_en }}</p>
          @endif
          <div class="mt-2 flex items-center gap-2">
            <a href="{{ route('admin.albums.edit', $album) }}"
               class="flex-1 inline-flex items-center justify-center gap-1 text-xs font-semibold text-primary hover:bg-primary/5 py-1.5 rounded-md transition-colors">
              <i class="fas fa-edit text-[10px]"></i> ແກ້ໄຂ
            </a>
            <form action="{{ route('admin.albums.destroy', $album) }}" method="POST"
                  onsubmit="return confirm('ລຶບອາລ໌ບໍ້ \'{{ addslashes($album->title_lo) }}\' ແລະຮູບ {{ $album->images_count }} ໃບທັງໝົດ?')">
              @csrf @method('DELETE')
              <button type="submit" class="p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors">
                <i class="fas fa-trash text-xs"></i>
              </button>
            </form>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="mt-6">{{ $albums->links() }}</div>
@endif

@endsection
