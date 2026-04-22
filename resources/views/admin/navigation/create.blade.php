@extends('admin.layouts.app')

@section('page_title', 'ເພີ່ມລາຍການເມນູ')

@section('content')
<div class="max-w-2xl mx-auto">

  <div class="flex items-center gap-2 text-xs text-outline mb-4">
    <a href="{{ route('admin.navigation.index') }}" class="hover:text-primary transition-colors">ຈັດການເມນູ</a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <span>ເພີ່ມໃໝ່</span>
  </div>

  <form action="{{ route('admin.navigation.store') }}" method="POST" class="space-y-5">
    @csrf

    {{-- ── ຊື່ເມນູ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-bars text-primary text-xs"></i> ຊື່ເມນູ (3 ພາສາ)
      </h3>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (ລາວ) <span class="text-red-500">*</span></label>
          <input type="text" name="label_lo" value="{{ old('label_lo') }}" required
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 @error('label_lo') border-red-400 @enderror"
                 placeholder="ຊື່ເມນູ ພາສາລາວ">
          @error('label_lo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (ອັງກິດ)</label>
          <input type="text" name="label_en" value="{{ old('label_en') }}"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                 placeholder="Menu label (EN)">
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (ຈີນ)</label>
          <input type="text" name="label_zh" value="{{ old('label_zh') }}"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                 placeholder="菜单名称 (中文)">
        </div>
      </div>
    </div>

    {{-- ── ການເຊື່ອມຕໍ່ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-link text-primary text-xs"></i> ການເຊື່ອມຕໍ່
      </h3>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-3">
        <div>
          <label class="block text-xs font-semibold mb-1">URL / ເສັ້ນທາງ</label>
          <input type="text" name="url" id="url-input" value="{{ old('url') }}"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                 placeholder="/about/history">
          <p class="text-xs text-outline mt-1">ປ່ອຍຫວ່າງ = ເມນູ dropdown ທີ່ ບໍ່ມີລິ້ງ</p>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ເປີດໃນ</label>
          <select name="target"
                  class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
            <option value="_self" {{ old('target','_self')==='_self' ? 'selected' : '' }}>ໜ້າດຽວກັນ (_self)</option>
            <option value="_blank" {{ old('target')==='_blank' ? 'selected' : '' }}>ໜ້າໃໝ່ (_blank)</option>
          </select>
        </div>
      </div>

      {{-- URL Helper --}}
      <div class="p-3 bg-blue-50 rounded-lg border border-blue-100 mb-4">
        <p class="text-xs font-semibold text-blue-800 mb-2">
          <i class="fas fa-magic text-xs mr-1"></i> ເລືອກ URL ດ່ວນ
        </p>
        <div class="flex flex-wrap gap-1.5 mb-2">
          @foreach([
            ['/', 'ໜ້າຫຼັກ'],
            ['/news', 'ຂ່າວສານ'],
            ['/contact', 'ຕິດຕໍ່'],
            ['/about/history', 'ປະຫວັດ'],
            ['/about/mission', 'ວິໄສທັດ'],
            ['/about/structure', 'ໂຄງສ້າງ'],
            ['/about/committee', 'ຄະນະກຳ'],
            ['/dhamma/sila', 'ສີລ ສະມາທິ'],
            ['/dhamma/teach', 'ດ້ານສອນ'],
            ['/dhamma/research', 'ວິໄຊ'],
            ['/foreign/diplomacy', 'ການທູດ'],
            ['/foreign/exchange', 'ແລກປ່ຽນ'],
            ['/foreign/mou', 'MOU'],
            ['/foreign/aid', 'ຊ່ວຍເຫຼືອ'],
            ['/foreign/education', 'ສຶກສາສາກົນ'],
            ['/media/dhamma-on-len', 'DhammaOnLen'],
            ['/media/online', 'ສອນ Online'],
            ['/media/gallery', 'ຮູບພາບ'],
            ['/media/video', 'ວິດີໂອ'],
            ['/media/documents', 'ເອກະສານ'],
          ] as [$url, $label])
            <button type="button" onclick="setUrl('{{ $url }}')"
                    class="px-2 py-1 text-xs bg-white border border-blue-200 rounded hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-colors text-blue-700">
              {{ $label }}
            </button>
          @endforeach
        </div>
        @if($pages->count())
        <div class="flex items-center gap-2 pt-2 border-t border-blue-100">
          <label class="text-xs text-blue-700 whitespace-nowrap shrink-0">
            <i class="fas fa-file-alt text-xs mr-1"></i> ໜ້າ CMS:
          </label>
          <select onchange="if(this.value) setUrl(this.value); this.value=''"
                  class="flex-1 rounded border border-blue-200 px-2 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-400 bg-white">
            <option value="">— ເລືອກໜ້າ CMS —</option>
            @foreach($pages as $page)
              <option value="/lo/page/{{ $page->slug }}">
                {{ $page->title_lo ?: $page->title_en }} ({{ $page->slug }})
              </option>
            @endforeach
          </select>
        </div>
        @endif
      </div>

      <div>
        <label class="block text-xs font-semibold mb-1">Icon (Font Awesome class)</label>
        <div class="flex items-center gap-2">
          <i id="icon-preview" class="fas fa-bars text-blue-600 w-5 text-center"></i>
          <input type="text" name="icon" id="icon-input" value="{{ old('icon') }}"
                 class="flex-1 rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                 placeholder="fas fa-globe">
        </div>
        <p class="text-xs text-outline mt-1">ໃຊ້ຊື່ class Font Awesome ເຊັ່ນ: <code class="bg-surface-container px-1 rounded">fas fa-globe</code></p>
      </div>
    </div>

    {{-- ── ໂຄງສ້າງ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-sitemap text-primary text-xs"></i> ໂຄງສ້າງ
      </h3>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ເມນູແມ່ (Parent)</label>
          <select name="parent_id"
                  class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
            <option value="">— ບໍ່ມີ (ລະດັບຫຼັກ) —</option>
            @foreach($parents as $p)
              <option value="{{ $p->id }}" {{ old('parent_id') == $p->id ? 'selected' : '' }}>
                {{ $p->label_lo }}
              </option>
            @endforeach
          </select>
          <p class="text-xs text-outline mt-1">ເລືອກ parent ເພື່ອສ້າງ sub-menu</p>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ລຳດັບການສະແດງ</label>
          <input type="number" name="sort_order" min="0" max="9999"
                 value="{{ old('sort_order', 0) }}"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
          <p class="text-xs text-outline mt-1">ໜ້ອຍ = ສະແດງກ່ອນ</p>
        </div>
      </div>
    </div>

    {{-- ── ສະຖານະ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-3 flex items-center gap-2">
        <i class="fas fa-toggle-on text-primary text-xs"></i> ສະຖານະ
      </h3>
      <label class="flex items-center gap-3 cursor-pointer select-none">
        <input type="checkbox" name="is_active" value="1"
               {{ old('is_active', true) ? 'checked' : '' }}
               class="w-4 h-4 rounded accent-primary">
        <div>
          <p class="text-sm font-semibold">ສະແດງໃນເມນູ</p>
          <p class="text-xs text-outline">ຖ້າບໍ່ໝາຍ ລາຍການນີ້ຈະຖືກເຊື່ອງ</p>
        </div>
      </label>
    </div>

    <div class="flex gap-3">
      <button type="submit"
              class="inline-flex items-center gap-2 primary-gradient text-white font-semibold px-6 py-2.5 rounded-lg hover:opacity-90 transition text-sm">
        <i class="fas fa-save text-xs"></i> ບັນທຶກ
      </button>
      <a href="{{ route('admin.navigation.index') }}"
         class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg border border-surface-container-high text-on-surface-variant hover:bg-surface-container transition text-sm">
        <i class="fas fa-times text-xs"></i> ຍົກເລີກ
      </a>
    </div>

  </form>
</div>
@endsection

@push('scripts')
<script>
  document.getElementById('icon-input').addEventListener('input', function() {
    const preview = document.getElementById('icon-preview');
    preview.className = this.value || 'fas fa-bars';
    preview.classList.add('text-blue-600', 'w-5', 'text-center');
  });

  function setUrl(url) {
    document.getElementById('url-input').value = url;
    document.getElementById('url-input').focus();
  }
</script>
@endpush
