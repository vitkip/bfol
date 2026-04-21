@extends('admin.layouts.app')

@section('page_title', 'ເພີ່ມເອກະສານ')

@section('content')
<div class="max-w-3xl mx-auto">

  {{-- Breadcrumb --}}
  <div class="flex items-center gap-2 text-xs text-outline mb-4">
    <a href="{{ route('admin.documents.index') }}" class="hover:text-primary">ເອກະສານ</a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <span>ເພີ່ມໃໝ່</span>
  </div>

  <form action="{{ route('admin.documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5"
        x-data="{ fileName: '', fileSize: '', fileType: '', dragging: false }">
    @csrf

    {{-- ── ອັບໂຫຼດໄຟລ໌ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-file-upload text-primary text-xs"></i> ໄຟລ໌ເອກະສານ <span class="text-red-500">*</span>
      </h3>

      {{-- Drop zone --}}
      <div class="relative border-2 border-dashed rounded-xl transition-colors cursor-pointer"
           :class="dragging ? 'border-primary bg-primary/5' : 'border-surface-container-high hover:border-primary/40'"
           @dragover.prevent="dragging=true"
           @dragleave.prevent="dragging=false"
           @drop.prevent="
             dragging=false;
             const f=($event.dataTransfer.files[0]);
             if(f){ fileName=f.name; fileSize=(f.size>=1048576?Math.round(f.size/1048576*10)/10+' MB':Math.ceil(f.size/1024)+' KB');
             $refs.fileInput.files=$event.dataTransfer.files; }">
        <label class="flex flex-col items-center justify-center gap-3 py-10 cursor-pointer" for="file_input">
          <div class="w-14 h-14 rounded-full bg-surface-container flex items-center justify-center">
            <i class="fas fa-cloud-upload-alt text-2xl text-outline"></i>
          </div>
          <div class="text-center">
            <p class="text-sm font-semibold text-on-surface">ລາກໄຟລ໌ມາວາງ ຫຼື <span class="text-primary underline">ກົດເລືອກໄຟລ໌</span></p>
            <p class="text-xs text-outline mt-1">PDF, Word, Excel, PPT, TXT, ZIP, RAR · ສູງສຸດ 50 MB</p>
          </div>
          <input type="file" id="file_input" name="file" x-ref="fileInput"
                 class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                 accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar"
                 @change="
                   const f=$event.target.files[0];
                   if(f){ fileName=f.name; fileSize=(f.size>=1048576?Math.round(f.size/1048576*10)/10+' MB':Math.ceil(f.size/1024)+' KB'); }">
        </label>
      </div>

      {{-- ໄຟລ໌ທີ່ເລືອກ --}}
      <div x-show="fileName" x-cloak class="mt-3 flex items-center gap-3 p-3 rounded-lg bg-surface-container-low border border-surface-container-high">
        <i class="fas fa-file text-primary text-sm"></i>
        <div class="flex-1 min-w-0">
          <p class="text-sm font-semibold text-on-surface truncate" x-text="fileName"></p>
          <p class="text-xs text-outline" x-text="fileSize"></p>
        </div>
        <button type="button" @click="fileName=''; fileSize=''; $refs.fileInput.value='';"
                class="text-outline hover:text-red-500 transition-colors">
          <i class="fas fa-times text-sm"></i>
        </button>
      </div>

      @error('file')
        <p class="text-red-500 text-xs mt-2 flex items-center gap-1">
          <i class="fas fa-exclamation-circle"></i> {{ $message }}
        </p>
      @enderror
    </div>

    {{-- ── ຊື່ & ລາຍລະອຽດ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-heading text-primary text-xs"></i> ຊື່ ແລະ ລາຍລະອຽດ
      </h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (ລາວ) <span class="text-red-500">*</span></label>
          <input type="text" name="title_lo" value="{{ old('title_lo') }}" maxlength="300"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 @error('title_lo') border-red-400 @enderror"
                 placeholder="ຊື່ເອກະສານ" required>
          @error('title_lo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (EN)</label>
          <input type="text" name="title_en" value="{{ old('title_en') }}" maxlength="300"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                 placeholder="Document title">
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (ZH)</label>
          <input type="text" name="title_zh" value="{{ old('title_zh') }}" maxlength="300"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                 placeholder="文件标题">
        </div>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ລາຍລະອຽດ (ລາວ)</label>
          <textarea name="description_lo" rows="3"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                    placeholder="ລາຍລະອຽດສັ້ນ...">{{ old('description_lo') }}</textarea>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ລາຍລະອຽດ (EN)</label>
          <textarea name="description_en" rows="3"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                    placeholder="Short description...">{{ old('description_en') }}</textarea>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ລາຍລະອຽດ (ZH)</label>
          <textarea name="description_zh" rows="3"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                    placeholder="简短说明...">{{ old('description_zh') }}</textarea>
        </div>
      </div>
    </div>

    {{-- ── ໝວດໝູ່ & ການຕັ້ງຄ່າ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-cog text-primary text-xs"></i> ການຕັ້ງຄ່າ
      </h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ໝວດໝູ່</label>
          <select name="category_id"
                  class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
            <option value="">— ບໍ່ລະບຸ —</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                {{ $cat->name_lo }}
              </option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ວັນທີ-ເວລາເຜີຍແຜ່</label>
          <input type="datetime-local" name="published_at" value="{{ old('published_at') }}"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
          <p class="text-xs text-outline mt-0.5">ຫວ່າງ = ເກັບເປັນຮ່າງ</p>
        </div>
      </div>
      <div class="flex flex-wrap items-center gap-6">
        <label class="flex items-center gap-2.5 cursor-pointer select-none">
          <input type="checkbox" name="publish_now" value="1" {{ old('publish_now') ? 'checked' : '' }}
                 class="w-4 h-4 rounded accent-primary">
          <div>
            <p class="text-sm font-semibold">ເຜີຍແຜ່ທັນທີ</p>
            <p class="text-xs text-outline">ໃຊ້ເວລາ now() ທັນທີ</p>
          </div>
        </label>
        <label class="flex items-center gap-2.5 cursor-pointer select-none">
          <input type="checkbox" name="is_public" value="1"
                 {{ old('is_public', true) ? 'checked' : '' }}
                 class="w-4 h-4 rounded accent-primary">
          <div>
            <p class="text-sm font-semibold">ສາທາລະນະ</p>
            <p class="text-xs text-outline">ທຸກຄົນສາມາດດາວໂຫຼດໄດ້</p>
          </div>
        </label>
      </div>
    </div>

    {{-- Actions --}}
    <div class="flex gap-3">
      <button type="submit"
              class="inline-flex items-center gap-2 primary-gradient text-white font-semibold px-6 py-2.5 rounded-lg hover:opacity-90 transition text-sm">
        <i class="fas fa-save text-xs"></i> ບັນທຶກ
      </button>
      <a href="{{ route('admin.documents.index') }}"
         class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg border border-surface-container-high text-on-surface-variant hover:bg-surface-container transition text-sm">
        <i class="fas fa-times text-xs"></i> ຍົກເລີກ
      </a>
    </div>

  </form>
</div>
@endsection
