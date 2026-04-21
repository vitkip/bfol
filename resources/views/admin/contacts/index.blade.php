@extends('admin.layouts.app')

@section('page_title', 'ຂໍ້ຄວາມຕິດຕໍ່')

@section('content')

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
  <div>
    <h2 class="text-base font-bold text-on-surface flex items-center gap-2">
      ຂໍ້ຄວາມຕິດຕໍ່
      @if($unreadCount > 0)
        <span class="w-5 h-5 flex items-center justify-center primary-gradient text-white text-[10px] font-bold rounded-full">{{ $unreadCount }}</span>
      @endif
    </h2>
    <p class="text-xs text-outline mt-0.5">{{ $messages->total() }} ຂໍ້ຄວາມທັງໝົດ</p>
  </div>
</div>

@if(session('success'))
  <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 mb-4 text-sm">
    <i class="fas fa-check-circle text-green-500"></i> {{ session('success') }}
  </div>
@endif

{{-- Read status chips --}}
<div class="flex flex-wrap gap-2 mb-4">
  <a href="{{ route('admin.contacts.index', request()->except('read')) }}"
     class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full border transition-colors
            {{ request('read') === null || request('read') === '' ? 'primary-gradient text-white border-transparent' : 'border-surface-container-high hover:bg-surface-container-low' }}">
    ທຸກຂໍ້ຄວາມ
  </a>
  <a href="{{ route('admin.contacts.index', array_merge(request()->query(), ['read' => '0'])) }}"
     class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full border transition-colors
            {{ request('read') === '0' ? 'primary-gradient text-white border-transparent' : 'border-surface-container-high hover:bg-surface-container-low' }}">
    <i class="fas fa-circle text-[8px] text-blue-500"></i> ຍັງບໍ່ໄດ້ອ່ານ
  </a>
  <a href="{{ route('admin.contacts.index', array_merge(request()->query(), ['read' => '1'])) }}"
     class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full border transition-colors
            {{ request('read') === '1' ? 'primary-gradient text-white border-transparent' : 'border-surface-container-high hover:bg-surface-container-low' }}">
    <i class="fas fa-check text-[8px] text-green-500"></i> ອ່ານແລ້ວ
  </a>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.contacts.index') }}" class="flex flex-wrap gap-2 mb-4">
  <input type="hidden" name="read" value="{{ request('read') }}">
  <input type="text" name="search" value="{{ request('search') }}" placeholder="ຄົ້ນຫາຊື່, ອີເມລ, ຫົວຂໍ້..."
         class="flex-1 min-w-[160px] rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
  <select name="language" class="rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
    <option value="">ທຸກພາສາ</option>
    @foreach($languages as $key => $meta)
      <option value="{{ $key }}" {{ request('language') === $key ? 'selected' : '' }}>{{ $meta['label'] }}</option>
    @endforeach
  </select>
  <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg border border-surface-container-high hover:bg-surface-container transition-colors">
    <i class="fas fa-search text-xs"></i> ຄົ້ນຫາ
  </button>
  @if(request()->hasAny(['search', 'language', 'read']))
    <a href="{{ route('admin.contacts.index') }}"
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
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide">ຜູ້ສົ່ງ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide hidden sm:table-cell">ຫົວຂໍ້ / ຂໍ້ຄວາມ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide hidden md:table-cell text-center">ພາສາ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide hidden lg:table-cell">ວັນທີ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide text-center w-24">ສະຖານະ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide text-right">ຈັດການ</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-surface-container-high">
        @forelse($messages as $msg)
        @php $lang = $languages[$msg->language] ?? $languages['lo']; @endphp
        <tr class="hover:bg-surface-container-low transition-colors {{ !$msg->is_read ? 'bg-blue-50/40' : '' }}">
          {{-- ຜູ້ສົ່ງ --}}
          <td class="px-4 py-3">
            <div class="flex items-center gap-2.5">
              {{-- Unread dot --}}
              <div class="w-2 h-2 rounded-full shrink-0 {{ !$msg->is_read ? 'bg-blue-500' : 'bg-transparent' }}"></div>
              <div class="min-w-0">
                <p class="font-semibold text-on-surface leading-tight truncate {{ !$msg->is_read ? 'font-bold' : '' }}">{{ $msg->name }}</p>
                <p class="text-xs text-outline mt-0.5 truncate max-w-[160px]">{{ $msg->email }}</p>
                @if($msg->phone)
                  <p class="text-[11px] text-outline">{{ $msg->phone }}</p>
                @endif
              </div>
            </div>
          </td>
          {{-- ຫົວຂໍ້ --}}
          <td class="px-4 py-3 hidden sm:table-cell max-w-[220px]">
            @if($msg->subject)
              <p class="font-semibold text-on-surface text-xs truncate">{{ $msg->subject }}</p>
            @endif
            <p class="text-xs text-on-surface-variant truncate mt-0.5">{{ Str::limit($msg->message, 80) }}</p>
          </td>
          {{-- ພາສາ --}}
          <td class="px-4 py-3 hidden md:table-cell text-center">
            <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold rounded-full {{ $lang['class'] }}">
              {{ $lang['label'] }}
            </span>
          </td>
          {{-- ວັນທີ --}}
          <td class="px-4 py-3 hidden lg:table-cell text-xs text-on-surface-variant">
            <p>{{ $msg->created_at->format('d/m/Y') }}</p>
            <p class="text-outline">{{ $msg->created_at->format('H:i') }}</p>
          </td>
          {{-- ສະຖານະ --}}
          <td class="px-4 py-3 text-center">
            @if($msg->is_read)
              <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-semibold rounded-full bg-green-100 text-green-700">
                <i class="fas fa-check text-[8px]"></i> ອ່ານແລ້ວ
              </span>
            @else
              <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-semibold rounded-full bg-blue-100 text-blue-700">
                <i class="fas fa-circle text-[7px]"></i> ໃໝ່
              </span>
            @endif
          </td>
          {{-- ຈັດການ --}}
          <td class="px-4 py-3 text-right">
            <div class="flex items-center justify-end gap-2">
              <a href="{{ route('admin.contacts.show', $msg) }}"
                 class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:underline">
                <i class="fas fa-eye"></i><span class="hidden sm:inline">ອ່ານ</span>
              </a>
              <form action="{{ route('admin.contacts.destroy', $msg) }}" method="POST" class="inline-block"
                    onsubmit="return confirm('ລຶບຂໍ້ຄວາມຈາກ «{{ $msg->name }}» ແທ້ບໍ?')">
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
          <td colspan="6" class="text-center text-outline py-12">
            <i class="fas fa-envelope-open text-3xl mb-2 block opacity-30"></i>
            ຍັງບໍ່ມີຂໍ້ຄວາມ
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($messages->hasPages())
    <div class="px-4 py-3 border-t border-surface-container-high">{{ $messages->links() }}</div>
  @endif
</div>

@endsection
