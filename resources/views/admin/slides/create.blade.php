@extends('admin.layouts.app')

@section('page_title', 'ເພີ່ມ Slide ໃໝ່')

@section('content')
<div class="max-w-4xl mx-auto">

  {{-- Breadcrumb --}}
  <div class="flex items-center gap-2 text-xs text-outline mb-4">
    <a href="{{ route('admin.slides.index') }}" class="hover:text-primary transition-colors">Hero Slides</a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <span>ເພີ່ມໃໝ່</span>
  </div>

  <form action="{{ route('admin.slides.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
    @csrf

    {{-- ── ຮູບ Slide ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-image text-primary text-xs"></i> ຮູບພາບ Slide <span class="text-red-500">*</span>
      </h3>

      {{-- Preview --}}
      <div id="preview-wrap" class="hidden mb-4">
        <img id="preview-img" src="" alt="preview"
             class="w-full max-h-52 object-cover rounded-lg border border-surface-container-high">
      </div>

      <div x-data="{ mode: 'upload' }" class="space-y-3">
        {{-- Tab toggle --}}
        <div class="flex gap-1 border-b border-surface-container-high mb-3">
          <button type="button" @click="mode = 'upload'"
                  :class="mode === 'upload' ? 'border-primary text-primary font-semibold' : 'border-transparent text-outline'"
                  class="px-3 py-1.5 text-xs border-b-2 transition-colors -mb-px">
            <i class="fas fa-upload text-[10px] mr-1"></i> ອັບໂຫຼດໄຟລ໌
          </button>
          <button type="button" @click="mode = 'url'"
                  :class="mode === 'url' ? 'border-primary text-primary font-semibold' : 'border-transparent text-outline'"
                  class="px-3 py-1.5 text-xs border-b-2 transition-colors -mb-px">
            <i class="fas fa-link text-[10px] mr-1"></i> ໃສ່ URL
          </button>
        </div>

        <div x-show="mode === 'upload'">
          <input type="file" name="image_file" id="image_file" accept="image/*"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
          <p class="text-xs text-outline mt-1">JPG, PNG, WEBP — ບໍ່ເກີນ 4 MB · ແນະນຳ: 1920×800 px</p>
          @error('image_file')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div x-show="mode === 'url'">
          <input type="text" name="image_url" id="image_url_input"
                 value="{{ old('image_url') }}"
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

      {{-- Tag --}}
      <p class="text-xs font-semibold text-outline uppercase tracking-wide mb-2">Tag / ປ້າຍກຳກັບ</p>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
        <div>
          <label class="block text-xs font-semibold mb-1">Tag (ລາວ)</label>
          <input type="text" name="tag_lo" value="{{ old('tag_lo') }}" maxlength="100"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                 placeholder="ເຊັ່ນ: ຂ່າວໃໝ່">
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">Tag (EN)</label>
          <input type="text" name="tag_en" value="{{ old('tag_en') }}" maxlength="100"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                 placeholder="e.g. Latest News">
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">Tag (ZH)</label>
          <input type="text" name="tag_zh" value="{{ old('tag_zh') }}" maxlength="100"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                 placeholder="例如: 最新消息">
        </div>
      </div>

      {{-- Title --}}
      <p class="text-xs font-semibold text-outline uppercase tracking-wide mb-2">ຫົວຂໍ້ <span class="text-red-500">*</span></p>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
        <div>
          <label class="block text-xs font-semibold mb-1">ຫົວຂໍ້ (ລາວ) <span class="text-red-500">*</span></label>
          <input type="text" name="title_lo" value="{{ old('title_lo') }}" maxlength="300"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 @error('title_lo') border-red-400 @enderror"
                 placeholder="ຫົວຂໍ້ Slide" required>
          @error('title_lo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຫົວຂໍ້ (EN)</label>
          <input type="text" name="title_en" value="{{ old('title_en') }}" maxlength="300"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                 placeholder="Slide Title">
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຫົວຂໍ້ (ZH)</label>
          <input type="text" name="title_zh" value="{{ old('title_zh') }}" maxlength="300"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                 placeholder="幻灯片标题">
        </div>
      </div>

      {{-- Subtitle --}}
      <p class="text-xs font-semibold text-outline uppercase tracking-wide mb-2">ຄຳບັນຍາຍ</p>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ຄຳບັນຍາຍ (ລາວ)</label>
          <textarea name="subtitle_lo" rows="3"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                    placeholder="ຄຳອະທິບາຍສັ້ນ...">{{ old('subtitle_lo') }}</textarea>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຄຳບັນຍາຍ (EN)</label>
          <textarea name="subtitle_en" rows="3"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                    placeholder="Short description...">{{ old('subtitle_en') }}</textarea>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຄຳບັນຍາຍ (ZH)</label>
          <textarea name="subtitle_zh" rows="3"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                    placeholder="简短说明...">{{ old('subtitle_zh') }}</textarea>
        </div>
      </div>
    </div>

    {{-- ── ປຸ່ມ CTA ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-mouse-pointer text-primary text-xs"></i> ປຸ່ມ Call-to-Action
      </h3>

      {{-- Button 1 --}}
      <div class="mb-5">
        <p class="text-xs font-semibold text-outline uppercase tracking-wide mb-2">
          <i class="fas fa-circle text-primary text-[8px] mr-1"></i> ປຸ່ມທີ 1 (ຫຼັກ)
        </p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="grid grid-cols-3 gap-2">
            <div>
              <label class="block text-xs font-semibold mb-1">ຊື່ (ລາວ)</label>
              <input type="text" name="btn1_text_lo" value="{{ old('btn1_text_lo') }}" maxlength="80"
                     class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                     placeholder="ອ່ານເພີ່ມ">
            </div>
            <div>
              <label class="block text-xs font-semibold mb-1">EN</label>
              <input type="text" name="btn1_text_en" value="{{ old('btn1_text_en') }}" maxlength="80"
                     class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                     placeholder="Read More">
            </div>
            <div>
              <label class="block text-xs font-semibold mb-1">ZH</label>
              <input type="text" name="btn1_text_zh" value="{{ old('btn1_text_zh') }}" maxlength="80"
                     class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                     placeholder="阅读更多">
            </div>
          </div>
          <div>
            <label class="block text-xs font-semibold mb-1">URL / ລິ້ງ</label>
            <input type="text" name="btn1_url" value="{{ old('btn1_url') }}" maxlength="500"
                   class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                   placeholder="/lo/news ຫຼື https://...">
          </div>
        </div>
      </div>

      {{-- Button 2 --}}
      <div>
        <p class="text-xs font-semibold text-outline uppercase tracking-wide mb-2">
          <i class="fas fa-circle text-outline text-[8px] mr-1"></i> ປຸ່ມທີ 2 (ສອງ)
        </p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="grid grid-cols-3 gap-2">
            <div>
              <label class="block text-xs font-semibold mb-1">ຊື່ (ລາວ)</label>
              <input type="text" name="btn2_text_lo" value="{{ old('btn2_text_lo') }}" maxlength="80"
                     class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                     placeholder="ຕິດຕໍ່ພວກເຮົາ">
            </div>
            <div>
              <label class="block text-xs font-semibold mb-1">EN</label>
              <input type="text" name="btn2_text_en" value="{{ old('btn2_text_en') }}" maxlength="80"
                     class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                     placeholder="Contact Us">
            </div>
            <div>
              <label class="block text-xs font-semibold mb-1">ZH</label>
              <input type="text" name="btn2_text_zh" value="{{ old('btn2_text_zh') }}" maxlength="80"
                     class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                     placeholder="联系我们">
            </div>
          </div>
          <div>
            <label class="block text-xs font-semibold mb-1">URL / ລິ້ງ</label>
            <input type="text" name="btn2_url" value="{{ old('btn2_url') }}" maxlength="500"
                   class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                   placeholder="/lo/contact ຫຼື https://...">
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
          <label class="block text-xs font-semibold mb-1">ລຳດັບ (Sort Order)</label>
          <input type="number" name="sort_order" min="0" max="9999"
                 value="{{ old('sort_order', 0) }}"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
          <p class="text-xs text-outline mt-1">ໜ້ອຍ = ສະແດງກ່ອນ</p>
        </div>
        <div class="flex items-center">
          <label class="flex items-center gap-3 cursor-pointer select-none">
            <input type="checkbox" name="is_active" value="1"
                   {{ old('is_active', true) ? 'checked' : '' }}
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
        <i class="fas fa-save text-xs"></i> ບັນທຶກ
      </button>
      <a href="{{ route('admin.slides.index') }}"
         class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg border border-surface-container-high text-on-surface-variant hover:bg-surface-container transition text-sm">
        <i class="fas fa-times text-xs"></i> ຍົກເລີກ
      </a>
    </div>

  </form>
</div>
@endsection

@push('scripts')
<script>
  // Preview image on file select
  document.getElementById('image_file').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    const wrap = document.getElementById('preview-wrap');
    const img  = document.getElementById('preview-img');
    img.src = URL.createObjectURL(file);
    wrap.classList.remove('hidden');
  });

  // Preview from URL input
  document.getElementById('image_url_input').addEventListener('blur', function () {
    const url = this.value.trim();
    if (!url) return;
    const wrap = document.getElementById('preview-wrap');
    const img  = document.getElementById('preview-img');
    img.src = url;
    wrap.classList.remove('hidden');
  });
</script>
@endpush
