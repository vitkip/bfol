@extends('admin.layouts.app')

@section('page_title', 'ແກ້ໄຂອົງກອນ')

@section('content')
<div class="max-w-3xl mx-auto">

  {{-- Breadcrumb --}}
  <div class="flex items-center gap-2 text-xs text-outline mb-4">
    <a href="{{ route('admin.partners.index') }}" class="hover:text-primary">ອົງກອນຄູ່ຮ່ວມ</a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <a href="{{ route('admin.partners.show', $partner) }}" class="hover:text-primary truncate max-w-[180px]">{{ $partner->name_lo }}</a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <span>ແກ້ໄຂ</span>
  </div>

  <form action="{{ route('admin.partners.update', $partner) }}" method="POST" enctype="multipart/form-data" class="space-y-5"
        x-data="{ logoMode: '{{ $partner->logo_url ? 'current' : 'url' }}', logoPreview: '{{ $partner->logo_url }}', fileName: '', replacingLogo: false }">
    @csrf @method('PUT')

    {{-- ── ຊື່ & ຕົວຫຍໍ້ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-building text-primary text-xs"></i> ຊື່ອົງກອນ
      </h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (ລາວ) <span class="text-red-500">*</span></label>
          <input type="text" name="name_lo" value="{{ old('name_lo', $partner->name_lo) }}" maxlength="200" required
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 @error('name_lo') border-red-400 @enderror">
          @error('name_lo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (EN)</label>
          <input type="text" name="name_en" value="{{ old('name_en', $partner->name_en) }}" maxlength="200"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (ZH)</label>
          <input type="text" name="name_zh" value="{{ old('name_zh', $partner->name_zh) }}" maxlength="200"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
      </div>
      <div class="max-w-xs">
        <label class="block text-xs font-semibold mb-1">ຕົວຫຍໍ້ (Acronym)</label>
        <input type="text" name="acronym" value="{{ old('acronym', $partner->acronym) }}" maxlength="30"
               class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
      </div>
    </div>

    {{-- ── ປະເທດ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-globe text-primary text-xs"></i> ປະເທດ
      </h3>
      <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ລະຫັດ <span class="text-red-500">*</span></label>
          <input type="text" name="country_code" value="{{ old('country_code', $partner->country_code) }}" maxlength="2" required
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 font-mono uppercase tracking-widest"
                 style="text-transform:uppercase">
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (ລາວ) <span class="text-red-500">*</span></label>
          <input type="text" name="country_name_lo" value="{{ old('country_name_lo', $partner->country_name_lo) }}" maxlength="100" required
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (EN)</label>
          <input type="text" name="country_name_en" value="{{ old('country_name_en', $partner->country_name_en) }}" maxlength="100"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (ZH)</label>
          <input type="text" name="country_name_zh" value="{{ old('country_name_zh', $partner->country_name_zh) }}" maxlength="100"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
      </div>
    </div>

    {{-- ── ໂລໂກ & ເວັບໄຊ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-image text-primary text-xs"></i> ໂລໂກ & ເວັບໄຊ
      </h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
          <label class="block text-xs font-semibold mb-2">ໂລໂກ</label>

          @if($partner->logo_url)
            {{-- ໂລໂກປັດຈຸບັນ --}}
            <div x-show="!replacingLogo" class="flex items-center gap-3 p-3 rounded-lg bg-surface-container-low border border-surface-container-high mb-3">
              <img src="{{ $partner->logo_url }}" class="h-12 w-12 object-contain rounded border border-surface-container-high bg-white"
                   onerror="this.style.display='none'">
              <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold">ໂລໂກປັດຈຸບັນ</p>
                <p class="text-[10px] text-outline truncate">{{ basename($partner->logo_url) }}</p>
              </div>
              <button type="button" @click="replacingLogo=true"
                      class="text-xs font-semibold text-outline hover:text-primary">
                <i class="fas fa-exchange-alt"></i> ປ່ຽນ
              </button>
            </div>
          @endif

          <div x-show="!{{ $partner->logo_url ? 'false' : 'true' }} ? replacingLogo : true" x-transition>
            <div class="flex gap-2 mb-3">
              <button type="button" @click="logoMode='url'"
                      :class="logoMode==='url' ? 'primary-gradient text-white' : 'border-surface-container-high hover:bg-surface-container-low'"
                      class="px-3 py-1.5 text-xs font-semibold rounded-lg border transition-colors">
                <i class="fas fa-link text-[10px] mr-1"></i> URL
              </button>
              <button type="button" @click="logoMode='file'"
                      :class="logoMode==='file' ? 'primary-gradient text-white' : 'border-surface-container-high hover:bg-surface-container-low'"
                      class="px-3 py-1.5 text-xs font-semibold rounded-lg border transition-colors">
                <i class="fas fa-upload text-[10px] mr-1"></i> ໄຟລ໌
              </button>
              @if($partner->logo_url)
              <button type="button" @click="replacingLogo=false; logoPreview='{{ $partner->logo_url }}'"
                      class="px-3 py-1.5 text-xs text-outline rounded-lg border border-surface-container-high hover:bg-surface-container transition-colors">
                ຍົກເລີກ
              </button>
              @endif
            </div>
            <div x-show="logoMode==='url'">
              <input type="text" name="logo_url" value="{{ old('logo_url', $partner->logo_url) }}"
                     placeholder="https://example.com/logo.png"
                     @input="logoPreview=$event.target.value"
                     class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
            </div>
            <div x-show="logoMode==='file'" class="space-y-2">
              <div class="relative border-2 border-dashed border-surface-container-high rounded-xl hover:border-primary/40 transition-colors cursor-pointer">
                <label class="flex flex-col items-center justify-center gap-2 py-5 cursor-pointer" for="logo_file_edit">
                  <i class="fas fa-cloud-upload-alt text-xl text-outline"></i>
                  <p class="text-xs text-on-surface-variant">ກົດເພື່ອເລືອກໄຟລ໌</p>
                  <p class="text-[10px] text-outline">JPG, PNG, SVG · ສູງສຸດ 5MB</p>
                  <input type="file" id="logo_file_edit" name="logo_file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                         accept="image/*"
                         @change="const f=$event.target.files[0]; if(f){ fileName=f.name; logoPreview=URL.createObjectURL(f); }">
                </label>
              </div>
              <p x-show="fileName" class="text-xs text-outline truncate" x-text="fileName"></p>
            </div>
          </div>

          {{-- Preview --}}
          <div x-show="logoPreview" class="mt-2 p-2 bg-white border border-surface-container-high rounded-lg inline-flex">
            <img :src="logoPreview" class="h-12 object-contain" onerror="this.style.display='none'">
          </div>
        </div>

        <div>
          <label class="block text-xs font-semibold mb-1">ເວັບໄຊ</label>
          <input type="url" name="website_url" value="{{ old('website_url', $partner->website_url) }}"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                 placeholder="https://www.example.org">
        </div>
      </div>
    </div>

    {{-- ── ຂໍ້ມູນຕິດຕໍ່ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-address-card text-primary text-xs"></i> ຂໍ້ມູນຕິດຕໍ່
      </h3>
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ຜູ້ຕິດຕໍ່</label>
          <input type="text" name="contact_person" value="{{ old('contact_person', $partner->contact_person) }}" maxlength="200"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ອີເມວ</label>
          <input type="email" name="contact_email" value="{{ old('contact_email', $partner->contact_email) }}" maxlength="120"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ເບີໂທ</label>
          <input type="tel" name="contact_phone" value="{{ old('contact_phone', $partner->contact_phone) }}" maxlength="50"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
      </div>
    </div>

    {{-- ── ການຕັ້ງຄ່າ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-cog text-primary text-xs"></i> ການຕັ້ງຄ່າ
      </h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
        <div>
          <label class="block text-xs font-semibold mb-2">ປະເພດ <span class="text-red-500">*</span></label>
          <div class="space-y-2">
            @foreach($types as $key => $meta)
              <label class="flex items-center gap-2.5 cursor-pointer">
                <input type="radio" name="type" value="{{ $key }}" class="accent-primary"
                       {{ old('type', $partner->type) === $key ? 'checked' : '' }}>
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2 py-0.5 rounded-full {{ $meta['class'] }}">
                  <i class="fas {{ $meta['icon'] }} text-[8px]"></i> {{ $meta['lo'] }}
                </span>
              </label>
            @endforeach
          </div>
        </div>
        <div class="space-y-4">
          <div>
            <label class="block text-xs font-semibold mb-2">ສະຖານະ</label>
            <div class="flex flex-wrap gap-2">
              @foreach($statuses as $key => $meta)
                <label class="flex items-center gap-2 px-3 py-1.5 rounded-lg border cursor-pointer transition-colors
                              {{ old('status', $partner->status) === $key ? 'border-primary bg-primary/5' : 'border-surface-container-high hover:bg-surface-container-low' }}">
                  <input type="radio" name="status" value="{{ $key }}" class="accent-primary"
                         {{ old('status', $partner->status) === $key ? 'checked' : '' }}>
                  <span class="text-xs font-semibold px-1.5 py-0.5 rounded-full {{ $meta['class'] }}">{{ $meta['lo'] }}</span>
                </label>
              @endforeach
            </div>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-semibold mb-1">ຮ່ວມມືຕັ້ງແຕ່ (ປີ)</label>
              <input type="number" name="partnership_since" value="{{ old('partnership_since', $partner->partnership_since) }}"
                     min="1900" max="{{ date('Y') }}"
                     class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
            </div>
            <div>
              <label class="block text-xs font-semibold mb-1">ລຳດັບ</label>
              <input type="number" name="sort_order" value="{{ old('sort_order', $partner->sort_order) }}"
                     class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- ── ລາຍລະອຽດ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-align-left text-primary text-xs"></i> ລາຍລະອຽດ
      </h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ລາຍລະອຽດ (ລາວ)</label>
          <textarea name="description_lo" rows="4"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 resize-y">{{ old('description_lo', $partner->description_lo) }}</textarea>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ລາຍລະອຽດ (EN)</label>
          <textarea name="description_en" rows="4"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 resize-y">{{ old('description_en', $partner->description_en) }}</textarea>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ລາຍລະອຽດ (ZH)</label>
          <textarea name="description_zh" rows="4"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 resize-y">{{ old('description_zh', $partner->description_zh) }}</textarea>
        </div>
      </div>
    </div>

    {{-- Actions --}}
    <div class="flex gap-3">
      <button type="submit"
              class="inline-flex items-center gap-2 primary-gradient text-white font-semibold px-6 py-2.5 rounded-lg hover:opacity-90 transition text-sm">
        <i class="fas fa-save text-xs"></i> ບັນທຶກການປ່ຽນແປງ
      </button>
      <a href="{{ route('admin.partners.show', $partner) }}"
         class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg border border-surface-container-high text-on-surface-variant hover:bg-surface-container transition text-sm">
        <i class="fas fa-times text-xs"></i> ຍົກເລີກ
      </a>
    </div>

  </form>
</div>
@endsection
