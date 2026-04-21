@extends('admin.layouts.app')

@section('page_title', 'ເອກະສານ')

@section('content')

@php
  $typeIcon  = ['PDF'=>['fa-file-pdf','text-red-500','bg-red-50'],'Word'=>['fa-file-word','text-blue-600','bg-blue-50'],'Excel'=>['fa-file-excel','text-green-600','bg-green-50'],'PPT'=>['fa-file-powerpoint','text-orange-500','bg-orange-50'],'Text'=>['fa-file-alt','text-gray-500','bg-gray-50'],'ZIP'=>['fa-file-archive','text-yellow-600','bg-yellow-50'],'RAR'=>['fa-file-archive','text-yellow-700','bg-yellow-50']];
  function docIcon($t, $map) { return $map[$t] ?? ['fa-file','text-outline','bg-surface-container']; }
@endphp

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
  <div>
    <h2 class="text-base font-bold text-on-surface">ຈັດການເອກະສານ</h2>
    <p class="text-xs text-outline mt-0.5">{{ $documents->total() }} ລາຍການທັງໝົດ</p>
  </div>
  <a href="{{ route('admin.documents.create') }}"
     class="inline-flex items-center gap-2 text-sm font-semibold text-white primary-gradient px-4 py-2 rounded-lg hover:opacity-90 transition whitespace-nowrap">
    <i class="fas fa-plus text-xs"></i> ເພີ່ມເອກະສານ
  </a>
</div>

{{-- Type count chips --}}
@if($typeCounts->isNotEmpty())
<div class="flex flex-wrap gap-2 mb-4">
  <a href="{{ route('admin.documents.index', request()->except('file_type')) }}"
     class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full border transition-colors {{ !request('file_type') ? 'primary-gradient text-white border-transparent' : 'border-surface-container-high hover:bg-surface-container-low' }}">
    ທັງໝົດ <span class="opacity-75">({{ $documents->total() }})</span>
  </a>
  @foreach($typeCounts as $type => $count)
    @php [$ico,$col,$bg] = docIcon($type, $typeIcon); @endphp
    <a href="{{ route('admin.documents.index', array_merge(request()->query(), ['file_type'=>$type])) }}"
       class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full border transition-colors {{ request('file_type') === $type ? 'primary-gradient text-white border-transparent' : 'border-surface-container-high hover:bg-surface-container-low' }}">
      <i class="fas {{ $ico }} text-[10px] {{ request('file_type')===$type ? 'text-white' : $col }}"></i>
      {{ $type }} <span class="opacity-75">({{ $count }})</span>
    </a>
  @endforeach
</div>
@endif

{{-- Filters --}}
<form method="GET" action="{{ route('admin.documents.index') }}" class="flex flex-wrap gap-2 mb-4">
  <input type="hidden" name="file_type" value="{{ request('file_type') }}">
  <input type="text" name="search" value="{{ request('search') }}" placeholder="ຄົ້ນຫາຊື່..."
         class="flex-1 min-w-[160px] rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
  <select name="category_id" class="rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
    <option value="">ທຸກໝວດໝູ່</option>
    @foreach($categories as $cat)
      <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name_lo }}</option>
    @endforeach
  </select>
  <select name="visibility" class="rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
    <option value="">ທຸກການເຂົ້າເຖິງ</option>
    <option value="public"  {{ request('visibility') === 'public'  ? 'selected' : '' }}>ສາທາລະນະ</option>
    <option value="private" {{ request('visibility') === 'private' ? 'selected' : '' }}>ສ່ວນຕົວ</option>
  </select>
  <select name="status" class="rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
    <option value="">ທຸກສະຖານະ</option>
    <option value="published"  {{ request('status') === 'published'  ? 'selected' : '' }}>ເຜີຍແຜ່</option>
    <option value="scheduled"  {{ request('status') === 'scheduled'  ? 'selected' : '' }}>ກຳນົດເວລາ</option>
    <option value="draft"      {{ request('status') === 'draft'      ? 'selected' : '' }}>ຮ່າງ</option>
  </select>
  <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg border border-surface-container-high hover:bg-surface-container transition-colors">
    <i class="fas fa-search text-xs"></i> ຄົ້ນຫາ
  </button>
  @if(request()->hasAny(['search','category_id','visibility','status','file_type']))
    <a href="{{ route('admin.documents.index') }}"
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
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide w-10">ໄຟລ໌</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide">ຊື່ / ໝວດໝູ່</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide hidden sm:table-cell w-20 text-center">ຂະໜາດ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide hidden md:table-cell text-center w-20">ດາວໂຫຼດ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide hidden lg:table-cell text-center w-20">ເຂົ້າເຖິງ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide text-center w-24">ສະຖານະ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide text-right">ຈັດການ</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-surface-container-high">
        @forelse($documents as $doc)
        @php [$ico, $col, $bg] = docIcon($doc->file_type, $typeIcon); @endphp
        <tr class="hover:bg-surface-container-low transition-colors">
          {{-- ໄຟລ໌ icon --}}
          <td class="px-4 py-3">
            <div class="w-9 h-9 rounded-lg {{ $bg }} flex items-center justify-center flex-shrink-0">
              <i class="fas {{ $ico }} {{ $col }} text-sm"></i>
            </div>
          </td>
          {{-- ຊື່ --}}
          <td class="px-4 py-3">
            <p class="font-semibold text-on-surface leading-tight">{{ $doc->title_lo }}</p>
            @if($doc->title_en)
              <p class="text-xs text-outline mt-0.5">{{ $doc->title_en }}</p>
            @endif
            <div class="flex items-center gap-2 mt-1">
              @if($doc->file_type)
                <span class="inline-flex items-center gap-1 text-[10px] font-bold {{ $col }} uppercase">{{ $doc->file_type }}</span>
              @endif
              @if($doc->category)
                <span class="text-[10px] text-outline">· {{ $doc->category->name_lo }}</span>
              @endif
            </div>
          </td>
          {{-- ຂະໜາດ --}}
          <td class="px-4 py-3 hidden sm:table-cell text-center text-xs text-outline">
            @if($doc->file_size_kb)
              {{ $doc->file_size_kb >= 1024 ? round($doc->file_size_kb/1024,1).' MB' : $doc->file_size_kb.' KB' }}
            @else
              —
            @endif
          </td>
          {{-- ດາວໂຫຼດ --}}
          <td class="px-4 py-3 hidden md:table-cell text-center">
            <span class="inline-flex items-center gap-1 text-xs text-outline">
              <i class="fas fa-download text-[10px]"></i> {{ number_format($doc->download_count) }}
            </span>
          </td>
          {{-- ການເຂົ້າເຖິງ --}}
          <td class="px-4 py-3 hidden lg:table-cell text-center">
            @if($doc->is_public)
              <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-semibold rounded-full bg-blue-100 text-blue-700">
                <i class="fas fa-globe text-[8px]"></i> ສາທາລະນະ
              </span>
            @else
              <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-semibold rounded-full bg-gray-100 text-gray-500">
                <i class="fas fa-lock text-[8px]"></i> ສ່ວນຕົວ
              </span>
            @endif
          </td>
          {{-- ສະຖານະ --}}
          <td class="px-4 py-3 text-center">
            @if($doc->published_at && $doc->published_at <= now())
              <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-semibold rounded-full bg-green-100 text-green-700">
                <i class="fas fa-circle text-[5px]"></i> ເຜີຍ
              </span>
            @elseif($doc->published_at)
              <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-semibold rounded-full bg-amber-100 text-amber-700">
                <i class="fas fa-clock text-[8px]"></i> ກຳນົດ
              </span>
            @else
              <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-semibold rounded-full bg-gray-100 text-gray-500">
                <i class="fas fa-circle text-[5px]"></i> ຮ່າງ
              </span>
            @endif
          </td>
          {{-- ຈັດການ --}}
          <td class="px-4 py-3 text-right">
            <div class="flex items-center justify-end gap-2">
              <a href="{{ route('admin.documents.show', $doc) }}"
                 class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:underline">
                <i class="fas fa-eye"></i><span class="hidden sm:inline">ລາຍລະອຽດ</span>
              </a>
              <a href="{{ route('admin.documents.download', $doc) }}"
                 class="inline-flex items-center gap-1 text-xs font-semibold text-green-600 hover:underline">
                <i class="fas fa-download"></i><span class="hidden sm:inline">ດາວໂຫຼດ</span>
              </a>
              <a href="{{ route('admin.documents.edit', $doc) }}"
                 class="inline-flex items-center gap-1 text-xs font-semibold text-yellow-600 hover:underline">
                <i class="fas fa-edit"></i><span class="hidden sm:inline">ແກ້ໄຂ</span>
              </a>
              <form action="{{ route('admin.documents.destroy', $doc) }}" method="POST" class="inline-block"
                    onsubmit="return confirm('ທ່ານຕ້ອງການລຶບ «{{ $doc->title_lo }}» ແທ້ບໍ?\n\nໄຟລ໌ຈະຖືກລຶບຖາວອນ!')">
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
            <i class="fas fa-folder-open text-3xl mb-2 block opacity-30"></i>
            ຍັງບໍ່ມີເອກະສານ
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($documents->hasPages())
    <div class="px-4 py-3 border-t border-surface-container-high">{{ $documents->links() }}</div>
  @endif
</div>

@endsection
