@extends('admin.layouts.app')

@section('page_title', 'ຂ່າວສານ')

@section('content')

@php
  $statusCfg = [
    'published' => ['label' => 'ເຜີຍແຜ່',  'class' => 'bg-green-100 text-green-700'],
    'draft'     => ['label' => 'ຮ່າງ',       'class' => 'bg-amber-100 text-amber-700'],
    'archived'  => ['label' => 'ເກັບ',       'class' => 'bg-surface-container-high text-outline'],
  ];
@endphp

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
  <div>
    <h2 class="text-base font-bold text-on-surface">ຂ່າວທັງໝົດ</h2>
    <p class="text-xs text-outline mt-0.5">{{ $counts['all'] }} ລາຍການ</p>
  </div>
  <a href="{{ route('admin.news.create') }}"
     class="inline-flex items-center gap-2 text-sm font-semibold text-white primary-gradient px-4 py-2 rounded-lg hover:opacity-90 transition-opacity whitespace-nowrap">
    <i class="fas fa-plus text-xs"></i> ສ້າງຂ່າວໃໝ່
  </a>
</div>

{{-- Status tabs --}}
<div class="flex items-center gap-1 mb-4 overflow-x-auto pb-1">
  @foreach([
    ['', 'ທັງໝົດ', $counts['all']],
    ['published', 'ເຜີຍແຜ່', $counts['published']],
    ['draft', 'ຮ່າງ', $counts['draft']],
    ['archived', 'ເກັບ', $counts['archived']],
  ] as [$val, $lbl, $cnt])
    @php
      $active = request('status', '') === $val;
      $params = request()->except('status', 'page');
      if ($val) $params['status'] = $val;
    @endphp
    <a href="{{ route('admin.news.index', $params) }}"
       class="flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-colors
              {{ $active ? 'primary-gradient text-white' : 'bg-surface-container-low text-on-surface-variant hover:bg-surface-container border border-surface-container-high' }}">
      {{ $lbl }}
      <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold
                   {{ $active ? 'bg-white/20 text-white' : 'bg-surface-container-high text-outline' }}">
        {{ $cnt }}
      </span>
    </a>
  @endforeach
</div>

{{-- Search & filters --}}
<form method="GET" class="flex flex-col sm:flex-row gap-2 mb-5">
  @if(request('status'))
    <input type="hidden" name="status" value="{{ request('status') }}">
  @endif
  <div class="relative flex-1">
    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-outline text-xs pointer-events-none"></i>
    <input name="search" value="{{ request('search') }}" placeholder="ຄົ້ນຫາຫົວຂໍ້…"
           class="w-full pl-8 pr-3 py-2 text-sm bg-surface-container-lowest border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30" />
  </div>
  <select name="category" class="text-sm bg-surface-container-lowest border border-surface-container-high rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary/30 min-w-[130px]">
    <option value="">ທຸກໝວດ</option>
    @foreach($categories as $cat)
      <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>{{ $cat->name_lo }}</option>
    @endforeach
  </select>
  <button type="submit" class="px-4 py-2 text-sm font-semibold primary-gradient text-white rounded-lg hover:opacity-90 transition-opacity whitespace-nowrap">
    ຄົ້ນຫາ
  </button>
  @if(request()->hasAny(['search','category']))
    <a href="{{ route('admin.news.index', request()->only('status')) }}"
       class="px-4 py-2 text-sm text-outline bg-surface-container-low border border-surface-container-high rounded-lg hover:bg-surface-container transition-colors whitespace-nowrap">
      ລ້າງ
    </a>
  @endif
</form>

{{-- Table --}}
<div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr class="border-b border-surface-container-high bg-surface-container-low text-left">
          <th class="px-4 sm:px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide">ຂ່າວ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide hidden md:table-cell whitespace-nowrap">ໝວດ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide">ສະຖານະ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide hidden lg:table-cell whitespace-nowrap">ຜູ້ຂຽນ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide hidden sm:table-cell whitespace-nowrap">ວັນທີ</th>
          <th class="px-4 sm:px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide text-right">ຈັດການ</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-surface-container-high">
        @forelse($news as $item)
          <tr class="hover:bg-surface-container-low transition-colors group">

            {{-- Thumbnail + Title --}}
            <td class="px-4 sm:px-5 py-3">
              <div class="flex items-center gap-3">
                {{-- Thumbnail --}}
                <div class="w-12 h-12 rounded-lg overflow-hidden flex-shrink-0 bg-surface-container-high">
                  @if($item->thumbnail)
                    <img src="{{ Storage::url($item->thumbnail) }}" alt=""
                         class="w-full h-full object-cover" loading="lazy" />
                  @else
                    <div class="w-full h-full flex items-center justify-center">
                      <i class="fas fa-newspaper text-outline/30 text-lg"></i>
                    </div>
                  @endif
                </div>
                <div class="min-w-0">
                  <a href="{{ route('admin.news.edit', $item) }}"
                     class="font-semibold text-on-surface hover:text-primary transition-colors line-clamp-2 leading-snug block">
                    {{ Str::limit($item->title_lo, 65) }}
                  </a>
                  <div class="flex items-center gap-2 mt-1">
                    @if($item->is_featured)
                      <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-amber-600">
                        <i class="fas fa-star text-[9px]"></i> ເດັ່ນ
                      </span>
                    @endif
                    @if($item->is_urgent)
                      <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-red-500">
                        <i class="fas fa-bolt text-[9px]"></i> ດ່ວນ
                      </span>
                    @endif
                  </div>
                </div>
              </div>
            </td>

            {{-- Category --}}
            <td class="px-4 py-3 hidden md:table-cell">
              @if($item->category)
                <span class="text-xs px-2 py-0.5 rounded-full bg-surface-container-low border border-surface-container-high text-on-surface-variant">
                  {{ $item->category->name_lo }}
                </span>
              @else
                <span class="text-xs text-outline">—</span>
              @endif
            </td>

            {{-- Status --}}
            <td class="px-4 py-3">
              <span class="text-[11px] font-semibold px-2.5 py-0.5 rounded-full {{ $statusCfg[$item->status]['class'] ?? 'bg-surface-container-high text-outline' }}">
                {{ $statusCfg[$item->status]['label'] ?? $item->status }}
              </span>
            </td>

            {{-- Author --}}
            <td class="px-4 py-3 hidden lg:table-cell text-xs text-on-surface-variant whitespace-nowrap">
              {{ $item->author?->full_name_lo ?? '—' }}
            </td>

            {{-- Date --}}
            <td class="px-4 py-3 hidden sm:table-cell text-xs text-outline whitespace-nowrap">
              {{ ($item->published_at ?? $item->created_at)?->format('d/m/Y') }}
            </td>

            {{-- Actions --}}
            <td class="px-4 sm:px-5 py-3 text-right">
              <div class="inline-flex items-center gap-1">
                <a href="{{ route('admin.news.edit', $item) }}"
                   class="p-1.5 text-outline hover:text-primary hover:bg-surface-container rounded-lg transition-colors" title="ແກ້ໄຂ">
                  <i class="fas fa-pen text-xs"></i>
                </a>
                <form method="POST" action="{{ route('admin.news.destroy', $item) }}"
                      onsubmit="return confirm('ລົບຂ່າວນີ້ແທ້ບໍ?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="p-1.5 text-outline hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="ລົບ">
                    <i class="fas fa-trash text-xs"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="px-5 py-16 text-center text-sm text-outline">
              <i class="fas fa-newspaper text-4xl mb-3 block opacity-20"></i>
              ບໍ່ພົບຂ່າວ
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Pagination --}}
  @if($news->hasPages())
    <div class="px-5 py-4 border-t border-surface-container-high flex flex-col sm:flex-row items-center justify-between gap-3">
      <p class="text-xs text-outline">
        ສະແດງ {{ $news->firstItem() }}–{{ $news->lastItem() }} ຈາກ {{ $news->total() }} ລາຍການ
      </p>
      <div class="flex items-center gap-1">
        @if($news->onFirstPage())
          <span class="px-3 py-1.5 rounded-lg text-outline bg-surface-container-low cursor-not-allowed text-xs">←</span>
        @else
          <a href="{{ $news->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg text-on-surface-variant bg-surface-container-low hover:bg-surface-container text-xs transition-colors">←</a>
        @endif
        @foreach($news->getUrlRange(max(1, $news->currentPage()-2), min($news->lastPage(), $news->currentPage()+2)) as $page => $url)
          <a href="{{ $url }}"
             class="px-3 py-1.5 rounded-lg text-xs transition-colors {{ $page === $news->currentPage() ? 'primary-gradient text-white' : 'text-on-surface-variant bg-surface-container-low hover:bg-surface-container' }}">
            {{ $page }}
          </a>
        @endforeach
        @if($news->hasMorePages())
          <a href="{{ $news->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg text-on-surface-variant bg-surface-container-low hover:bg-surface-container text-xs transition-colors">→</a>
        @else
          <span class="px-3 py-1.5 rounded-lg text-outline bg-surface-container-low cursor-not-allowed text-xs">→</span>
        @endif
      </div>
    </div>
  @endif
</div>

@endsection
