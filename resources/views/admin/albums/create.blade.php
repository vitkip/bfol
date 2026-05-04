@extends('admin.layouts.app')

@section('page_title', 'ສ້າງອາລ໌ບໍ້')

@section('content')
<div class="max-w-3xl mx-auto">

  <div class="flex items-center gap-2 text-xs text-outline mb-4">
    <a href="{{ route('admin.albums.index') }}" class="hover:text-primary">ອາລ໌ບໍ້ຮູບ</a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <span>ສ້າງໃໝ່</span>
  </div>

  <form action="{{ route('admin.albums.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
    @csrf

    {{-- ── ຊື່ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-images text-primary text-xs"></i> ຊື່ອາລ໌ບໍ້
      </h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (ລາວ) <span class="text-red-500">*</span></label>
          <input type="text" name="title_lo" value="{{ old('title_lo') }}" maxlength="200" required
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 @error('title_lo') border-red-400 @enderror"
                 placeholder="ຊື່ອາລ໌ບໍ້">
          @error('title_lo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (EN)</label>
          <input type="text" name="title_en" value="{{ old('title_en') }}" maxlength="200"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                 placeholder="Album title">
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (ZH)</label>
          <input type="text" name="title_zh" value="{{ old('title_zh') }}" maxlength="200"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                 placeholder="相册名称">
        </div>
      </div>
    </div>

    {{-- ── ຄຳອະທິບາຍ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-align-left text-primary text-xs"></i> ຄຳອະທິບາຍ
      </h3>
      @foreach([['lo','ລາວ','ຄຳອະທິບາຍ...'],['en','EN','Description...'],['zh','ZH','描述...']] as [$lang,$lbl,$ph])
        <div class="mb-3">
          <label class="block text-xs font-semibold mb-1">ຄຳອະທິບາຍ ({{ $lbl }})</label>
          <textarea name="description_{{ $lang }}" rows="2"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                    placeholder="{{ $ph }}">{{ old('description_'.$lang) }}</textarea>
        </div>
      @endforeach
    </div>

    {{-- ── ຮູບ Cover ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5"
         x-data="{ preview: '' }">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-image text-primary text-xs"></i> ຮູບໜ້າປົກ
      </h3>
      <label class="block w-full cursor-pointer">
        <div class="border-2 border-dashed border-surface-container-high rounded-xl p-6 text-center hover:border-primary/40 transition-colors"
             :class="preview ? 'border-primary/30' : ''">
          <template x-if="!preview">
            <div>
              <i class="fas fa-cloud-upload-alt text-2xl text-outline/50 mb-2"></i>
              <p class="text-xs text-outline">ຄລິກ ຫຼື ລາກຮູບ Cover (JPG/PNG ສູງສຸດ 4MB)</p>
            </div>
          </template>
          <template x-if="preview">
            <img :src="preview" class="max-h-40 mx-auto rounded-lg object-contain" />
          </template>
        </div>
        <input type="file" name="cover_file" accept="image/*" class="hidden"
               @change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : ''" />
      </label>
      @error('cover_file')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    {{-- ── ອັບໂຫຼດຮູບ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5"
         x-data="{ files: [], previews: [] }">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-photo-video text-primary text-xs"></i> ຮູບໃນອາລ໌ບໍ້
        <span class="text-xs text-outline font-normal">(ສາມາດເພີ່ມຮູບໄດ້ຫຼາຍຮູບ)</span>
      </h3>
      <label class="block w-full cursor-pointer">
        <div class="border-2 border-dashed border-surface-container-high rounded-xl p-6 text-center hover:border-primary/40 transition-colors">
          <i class="fas fa-images text-3xl text-outline/40 mb-2"></i>
          <p class="text-sm font-semibold text-on-surface-variant mb-1">ຄລິກ ຫຼື ລາກຮູບ</p>
          <p class="text-xs text-outline">JPG/PNG — ສາມາດເລືອກຫຼາຍຮູບພ້ອມກັນ</p>
        </div>
        <input type="file" name="images[]" accept="image/*" multiple class="hidden"
               @change="
                 files = Array.from($event.target.files);
                 previews = files.map(f => URL.createObjectURL(f));
               " />
      </label>
      <div x-show="previews.length" class="mt-4 grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-2">
        <template x-for="(p,i) in previews" :key="i">
          <div class="aspect-square rounded-lg overflow-hidden bg-surface-container">
            <img :src="p" class="w-full h-full object-cover" />
          </div>
        </template>
      </div>
      <p x-show="files.length" class="text-xs text-outline mt-2" x-text="files.length + ' ຮູບທີ່ເລືອກ'"></p>
      @error('images.*')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    {{-- ── ຕົວເລືອກ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-cog text-primary text-xs"></i> ຕົວເລືອກ
      </h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ກິດຈະກຳ (ຖ້າມີ)</label>
          <select name="event_id" class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
            <option value="">— ບໍ່ລະບຸ —</option>
            @foreach($events as $ev)
              <option value="{{ $ev->id }}" {{ old('event_id') == $ev->id ? 'selected' : '' }}>{{ $ev->title_lo }}</option>
            @endforeach
          </select>
        </div>
        <div class="flex items-center">
          <label class="flex items-center gap-3 cursor-pointer select-none">
            <div class="relative">
              <input type="checkbox" name="is_public" value="1" class="sr-only peer" {{ old('is_public') ? 'checked' : '' }}>
              <div class="w-10 h-5 bg-surface-container-high rounded-full peer-checked:bg-primary transition-colors"></div>
              <div class="absolute left-0.5 top-0.5 w-4 h-4 bg-white rounded-full shadow transition-all peer-checked:left-5.5 peer-checked:translate-x-full"></div>
            </div>
            <span class="text-sm font-medium">ສາທາລະນະ (ເຫັນໃນໜ້າ front)</span>
          </label>
        </div>
      </div>
    </div>

    {{-- Actions --}}
    <div class="flex items-center justify-end gap-3 pt-2">
      <a href="{{ route('admin.albums.index') }}"
         class="px-5 py-2.5 text-sm rounded-lg border border-surface-container-high hover:bg-surface-container transition-colors">
        ຍົກເລີກ
      </a>
      <button type="submit"
              class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold text-white primary-gradient rounded-lg hover:opacity-90 transition">
        <i class="fas fa-save text-xs"></i> ສ້າງອາລ໌ບໍ້
      </button>
    </div>

  </form>
</div>
@endsection
