@extends('admin.layouts.app')

@section('page_title', 'ເພີ່ມສື່ທຳ')

@section('content')
<div class="max-w-4xl mx-auto"
     x-data="{
       type: '{{ old('type','image') }}',
       platform: '{{ old('platform','local') }}',
       isLocal() { return this.platform === 'local'; },
       isYoutube() { return this.platform === 'youtube'; },
       hasThumb() { return ['video','audio','document'].includes(this.type); },
     }">

  {{-- Breadcrumb --}}
  <div class="flex items-center gap-2 text-xs text-outline mb-4">
    <a href="{{ route('admin.media.index') }}" class="hover:text-primary">ສື່ທຳ</a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <span>ເພີ່ມໃໝ່</span>
  </div>

  <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
    @csrf

    {{-- ── ປະເພດ & Platform ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-sliders-h text-primary text-xs"></i> ປະເພດ ແລະ Platform
      </h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-semibold mb-2">ປະເພດສື່ <span class="text-red-500">*</span></label>
          <div class="grid grid-cols-2 gap-2">
            @foreach(['image'=>['ຮູບພາບ','fa-image','blue'],'video'=>['ວິດີໂອ','fa-video','purple'],'audio'=>['ສຽງ','fa-music','green'],'document'=>['ເອກະສານ','fa-file-alt','orange']] as $val=>[$lbl,$ico,$clr])
            <label class="flex items-center gap-2 p-2.5 rounded-lg border cursor-pointer transition-colors"
                   :class="type === '{{ $val }}' ? 'border-primary bg-primary/5 text-primary font-semibold' : 'border-surface-container-high hover:bg-surface-container-low'">
              <input type="radio" name="type" value="{{ $val }}" x-model="type" class="sr-only">
              <i class="fas {{ $ico }} text-xs w-4 text-center"></i>
              <span class="text-xs">{{ $lbl }}</span>
            </label>
            @endforeach
          </div>
          @error('type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="block text-xs font-semibold mb-2">Platform <span class="text-red-500">*</span></label>
          <div class="grid grid-cols-2 gap-2">
            @foreach(['local'=>['Local','fa-server'],'youtube'=>['YouTube','fa-youtube'],'facebook'=>['Facebook','fa-facebook'],'soundcloud'=>['SoundCloud','fa-soundcloud'],'other'=>['Other','fa-link']] as $val=>[$lbl,$ico])
            <label class="flex items-center gap-2 p-2.5 rounded-lg border cursor-pointer transition-colors"
                   :class="platform === '{{ $val }}' ? 'border-primary bg-primary/5 text-primary font-semibold' : 'border-surface-container-high hover:bg-surface-container-low'">
              <input type="radio" name="platform" value="{{ $val }}" x-model="platform" class="sr-only">
              <i class="fab {{ $ico }} text-xs w-4 text-center"></i>
              <span class="text-xs">{{ $lbl }}</span>
            </label>
            @endforeach
          </div>
          @error('platform')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
      </div>
    </div>

    {{-- ── ໄຟລ໌ / External URL ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-upload text-primary text-xs"></i> ໄຟລ໌ / URL
      </h3>

      {{-- Local upload --}}
      <div x-show="isLocal()" x-transition>
        <label class="block text-xs font-semibold mb-1">ອັບໂຫຼດໄຟລ໌ <span class="text-red-500">*</span></label>
        <input type="file" name="file_upload" id="file_upload" accept="*/*"
               class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        <p class="text-xs text-outline mt-1">ສູງສຸດ 100 MB</p>
        @error('file_upload')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
      </div>

      {{-- External URL --}}
      <div x-show="!isLocal()" x-transition>
        <label class="block text-xs font-semibold mb-1">External URL <span class="text-red-500">*</span></label>
        <input type="text" name="external_url" value="{{ old('external_url') }}"
               class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 @error('external_url') border-red-400 @enderror"
               placeholder="https://www.youtube.com/watch?v=...">
        {{-- YouTube embed preview --}}
        <div x-show="isYoutube() && '{{ old('external_url') }}'">
          <p class="text-xs text-outline mt-1">
            <i class="fab fa-youtube text-red-500"></i> ຕ້ອງໃຊ້ Link YouTube ແບບ: https://youtu.be/ID ຫຼື https://www.youtube.com/watch?v=ID
          </p>
        </div>
        @error('external_url')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
      </div>

      {{-- Thumbnail --}}
      <div class="mt-4" x-show="hasThumb() || !isLocal()">
        <label class="block text-xs font-semibold mb-1">Thumbnail (ຮູບຕົວຢ່າງ)</label>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <p class="text-xs text-outline mb-1">ອັບໂຫຼດໄຟລ໌ຮູບ</p>
            <input type="file" name="thumbnail_file" accept="image/*"
                   class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
            @error('thumbnail_file')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
          </div>
          <div>
            <p class="text-xs text-outline mb-1">ຫຼື URL ຮູບ</p>
            <input type="text" name="thumbnail_url" value="{{ old('thumbnail_url') }}"
                   class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                   placeholder="https://...">
          </div>
        </div>
      </div>
    </div>

    {{-- ── ຊື່ & ລາຍລະອຽດ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-heading text-primary text-xs"></i> ຊື່ ແລະ ລາຍລະອຽດ
      </h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (ລາວ) <span class="text-red-500">*</span></label>
          <input type="text" name="title_lo" value="{{ old('title_lo') }}" maxlength="300"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 @error('title_lo') border-red-400 @enderror"
                 required>
          @error('title_lo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (EN)</label>
          <input type="text" name="title_en" value="{{ old('title_en') }}" maxlength="300"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (ZH)</label>
          <input type="text" name="title_zh" value="{{ old('title_zh') }}" maxlength="300"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ລາຍລະອຽດ (ລາວ)</label>
          <textarea name="description_lo" rows="3"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">{{ old('description_lo') }}</textarea>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ລາຍລະອຽດ (EN)</label>
          <textarea name="description_en" rows="3"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">{{ old('description_en') }}</textarea>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ລາຍລະອຽດ (ZH)</label>
          <textarea name="description_zh" rows="3"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">{{ old('description_zh') }}</textarea>
        </div>
      </div>
    </div>

    {{-- ── ໝວດໝູ່ & ກິດຈະກຳ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-layer-group text-primary text-xs"></i> ການຈັດໝວດໝູ່
      </h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ໝວດໝູ່</label>
          <select name="category_id"
                  class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
            <option value="">— ບໍ່ລະບຸ —</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                {{ $cat->name_lo }}
              </option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ກິດຈະກຳທີ່ກ່ຽວຂ້ອງ</label>
          <select name="event_id"
                  class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
            <option value="">— ບໍ່ລະບຸ —</option>
            @foreach($events as $ev)
              <option value="{{ $ev->id }}" {{ old('event_id') == $ev->id ? 'selected' : '' }}>
                {{ $ev->title_lo }}
              </option>
            @endforeach
          </select>
        </div>
      </div>
    </div>

    {{-- ── ຂໍ້ມູນເພີ່ມ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-info-circle text-primary text-xs"></i> ຂໍ້ມູນເພີ່ມເຕີມ
      </h3>
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ໄລຍະເວລາ (ວິນາທີ)</label>
          <input type="number" name="duration_sec" min="0" value="{{ old('duration_sec') }}"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                 placeholder="0">
          <p class="text-xs text-outline mt-0.5">ສຳລັບ video/audio</p>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ວັນທີ-ເວລາເຜີຍແຜ່</label>
          <input type="datetime-local" name="published_at" value="{{ old('published_at') }}"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
        <div class="flex flex-col gap-3 justify-end pb-1">
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="publish_now" value="1" {{ old('publish_now') ? 'checked' : '' }} class="w-4 h-4 rounded accent-primary">
            <span class="text-xs font-semibold">ເຜີຍແຜ່ທັນທີ</span>
          </label>
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="w-4 h-4 rounded accent-primary">
            <span class="text-xs font-semibold">ສື່ທຳແນະນຳ ⭐</span>
          </label>
        </div>
      </div>
    </div>

    {{-- Actions --}}
    <div class="flex gap-3">
      <button type="submit"
              class="inline-flex items-center gap-2 primary-gradient text-white font-semibold px-6 py-2.5 rounded-lg hover:opacity-90 transition text-sm">
        <i class="fas fa-save text-xs"></i> ບັນທຶກ
      </button>
      <a href="{{ route('admin.media.index') }}"
         class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg border border-surface-container-high text-on-surface-variant hover:bg-surface-container transition text-sm">
        <i class="fas fa-times text-xs"></i> ຍົກເລີກ
      </a>
    </div>

  </form>
</div>
@endsection
