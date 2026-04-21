@extends('admin.layouts.app')

@section('page_title', 'ແກ້ໄຂສື່ທຳ')

@section('content')
<div class="max-w-4xl mx-auto"
     x-data="{
       type: '{{ old('type', $medium->type) }}',
       platform: '{{ old('platform', $medium->platform) }}',
       isLocal() { return this.platform === 'local'; },
       hasThumb() { return ['video','audio','document'].includes(this.type); },
     }">

  {{-- Breadcrumb --}}
  <div class="flex items-center gap-2 text-xs text-outline mb-4">
    <a href="{{ route('admin.media.index') }}" class="hover:text-primary">ສື່ທຳ</a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <a href="{{ route('admin.media.show', $medium) }}" class="hover:text-primary truncate max-w-[200px]">{{ $medium->title_lo }}</a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <span>ແກ້ໄຂ</span>
  </div>

  <form action="{{ route('admin.media.update', $medium) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
    @csrf
    @method('PUT')

    {{-- ── ປະເພດ & Platform ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-sliders-h text-primary text-xs"></i> ປະເພດ ແລະ Platform
      </h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-semibold mb-2">ປະເພດສື່ <span class="text-red-500">*</span></label>
          <div class="grid grid-cols-2 gap-2">
            @foreach(['image'=>['ຮູບພາບ','fa-image'],'video'=>['ວິດີໂອ','fa-video'],'audio'=>['ສຽງ','fa-music'],'document'=>['ເອກະສານ','fa-file-alt']] as $val=>[$lbl,$ico])
            <label class="flex items-center gap-2 p-2.5 rounded-lg border cursor-pointer transition-colors"
                   :class="type === '{{ $val }}' ? 'border-primary bg-primary/5 text-primary font-semibold' : 'border-surface-container-high hover:bg-surface-container-low'">
              <input type="radio" name="type" value="{{ $val }}" x-model="type" class="sr-only">
              <i class="fas {{ $ico }} text-xs w-4 text-center"></i>
              <span class="text-xs">{{ $lbl }}</span>
            </label>
            @endforeach
          </div>
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
        </div>
      </div>
    </div>

    {{-- ── ໄຟລ໌ / URL ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-upload text-primary text-xs"></i> ໄຟລ໌ / URL
      </h3>

      {{-- Current file info --}}
      @if($medium->file_url)
        <div class="flex items-center gap-3 p-3 rounded-lg bg-surface-container-low border border-surface-container-high mb-4">
          <i class="fas fa-file text-outline text-sm"></i>
          <div class="flex-1 min-w-0">
            <p class="text-xs font-semibold text-on-surface">ໄຟລ໌ປັດຈຸບັນ</p>
            <p class="text-xs text-outline truncate">{{ $medium->file_url }}</p>
          </div>
          @if($medium->mime_type)
            <span class="text-[10px] bg-surface-container px-1.5 py-0.5 rounded font-mono">{{ $medium->mime_type }}</span>
          @endif
          @if($medium->file_size_kb)
            <span class="text-[10px] text-outline">{{ $medium->file_size_kb >= 1024 ? round($medium->file_size_kb/1024,1).' MB' : $medium->file_size_kb.' KB' }}</span>
          @endif
        </div>
      @endif

      {{-- Local --}}
      <div x-show="isLocal()" x-transition>
        <label class="block text-xs font-semibold mb-1">ອັບໂຫຼດໄຟລ໌ໃໝ່ (ທາງເລືອກ)</label>
        <input type="file" name="file_upload" accept="*/*"
               class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        <p class="text-xs text-outline mt-1">ອັບໂຫຼດໃໝ່ຈຶ່ງຈະທຳການປ່ຽນໄຟລ໌ · ສູງສຸດ 100 MB</p>
      </div>

      {{-- External --}}
      <div x-show="!isLocal()" x-transition>
        <label class="block text-xs font-semibold mb-1">External URL</label>
        <input type="text" name="external_url"
               value="{{ old('external_url', $medium->external_url) }}"
               class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
               placeholder="https://www.youtube.com/watch?v=...">
      </div>

      {{-- Thumbnail --}}
      <div class="mt-4">
        <label class="block text-xs font-semibold mb-2">Thumbnail</label>
        @if($medium->thumbnail_url)
          <div class="mb-2">
            <img src="{{ Str::startsWith($medium->thumbnail_url,'http') ? $medium->thumbnail_url : asset($medium->thumbnail_url) }}"
                 class="h-20 rounded-lg object-cover border border-surface-container-high" alt="thumb">
            <p class="text-xs text-outline mt-1">Thumbnail ປັດຈຸບັນ</p>
          </div>
        @endif
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <p class="text-xs text-outline mb-1">ອັບໂຫຼດໃໝ່</p>
            <input type="file" name="thumbnail_file" accept="image/*"
                   class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
          </div>
          <div>
            <p class="text-xs text-outline mb-1">ຫຼື URL</p>
            <input type="text" name="thumbnail_url"
                   value="{{ old('thumbnail_url', $medium->thumbnail_url) }}"
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
          <input type="text" name="title_lo" value="{{ old('title_lo', $medium->title_lo) }}" maxlength="300"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 @error('title_lo') border-red-400 @enderror"
                 required>
          @error('title_lo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (EN)</label>
          <input type="text" name="title_en" value="{{ old('title_en', $medium->title_en) }}" maxlength="300"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (ZH)</label>
          <input type="text" name="title_zh" value="{{ old('title_zh', $medium->title_zh) }}" maxlength="300"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ລາຍລະອຽດ (ລາວ)</label>
          <textarea name="description_lo" rows="3"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">{{ old('description_lo', $medium->description_lo) }}</textarea>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ລາຍລະອຽດ (EN)</label>
          <textarea name="description_en" rows="3"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">{{ old('description_en', $medium->description_en) }}</textarea>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ລາຍລະອຽດ (ZH)</label>
          <textarea name="description_zh" rows="3"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">{{ old('description_zh', $medium->description_zh) }}</textarea>
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
          <select name="category_id" class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
            <option value="">— ບໍ່ລະບຸ —</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}" {{ old('category_id', $medium->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name_lo }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ກິດຈະກຳທີ່ກ່ຽວຂ້ອງ</label>
          <select name="event_id" class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
            <option value="">— ບໍ່ລະບຸ —</option>
            @foreach($events as $ev)
              <option value="{{ $ev->id }}" {{ old('event_id', $medium->event_id) == $ev->id ? 'selected' : '' }}>{{ $ev->title_lo }}</option>
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
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ໄລຍະເວລາ (ວິນາທີ)</label>
          <input type="number" name="duration_sec" min="0"
                 value="{{ old('duration_sec', $medium->duration_sec) }}"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ວັນທີ-ເວລາເຜີຍແຜ່</label>
          <input type="datetime-local" name="published_at"
                 value="{{ old('published_at', $medium->published_at?->format('Y-m-d\TH:i')) }}"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
        <div class="flex flex-col gap-3 justify-end pb-1">
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="publish_now" value="1" class="w-4 h-4 rounded accent-primary">
            <span class="text-xs font-semibold">ເຜີຍແຜ່ທັນທີ</span>
          </label>
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="is_featured" value="1"
                   {{ old('is_featured', $medium->is_featured) ? 'checked' : '' }}
                   class="w-4 h-4 rounded accent-primary">
            <span class="text-xs font-semibold">ສື່ທຳແນະນຳ ⭐</span>
          </label>
        </div>
      </div>
    </div>

    {{-- Actions --}}
    <div class="flex gap-3">
      <button type="submit"
              class="inline-flex items-center gap-2 primary-gradient text-white font-semibold px-6 py-2.5 rounded-lg hover:opacity-90 transition text-sm">
        <i class="fas fa-save text-xs"></i> ບັນທຶກການປ່ຽນແປງ
      </button>
      <a href="{{ route('admin.media.show', $medium) }}"
         class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg border border-surface-container-high text-on-surface-variant hover:bg-surface-container transition text-sm">
        <i class="fas fa-times text-xs"></i> ຍົກເລີກ
      </a>
    </div>

  </form>
</div>
@endsection
