@extends('admin.layouts.app')

@section('page_title', 'ເພີ່ມ MOU')

@section('content')
<div class="max-w-3xl mx-auto">

  {{-- Breadcrumb --}}
  <div class="flex items-center gap-2 text-xs text-outline mb-4">
    <a href="{{ route('admin.mou.index') }}" class="hover:text-primary">ສັນຍາ MOU</a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <span>ເພີ່ມໃໝ່</span>
  </div>

  @if($partners->isEmpty())
    <div class="flex items-start gap-3 bg-amber-50 border border-amber-200 text-amber-800 rounded-xl px-4 py-3 mb-5 text-sm">
      <i class="fas fa-exclamation-triangle mt-0.5"></i>
      <div>
        ຍັງບໍ່ມີອົງກອນຄູ່ຮ່ວມງານ.
        <a href="{{ route('admin.partners.create') }}" class="font-semibold underline ml-1">ເພີ່ມອົງກອນກ່ອນ</a>
      </div>
    </div>
  @endif

  <form action="{{ route('admin.mou.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5"
        x-data="{ docMode: 'none', fileName: '', fileSize: '' }">
    @csrf

    {{-- ── ຊື່ MOU ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-file-signature text-primary text-xs"></i> ຊື່ MOU
      </h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (ລາວ) <span class="text-red-500">*</span></label>
          <input type="text" name="title_lo" value="{{ old('title_lo') }}" maxlength="300" required
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 @error('title_lo') border-red-400 @enderror"
                 placeholder="ຊື່ສັນຍາ MOU">
          @error('title_lo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (EN)</label>
          <input type="text" name="title_en" value="{{ old('title_en') }}" maxlength="300"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                 placeholder="MOU Title">
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (ZH)</label>
          <input type="text" name="title_zh" value="{{ old('title_zh') }}" maxlength="300"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                 placeholder="谅解备忘录标题">
        </div>
      </div>
    </div>

    {{-- ── ອົງກອນ & ຂໍ້ມູນຫຼັກ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-building text-primary text-xs"></i> ຂໍ້ມູນຫຼັກ
      </h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
        <div class="sm:col-span-2">
          <label class="block text-xs font-semibold mb-1">ອົງກອນຄູ່ຮ່ວມງານ <span class="text-red-500">*</span></label>
          <select name="partner_org_id" required
                  class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 @error('partner_org_id') border-red-400 @enderror">
            <option value="">— ເລືອກອົງກອນ —</option>
            @foreach($partners as $partner)
              <option value="{{ $partner->id }}" {{ old('partner_org_id') == $partner->id ? 'selected' : '' }}>
                {{ $partner->acronym ? "[{$partner->acronym}] " : '' }}{{ $partner->name_lo }}
              </option>
            @endforeach
          </select>
          @error('partner_org_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ວັນທີລົງນາມ <span class="text-red-500">*</span></label>
          <input type="date" name="signed_date" value="{{ old('signed_date') }}" required
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 @error('signed_date') border-red-400 @enderror">
          @error('signed_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ວັນໝົດອາຍຸ</label>
          <input type="date" name="expiry_date" value="{{ old('expiry_date') }}"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
          <p class="text-xs text-outline mt-0.5">ຫວ່າງ = ບໍ່ກຳນົດອາຍຸ</p>
          @error('expiry_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
      </div>
      <div>
        <label class="block text-xs font-semibold mb-2">ສະຖານະ <span class="text-red-500">*</span></label>
        <div class="flex flex-wrap gap-2">
          @foreach($statuses as $key => $meta)
            <label class="flex items-center gap-2 px-3 py-2 rounded-lg border cursor-pointer transition-colors
                          {{ old('status', 'active') === $key ? 'border-primary bg-primary/5' : 'border-surface-container-high hover:bg-surface-container-low' }}">
              <input type="radio" name="status" value="{{ $key }}" class="accent-primary"
                     {{ old('status', 'active') === $key ? 'checked' : '' }}>
              <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full {{ $meta['class'] }}">
                <i class="fas {{ $meta['icon'] }} text-[8px]"></i> {{ $meta['lo'] }}
              </span>
            </label>
          @endforeach
        </div>
        @error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
      </div>
    </div>

    {{-- ── ເອກະສານ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-paperclip text-primary text-xs"></i> ເອກະສານ MOU
        <span class="text-xs font-normal text-outline">(ທາງເລືອກ)</span>
      </h3>
      {{-- Toggle --}}
      <div class="flex gap-2 mb-4">
        <button type="button" @click="docMode='none'"
                :class="docMode==='none' ? 'primary-gradient text-white' : 'border-surface-container-high hover:bg-surface-container-low'"
                class="px-3 py-1.5 text-xs font-semibold rounded-lg border transition-colors">
          <i class="fas fa-minus text-[10px] mr-1"></i> ບໍ່ມີ
        </button>
        <button type="button" @click="docMode='file'"
                :class="docMode==='file' ? 'primary-gradient text-white' : 'border-surface-container-high hover:bg-surface-container-low'"
                class="px-3 py-1.5 text-xs font-semibold rounded-lg border transition-colors">
          <i class="fas fa-upload text-[10px] mr-1"></i> ອັບໂຫຼດໄຟລ໌
        </button>
        <button type="button" @click="docMode='url'"
                :class="docMode==='url' ? 'primary-gradient text-white' : 'border-surface-container-high hover:bg-surface-container-low'"
                class="px-3 py-1.5 text-xs font-semibold rounded-lg border transition-colors">
          <i class="fas fa-link text-[10px] mr-1"></i> URL ພາຍນອກ
        </button>
      </div>

      {{-- File upload --}}
      <div x-show="docMode==='file'" x-transition class="space-y-3">
        <div class="relative border-2 border-dashed border-surface-container-high rounded-xl hover:border-primary/40 transition-colors cursor-pointer">
          <label class="flex flex-col items-center justify-center gap-2 py-8 cursor-pointer" for="doc_file">
            <i class="fas fa-file-pdf text-2xl text-red-400"></i>
            <p class="text-sm text-on-surface-variant">ກົດເພື່ອເລືອກໄຟລ໌</p>
            <p class="text-xs text-outline">PDF, Word · ສູງສຸດ 20 MB</p>
            <input type="file" id="doc_file" name="doc_file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                   accept=".pdf,.doc,.docx"
                   @change="const f=$event.target.files[0]; if(f){ fileName=f.name; fileSize=(f.size>=1048576?Math.round(f.size/1048576*10)/10+' MB':Math.ceil(f.size/1024)+' KB'); }">
          </label>
        </div>
        <div x-show="fileName" class="flex items-center gap-3 p-3 rounded-lg bg-surface-container-low border border-surface-container-high">
          <i class="fas fa-file-pdf text-red-500 text-sm"></i>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold truncate" x-text="fileName"></p>
            <p class="text-xs text-outline" x-text="fileSize"></p>
          </div>
        </div>
        @error('doc_file')<p class="text-red-500 text-xs flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
      </div>

      {{-- URL --}}
      <div x-show="docMode==='url'" x-transition>
        <input type="url" name="doc_url" value="{{ old('doc_url') }}"
               placeholder="https://drive.google.com/..."
               class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        @error('doc_url')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
      </div>

      <div x-show="docMode==='none'" class="text-xs text-outline italic">
        ສາມາດເພີ່ມເອກະສານໄດ້ຕອນແກ້ໄຂ MOU ໃນພາຍຫຼັງ
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
                    placeholder="ສະຫຼຸບລາຍລະອຽດຂອງ MOU...">{{ old('description_lo') }}</textarea>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ລາຍລະອຽດ (EN)</label>
          <textarea name="description_en" rows="4"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 resize-y"
                    placeholder="MOU summary...">{{ old('description_en') }}</textarea>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ລາຍລະອຽດ (ZH)</label>
          <textarea name="description_zh" rows="4"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 resize-y"
                    placeholder="谅解备忘录摘要...">{{ old('description_zh') }}</textarea>
        </div>
      </div>
    </div>

    {{-- ── ຜູ້ລົງນາມ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-user-check text-primary text-xs"></i> ຜູ້ລົງນາມ
        <span class="text-xs font-normal text-outline">(ລາຍຊື່ຜູ້ທີ່ລົງນາມ)</span>
      </h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ຜູ້ລົງນາມ (ລາວ)</label>
          <textarea name="signers_lo" rows="3"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 resize-y"
                    placeholder="ທ່ານ ...&#10;ຕຳແໜ່ງ ...">{{ old('signers_lo') }}</textarea>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຜູ້ລົງນາມ (EN)</label>
          <textarea name="signers_en" rows="3"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 resize-y"
                    placeholder="Venerable ...&#10;Position ...">{{ old('signers_en') }}</textarea>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຜູ້ລົງນາມ (ZH)</label>
          <textarea name="signers_zh" rows="3"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 resize-y"
                    placeholder="尊者 ...&#10;职位 ...">{{ old('signers_zh') }}</textarea>
        </div>
      </div>
    </div>

    {{-- ── ຂອບເຂດ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-list-ul text-primary text-xs"></i> ຂອບເຂດຄວາມຮ່ວມມື
      </h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ຂອບເຂດ (ລາວ)</label>
          <textarea name="scope_lo" rows="4"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 resize-y"
                    placeholder="ຂອບເຂດການຮ່ວມມື...">{{ old('scope_lo') }}</textarea>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຂອບເຂດ (EN)</label>
          <textarea name="scope_en" rows="4"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 resize-y"
                    placeholder="Scope of cooperation...">{{ old('scope_en') }}</textarea>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຂອບເຂດ (ZH)</label>
          <textarea name="scope_zh" rows="4"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 resize-y"
                    placeholder="合作范围...">{{ old('scope_zh') }}</textarea>
        </div>
      </div>
    </div>

    {{-- Actions --}}
    <div class="flex gap-3">
      <button type="submit" {{ $partners->isEmpty() ? 'disabled' : '' }}
              class="inline-flex items-center gap-2 primary-gradient text-white font-semibold px-6 py-2.5 rounded-lg hover:opacity-90 transition text-sm disabled:opacity-50">
        <i class="fas fa-save text-xs"></i> ບັນທຶກ
      </button>
      <a href="{{ route('admin.mou.index') }}"
         class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg border border-surface-container-high text-on-surface-variant hover:bg-surface-container transition text-sm">
        <i class="fas fa-times text-xs"></i> ຍົກເລີກ
      </a>
    </div>

  </form>
</div>
@endsection
