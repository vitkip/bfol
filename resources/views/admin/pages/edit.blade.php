@extends('admin.layouts.app')

@section('page_title', 'ແກ້ໄຂໜ້າຂໍ້ມູນ')

@section('content')
<div class="max-w-4xl mx-auto">

  {{-- Breadcrumb --}}
  <div class="flex items-center gap-2 text-xs text-outline mb-4">
    <a href="{{ route('admin.pages.index') }}" class="hover:text-primary transition-colors">ໜ້າຂໍ້ມູນ</a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <a href="{{ route('admin.pages.show', $page) }}" class="hover:text-primary transition-colors truncate max-w-[200px]">{{ $page->title_lo }}</a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <span>ແກ້ໄຂ</span>
  </div>

  <form action="{{ route('admin.pages.update', $page) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
    @csrf
    @method('PUT')

    {{-- ── ຂໍ້ມູນຫຼັກ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-file-alt text-primary text-xs"></i> ຂໍ້ມູນຫຼັກ
      </h3>

      {{-- ຊື່ --}}
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ໜ້າ (ລາວ) <span class="text-red-500">*</span></label>
          <input type="text" name="title_lo"
                 value="{{ old('title_lo', $page->title_lo) }}"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 @error('title_lo') border-red-400 @enderror"
                 required>
          @error('title_lo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ໜ້າ (ອັງກິດ)</label>
          <input type="text" name="title_en"
                 value="{{ old('title_en', $page->title_en) }}"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ໜ້າ (ຈີນ)</label>
          <input type="text" name="title_zh"
                 value="{{ old('title_zh', $page->title_zh) }}"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
      </div>

      {{-- Slug --}}
      <div class="mb-4">
        <label class="block text-xs font-semibold mb-1">
          Slug (URL)
          <span class="text-outline font-normal ml-1">— ປ່ອຍຫວ່າງເພື່ອຮັກສາ slug ເດີມ</span>
        </label>
        <div class="flex items-center gap-2">
          <span class="text-xs text-outline whitespace-nowrap">/lo/page/</span>
          <input type="text" name="slug"
                 value="{{ old('slug', $page->slug) }}"
                 class="flex-1 rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 @error('slug') border-red-400 @enderror">
        </div>
        <p class="text-xs text-outline mt-1">⚠ ການປ່ຽນ slug ຈະທຳໃຫ້ລິ້ງເກົ່າໃຊ້ບໍ່ໄດ້</p>
        @error('slug')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
      </div>

      {{-- ໜ້າແມ່ ແລະ ລຳດັບ --}}
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ໜ້າແມ່ (Parent)</label>
          <select name="parent_slug"
                  class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 @error('parent_slug') border-red-400 @enderror">
            <option value="">— ບໍ່ມີ (ໜ້າຫຼັກ) —</option>
            @foreach($parentPages as $p)
              <option value="{{ $p->slug }}"
                {{ old('parent_slug', $page->parent_slug) === $p->slug ? 'selected' : '' }}>
                {{ $p->title_lo }} ({{ $p->slug }})
              </option>
            @endforeach
          </select>
          @error('parent_slug')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ລຳດັບການສະແດງ</label>
          <input type="number" name="sort_order" min="0" max="9999"
                 value="{{ old('sort_order', $page->sort_order) }}"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
          <p class="text-xs text-outline mt-1">ໜ້ອຍ = ສະແດງກ່ອນ</p>
        </div>
      </div>
    </div>

    {{-- ── ເນື້ອໃນ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-align-left text-primary text-xs"></i> ເນື້ອໃນ
      </h3>
      <div class="space-y-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ເນື້ອໃນ (ລາວ)</label>
          <textarea name="content_lo" rows="8"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 font-mono">{{ old('content_lo', $page->content_lo) }}</textarea>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ເນື້ອໃນ (ອັງກິດ)</label>
          <textarea name="content_en" rows="6"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 font-mono">{{ old('content_en', $page->content_en) }}</textarea>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ເນື້ອໃນ (ຈີນ)</label>
          <textarea name="content_zh" rows="6"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 font-mono">{{ old('content_zh', $page->content_zh) }}</textarea>
        </div>
      </div>
    </div>

    {{-- ── ຮູບປົກ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-image text-primary text-xs"></i> ຮູບປົກ
      </h3>
      @if($page->thumbnail)
        <div class="mb-3">
          <img src="{{ asset('storage/' . $page->thumbnail) }}"
               class="h-28 rounded-lg object-cover border border-surface-container-high" alt="ຮູບປົກປັດຈຸບັນ">
          <p class="text-xs text-outline mt-1">ຮູບປົກປັດຈຸບັນ — ອັບໂຫຼດໃໝ່ຈຶ່ງຈະທຳການປ່ຽນ</p>
        </div>
      @endif
      <input type="file" name="thumbnail" accept="image/*"
             class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
      <p class="text-xs text-outline mt-1">JPG, PNG, WEBP — ບໍ່ເກີນ 2 MB</p>
      @error('thumbnail')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    {{-- ── SEO Meta ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-search text-primary text-xs"></i> SEO / Meta
      </h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <div>
          <label class="block text-xs font-semibold mb-1">Meta Title (ລາວ)</label>
          <input type="text" name="meta_title_lo"
                 value="{{ old('meta_title_lo', $page->meta_title_lo) }}" maxlength="200"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">Meta Title (EN)</label>
          <input type="text" name="meta_title_en"
                 value="{{ old('meta_title_en', $page->meta_title_en) }}" maxlength="200"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">Meta Title (ZH)</label>
          <input type="text" name="meta_title_zh"
                 value="{{ old('meta_title_zh', $page->meta_title_zh) }}" maxlength="200"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
      </div>
      <div>
        <label class="block text-xs font-semibold mb-1">Meta Description</label>
        <textarea name="meta_description" rows="2" maxlength="500"
                  class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">{{ old('meta_description', $page->meta_description) }}</textarea>
        <p class="text-xs text-outline mt-1" id="meta-count">0 / 500</p>
      </div>
    </div>

    {{-- ── ການເຜີຍແຜ່ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-toggle-on text-primary text-xs"></i> ການເຜີຍແຜ່
      </h3>
      <label class="flex items-center gap-3 cursor-pointer select-none">
        <input type="checkbox" name="is_published" value="1"
               {{ old('is_published', $page->is_published) ? 'checked' : '' }}
               class="w-4 h-4 rounded accent-primary">
        <div>
          <p class="text-sm font-semibold">ເຜີຍແຜ່ທັນທີ</p>
          <p class="text-xs text-outline">ຖ້າບໍ່ໝາຍ ໜ້ານີ້ຈະເປັນສະຖານະຮ່າງ</p>
        </div>
      </label>
    </div>

    {{-- Actions --}}
    <div class="flex gap-3">
      <button type="submit"
              class="inline-flex items-center gap-2 primary-gradient text-white font-semibold px-6 py-2.5 rounded-lg hover:opacity-90 transition text-sm">
        <i class="fas fa-save text-xs"></i> ບັນທຶກການປ່ຽນແປງ
      </button>
      <a href="{{ route('admin.pages.show', $page) }}"
         class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg border border-surface-container-high text-on-surface-variant hover:bg-surface-container transition text-sm">
        <i class="fas fa-times text-xs"></i> ຍົກເລີກ
      </a>
    </div>

  </form>
</div>
@endsection

@push('scripts')
<script>
  const metaDesc  = document.querySelector('[name="meta_description"]');
  const metaCount = document.getElementById('meta-count');
  function updateCount() { metaCount.textContent = metaDesc.value.length + ' / 500'; }
  metaDesc.addEventListener('input', updateCount);
  updateCount();
</script>
@endpush
