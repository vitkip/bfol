@extends('admin.layouts.app')

@section('page_title', 'ເພີ່ມ Banner')

@section('content')
<div class="max-w-3xl mx-auto">

  {{-- Breadcrumb --}}
  <div class="flex items-center gap-2 text-xs text-outline mb-4">
    <a href="{{ route('admin.banners.index') }}" class="hover:text-primary">Banners</a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <span>ເພີ່ມໃໝ່</span>
  </div>

  <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
    @csrf

    {{-- ── ຊື່ Banner ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-ad text-primary text-xs"></i> ຊື່ Banner
      </h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (ລາວ) <span class="text-red-500">*</span></label>
          <input type="text" name="title_lo" value="{{ old('title_lo') }}" maxlength="200" required
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 @error('title_lo') border-red-400 @enderror"
                 placeholder="ຫົວຂໍ້ Banner">
          @error('title_lo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (EN)</label>
          <input type="text" name="title_en" value="{{ old('title_en') }}" maxlength="200"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                 placeholder="Banner Title">
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (ZH)</label>
          <input type="text" name="title_zh" value="{{ old('title_zh') }}" maxlength="200"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                 placeholder="横幅标题">
        </div>
      </div>
    </div>

    {{-- ── ຄຳບັນຍາຍ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-align-left text-primary text-xs"></i> ຄຳບັນຍາຍ (Subtitle)
      </h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ລາວ</label>
          <textarea name="subtitle_lo" rows="3"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 resize-y"
                    placeholder="ຄຳອະທິບາຍ...">{{ old('subtitle_lo') }}</textarea>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">EN</label>
          <textarea name="subtitle_en" rows="3"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 resize-y"
                    placeholder="Subtitle...">{{ old('subtitle_en') }}</textarea>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ZH</label>
          <textarea name="subtitle_zh" rows="3"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 resize-y"
                    placeholder="副标题...">{{ old('subtitle_zh') }}</textarea>
        </div>
      </div>
    </div>

    {{-- ── ຮູບພາບ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5"
         x-data="{ imgMode: '{{ old('image_url') ? 'url' : 'file' }}', preview: null }">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-image text-primary text-xs"></i> ຮູບພາບ
        <span class="text-xs font-normal text-outline">(ທາງເລືອກ)</span>
      </h3>

      {{-- Toggle --}}
      <div class="flex gap-2 mb-4">
        <button type="button" @click="imgMode='file'"
                :class="imgMode==='file' ? 'primary-gradient text-white' : 'border border-surface-container-high hover:bg-surface-container'"
                class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors">
          <i class="fas fa-upload text-[10px]"></i> ອັບໂຫຼດໄຟລ໌
        </button>
        <button type="button" @click="imgMode='url'"
                :class="imgMode==='url' ? 'primary-gradient text-white' : 'border border-surface-container-high hover:bg-surface-container'"
                class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors">
          <i class="fas fa-link text-[10px]"></i> URL
        </button>
      </div>

      <div x-show="imgMode==='file'">
        <input type="file" name="image_file" accept="image/*"
               @change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null"
               class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm file:mr-3 file:rounded file:border-0 file:bg-primary/10 file:text-primary file:text-xs file:font-semibold file:px-3 file:py-1 focus:outline-none">
        <template x-if="preview">
          <img :src="preview" class="mt-3 h-28 rounded-lg object-cover border border-surface-container-high">
        </template>
        <p class="text-[11px] text-outline mt-1">JPG, PNG, WebP — ສູງສຸດ 4 MB</p>
      </div>

      <div x-show="imgMode==='url'">
        <input type="text" name="image_url" value="{{ old('image_url') }}" maxlength="500"
               class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
               placeholder="https://example.com/image.jpg">
      </div>
    </div>

    {{-- ── ປຸ່ມ CTA ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-mouse-pointer text-primary text-xs"></i> ປຸ່ມ (Call to Action)
        <span class="text-xs font-normal text-outline">(ທາງເລືອກ)</span>
      </h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ປຸ່ມ (ລາວ)</label>
          <input type="text" name="btn_text_lo" value="{{ old('btn_text_lo') }}" maxlength="80"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                 placeholder="ອ່ານເພີ່ມ">
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ປຸ່ມ (EN)</label>
          <input type="text" name="btn_text_en" value="{{ old('btn_text_en') }}" maxlength="80"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                 placeholder="Read more">
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ປຸ່ມ (ZH)</label>
          <input type="text" name="btn_text_zh" value="{{ old('btn_text_zh') }}" maxlength="80"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                 placeholder="阅读更多">
        </div>
      </div>
      <div>
        <label class="block text-xs font-semibold mb-1">URL ປຸ່ມ</label>
        <input type="text" name="btn_url" value="{{ old('btn_url') }}" maxlength="500"
               class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
               placeholder="/lo/news ຫຼື https://...">
        @error('btn_url')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
      </div>
    </div>

    {{-- ── ການຕັ້ງຄ່າ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-sliders-h text-primary text-xs"></i> ການຕັ້ງຄ່າ
      </h3>

      {{-- Style --}}
      <div class="mb-5">
        <label class="block text-xs font-semibold mb-2">ສີ/ຮູບແບບ <span class="text-red-500">*</span></label>
        <div class="flex flex-wrap gap-2">
          @foreach($styles as $key => $meta)
            <label class="flex items-center gap-2 px-3 py-2 rounded-lg border cursor-pointer transition-colors
                          {{ old('style', 'banner-blue') === $key ? 'border-primary bg-primary/5' : 'border-surface-container-high hover:bg-surface-container-low' }}">
              <input type="radio" name="style" value="{{ $key }}" class="accent-primary"
                     {{ old('style', 'banner-blue') === $key ? 'checked' : '' }}>
              <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2 py-0.5 rounded-full {{ $meta['bg'] }} {{ $meta['text'] }}">
                {{ $meta['lo'] }}
              </span>
            </label>
          @endforeach
        </div>
        @error('style')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
      </div>

      {{-- Position --}}
      <div class="mb-5">
        <label class="block text-xs font-semibold mb-2">ຕຳແໜ່ງ <span class="text-red-500">*</span></label>
        <div class="flex flex-wrap gap-2">
          @foreach($positions as $key => $meta)
            <label class="flex items-center gap-2 px-3 py-2 rounded-lg border cursor-pointer transition-colors
                          {{ old('position', 'sidebar') === $key ? 'border-primary bg-primary/5' : 'border-surface-container-high hover:bg-surface-container-low' }}">
              <input type="radio" name="position" value="{{ $key }}" class="accent-primary"
                     {{ old('position', 'sidebar') === $key ? 'checked' : '' }}>
              <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-on-surface-variant">
                <i class="fas {{ $meta['icon'] }} text-[10px] text-outline"></i> {{ $meta['lo'] }}
              </span>
            </label>
          @endforeach
        </div>
        @error('position')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
      </div>

      {{-- Sort order + Active --}}
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ລຳດັບ (Sort Order)</label>
          <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" max="9999"
                 class="w-full max-w-[120px] rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
          <p class="text-[11px] text-outline mt-1">ຕ່ຳ = ສະແດງກ່ອນ</p>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-2">ສະຖານະ</label>
          <label class="inline-flex items-center gap-2.5 cursor-pointer">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}
                   class="w-4 h-4 accent-primary rounded">
            <span class="text-sm font-semibold text-on-surface">ເປີດໃຊ້ງານ</span>
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
      <a href="{{ route('admin.banners.index') }}"
         class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg border border-surface-container-high text-on-surface-variant hover:bg-surface-container transition text-sm">
        <i class="fas fa-times text-xs"></i> ຍົກເລີກ
      </a>
    </div>

  </form>
</div>
@endsection
