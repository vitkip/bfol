@extends('admin.layouts.app')

@section('page_title', 'ພະແນກ')

@php
  /** Flatten nested dept tree into a flat array with depth info. */
  $flatDepts = [];
  $stack = $roots->map(fn($r) => [$r, 0])->toArray();
  while (!empty($stack)) {
    [$dept, $depth] = array_shift($stack);
    $flatDepts[] = ['dept' => $dept, 'depth' => $depth];
    // Prepend children so they appear right after parent
    $childPairs = $dept->children->map(fn($c) => [$c, $depth + 1])->toArray();
    $stack = array_merge($childPairs, $stack);
  }

  $totalSubs = $roots->sum(fn($r) => $r->children->count());
@endphp

@section('content')

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
  <div>
    <h2 class="text-base font-bold text-on-surface">ພະແນກທັງໝົດ</h2>
    <p class="text-xs text-outline mt-0.5">
      {{ $roots->count() }} ໜ່ວຍງານຫຼັກ
      @if($totalSubs > 0) · {{ $totalSubs }} sub-ພະແນກ @endif
    </p>
  </div>
  <a href="{{ route('admin.departments.create') }}"
     class="inline-flex items-center gap-2 text-sm font-semibold text-white primary-gradient px-4 py-2 rounded-lg hover:opacity-90 transition-opacity whitespace-nowrap">
    <i class="fas fa-plus text-xs"></i> ເພີ່ມພະແນກໃໝ່
  </a>
</div>

@if(session('success'))
  <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 mb-5 text-sm">
    <i class="fas fa-check-circle text-green-500"></i> {{ session('success') }}
  </div>
@endif
@if(session('error'))
  <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-5 text-sm">
    <i class="fas fa-exclamation-circle text-red-500"></i> {{ session('error') }}
  </div>
@endif

<div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] overflow-hidden">

  {{-- Legend --}}
  <div class="px-5 py-2.5 border-b border-surface-container-high bg-surface-container-low flex items-center gap-4 text-[11px] text-outline">
    <span class="flex items-center gap-1.5"><i class="fas fa-layer-group text-[10px]"></i> ໜ່ວຍງານຫຼັກ</span>
    <span class="flex items-center gap-1.5"><span>↳</span> sub-ພະແນກ</span>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr class="border-b border-surface-container-high bg-surface-container-low text-left">
          <th class="px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide w-6"></th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide">ຊື່ພະແນກ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide hidden md:table-cell">ຄຳອະທິບາຍ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide text-center w-16">Sub</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide text-center w-20">ສະມາຊິກ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide text-center w-16">ລຳດັບ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide w-20">ສະຖານະ</th>
          <th class="px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide text-right w-20">ຈັດການ</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-surface-container-high">

        @forelse($flatDepts as $item)
          @php
            $dept       = $item['dept'];
            $depth      = $item['depth'];
            $isRoot     = $depth === 0;
            $hasChildren = $dept->children->isNotEmpty();
            $canDelete  = ($dept->members_count ?? 0) === 0 && !$hasChildren;
            $rowBg      = match($depth) {
              1 => 'bg-surface-container-low/40',
              2 => 'bg-surface-container/30',
              default => '',
            };
          @endphp

          <tr class="hover:bg-surface-container-low/70 transition-colors group {{ $rowBg }}">

            {{-- Tree indicator --}}
            <td class="px-5 py-3 text-center">
              @if($isRoot)
                <i class="fas fa-layer-group text-outline/35 text-[10px]"></i>
              @elseif($depth === 1)
                <span class="text-outline/50 text-xs font-bold">↳</span>
              @else
                <span class="text-outline/30 text-xs pl-2">↳</span>
              @endif
            </td>

            {{-- Name --}}
            <td class="py-3" style="padding-left:{{ 16 + $depth * 18 }}px; padding-right:16px;">
              <div class="flex items-center gap-2">
                @if($depth > 0)
                  <span class="w-0.5 h-4 rounded-full flex-shrink-0
                               {{ $depth === 1 ? 'bg-primary/30' : 'bg-outline/20' }}"></span>
                @endif
                <div>
                  <p class="font-semibold text-on-surface {{ $isRoot ? 'text-sm' : 'text-[13px]' }}">
                    {{ $dept->name_lo }}
                  </p>
                  @if($dept->name_en)
                    <p class="text-[11px] text-outline">{{ $dept->name_en }}</p>
                  @endif
                  @if($isRoot && $hasChildren)
                    <div class="flex flex-wrap gap-1 mt-1">
                      @foreach($dept->children as $child)
                        <span class="text-[9px] px-1.5 py-0.5 rounded-full bg-primary/8 text-primary/70 border border-primary/15">
                          {{ $child->name_lo }}
                        </span>
                      @endforeach
                    </div>
                  @endif
                </div>
              </div>
            </td>

            {{-- Description --}}
            <td class="px-4 py-3 hidden md:table-cell max-w-[200px]">
              <p class="text-xs text-on-surface-variant line-clamp-2">{{ Str::limit($dept->description_lo, 70) ?: '—' }}</p>
            </td>

            {{-- Sub-depts count --}}
            <td class="px-4 py-3 text-center">
              @if($hasChildren)
                <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full
                             bg-amber-100 text-amber-700 border border-amber-200">
                  <i class="fas fa-sitemap text-[9px]"></i>{{ $dept->children->count() }}
                </span>
              @else
                <span class="text-outline/30 text-xs">—</span>
              @endif
            </td>

            {{-- Members --}}
            <td class="px-4 py-3 text-center">
              <a href="{{ route('admin.committee.index', ['department' => $dept->id]) }}"
                 class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full
                        bg-primary/10 text-primary hover:bg-primary/20 transition-colors">
                <i class="fas fa-users text-[9px]"></i>{{ $dept->members_count ?? 0 }}
              </a>
            </td>

            {{-- Sort --}}
            <td class="px-4 py-3 text-center text-xs text-outline">{{ $dept->sort_order }}</td>

            {{-- Status --}}
            <td class="px-4 py-3">
              <span class="text-[11px] font-semibold px-2.5 py-0.5 rounded-full
                    {{ $dept->is_active ? 'bg-green-100 text-green-700' : 'bg-surface-container-high text-outline' }}">
                {{ $dept->is_active ? 'ເປີດໃຊ້' : 'ປິດໃຊ້' }}
              </span>
            </td>

            {{-- Actions --}}
            <td class="px-5 py-3 text-right">
              <div class="inline-flex items-center gap-1">
                <a href="{{ route('admin.departments.edit', $dept) }}"
                   class="p-1.5 text-outline hover:text-primary hover:bg-surface-container rounded-lg transition-colors"
                   title="ແກ້ໄຂ">
                  <i class="fas fa-pen text-xs"></i>
                </a>
                <form method="POST" action="{{ route('admin.departments.destroy', $dept) }}"
                      onsubmit="return confirm('ລົບພະແນກ \'{{ addslashes($dept->name_lo) }}\' ແທ້ບໍ?')">
                  @csrf @method('DELETE')
                  <button type="submit"
                          class="p-1.5 text-outline rounded-lg transition-colors
                                 {{ $canDelete
                                    ? 'hover:text-red-500 hover:bg-red-50'
                                    : 'opacity-40 cursor-not-allowed' }}"
                          title="{{ !$canDelete ? 'ມີສະມາຊິກ/sub-ພະແນກ — ລົບບໍ່ໄດ້' : 'ລົບ' }}"
                          @if(!$canDelete) onclick="return false;" @endif>
                    <i class="fas fa-trash text-xs"></i>
                  </button>
                </form>
              </div>
            </td>

          </tr>
        @empty
          <tr>
            <td colspan="8" class="px-5 py-16 text-center text-sm text-outline">
              <i class="fas fa-building text-4xl mb-3 block opacity-20"></i>
              ຍັງບໍ່ມີພະແນກ
            </td>
          </tr>
        @endforelse

      </tbody>
    </table>
  </div>
</div>

@endsection
