@extends('admin.layouts.app')

@section('page_title', 'ພະແນກ')

@section('content')

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
  <div>
    <h2 class="text-base font-bold text-on-surface">ພະແນກທັງໝົດ</h2>
    <p class="text-xs text-outline mt-0.5">{{ $departments->count() }} ລາຍການ</p>
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
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr class="border-b border-surface-container-high bg-surface-container-low text-left">
          <th class="px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide w-8">#</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide">ຊື່ພະແນກ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide hidden md:table-cell">ຄຳອະທິບາຍ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide text-center">ສະມາຊິກ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide text-center">ລຳດັບ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide">ສະຖານະ</th>
          <th class="px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide text-right">ຈັດການ</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-surface-container-high">
        @forelse($departments as $dept)
          <tr class="hover:bg-surface-container-low transition-colors group">
            <td class="px-5 py-3 text-outline text-xs">{{ $loop->iteration }}</td>

            <td class="px-4 py-3">
              <p class="font-semibold text-on-surface">{{ $dept->name_lo }}</p>
              @if($dept->name_en)
                <p class="text-xs text-outline">{{ $dept->name_en }}</p>
              @endif
              @if($dept->name_zh)
                <p class="text-xs text-outline">{{ $dept->name_zh }}</p>
              @endif
            </td>

            <td class="px-4 py-3 hidden md:table-cell">
              <p class="text-xs text-on-surface-variant line-clamp-2">{{ Str::limit($dept->description_lo, 80) ?? '—' }}</p>
            </td>

            <td class="px-4 py-3 text-center">
              <a href="{{ route('admin.committee.index', ['department' => $dept->id]) }}"
                 class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full bg-primary/10 text-primary hover:bg-primary/20 transition-colors">
                <i class="fas fa-users text-[10px]"></i>
                {{ $dept->members_count }}
              </a>
            </td>

            <td class="px-4 py-3 text-center text-xs text-outline">{{ $dept->sort_order }}</td>

            <td class="px-4 py-3">
              <span class="text-[11px] font-semibold px-2.5 py-0.5 rounded-full
                {{ $dept->is_active ? 'bg-green-100 text-green-700' : 'bg-surface-container-high text-outline' }}">
                {{ $dept->is_active ? 'ເປີດໃຊ້' : 'ປິດໃຊ້' }}
              </span>
            </td>

            <td class="px-5 py-3 text-right">
              <div class="inline-flex items-center gap-1">
                <a href="{{ route('admin.departments.edit', $dept) }}"
                   class="p-1.5 text-outline hover:text-primary hover:bg-surface-container rounded-lg transition-colors" title="ແກ້ໄຂ">
                  <i class="fas fa-pen text-xs"></i>
                </a>
                <form method="POST" action="{{ route('admin.departments.destroy', $dept) }}"
                      onsubmit="return confirm('ລົບພະແນກ \'{{ $dept->name_lo }}\' ແທ້ບໍ?')">
                  @csrf @method('DELETE')
                  <button type="submit"
                          class="p-1.5 text-outline hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors {{ $dept->members_count > 0 ? 'opacity-40 cursor-not-allowed' : '' }}"
                          title="{{ $dept->members_count > 0 ? 'ມີສະມາຊິກຢູ່ ລົບບໍ່ໄດ້' : 'ລົບ' }}"
                          @if($dept->members_count > 0) onclick="return false;" @endif>
                    <i class="fas fa-trash text-xs"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="px-5 py-16 text-center text-sm text-outline">
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
