@extends('admin.layouts.app')

@section('page_title', 'ແກ້ໄຂ MOU')

@section('content')
<div class="max-w-3xl mx-auto">

  {{-- Breadcrumb --}}
  <div class="flex items-center gap-2 text-xs text-outline mb-4">
    <a href="{{ route('admin.mou.index') }}" class="hover:text-primary">ສັນຍາ MOU</a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <a href="{{ route('admin.mou.show', $mou) }}" class="hover:text-primary truncate max-w-[200px]">{{ $mou->title_lo }}</a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <span>ແກ້ໄຂ</span>
  </div>

  <form action="{{ route('admin.mou.update', $mou) }}" method="POST" enctype="multipart/form-data" class="space-y-5"
        x-data="{ docMode: '{{ $mou->document_url ? 'current' : 'none' }}', fileName: '', fileSize: '', replacing: false }">
    @csrf @method('PUT')

    {{-- ── ຊື່ MOU ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-file-signature text-primary text-xs"></i> ຊື່ MOU
      </h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (ລາວ) <span class="text-red-500">*</span></label>
          <input type="text" name="title_lo" value="{{ old('title_lo', $mou->title_lo) }}" maxlength="300" required
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 @error('title_lo') border-red-400 @enderror">
          @error('title_lo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (EN)</label>
          <input type="text" name="title_en" value="{{ old('title_en', $mou->title_en) }}" maxlength="300"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (ZH)</label>
          <input type="text" name="title_zh" value="{{ old('title_zh', $mou->title_zh) }}" maxlength="300"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
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
              <option value="{{ $partner->id }}" {{ old('partner_org_id', $mou->partner_org_id) == $partner->id ? 'selected' : '' }}>
                {{ $partner->acronym ? "[{$partner->acronym}] " : '' }}{{ $partner->name_lo }}
              </option>
            @endforeach
          </select>
          @error('partner_org_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ວັນທີລົງນາມ <span class="text-red-500">*</span></label>
          <input type="date" name="signed_date" value="{{ old('signed_date', $mou->signed_date?->format('Y-m-d')) }}" required
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 @error('signed_date') border-red-400 @enderror">
          @error('signed_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ວັນໝົດອາຍຸ</label>
          <input type="date" name="expiry_date" value="{{ old('expiry_date', $mou->expiry_date?->format('Y-m-d')) }}"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
          @error('expiry_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
      </div>
      <div>
        <label class="block text-xs font-semibold mb-2">ສະຖານະ <span class="text-red-500">*</span></label>
        <div class="flex flex-wrap gap-2">
          @foreach($statuses as $key => $meta)
            <label class="flex items-center gap-2 px-3 py-2 rounded-lg border cursor-pointer transition-colors
                          {{ old('status', $mou->status) === $key ? 'border-primary bg-primary/5' : 'border-surface-container-high hover:bg-surface-container-low' }}">
              <input type="radio" name="status" value="{{ $key }}" class="accent-primary"
                     {{ old('status', $mou->status) === $key ? 'checked' : '' }}>
              <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full {{ $meta['class'] }}">
                <i class="fas {{ $meta['icon'] }} text-[8px]"></i> {{ $meta['lo'] }}
              </span>
            </label>
          @endforeach
        </div>
      </div>
    </div>

    {{-- ── ເອກະສານ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-paperclip text-primary text-xs"></i> ເອກະສານ MOU
      </h3>

      @if($mou->document_url)
        {{-- ເອກະສານປັດຈຸບັນ --}}
        <div x-show="!replacing" class="flex items-center gap-4 p-4 rounded-xl border border-surface-container-high bg-surface-container-low mb-4">
          <div class="w-12 h-12 rounded-lg bg-red-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-file-pdf text-red-500 text-xl"></i>
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-on-surface">ເອກະສານປັດຈຸບັນ</p>
            <p class="text-xs text-outline truncate mt-0.5">{{ basename($mou->document_url) }}</p>
          </div>
          <div class="flex gap-2 flex-shrink-0">
            <a href="{{ $mou->document_url }}" target="_blank"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-blue-700 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors">
              <i class="fas fa-external-link-alt text-[10px]"></i> ເປີດ
            </a>
            <button type="button" @click="replacing=true"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg border border-surface-container-high hover:bg-surface-container transition-colors">
              <i class="fas fa-exchange-alt text-[10px]"></i> ປ່ຽນ
            </button>
          </div>
        </div>

        {{-- ປ່ຽນເອກະສານ --}}
        <div x-show="replacing" x-transition class="space-y-3">
          <div class="flex items-center justify-between">
            <p class="text-xs text-amber-600 flex items-center gap-1.5">
              <i class="fas fa-exclamation-triangle"></i> ເອກະສານເກົ່າຈະຖືກລຶບ ແລະ ແທນທີ່
            </p>
            <button type="button" @click="replacing=false; fileName=''; fileSize=''"
                    class="text-xs text-outline hover:text-red-500">
              <i class="fas fa-times"></i> ຍົກເລີກ
            </button>
          </div>

          {{-- file/url toggle --}}
          <div class="flex gap-2">
            <button type="button" @click="docMode='file'"
                    :class="docMode==='file' ? 'primary-gradient text-white' : 'border-surface-container-high hover:bg-surface-container-low'"
                    class="px-3 py-1.5 text-xs font-semibold rounded-lg border transition-colors">
              <i class="fas fa-upload text-[10px] mr-1"></i> ໄຟລ໌ໃໝ່
            </button>
            <button type="button" @click="docMode='url'"
                    :class="docMode==='url' ? 'primary-gradient text-white' : 'border-surface-container-high hover:bg-surface-container-low'"
                    class="px-3 py-1.5 text-xs font-semibold rounded-lg border transition-colors">
              <i class="fas fa-link text-[10px] mr-1"></i> URL ໃໝ່
            </button>
          </div>

          <div x-show="docMode==='file'" class="relative border-2 border-dashed border-surface-container-high rounded-xl hover:border-primary/40 transition-colors cursor-pointer">
            <label class="flex flex-col items-center justify-center gap-2 py-6 cursor-pointer" for="doc_file_edit">
              <i class="fas fa-cloud-upload-alt text-2xl text-outline"></i>
              <p class="text-sm text-on-surface-variant">ກົດເພື່ອເລືອກໄຟລ໌</p>
              <p class="text-xs text-outline">PDF, Word · ສູງສຸດ 20 MB</p>
              <input type="file" id="doc_file_edit" name="doc_file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
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
          <div x-show="docMode==='url'">
            <input type="url" name="doc_url" value="{{ old('doc_url') }}"
                   placeholder="https://drive.google.com/..."
                   class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
          </div>

          {{-- Clear document --}}
          <label class="flex items-center gap-2 cursor-pointer select-none">
            <input type="checkbox" name="clear_document" value="1" class="w-4 h-4 rounded accent-red-500">
            <span class="text-xs text-red-600 font-semibold">ລຶບເອກະສານ (ບໍ່ຄ້າງໄວ້)</span>
          </label>
        </div>

      @else
        {{-- ບໍ່ມີເອກະສານ: ສະເໜີ toggle --}}
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
        <div x-show="docMode==='file'" x-transition class="space-y-3">
          <div class="relative border-2 border-dashed border-surface-container-high rounded-xl hover:border-primary/40 transition-colors cursor-pointer">
            <label class="flex flex-col items-center justify-center gap-2 py-8 cursor-pointer" for="doc_file_new">
              <i class="fas fa-file-pdf text-2xl text-red-400"></i>
              <p class="text-sm text-on-surface-variant">ກົດເພື່ອເລືອກໄຟລ໌</p>
              <p class="text-xs text-outline">PDF, Word · ສູງສຸດ 20 MB</p>
              <input type="file" id="doc_file_new" name="doc_file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                     accept=".pdf,.doc,.docx"
                     @change="const f=$event.target.files[0]; if(f){ fileName=f.name; fileSize=(f.size>=1048576?Math.round(f.size/1048576*10)/10+' MB':Math.ceil(f.size/1024)+' KB'); }">
            </label>
          </div>
          <div x-show="fileName" class="flex items-center gap-3 p-3 rounded-lg bg-surface-container-low border border-surface-container-high">
            <i class="fas fa-file-pdf text-red-500 text-sm"></i>
            <p class="text-sm font-semibold truncate flex-1" x-text="fileName"></p>
            <p class="text-xs text-outline" x-text="fileSize"></p>
          </div>
        </div>
        <div x-show="docMode==='url'" x-transition>
          <input type="url" name="doc_url" value="{{ old('doc_url') }}"
                 placeholder="https://drive.google.com/..."
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
        @error('doc_file')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        @error('doc_url')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
      @endif
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
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 resize-y">{{ old('description_lo', $mou->description_lo) }}</textarea>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ລາຍລະອຽດ (EN)</label>
          <textarea name="description_en" rows="4"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 resize-y">{{ old('description_en', $mou->description_en) }}</textarea>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ລາຍລະອຽດ (ZH)</label>
          <textarea name="description_zh" rows="4"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 resize-y">{{ old('description_zh', $mou->description_zh) }}</textarea>
        </div>
      </div>
    </div>

    {{-- ── ຜູ້ລົງນາມ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-user-check text-primary text-xs"></i> ຜູ້ລົງນາມ
      </h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ຜູ້ລົງນາມ (ລາວ)</label>
          <textarea name="signers_lo" rows="3"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 resize-y">{{ old('signers_lo', $mou->signers_lo) }}</textarea>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຜູ້ລົງນາມ (EN)</label>
          <textarea name="signers_en" rows="3"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 resize-y">{{ old('signers_en', $mou->signers_en) }}</textarea>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຜູ້ລົງນາມ (ZH)</label>
          <textarea name="signers_zh" rows="3"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 resize-y">{{ old('signers_zh', $mou->signers_zh) }}</textarea>
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
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 resize-y">{{ old('scope_lo', $mou->scope_lo) }}</textarea>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຂອບເຂດ (EN)</label>
          <textarea name="scope_en" rows="4"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 resize-y">{{ old('scope_en', $mou->scope_en) }}</textarea>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຂອບເຂດ (ZH)</label>
          <textarea name="scope_zh" rows="4"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 resize-y">{{ old('scope_zh', $mou->scope_zh) }}</textarea>
        </div>
      </div>
    </div>

    {{-- Actions --}}
    <div class="flex gap-3">
      <button type="submit"
              class="inline-flex items-center gap-2 primary-gradient text-white font-semibold px-6 py-2.5 rounded-lg hover:opacity-90 transition text-sm">
        <i class="fas fa-save text-xs"></i> ບັນທຶກການປ່ຽນແປງ
      </button>
      <a href="{{ route('admin.mou.show', $mou) }}"
         class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg border border-surface-container-high text-on-surface-variant hover:bg-surface-container transition text-sm">
        <i class="fas fa-times text-xs"></i> ຍົກເລີກ
      </a>
    </div>

  </form>
</div>
@endsection
