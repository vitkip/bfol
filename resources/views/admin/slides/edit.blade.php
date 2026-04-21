@extends('admin.layouts.app')

@section('page_title', 'ແກ້ໄຂ Slide')

@section('content')
<div class="max-w-4xl mx-auto">

  {{-- Breadcrumb --}}
  <div class="flex items-center gap-2 text-xs text-outline mb-4">
    <a href="{{ route('admin.slides.index') }}" class="hover:text-primary transition-colors">Hero Slides</a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <span class="truncate max-w-[200px]">{{ $slide->title_lo }}</span>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <span>ແກ້ໄຂ</span>
  </div>

  <form action="{{ route('admin.slides.update', $slide) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
    @csrf
    @method('PUT')

    {{-- ── ຮູບ Slide ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-image text-primary text-xs"></i> ຮູບພາບ Slide
      </h3>

      {{-- Current image --}}
      @if($slide->image_url)
      <div class="mb-4">
        <p class="text-xs text-outline mb-2">ຮູບປັດຈຸບັນ:</p>
        <img id="preview-img"
             src="{{ Str::startsWith($slide->image_url, 'http') ? $slide->image_url : asset($slide->image_url) }}"
             class="w-full max-h-52 object-cover rounded-lg border border-surface-container-high"
             alt="ຮູບ Slide">
      </div>
      @else
      <div id="preview-wrap" class="hidden mb-4">
        <img id="preview-img" src="" alt="preview"
             class="w-full max-h-52 object-cover rounded-lg border border-surface-container-high">
      </div>
      @endif

      <div x-data="{ mode: 'upload' }" class="space-y-3">
        <div class="flex gap-1 border-b border-surface-container-high mb-3">
          <button type="button" @click="mode = 'upload'"
                  :class="mode === 'upload' ? 'border-primary text-primary font-semibold' : 'border-transparent text-outline'"
                  class="px-3 py-1.5 text-xs border-b-2 transition-colors -mb-px">
            <i class="fas fa-upload text-[10px] mr-1"></i> ອັບໂຫຼດໃໝ່
          </button>
          <button type="button" @click="mode = 'url'"
                  :class="mode === 'url' ? 'border-primary text-primary font-semibold' : 'border-transparent text-outline'"
                  class="px-3 py-1.5 text-xs border-b-2 transition-colors -mb-px">
            <i class="fas fa-link text-[10px] mr-1"></i> ປ່ຽນ URL
          </button>
        </div>

        <div x-show="mode === 'upload'">
          <input type="file" name="image_file" id="image_file" accept="image/*"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
          <p class="text-xs text-outline mt-1">ອັບໂຫຼດໃໝ່ຈຶ່ງຈະທຳການປ່ຽນຮູບ · 1920×800 px, ສູງສຸດ 4 MB</p>
          @error('image_file')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div x-show="mode === 'url'">
          <input type="text" name="image_url" id="image_url_input"
                 value="{{ old('image_url', $slide->image_url) }}"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 @error('image_url') border-red-400 @enderror"
                 placeholder="https://example.com/image.jpg">
          @error('image_url')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
      </div>
    </div>

    {{-- ── ຫົວຂໍ້ & Tag ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-heading text-primary text-xs"></i> ຫົວຂໍ້ ແລະ Tag
      </h3>

      <p class="text-xs font-semibold text-outline uppercase tracking-wide mb-2">Tag</p>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
        <div>
          <label class="block text-xs font-semibold mb-1">Tag (ລາວ)</label>
          <input type="text" name="tag_lo" value="{{ old('tag_lo', $slide->tag_lo) }}" maxlength="100"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">Tag (EN)</label>
          <input type="text" name="tag_en" value="{{ old('tag_en', $slide->tag_en) }}" maxlength="100"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">Tag (ZH)</label>
          <input type="text" name="tag_zh" value="{{ old('tag_zh', $slide->tag_zh) }}" maxlength="100"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
      </div>

      <p class="text-xs font-semibold text-outline uppercase tracking-wide mb-2">ຫົວຂໍ້ <span class="text-red-500">*</span></p>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
        <div>
          <label class="block text-xs font-semibold mb-1">ຫົວຂໍ້ (ລາວ) <span class="text-red-500">*</span></label>
          <input type="text" name="title_lo" value="{{ old('title_lo', $slide->title_lo) }}" maxlength="300"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 @error('title_lo') border-red-400 @enderror"
                 required>
          @error('title_lo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຫົວຂໍ້ (EN)</label>
          <input type="text" name="title_en" value="{{ old('title_en', $slide->title_en) }}" maxlength="300"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຫົວຂໍ້ (ZH)</label>
          <input type="text" name="title_zh" value="{{ old('title_zh', $slide->title_zh) }}" maxlength="300"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
      </div>

      <p class="text-xs font-semibold text-outline uppercase tracking-wide mb-2">ຄຳບັນຍາຍ</p>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ຄຳບັນຍາຍ (ລາວ)</label>
          <textarea name="subtitle_lo" rows="3"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">{{ old('subtitle_lo', $slide->subtitle_lo) }}</textarea>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຄຳບັນຍາຍ (EN)</label>
          <textarea name="subtitle_en" rows="3"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">{{ old('subtitle_en', $slide->subtitle_en) }}</textarea>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຄຳບັນຍາຍ (ZH)</label>
          <textarea name="subtitle_zh" rows="3"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">{{ old('subtitle_zh', $slide->subtitle_zh) }}</textarea>
        </div>
      </div>
    </div>

    {{-- ── ປຸ່ມ CTA ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-mouse-pointer text-primary text-xs"></i> ປຸ່ມ Call-to-Action
      </h3>

      <div class="mb-5">
        <p class="text-xs font-semibold text-outline uppercase tracking-wide mb-2">
          <i class="fas fa-circle text-primary text-[8px] mr-1"></i> ປຸ່ມທີ 1 (ຫຼັກ)
        </p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="grid grid-cols-3 gap-2">
            <div>
              <label class="block text-xs font-semibold mb-1">ຊື່ (ລາວ)</label>
              <input type="text" name="btn1_text_lo" value="{{ old('btn1_text_lo', $slide->btn1_text_lo) }}" maxlength="80"
                     class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
            </div>
            <div>
              <label class="block text-xs font-semibold mb-1">EN</label>
              <input type="text" name="btn1_text_en" value="{{ old('btn1_text_en', $slide->btn1_text_en) }}" maxlength="80"
                     class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
            </div>
            <div>
              <label class="block text-xs font-semibold mb-1">ZH</label>
              <input type="text" name="btn1_text_zh" value="{{ old('btn1_text_zh', $slide->btn1_text_zh) }}" maxlength="80"
                     class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
            </div>
          </div>
          <div>
            <label class="block text-xs font-semibold mb-1">URL / ລິ້ງ</label>
            <input type="text" name="btn1_url" value="{{ old('btn1_url', $slide->btn1_url) }}" maxlength="500"
                   class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
          </div>
        </div>
      </div>

      <div>
        <p class="text-xs font-semibold text-outline uppercase tracking-wide mb-2">
          <i class="fas fa-circle text-outline text-[8px] mr-1"></i> ປຸ່ມທີ 2
        </p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="grid grid-cols-3 gap-2">
            <div>
              <label class="block text-xs font-semibold mb-1">ຊື່ (ລາວ)</label>
              <input type="text" name="btn2_text_lo" value="{{ old('btn2_text_lo', $slide->btn2_text_lo) }}" maxlength="80"
                     class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
            </div>
            <div>
              <label class="block text-xs font-semibold mb-1">EN</label>
              <input type="text" name="btn2_text_en" value="{{ old('btn2_text_en', $slide->btn2_text_en) }}" maxlength="80"
                     class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
            </div>
            <div>
              <label class="block text-xs font-semibold mb-1">ZH</label>
              <input type="text" name="btn2_text_zh" value="{{ old('btn2_text_zh', $slide->btn2_text_zh) }}" maxlength="80"
                     class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
            </div>
          </div>
          <div>
            <label class="block text-xs font-semibold mb-1">URL / ລິ້ງ</label>
            <input type="text" name="btn2_url" value="{{ old('btn2_url', $slide->btn2_url) }}" maxlength="500"
                   class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
          </div>
        </div>
      </div>
    </div>

    {{-- ── ການຕັ້ງຄ່າ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-sliders-h text-primary text-xs"></i> ການຕັ້ງຄ່າ
      </h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
          <label class="block text-xs font-semibold mb-1">ລຳດັບ</label>
          <input type="number" name="sort_order" min="0" max="9999"
                 value="{{ old('sort_order', $slide->sort_order) }}"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
          <p class="text-xs text-outline mt-1">ໜ້ອຍ = ສະແດງກ່ອນ</p>
        </div>
        <div class="flex items-center">
          <label class="flex items-center gap-3 cursor-pointer select-none">
            <input type="checkbox" name="is_active" value="1"
                   {{ old('is_active', $slide->is_active) ? 'checked' : '' }}
                   class="w-4 h-4 rounded accent-primary">
            <div>
              <p class="text-sm font-semibold">ໃຊ້ງານ (Active)</p>
              <p class="text-xs text-outline">Slide ນີ້ຈະສະແດງໃນໜ້າຫຼັກ</p>
            </div>
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
      <a href="{{ route('admin.slides.show', $slide) }}"
         class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg border border-surface-container-high text-on-surface-variant hover:bg-surface-container transition text-sm">
        <i class="fas fa-times text-xs"></i> ຍົກເລີກ
      </a>
    </div>

  </form>
</div>
@endsection

@push('scripts')
<script>
  // Live preview when new file selected
  document.getElementById('image_file').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    document.getElementById('preview-img').src = URL.createObjectURL(file);
  });

  // Preview from URL
  const urlInput = document.getElementById('image_url_input');
  if (urlInput) {
    urlInput.addEventListener('blur', function () {
      const url = this.value.trim();
      if (url) document.getElementById('preview-img').src = url;
    });
  }
</script>
@endpush
