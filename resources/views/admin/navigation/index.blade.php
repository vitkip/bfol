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
        @forelse($menus as $menu)

          {{-- Top-level item --}}
          <tr class="hover:bg-surface-container-low transition-colors bg-blue-50/30">
            <td class="px-4 py-3 text-outline text-xs">{{ $menu->id }}</td>
            <td class="px-4 py-3">
              <div class="flex items-center gap-2">
                @if($menu->icon)
                  <i class="{{ $menu->icon }} text-blue-600 w-4 text-center text-xs"></i>
                @else
                  <i class="fas fa-bars text-blue-300 w-4 text-center text-xs"></i>
                @endif
                <div>
                  <p class="font-semibold text-on-surface">{{ $menu->label_lo }}</p>
                  @if($menu->label_en)
                    <p class="text-xs text-outline">{{ $menu->label_en }}</p>
                  @endif
                </div>
              </div>
            </td>
            <td class="px-4 py-3 hidden md:table-cell">
              @if($menu->url)
                <code class="text-xs bg-surface-container px-1.5 py-0.5 rounded text-on-surface-variant">{{ $menu->url }}</code>
              @else
                <span class="text-xs text-outline italic">dropdown</span>
              @endif
            </td>
            <td class="px-4 py-3 text-center hidden lg:table-cell text-xs text-outline">{{ $menu->sort_order }}</td>
            <td class="px-4 py-3 text-center">
              @if($menu->is_active)
                <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                  <i class="fas fa-circle text-[6px]"></i> ເປີດ
                </span>
              @else
                <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-500">
                  <i class="fas fa-circle text-[6px]"></i> ປິດ
                </span>
              @endif
            </td>
            <td class="px-4 py-3 text-right">
              <div class="flex items-center justify-end gap-3">
                <a href="{{ route('admin.navigation.edit', $menu) }}"
                   class="inline-flex items-center gap-1 text-xs font-semibold text-yellow-600 hover:underline">
                  <i class="fas fa-edit"></i><span class="hidden sm:inline">ແກ້ໄຂ</span>
                </a>
                <form action="{{ route('admin.navigation.destroy', $menu) }}" method="POST" class="inline-block"
                      onsubmit="return confirm('ລຶບ «{{ $menu->label_lo }}» ແທ້ບໍ?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="inline-flex items-center gap-1 text-xs font-semibold text-red-600 hover:underline">
                    <i class="fas fa-trash"></i><span class="hidden sm:inline">ລຶບ</span>
                  </button>
                </form>
              </div>
            </td>
          </tr>

          {{-- Sub-items --}}
          @foreach($menu->children as $child)
          <tr class="hover:bg-surface-container-low transition-colors">
            <td class="px-4 py-2 text-outline text-xs">{{ $child->id }}</td>
            <td class="px-4 py-2">
              <div class="flex items-center gap-2 pl-6">
                <i class="fas fa-level-up-alt fa-rotate-90 text-outline text-xs w-4 text-center"></i>
                <div>
                  <p class="text-on-surface text-sm">{{ $child->label_lo }}</p>
                  @if($child->label_en)
                    <p class="text-xs text-outline">{{ $child->label_en }}</p>
                  @endif
                </div>
              </div>
            </td>
            <td class="px-4 py-2 hidden md:table-cell">
              @if($child->url)
                <code class="text-xs bg-surface-container px-1.5 py-0.5 rounded text-on-surface-variant">{{ $child->url }}</code>
              @endif
            </td>
            <td class="px-4 py-2 text-center hidden lg:table-cell text-xs text-outline">{{ $child->sort_order }}</td>
            <td class="px-4 py-2 text-center">
              @if($child->is_active)
                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                  <i class="fas fa-circle text-[6px]"></i> ເປີດ
                </span>
              @else
                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-semibold rounded-full bg-gray-100 text-gray-500">
                  <i class="fas fa-circle text-[6px]"></i> ປິດ
                </span>
              @endif
            </td>
            <td class="px-4 py-2 text-right">
              <div class="flex items-center justify-end gap-3">
                <a href="{{ route('admin.navigation.edit', $child) }}"
                   class="inline-flex items-center gap-1 text-xs font-semibold text-yellow-600 hover:underline">
                  <i class="fas fa-edit"></i><span class="hidden sm:inline">ແກ້ໄຂ</span>
                </a>
                <form action="{{ route('admin.navigation.destroy', $child) }}" method="POST" class="inline-block"
                      onsubmit="return confirm('ລຶບ «{{ $child->label_lo }}» ແທ້ບໍ?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="inline-flex items-center gap-1 text-xs font-semibold text-red-600 hover:underline">
                    <i class="fas fa-trash"></i><span class="hidden sm:inline">ລຶບ</span>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @endforeach

        @empty
        <tr>
          <td colspan="6" class="text-center text-outline py-12">
            <i class="fas fa-bars text-3xl mb-2 block opacity-30"></i>
            ຍັງບໍ່ມີລາຍການເມນູ
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection
