@extends('admin.layouts.app')

@section('page_title', 'ແກ້ໄຂກິດຈະກຳ')

@section('content')
<div class="max-w-3xl mx-auto">
  <div class="bg-surface-container-lowest rounded-xl shadow p-6 mb-6">
    <h2 class="text-lg font-bold mb-4">ແກ້ໄຂກິດຈະກຳ</h2>
    <form action="{{ route('admin.events.update', $event) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
      @csrf
      @method('PUT')

      {{-- ຊື່ກິດຈະກຳ --}}
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label for="title_lo" class="block text-sm font-semibold mb-1">ຊື່ກິດຈະກຳ (ພາສາລາວ) <span class="text-red-500">*</span></label>
          <input type="text" name="title_lo" id="title_lo" class="w-full rounded-lg border border-surface-container-high px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary/30" value="{{ old('title_lo', $event->title_lo) }}" required>
          @error('title_lo')<div class="text-red-600 text-xs mt-1">{{ $message }}</div>@enderror
        </div>
        <div>
          <label for="title_en" class="block text-sm font-semibold mb-1">ຊື່ກິດຈະກຳ (ພາສາອັງກິດ) <span class="text-red-500">*</span></label>
          <input type="text" name="title_en" id="title_en" class="w-full rounded-lg border border-surface-container-high px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary/30" value="{{ old('title_en', $event->title_en) }}" required>
          @error('title_en')<div class="text-red-600 text-xs mt-1">{{ $message }}</div>@enderror
        </div>
        <div>
          <label for="title_zh" class="block text-sm font-semibold mb-1">ຊື່ກິດຈະກຳ (ພາສາຈີນ) <span class="text-red-500">*</span></label>
          <input type="text" name="title_zh" id="title_zh" class="w-full rounded-lg border border-surface-container-high px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary/30" value="{{ old('title_zh', $event->title_zh) }}" required>
          @error('title_zh')<div class="text-red-600 text-xs mt-1">{{ $message }}</div>@enderror
        </div>
      </div>

      {{-- ຄຳອະທິບາຍ --}}
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label for="description_lo" class="block text-sm font-semibold mb-1">ຄຳອະທິບາຍ (ພາສາລາວ)</label>
          <textarea name="description_lo" id="description_lo" rows="3" class="w-full rounded-lg border border-surface-container-high px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary/30">{{ old('description_lo', $event->description_lo) }}</textarea>
          @error('description_lo')<div class="text-red-600 text-xs mt-1">{{ $message }}</div>@enderror
        </div>
        <div>
          <label for="description_en" class="block text-sm font-semibold mb-1">ຄຳອະທິບາຍ (ພາສາອັງກິດ)</label>
          <textarea name="description_en" id="description_en" rows="3" class="w-full rounded-lg border border-surface-container-high px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary/30">{{ old('description_en', $event->description_en) }}</textarea>
          @error('description_en')<div class="text-red-600 text-xs mt-1">{{ $message }}</div>@enderror
        </div>
        <div>
          <label for="description_zh" class="block text-sm font-semibold mb-1">ຄຳອະທິບາຍ (ພາສາຈີນ)</label>
          <textarea name="description_zh" id="description_zh" rows="3" class="w-full rounded-lg border border-surface-container-high px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary/30">{{ old('description_zh', $event->description_zh) }}</textarea>
          @error('description_zh')<div class="text-red-600 text-xs mt-1">{{ $message }}</div>@enderror
        </div>
      </div>

      {{-- ວັນທີ --}}
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label for="start_date" class="block text-sm font-semibold mb-1">ວັນທີເລີ່ມຕົ້ນ <span class="text-red-500">*</span></label>
          <input type="date" name="start_date" id="start_date" class="w-full rounded-lg border border-surface-container-high px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary/30" value="{{ old('start_date', $event->start_date?->format('Y-m-d')) }}" required>
          @error('start_date')<div class="text-red-600 text-xs mt-1">{{ $message }}</div>@enderror
        </div>
        <div>
          <label for="end_date" class="block text-sm font-semibold mb-1">ວັນທີສິ້ນສຸດ</label>
          <input type="date" name="end_date" id="end_date" class="w-full rounded-lg border border-surface-container-high px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary/30" value="{{ old('end_date', $event->end_date?->format('Y-m-d')) }}">
          @error('end_date')<div class="text-red-600 text-xs mt-1">{{ $message }}</div>@enderror
        </div>
      </div>

      {{-- ສະຖານທີ --}}
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label for="location_lo" class="block text-sm font-semibold mb-1">ສະຖານທີ (ພາສາລາວ)</label>
          <input type="text" name="location_lo" id="location_lo" class="w-full rounded-lg border border-surface-container-high px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary/30" value="{{ old('location_lo', $event->location_lo) }}">
        </div>
        <div>
          <label for="location_en" class="block text-sm font-semibold mb-1">ສະຖານທີ (ພາສາອັງກິດ)</label>
          <input type="text" name="location_en" id="location_en" class="w-full rounded-lg border border-surface-container-high px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary/30" value="{{ old('location_en', $event->location_en) }}">
        </div>
        <div>
          <label for="location_zh" class="block text-sm font-semibold mb-1">ສະຖານທີ (ພາສາຈີນ)</label>
          <input type="text" name="location_zh" id="location_zh" class="w-full rounded-lg border border-surface-container-high px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary/30" value="{{ old('location_zh', $event->location_zh) }}">
        </div>
      </div>

      {{-- ສະຖານະ ແລະ ຮູບປົກ --}}
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label for="status" class="block text-sm font-semibold mb-1">ສະຖານະ</label>
          <select name="status" id="status" class="w-full rounded-lg border border-surface-container-high px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary/30">
            <option value="upcoming"  {{ old('status', $event->status) === 'upcoming'  ? 'selected' : '' }}>ກຳລັງຈະມາເຖິງ</option>
            <option value="ongoing"   {{ old('status', $event->status) === 'ongoing'   ? 'selected' : '' }}>ກຳລັງດຳເນີນ</option>
            <option value="completed" {{ old('status', $event->status) === 'completed' ? 'selected' : '' }}>ສຳເລັດແລ້ວ</option>
            <option value="cancelled" {{ old('status', $event->status) === 'cancelled' ? 'selected' : '' }}>ຍົກເລີກ</option>
          </select>
        </div>
        <div>
          <label for="thumbnail" class="block text-sm font-semibold mb-1">ຮູບປົກ</label>
          @if($event->thumbnail)
            <img src="{{ asset('storage/' . $event->thumbnail) }}" class="h-16 rounded mb-2 object-cover" alt="ຮູບປົກ">
          @endif
          <input type="file" name="thumbnail" id="thumbnail" accept="image/*" class="w-full rounded-lg border border-surface-container-high px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary/30">
          @error('thumbnail')<div class="text-red-600 text-xs mt-1">{{ $message }}</div>@enderror
        </div>
      </div>

      {{-- ຕົວເລືອກເພີ່ມເຕີມ --}}
      <div class="flex items-center gap-6">
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $event->is_featured) ? 'checked' : '' }} class="rounded">
          <span class="text-sm font-semibold">ກິດຈະກຳແນະນຳ</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="checkbox" name="is_international" value="1" {{ old('is_international', $event->is_international) ? 'checked' : '' }} class="rounded">
          <span class="text-sm font-semibold">ກິດຈະກຳສາກົນ</span>
        </label>
      </div>

      <div class="flex gap-2 mt-4">
        <button type="submit" class="primary-gradient text-white font-semibold px-5 py-2 rounded-lg hover:opacity-90 transition">ບັນທຶກການປ່ຽນແປງ</button>
        <a href="{{ route('admin.events.index') }}" class="px-5 py-2 rounded-lg border border-surface-container-high text-outline hover:bg-surface-container transition">ກັບຄືນ</a>
      </div>
    </form>
  </div>
</div>
@endsection
