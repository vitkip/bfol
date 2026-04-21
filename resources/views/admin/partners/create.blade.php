@extends('admin.layouts.app')

@section('page_title', 'ເພີ່ມອົງກອນ')

@section('content')
<div class="max-w-3xl mx-auto">

  {{-- Breadcrumb --}}
  <div class="flex items-center gap-2 text-xs text-outline mb-4">
    <a href="{{ route('admin.partners.index') }}" class="hover:text-primary">ອົງກອນຄູ່ຮ່ວມ</a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <span>ເພີ່ມໃໝ່</span>
  </div>

  <form action="{{ route('admin.partners.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5"
        x-data="{ logoMode: 'url', logoPreview: '', fileName: '' }">
    @csrf

    {{-- ── ຊື່ & ຕົວຫຍໍ້ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-building text-primary text-xs"></i> ຊື່ອົງກອນ
      </h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (ລາວ) <span class="text-red-500">*</span></label>
          <input type="text" name="name_lo" value="{{ old('name_lo') }}" maxlength="200" required
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 @error('name_lo') border-red-400 @enderror"
                 placeholder="ຊື່ເຕັມ">
          @error('name_lo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (EN)</label>
          <input type="text" name="name_en" value="{{ old('name_en') }}" maxlength="200"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                 placeholder="Full name">
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (ZH)</label>
          <input type="text" name="name_zh" value="{{ old('name_zh') }}" maxlength="200"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                 placeholder="全称">
        </div>
      </div>
      <div class="max-w-xs">
        <label class="block text-xs font-semibold mb-1">ຕົວຫຍໍ້ (Acronym)</label>
        <input type="text" name="acronym" value="{{ old('acronym') }}" maxlength="30"
               class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
               placeholder="ຕ.ຢ. BFOL, WHO, UNESCO">
        <p class="text-xs text-outline mt-0.5">ຊື່ຫຍໍ້ (ຖ້າມີ)</p>
      </div>
    </div>

    {{-- ── ປະເທດ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-globe text-primary text-xs"></i> ປະເທດ
      </h3>
      <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ລະຫັດປະເທດ <span class="text-red-500">*</span></label>
          <input type="text" name="country_code" value="{{ old('country_code') }}" maxlength="2"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 font-mono uppercase tracking-widest @error('country_code') border-red-400 @enderror"
                 placeholder="CN" required style="text-transform:uppercase">
          <p class="text-xs text-outline mt-0.5">ISO 2 ຕົວອັກສອນ</p>
          @error('country_code')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ປະເທດ (ລາວ) <span class="text-red-500">*</span></label>
          <input type="text" name="country_name_lo" value="{{ old('country_name_lo') }}" maxlength="100" required
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 @error('country_name_lo') border-red-400 @enderror"
                 placeholder="ຈີນ">
          @error('country_name_lo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ປະເທດ (EN)</label>
          <input type="text" name="country_name_en" value="{{ old('country_name_en') }}" maxlength="100"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                 placeholder="China">
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ປະເທດ (ZH)</label>
          <input type="text" name="country_name_zh" value="{{ old('country_name_zh') }}" maxlength="100"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                 placeholder="中国">
        </div>
      </div>
      {{-- Common countries quick-fill --}}
      <div class="mt-3">
        <p class="text-xs text-outline mb-2">ເລືອກດ່ວນ:</p>
        <div class="flex flex-wrap gap-2">
          @foreach([
            ['CN','ຈີນ','China','中国'],
            ['TH','ໄທ','Thailand','泰国'],
            ['MM','ມຽນມາ','Myanmar','缅甸'],
            ['VN','ຫວຽດນາມ','Vietnam','越南'],
            ['KH','ກຳປູເຈຍ','Cambodia','柬埔寨'],
            ['JP','ຍີ່ປຸ່ນ','Japan','日本'],
            ['KR','ເກົາຫຼີ','South Korea','韩国'],
            ['IN','ອິນເດຍ','India','印度'],
            ['LK','ສີລັງກາ','Sri Lanka','斯里兰卡'],
            ['US','ອາເມລິກາ','USA','美国'],
          ] as [$code,$lo,$en,$zh])
            <button type="button"
                    onclick="document.querySelector('[name=country_code]').value='{{ $code }}';document.querySelector('[name=country_name_lo]').value='{{ $lo }}';document.querySelector('[name=country_name_en]').value='{{ $en }}';document.querySelector('[name=country_name_zh]').value='{{ $zh }}';"
                    class="px-2 py-1 text-[10px] font-bold rounded border border-surface-container-high hover:bg-surface-container transition-colors font-mono">
              {{ $code }}
            </button>
          @endforeach
        </div>
      </div>
    </div>

    {{-- ── ໂລໂກ & ເວັບໄຊ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-image text-primary text-xs"></i> ໂລໂກ & ເວັບໄຊ
      </h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        {{-- Logo --}}
        <div>
          <label class="block text-xs font-semibold mb-2">ໂລໂກ</label>
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
          </div>
          <div x-show="logoMode==='url'">
            <input type="text" name="logo_url" value="{{ old('logo_url') }}"
                   placeholder="https://example.com/logo.png"
                   @input="logoPreview=$event.target.value"
                   class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
          </div>
          <div x-show="logoMode==='file'" class="space-y-2">
            <div class="relative border-2 border-dashed border-surface-container-high rounded-xl hover:border-primary/40 transition-colors cursor-pointer">
              <label class="flex flex-col items-center justify-center gap-2 py-5 cursor-pointer" for="logo_file">
                <i class="fas fa-cloud-upload-alt text-xl text-outline"></i>
                <p class="text-xs text-on-surface-variant">ກົດເພື່ອເລືອກໄຟລ໌</p>
                <p class="text-[10px] text-outline">JPG, PNG, SVG · ສູງສຸດ 5MB</p>
                <input type="file" id="logo_file" name="logo_file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                       accept="image/*"
                       @change="const f=$event.target.files[0]; if(f){ fileName=f.name; logoPreview=URL.createObjectURL(f); }">
              </label>
            </div>
            <div x-show="fileName" class="text-xs text-outline truncate" x-text="fileName"></div>
          </div>
          {{-- Preview --}}
          <div x-show="logoPreview" class="mt-2 p-2 bg-white border border-surface-container-high rounded-lg inline-flex">
            <img :src="logoPreview" class="h-12 object-contain" onerror="this.style.display='none'">
          </div>
        </div>
        {{-- Website --}}
        <div>
          <label class="block text-xs font-semibold mb-1">ເວັບໄຊ</label>
          <input type="url" name="website_url" value="{{ old('website_url') }}"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                 placeholder="https://www.example.org">
          @error('website_url')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
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
          <input type="text" name="contact_person" value="{{ old('contact_person') }}" maxlength="200"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                 placeholder="ທ່ານ / ນາງ ...">
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ອີເມວ</label>
          <input type="email" name="contact_email" value="{{ old('contact_email') }}" maxlength="120"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                 placeholder="contact@example.org">
          @error('contact_email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ເບີໂທ</label>
          <input type="tel" name="contact_phone" value="{{ old('contact_phone') }}" maxlength="50"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                 placeholder="+86 10 0000 0000">
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
                       {{ old('type', 'buddhist_org') === $key ? 'checked' : '' }}>
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2 py-0.5 rounded-full {{ $meta['class'] }}">
                  <i class="fas {{ $meta['icon'] }} text-[8px]"></i> {{ $meta['lo'] }}
                </span>
              </label>
            @endforeach
          </div>
          @error('type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="space-y-4">
          <div>
            <label class="block text-xs font-semibold mb-2">ສະຖານະ <span class="text-red-500">*</span></label>
            <div class="flex flex-wrap gap-2">
              @foreach($statuses as $key => $meta)
                <label class="flex items-center gap-2 px-3 py-1.5 rounded-lg border cursor-pointer transition-colors
                              {{ old('status', 'active') === $key ? 'border-primary bg-primary/5' : 'border-surface-container-high hover:bg-surface-container-low' }}">
                  <input type="radio" name="status" value="{{ $key }}" class="accent-primary"
                         {{ old('status', 'active') === $key ? 'checked' : '' }}>
                  <span class="text-xs font-semibold px-1.5 py-0.5 rounded-full {{ $meta['class'] }}">{{ $meta['lo'] }}</span>
                </label>
              @endforeach
            </div>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-semibold mb-1">ຮ່ວມມືຕັ້ງແຕ່ (ປີ)</label>
              <input type="number" name="partnership_since" value="{{ old('partnership_since') }}"
                     min="1900" max="{{ date('Y') }}"
                     class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                     placeholder="{{ date('Y') }}">
            </div>
            <div>
              <label class="block text-xs font-semibold mb-1">ລຳດັບ</label>
              <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}"
                     class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
              <p class="text-xs text-outline mt-0.5">0 = ສະແດງກ່ອນ</p>
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
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 resize-y"
                    placeholder="ລາຍລະອຽດຂອງອົງກອນ...">{{ old('description_lo') }}</textarea>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ລາຍລະອຽດ (EN)</label>
          <textarea name="description_en" rows="4"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 resize-y"
                    placeholder="Organization description...">{{ old('description_en') }}</textarea>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ລາຍລະອຽດ (ZH)</label>
          <textarea name="description_zh" rows="4"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 resize-y"
                    placeholder="组织简介...">{{ old('description_zh') }}</textarea>
        </div>
      </div>
    </div>

    {{-- Actions --}}
    <div class="flex gap-3">
      <button type="submit"
              class="inline-flex items-center gap-2 primary-gradient text-white font-semibold px-6 py-2.5 rounded-lg hover:opacity-90 transition text-sm">
        <i class="fas fa-save text-xs"></i> ບັນທຶກ
      </button>
      <a href="{{ route('admin.partners.index') }}"
         class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg border border-surface-container-high text-on-surface-variant hover:bg-surface-container transition text-sm">
        <i class="fas fa-times text-xs"></i> ຍົກເລີກ
      </a>
    </div>

  </form>
</div>
@endsection
