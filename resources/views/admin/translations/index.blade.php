@extends('admin.layouts.app')

@section('page_title', 'ໂຄງການແປ')

@section('content')

@php
  $statusCfg = [
    'in_progress' => ['ກຳລັງດຳເນີນ', 'bg-blue-100 text-blue-700'],
    'reviewing'   => ['ກວດທານ',        'bg-amber-100 text-amber-700'],
    'completed'   => ['ສຳເລັດ',         'bg-emerald-100 text-emerald-700'],
    'published'   => ['ເຜີຍແຜ່',        'bg-primary/10 text-primary'],
  ];
  $langNames = [
    'lo'=>'ລາວ','en'=>'ອັງກິດ','zh'=>'ຈີນ','th'=>'ໄທ','my'=>'ພະມ້າ',
    'km'=>'ຂະແໝນ','ja'=>'ຍີ່ປຸ່ນ','ko'=>'ເກົາຫຼີ','vi'=>'ຫວຽດນາມ',
    'pi'=>'ບາລີ','sa'=>'ສັນສະກຣິດ','en'=>'ອັງກິດ','fr'=>'ຝຣັ່ງ',
  ];
@endphp

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
  <div>
    <h2 class="text-base font-bold text-on-surface">ໂຄງການແປພາສາ</h2>
    <p class="text-xs text-outline mt-0.5">{{ $projects->total() }} ໂຄງການທັງໝົດ</p>
  </div>
  <a href="{{ route('admin.translations.create') }}"
     class="inline-flex items-center gap-2 text-sm font-semibold text-white primary-gradient px-4 py-2 rounded-lg hover:opacity-90 transition whitespace-nowrap">
    <i class="fas fa-plus text-xs"></i> ເພີ່ມໂຄງການ
  </a>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.translations.index') }}" class="flex flex-wrap gap-2 mb-4">
  <input type="text" name="search" value="{{ request('search') }}" placeholder="ຄົ້ນຫາຊື່/ຜູ້ແປ..."
         class="flex-1 min-w-[180px] rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
  <select name="status" class="rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
    <option value="">ທຸກສະຖານະ</option>
    @foreach($statusCfg as $val => [$label, $_])
      <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
    @endforeach
  </select>
  <select name="year" class="rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
    <option value="">ທຸກປີ</option>
    @foreach($years as $y)
      <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
    @endforeach
  </select>
  <button type="submit" class="px-4 py-2 text-sm font-semibold rounded-lg border border-surface-container-high hover:bg-surface-container transition-colors flex items-center gap-2">
    <i class="fas fa-search text-xs"></i> ຄົ້ນຫາ
  </button>
  @if(request()->hasAny(['search','status','year']))
    <a href="{{ route('admin.translations.index') }}"
       class="px-4 py-2 text-sm rounded-lg border border-surface-container-high hover:bg-surface-container transition-colors text-outline flex items-center gap-2">
      <i class="fas fa-times text-xs"></i> ລ້າງ
    </a>
  @endif
</form>

<div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] overflow-hidden">
  @if($projects->isEmpty())
    <div class="p-12 text-center text-outline">
      <i class="fas fa-language text-4xl mb-3 opacity-30"></i>
      <p class="text-sm font-semibold">ຍັງບໍ່ມີໂຄງການແປ</p>
      <a href="{{ route('admin.translations.create') }}" class="mt-3 inline-flex items-center gap-2 text-sm text-primary hover:underline">
        <i class="fas fa-plus text-xs"></i> ເພີ່ມໂຄງການທຳອິດ
      </a>
    </div>
  @else
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-surface-container-high bg-surface-container-low text-left">
            <th class="px-4 py-3 text-xs font-bold text-outline/70 uppercase tracking-wide">#</th>
            <th class="px-4 py-3 text-xs font-bold text-outline/70 uppercase tracking-wide">ຊື່ໂຄງການ</th>
            <th class="px-4 py-3 text-xs font-bold text-outline/70 uppercase tracking-wide">ພາສາ</th>
            <th class="px-4 py-3 text-xs font-bold text-outline/70 uppercase tracking-wide">ຜູ້ແປ</th>
            <th class="px-4 py-3 text-xs font-bold text-outline/70 uppercase tracking-wide">ປີ</th>
            <th class="px-4 py-3 text-xs font-bold text-outline/70 uppercase tracking-wide">ສະຖານະ</th>
            <th class="px-4 py-3 text-xs font-bold text-outline/70 uppercase tracking-wide">ເອກະສານ</th>
            <th class="px-4 py-3"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-surface-container-high/60">
          @foreach($projects as $p)
            @php [$statusLabel, $statusClass] = $statusCfg[$p->status] ?? ['—','bg-slate-100 text-slate-500']; @endphp
            <tr class="hover:bg-surface-container-low/50 transition-colors">
              <td class="px-4 py-3 text-xs text-outline">{{ $p->id }}</td>
              <td class="px-4 py-3">
                <p class="font-semibold text-on-surface line-clamp-1">{{ $p->title_lo }}</p>
                @if($p->title_en)
                  <p class="text-xs text-outline mt-0.5 line-clamp-1">{{ $p->title_en }}</p>
                @endif
              </td>
              <td class="px-4 py-3">
                @if($p->source_language || $p->target_language)
                  <div class="flex items-center gap-1 text-xs">
                    <span class="bg-surface-container px-2 py-0.5 rounded font-medium">{{ $langNames[$p->source_language] ?? $p->source_language }}</span>
                    <i class="fas fa-arrow-right text-[9px] text-outline"></i>
                    <span class="bg-surface-container px-2 py-0.5 rounded font-medium">{{ $langNames[$p->target_language] ?? $p->target_language }}</span>
                  </div>
                @else
                  <span class="text-outline text-xs">—</span>
                @endif
              </td>
              <td class="px-4 py-3 text-xs text-on-surface-variant">{{ $p->translator ?: '—' }}</td>
              <td class="px-4 py-3 text-xs font-mono text-on-surface-variant">{{ $p->year ?: '—' }}</td>
              <td class="px-4 py-3">
                <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-semibold rounded-full {{ $statusClass }}">
                  {{ $statusLabel }}
                </span>
              </td>
              <td class="px-4 py-3">
                @if($p->document_url)
                  <a href="{{ $p->document_url }}" target="_blank"
                     class="inline-flex items-center gap-1 text-xs text-primary hover:underline">
                    <i class="fas fa-file-pdf text-[10px]"></i> ດາວໂຫຼດ
                  </a>
                @else
                  <span class="text-outline text-xs">—</span>
                @endif
              </td>
              <td class="px-4 py-3">
                <div class="flex items-center gap-1 justify-end">
                  <a href="{{ route('admin.translations.edit', $p) }}"
                     class="p-1.5 text-primary hover:bg-primary/5 rounded-md transition-colors" title="ແກ້ໄຂ">
                    <i class="fas fa-edit text-xs"></i>
                  </a>
                  <form action="{{ route('admin.translations.destroy', $p) }}" method="POST"
                        onsubmit="return confirm('ລຶບໂຄງການ \'{{ addslashes($p->title_lo) }}\'?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors" title="ລຶບ">
                      <i class="fas fa-trash text-xs"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="px-4 py-3 border-t border-surface-container-high">
      {{ $projects->links() }}
    </div>
  @endif
</div>

@endsection
