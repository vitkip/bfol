@extends('admin.layouts.app')

@section('page_title', 'ແກ້ໄຂອາລ໌ບໍ້')

@section('content')
<div class="max-w-4xl mx-auto">

  <div class="flex items-center gap-2 text-xs text-outline mb-4">
    <a href="{{ route('admin.albums.index') }}" class="hover:text-primary">ອາລ໌ບໍ້ຮູບ</a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <span class="truncate max-w-[160px]">{{ $album->title_lo }}</span>
  </div>

  <form action="{{ route('admin.albums.update', $album) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
    @csrf @method('PUT')

    {{-- ── ຊື່ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-images text-primary text-xs"></i> ຊື່ອາລ໌ບໍ້
      </h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (ລາວ) <span class="text-red-500">*</span></label>
          <input type="text" name="title_lo" value="{{ old('title_lo', $album->title_lo) }}" maxlength="200" required
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 @error('title_lo') border-red-400 @enderror">
          @error('title_lo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (EN)</label>
          <input type="text" name="title_en" value="{{ old('title_en', $album->title_en) }}" maxlength="200"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (ZH)</label>
          <input type="text" name="title_zh" value="{{ old('title_zh', $album->title_zh) }}" maxlength="200"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
      </div>
    </div>

    {{-- ── ຄຳອະທິບາຍ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-align-left text-primary text-xs"></i> ຄຳອະທິບາຍ
      </h3>
      @foreach([['lo','ລາວ'],['en','EN'],['zh','ZH']] as [$lang,$lbl])
        <div class="mb-3">
          <label class="block text-xs font-semibold mb-1">ຄຳອະທິບາຍ ({{ $lbl }})</label>
          <textarea name="description_{{ $lang }}" rows="2"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">{{ old('description_'.$lang, $album->{'description_'.$lang}) }}</textarea>
        </div>
      @endforeach
    </div>

    {{-- ── ຮູບ Cover ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5"
         x-data="{ preview: '' }">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-image text-primary text-xs"></i> ຮູບໜ້າປົກ
      </h3>
      @if($album->cover_image)
        <div class="mb-3 flex items-center gap-4">
          <img src="{{ $album->cover_image }}" class="w-32 h-20 object-cover rounded-lg border border-surface-container-high" />
          <p class="text-xs text-outline">ຮູບ Cover ປັດຈຸບັນ — ອັບໂຫຼດໃໝ່ເພື່ອປ່ຽນ</p>
        </div>
      @endif
      <label class="block w-full cursor-pointer">
        <div class="border-2 border-dashed border-surface-container-high rounded-xl p-5 text-center hover:border-primary/40 transition-colors"
             :class="preview ? 'border-primary/30' : ''">
          <template x-if="!preview">
            <div>
              <i class="fas fa-cloud-upload-alt text-xl text-outline/50 mb-1"></i>
              <p class="text-xs text-outline">ຄລິກ ຫຼື ລາກຮູບ Cover ໃໝ່ (ສູງສຸດ 4MB)</p>
            </div>
          </template>
          <template x-if="preview">
            <img :src="preview" class="max-h-32 mx-auto rounded-lg object-contain" />
          </template>
        </div>
        <input type="file" name="cover_file" accept="image/*" class="hidden"
               @change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : ''" />
      </label>
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
              <option value="{{ $ev->id }}" {{ old('event_id', $album->event_id) == $ev->id ? 'selected' : '' }}>{{ $ev->title_lo }}</option>
            @endforeach
          </select>
        </div>
        <div class="flex items-center">
          <label class="flex items-center gap-3 cursor-pointer select-none">
            <div class="relative">
              <input type="checkbox" name="is_public" value="1" class="sr-only peer" {{ old('is_public', $album->is_public) ? 'checked' : '' }}>
              <div class="w-10 h-5 bg-surface-container-high rounded-full peer-checked:bg-primary transition-colors"></div>
              <div class="absolute left-0.5 top-0.5 w-4 h-4 bg-white rounded-full shadow transition-all peer-checked:left-5.5 peer-checked:translate-x-full"></div>
            </div>
            <span class="text-sm font-medium">ສາທາລະນະ (ເຫັນໃນໜ້າ front)</span>
          </label>
        </div>
      </div>
    </div>

    {{-- Actions --}}
    <div class="flex items-center justify-end gap-3">
      <a href="{{ route('admin.albums.index') }}"
         class="px-5 py-2.5 text-sm rounded-lg border border-surface-container-high hover:bg-surface-container transition-colors">
        ຍົກເລີກ
      </a>
      <button type="submit"
              class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold text-white primary-gradient rounded-lg hover:opacity-90 transition">
        <i class="fas fa-save text-xs"></i> ບັນທຶກ
      </button>
    </div>
  </form>

  {{-- ── ຈັດການຮູບໃນອາລ໌ບໍ້ ── --}}
  <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] mt-6 overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-surface-container-high">
      <div>
        <h3 class="font-bold text-sm text-on-surface">ຮູບໃນອາລ໌ບໍ້</h3>
        <p class="text-xs text-outline mt-0.5">{{ $album->images->count() }} ຮູບ</p>
      </div>
      {{-- Upload more photos form --}}
      <form action="{{ route('admin.albums.update', $album) }}" method="POST" enctype="multipart/form-data"
            x-data="{ cnt: 0 }">
        @csrf @method('PUT')
        {{-- Hidden fields to keep existing album data --}}
        <input type="hidden" name="title_lo" value="{{ $album->title_lo }}">
        <input type="hidden" name="title_en" value="{{ $album->title_en }}">
        <input type="hidden" name="title_zh" value="{{ $album->title_zh }}">
        <input type="hidden" name="event_id" value="{{ $album->event_id }}">
        <input type="hidden" name="is_public" value="{{ $album->is_public ? '1' : '0' }}">

        <label class="cursor-pointer inline-flex items-center gap-2 text-sm font-semibold text-white primary-gradient px-3 py-2 rounded-lg hover:opacity-90 transition">
          <i class="fas fa-plus text-xs"></i>
          <span x-show="!cnt">ເພີ່ມຮູບ</span>
          <span x-show="cnt" x-text="'ອັບໂຫຼດ ' + cnt + ' ຮູບ'"></span>
          <input type="file" name="images[]" accept="image/*" multiple class="hidden"
                 @change="cnt = $event.target.files.length; if(cnt) $el.closest('form').submit()" />
        </label>
      </form>
    </div>

    @if($album->images->isEmpty())
      <div class="p-10 text-center text-outline">
        <i class="fas fa-photo-video text-3xl opacity-30 mb-2"></i>
        <p class="text-xs">ຍັງບໍ່ມີຮູບ — ຄລິກ "ເພີ່ມຮູບ" ດ້ານເທິງ</p>
      </div>
    @else
      <div class="p-5 grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-3">
        @foreach($album->images as $img)
          <div class="group relative aspect-square rounded-xl overflow-hidden bg-surface-container">
            <img src="{{ $img->image_url }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
            {{-- Delete button --}}
            <form action="{{ route('admin.albums.images.destroy', [$album, $img]) }}" method="POST"
                  class="absolute inset-0 flex items-center justify-center bg-black/0 group-hover:bg-black/40 transition-all"
                  onsubmit="return confirm('ລຶບຮູບນີ້?')">
              @csrf @method('DELETE')
              <button type="submit"
                      class="opacity-0 group-hover:opacity-100 w-8 h-8 rounded-full bg-red-500 text-white flex items-center justify-center transition-all hover:bg-red-600 hover:scale-110">
                <i class="fas fa-trash text-xs"></i>
              </button>
            </form>
            {{-- Sort order badge --}}
            <span class="absolute top-1 left-1 bg-black/50 text-white text-[9px] px-1 rounded">{{ $img->sort_order }}</span>
          </div>
        @endforeach
      </div>
    @endif
  </div>

</div>
@endsection
