@extends('admin.layouts.app')

@section('page_title', 'ໜ້າຂໍ້ມູນ')

@section('content')

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
  <div>
    <h2 class="text-base font-bold text-on-surface">ລາຍການໜ້າຂໍ້ມູນ</h2>
    <p class="text-xs text-outline mt-0.5">{{ $pages->total() }} ລາຍການທັງໝົດ</p>
  </div>
  <a href="{{ route('admin.pages.create') }}"
     class="inline-flex items-center gap-2 text-sm font-semibold text-white primary-gradient px-4 py-2 rounded-lg hover:opacity-90 transition-opacity whitespace-nowrap">
    <i class="fas fa-plus text-xs"></i> ສ້າງໜ້າໃໝ່
  </a>
</div>

{{-- Filter / Search --}}
<form method="GET" action="{{ route('admin.pages.index') }}" class="flex flex-col sm:flex-row gap-2 mb-4">
  <input type="text" name="search" value="{{ request('search') }}"
         placeholder="ຄົ້ນຫາຊື່, slug..."
         class="flex-1 rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
  <select name="status" class="rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
    <option value="">ທຸກສະຖານະ</option>
    <option value="published"   {{ request('status') === 'published'   ? 'selected' : '' }}>ເຜີຍແຜ່ແລ້ວ</option>
    <option value="unpublished" {{ request('status') === 'unpublished' ? 'selected' : '' }}>ຍັງບໍ່ເຜີຍແຜ່</option>
  </select>
  <button type="submit"
          class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg border border-surface-container-high hover:bg-surface-container transition-colors">
    <i class="fas fa-search text-xs"></i> ຄົ້ນຫາ
  </button>
  @if(request()->hasAny(['search','status']))
    <a href="{{ route('admin.pages.index') }}"
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
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide w-10">#</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide">ຊື່ໜ້າ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide hidden md:table-cell">Slug</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide hidden lg:table-cell">ໜ້າແມ່</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide text-center w-16 hidden sm:table-cell">ລຳດັບ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide text-center w-28">ສະຖານະ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide text-right">ຈັດການ</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-surface-container-high">
        @forelse($pages as $page)
        <tr class="hover:bg-surface-container-low transition-colors group">
          <td class="px-4 py-3 text-outline text-xs">{{ $page->id }}</td>
          <td class="px-4 py-3">
            <p class="font-semibold text-on-surface">{{ $page->title_lo }}</p>
            @if($page->title_en)
              <p class="text-xs text-outline mt-0.5">{{ $page->title_en }}</p>
            @endif
          </td>
          <td class="px-4 py-3 hidden md:table-cell">
            <code class="text-xs bg-surface-container px-1.5 py-0.5 rounded text-on-surface-variant">{{ $page->slug }}</code>
          </td>
          <td class="px-4 py-3 hidden lg:table-cell text-xs text-outline">
            {{ $page->parent_slug ?? '—' }}
          </td>
          <td class="px-4 py-3 text-center hidden sm:table-cell text-xs text-outline">
            {{ $page->sort_order }}
          </td>
          <td class="px-4 py-3 text-center">
            @if($page->is_published)
              <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                <i class="fas fa-circle text-[6px]"></i> ເຜີຍແຜ່
              </span>
            @else
              <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-500">
                <i class="fas fa-circle text-[6px]"></i> ຮ່າງ
              </span>
            @endif
          </td>
          <td class="px-4 py-3 text-right">
            <div class="flex items-center justify-end gap-2">
              <a href="{{ route('admin.pages.show', $page) }}"
                 class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:underline">
                <i class="fas fa-eye"></i><span class="hidden sm:inline">ລາຍລະອຽດ</span>
              </a>
              <a href="{{ route('admin.pages.edit', $page) }}"
                 class="inline-flex items-center gap-1 text-xs font-semibold text-yellow-600 hover:underline">
                <i class="fas fa-edit"></i><span class="hidden sm:inline">ແກ້ໄຂ</span>
              </a>
              <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" class="inline-block"
                    onsubmit="return confirm('ທ່ານຕ້ອງການລຶບໜ້າ «{{ $page->title_lo }}» ແທ້ບໍ?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-1 text-xs font-semibold text-red-600 hover:underline">
                  <i class="fas fa-trash"></i><span class="hidden sm:inline">ລຶບ</span>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" class="text-center text-outline py-12">
            <i class="fas fa-file-alt text-3xl mb-2 block opacity-30"></i>
            ຍັງບໍ່ມີໜ້າຂໍ້ມູນ
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($pages->hasPages())
    <div class="px-4 py-3 border-t border-surface-container-high">
      {{ $pages->links() }}
    </div>
  @endif
</div>

@endsection
