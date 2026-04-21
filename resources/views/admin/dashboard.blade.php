@extends('admin.layouts.app')

@section('page_title', 'Dashboard')

@section('content')

{{-- Stats Grid --}}
<div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-3 sm:gap-4 mb-6">
  @php
  $cards = [
    ['icon'=>'fa-newspaper',      'value'=>$news_count,      'label'=>'ຂ່າວເຜີຍແຜ່',      'color'=>'text-blue-600',   'bg'=>'bg-blue-50'],
    ['icon'=>'fa-calendar-check', 'value'=>$events_upcoming, 'label'=>'ກິດຈະກໍາທີ່ຈະມາ',  'color'=>'text-green-600',  'bg'=>'bg-green-50'],
    ['icon'=>'fa-file-signature', 'value'=>$mou_active,      'label'=>'MOU ທີ່ໃຊ້ງານ',      'color'=>'text-purple-600', 'bg'=>'bg-purple-50'],
    ['icon'=>'fa-globe',          'value'=>$partners_count,  'label'=>'ຄູ່ຮ່ວມມື',          'color'=>'text-indigo-600', 'bg'=>'bg-indigo-50'],
    ['icon'=>'fa-envelope',       'value'=>$unread_contacts, 'label'=>'ຂໍ້ຄວາມໃໝ່',        'color'=>($unread_contacts > 0 ? 'text-red-600' : 'text-orange-600'), 'bg'=>($unread_contacts > 0 ? 'bg-red-50' : 'bg-orange-50')],
    ['icon'=>'fa-exchange-alt',   'value'=>$monk_open,       'label'=>'ໂຄງການເປີດຮັບ',    'color'=>'text-amber-600',  'bg'=>'bg-amber-50'],
  ];
  @endphp

  @foreach($cards as $card)
    <div class="bg-surface-container-lowest rounded-xl p-4 shadow-[0px_2px_8px_rgba(26,28,29,0.06)] flex flex-col gap-3">
      <div class="w-9 h-9 {{ $card['bg'] }} rounded-lg flex items-center justify-center">
        <i class="fas {{ $card['icon'] }} {{ $card['color'] }} text-sm"></i>
      </div>
      <div>
        <p class="text-2xl font-extrabold text-on-surface">{{ $card['value'] }}</p>
        <p class="text-xs text-outline mt-0.5 leading-snug">{{ $card['label'] }}</p>
      </div>
    </div>
  @endforeach
</div>

{{-- Two-column panels --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-4 sm:gap-6">

  {{-- Recent News --}}
  <div class="xl:col-span-2 bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] overflow-hidden">
    <div class="flex items-center justify-between px-4 sm:px-5 py-4 border-b border-surface-container-high">
      <h3 class="text-sm font-bold text-on-surface">ຂ່າວຫຼ້າສຸດ</h3>
      <a href="{{ route('admin.news.create') }}"
         class="flex items-center gap-1.5 text-xs font-semibold text-white primary-gradient px-3 py-1.5 rounded-full hover:opacity-90 transition-opacity whitespace-nowrap">
        <i class="fas fa-plus text-[10px]"></i><span>ສ້າງໃໝ່</span>
      </a>
    </div>
    <div class="divide-y divide-surface-container-high">
      @forelse($recent_news as $item)
        <div class="flex items-start sm:items-center gap-3 sm:gap-4 px-4 sm:px-5 py-3 hover:bg-surface-container-low transition-colors">
          <div class="flex-1 min-w-0">
            <a href="{{ route('admin.news.edit', $item) }}"
               class="text-sm font-medium text-on-surface hover:text-primary transition-colors truncate block">
              {{ Str::limit($item->title_lo, 55) }}
            </a>
            <p class="text-xs text-outline mt-0.5">{{ $item->category?->name_lo ?? '—' }}</p>
          </div>
          <div class="flex flex-col sm:flex-row items-end sm:items-center gap-1 sm:gap-2 flex-shrink-0">
            @php
              $sc = ['published'=>'bg-green-100 text-green-700','draft'=>'bg-surface-container-high text-outline','archived'=>'bg-surface-container-highest text-outline'];
              $sl = ['published'=>'ເຜີຍແຜ່','draft'=>'ຮ່າງ','archived'=>'ເກັບ'];
            @endphp
            <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full {{ $sc[$item->status] ?? 'bg-surface-container-high text-outline' }}">
              {{ $sl[$item->status] ?? $item->status }}
            </span>
            <span class="text-[10px] text-outline whitespace-nowrap">
              {{ $item->published_at?->format('d/m/y') ?? '—' }}
            </span>
          </div>
        </div>
      @empty
        <div class="px-5 py-10 text-center text-sm text-outline">
          <i class="fas fa-newspaper text-3xl mb-2 block opacity-20"></i>
          ຍັງບໍ່ມີຂ່າວ
        </div>
      @endforelse
    </div>
    <div class="px-4 sm:px-5 py-3 border-t border-surface-container-high">
      <a href="{{ route('admin.news.index') }}" class="text-xs font-semibold text-primary hover:underline">
        ດູທັງໝົດ →
      </a>
    </div>
  </div>

  {{-- Unread Contacts --}}
  <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] overflow-hidden">
    <div class="flex items-center justify-between px-4 sm:px-5 py-4 border-b border-surface-container-high">
      <h3 class="text-sm font-bold text-on-surface">ຂໍ້ຄວາມໃໝ່</h3>
      <a href="{{ route('admin.contacts.index') }}" class="text-xs font-semibold text-primary hover:underline">
        ທັງໝົດ
      </a>
    </div>
    <div class="divide-y divide-surface-container-high">
      @forelse($recent_contacts as $msg)
        <a href="{{ route('admin.contacts.show', $msg) }}"
           class="flex flex-col gap-1 px-4 sm:px-5 py-3.5 hover:bg-surface-container-low transition-colors">
          <div class="flex items-center justify-between gap-2">
            <span class="text-sm font-semibold text-on-surface truncate">{{ $msg->name }}</span>
            <span class="text-[10px] text-outline whitespace-nowrap flex-shrink-0">{{ $msg->created_at->diffForHumans() }}</span>
          </div>
          <p class="text-xs text-on-surface-variant truncate">
            {{ Str::limit($msg->subject ?? $msg->message, 50) }}
          </p>
        </a>
      @empty
        <div class="px-5 py-10 text-center text-sm text-outline">
          <i class="fas fa-envelope-open text-3xl mb-2 block opacity-20"></i>
          ບໍ່ມີຂໍ້ຄວາມໃໝ່
        </div>
      @endforelse
    </div>
  </div>

</div>

@endsection
