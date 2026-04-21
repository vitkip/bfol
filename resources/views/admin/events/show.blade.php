@extends('admin.layouts.app')

@section('page_title', 'ລາຍລະອຽດກິດຈະກຳ')

@section('content')
<div class="max-w-2xl mx-auto">
  <div class="bg-surface-container-lowest rounded-xl shadow p-6 mb-6">
    <h2 class="text-lg font-bold mb-4">ລາຍລະອຽດກິດຈະກຳ</h2>

    {{-- ຮູບປົກ --}}
    @if($event->thumbnail)
      <div class="mb-4">
        <img src="{{ asset('storage/' . $event->thumbnail) }}" class="w-full h-48 object-cover rounded-lg" alt="ຮູບປົກ">
      </div>
    @endif

    <div class="divide-y divide-surface-container-high">

      <div class="flex justify-between py-2">
        <span class="font-semibold text-outline">ລະຫັດ</span>
        <span>{{ $event->id }}</span>
      </div>

      <div class="flex justify-between py-2">
        <span class="font-semibold text-outline">ສະຖານະ</span>
        <span>
          @php
            $statusMap = [
              'upcoming'  => ['label' => 'ກຳລັງຈະມາເຖິງ', 'class' => 'bg-blue-100 text-blue-700'],
              'ongoing'   => ['label' => 'ກຳລັງດຳເນີນ',   'class' => 'bg-green-100 text-green-700'],
              'completed' => ['label' => 'ສຳເລັດແລ້ວ',     'class' => 'bg-gray-100 text-gray-600'],
              'cancelled' => ['label' => 'ຍົກເລີກ',         'class' => 'bg-red-100 text-red-600'],
            ];
            $s = $statusMap[$event->status] ?? ['label' => $event->status, 'class' => 'bg-gray-100 text-gray-600'];
          @endphp
          <span class="px-2 py-1 rounded text-xs font-semibold {{ $s['class'] }}">{{ $s['label'] }}</span>
        </span>
      </div>

      <div class="flex justify-between py-2">
        <span class="font-semibold text-outline">ຊື່ (ລາວ)</span>
        <span>{{ $event->title_lo }}</span>
      </div>

      <div class="flex justify-between py-2">
        <span class="font-semibold text-outline">ຊື່ (ອັງກິດ)</span>
        <span>{{ $event->title_en }}</span>
      </div>

      <div class="flex justify-between py-2">
        <span class="font-semibold text-outline">ຊື່ (ຈີນ)</span>
        <span>{{ $event->title_zh }}</span>
      </div>

      @if($event->description_lo)
      <div class="py-2">
        <span class="font-semibold text-outline block mb-1">ຄຳອະທິບາຍ (ລາວ)</span>
        <p class="text-sm">{{ $event->description_lo }}</p>
      </div>
      @endif

      @if($event->description_en)
      <div class="py-2">
        <span class="font-semibold text-outline block mb-1">ຄຳອະທິບາຍ (ອັງກິດ)</span>
        <p class="text-sm">{{ $event->description_en }}</p>
      </div>
      @endif

      @if($event->description_zh)
      <div class="py-2">
        <span class="font-semibold text-outline block mb-1">ຄຳອະທິບາຍ (ຈີນ)</span>
        <p class="text-sm">{{ $event->description_zh }}</p>
      </div>
      @endif

      <div class="flex justify-between py-2">
        <span class="font-semibold text-outline">ວັນທີເລີ່ມຕົ້ນ</span>
        <span>{{ $event->start_date?->format('d/m/Y') ?? '-' }}</span>
      </div>

      <div class="flex justify-between py-2">
        <span class="font-semibold text-outline">ວັນທີສິ້ນສຸດ</span>
        <span>{{ $event->end_date?->format('d/m/Y') ?? '-' }}</span>
      </div>

      @if($event->location_lo)
      <div class="flex justify-between py-2">
        <span class="font-semibold text-outline">ສະຖານທີ (ລາວ)</span>
        <span>{{ $event->location_lo }}</span>
      </div>
      @endif

      @if($event->location_en)
      <div class="flex justify-between py-2">
        <span class="font-semibold text-outline">ສະຖານທີ (ອັງກິດ)</span>
        <span>{{ $event->location_en }}</span>
      </div>
      @endif

      <div class="flex justify-between py-2">
        <span class="font-semibold text-outline">ກິດຈະກຳແນະນຳ</span>
        <span>{{ $event->is_featured ? 'ແມ່ນ' : 'ບໍ່' }}</span>
      </div>

      <div class="flex justify-between py-2">
        <span class="font-semibold text-outline">ກິດຈະກຳສາກົນ</span>
        <span>{{ $event->is_international ? 'ແມ່ນ' : 'ບໍ່' }}</span>
      </div>

      <div class="flex justify-between py-2">
        <span class="font-semibold text-outline">ຈຳນວນການເບິ່ງ</span>
        <span>{{ number_format($event->view_count) }}</span>
      </div>

      <div class="flex justify-between py-2">
        <span class="font-semibold text-outline">ສ້າງເມື່ອ</span>
        <span>{{ $event->created_at->format('d/m/Y H:i') }}</span>
      </div>

    </div>

    <div class="flex gap-2 mt-6">
      <a href="{{ route('admin.events.edit', $event) }}" class="primary-gradient text-white font-semibold px-5 py-2 rounded-lg hover:opacity-90 transition">ແກ້ໄຂ</a>
      <a href="{{ route('admin.events.index') }}" class="px-5 py-2 rounded-lg border border-surface-container-high text-outline hover:bg-surface-container transition">ກັບຄືນ</a>
    </div>
  </div>
</div>
@endsection
