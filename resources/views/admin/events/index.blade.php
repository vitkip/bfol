@extends('admin.layouts.app')

@section('page_title', 'ກິດຈະກຳ')

@section('content')
<div class="flex justify-between items-center mb-6">
  <h1 class="text-2xl font-bold">ລາຍການກິດຈະກຳ</h1>
  <a href="{{ route('admin.events.create') }}" class="primary-gradient text-white font-semibold px-5 py-2 rounded-lg hover:opacity-90 transition">+ ເພີ່ມກິດຈະກຳ</a>
</div>

@if(session('success'))
  <div class="mb-4 text-green-700 bg-green-100 rounded p-3">{{ session('success') }}</div>
@endif

<div class="bg-surface-container-lowest rounded-xl shadow overflow-x-auto">
  <table class="min-w-full divide-y divide-surface-container-high">
    <thead>
      <tr>
        <th class="px-4 py-2 text-left">#</th>
        <th class="px-4 py-2 text-left">ຊື່ກິດຈະກຳ (ລາວ)</th>
        <th class="px-4 py-2 text-left">ວັນທີເລີ່ມ</th>
        <th class="px-4 py-2 text-left">ສະຖານະ</th>
        <th class="px-4 py-2 text-left">ການຈັດການ</th>
      </tr>
    </thead>
    <tbody>
      @forelse($events as $event)
      <tr class="border-b border-surface-container-high">
        <td class="px-4 py-2">{{ $event->id }}</td>
        <td class="px-4 py-2">{{ $event->title_lo }}</td>
        <td class="px-4 py-2">{{ $event->start_date?->format('d/m/Y') ?? '-' }}</td>
        <td class="px-4 py-2">
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
        </td>
        <td class="px-4 py-2 flex gap-2">
          <a href="{{ route('admin.events.show', $event) }}" class="text-blue-600 hover:underline text-sm">ລາຍລະອຽດ</a>
          <a href="{{ route('admin.events.edit', $event) }}" class="text-yellow-600 hover:underline text-sm">ແກ້ໄຂ</a>
          <form action="{{ route('admin.events.destroy', $event) }}" method="POST" onsubmit="return confirm('ທ່ານຕ້ອງການລຶບກິດຈະກຳນີ້ແທ້ບໍ?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-600 hover:underline text-sm">ລຶບ</button>
          </form>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="5" class="px-4 py-6 text-center text-outline">ຍັງບໍ່ມີກິດຈະກຳ</td>
      </tr>
      @endforelse
    </tbody>
  </table>
  <div class="p-4">{{ $events->links() }}</div>
</div>
@endsection
