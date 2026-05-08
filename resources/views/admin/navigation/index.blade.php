@extends('admin.layouts.app')

@section('page_title', 'ຈັດການເມນູ')

@section('content')

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
  <div>
    <h2 class="text-base font-bold text-on-surface">ລາຍການເມນູ</h2>
    <p class="text-xs text-outline mt-0.5">ຈັດການລາຍການເມນູ navigation ໜ້າເວັບ</p>
  </div>
  <a href="{{ route('admin.navigation.create') }}"
     class="inline-flex items-center gap-2 text-sm font-semibold text-white primary-gradient px-4 py-2 rounded-lg hover:opacity-90 transition-opacity whitespace-nowrap">
    <i class="fas fa-plus text-xs"></i> ເພີ່ມລາຍການໃໝ່
  </a>
</div>

@if(session('success'))
  <div class="mb-4 bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm flex items-center gap-2">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
  </div>
@endif
@if(session('error'))
  <div class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm flex items-center gap-2">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
  </div>
@endif

<div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr class="border-b border-surface-container-high bg-surface-container-low text-left">
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide w-8">#</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide">ຊື່ເມນູ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide hidden md:table-cell">URL</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide hidden lg:table-cell w-16 text-center">ລຳດັບ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide text-center w-24">ສະຖານະ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide text-right">ຈັດການ</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-surface-container-high">
        @if($menus->isEmpty())
        <tr>
          <td colspan="6" class="text-center text-outline py-12">
            <i class="fas fa-bars text-3xl mb-2 block opacity-30"></i>
            ຍັງບໍ່ມີລາຍການເມນູ
          </td>
        </tr>
        @else
          @include('admin.navigation._menu_tree', ['menus' => $menus, 'depth' => 0])
        @endif
      </tbody>
    </table>
  </div>
</div>

@endsection
