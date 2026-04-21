@extends('admin.layouts.app')

@section('page_title', 'ແກ້ໄຂເອກະສານ')

@section('content')
@php
  $typeIcon = ['PDF'=>['fa-file-pdf','text-red-500','bg-red-50'],'Word'=>['fa-file-word','text-blue-600','bg-blue-50'],'Excel'=>['fa-file-excel','text-green-600','bg-green-50'],'PPT'=>['fa-file-powerpoint','text-orange-500','bg-orange-50'],'Text'=>['fa-file-alt','text-gray-500','bg-gray-50'],'ZIP'=>['fa-file-archive','text-yellow-600','bg-yellow-50'],'RAR'=>['fa-file-archive','text-yellow-700','bg-yellow-50']];
  [$ico,$col,$bg] = $typeIcon[$document->file_type] ?? ['fa-file','text-outline','bg-surface-container'];
@endphp

<div class="max-w-3xl mx-auto">

  {{-- Breadcrumb --}}
  <div class="flex items-center gap-2 text-xs text-outline mb-4">
    <a href="{{ route('admin.documents.index') }}" class="hover:text-primary">ເອກະສານ</a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <a href="{{ route('admin.documents.show', $document) }}" class="hover:text-primary truncate max-w-[200px]">{{ $document->title_lo }}</a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <span>ແກ້ໄຂ</span>
  </div>

  <form action="{{ route('admin.documents.update', $document) }}" method="POST" enctype="multipart/form-data" class="space-y-5"
        x-data="{ fileName: '', fileSize: '', replacing: false }">
    @csrf
    @method('PUT')

    {{-- ── ໄຟລ໌ປັດຈຸບັນ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-file-alt text-primary text-xs"></i> ໄຟລ໌ເອກະສານ
      </h3>

      {{-- ໄຟລ໌ເດີມ --}}
      <div class="flex items-center gap-4 p-4 rounded-xl border border-surface-container-high bg-surface-container-low mb-4">
        <div class="w-12 h-12 rounded-lg {{ $bg }} flex items-center justify-center flex-shrink-0">
          <i class="fas {{ $ico }} {{ $col }} text-xl"></i>
        </div>
        <div class="flex-1 min-w-0">
          <p class="text-sm font-semibold text-on-surface">ໄຟລ໌ປັດຈຸບັນ</p>
          <p class="text-xs text-outline truncate mt-0.5">{{ basename($document->file_url) }}</p>
          <div class="flex items-center gap-3 mt-1 text-xs text-outline">
            @if($document->file_type)
              <span class="font-bold {{ $col }}">{{ $document->file_type }}</span>
            @endif
            @if($document->file_size_kb)
              <span>{{ $document->file_size_kb >= 1024 ? round($document->file_size_kb/1024,1).' MB' : $document->file_size_kb.' KB' }}</span>
            @endif
          </div>
        </div>
        <div class="flex gap-2 flex-shrink-0">
          <a href="{{ route('admin.documents.download', $document) }}"
             class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-green-700 bg-green-100 rounded-lg hover:bg-green-200 transition-colors">
            <i class="fas fa-download text-[10px]"></i> ດາວໂຫຼດ
          </a>
          <button type="button" @click="replacing=!replacing"
                  class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg border border-surface-container-high hover:bg-surface-container transition-colors"
                  :class="replacing ? 'text-red-600 border-red-200 bg-red-50' : ''">
            <i class="fas" :class="replacing ? 'fa-times' : 'fa-exchange-alt'" class="text-[10px]"></i>
            <span x-text="replacing ? 'ຍົກເລີກ' : 'ປ່ຽນໄຟລ໌'"></span>
          </button>
        </div>
      </div>

      {{-- ອັບໂຫຼດໃໝ່ --}}
      <div x-show="replacing" x-transition class="space-y-3">
        <p class="text-xs text-amber-600 flex items-center gap-1.5">
          <i class="fas fa-exclamation-triangle"></i>
          ໄຟລ໌ເກົ່າຈະຖືກລຶບ ແລະ ແທນທີ່ດ້ວຍໄຟລ໌ໃໝ່
        </p>
        <div class="relative border-2 border-dashed border-surface-container-high rounded-xl hover:border-primary/40 transition-colors cursor-pointer">
          <label class="flex flex-col items-center justify-center gap-2 py-8 cursor-pointer" for="file_replace">
            <i class="fas fa-cloud-upload-alt text-2xl text-outline"></i>
            <p class="text-sm text-on-surface-variant">ກົດເລືອກໄຟລ໌ໃໝ່</p>
            <p class="text-xs text-outline">PDF, Word, Excel, PPT, TXT, ZIP, RAR · ສູງສຸດ 50 MB</p>
            <input type="file" id="file_replace" name="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                   accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar"
                   @change="const f=$event.target.files[0]; if(f){ fileName=f.name; fileSize=(f.size>=1048576?Math.round(f.size/1048576*10)/10+' MB':Math.ceil(f.size/1024)+' KB'); }">
          </label>
        </div>
        <div x-show="fileName" class="flex items-center gap-3 p-3 rounded-lg bg-surface-container-low border border-surface-container-high">
          <i class="fas fa-file text-primary text-sm"></i>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold truncate" x-text="fileName"></p>
            <p class="text-xs text-outline" x-text="fileSize"></p>
          </div>
        </div>
        @error('file')<p class="text-red-500 text-xs mt-1 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
      </div>
    </div>

    {{-- ── ຊື່ & ລາຍລະອຽດ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-heading text-primary text-xs"></i> ຊື່ ແລະ ລາຍລະອຽດ
      </h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (ລາວ) <span class="text-red-500">*</span></label>
          <input type="text" name="title_lo" value="{{ old('title_lo', $document->title_lo) }}" maxlength="300"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 @error('title_lo') border-red-400 @enderror"
                 required>
          @error('title_lo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (EN)</label>
          <input type="text" name="title_en" value="{{ old('title_en', $document->title_en) }}" maxlength="300"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (ZH)</label>
          <input type="text" name="title_zh" value="{{ old('title_zh', $document->title_zh) }}" maxlength="300"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ລາຍລະອຽດ (ລາວ)</label>
          <textarea name="description_lo" rows="3"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">{{ old('description_lo', $document->description_lo) }}</textarea>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ລາຍລະອຽດ (EN)</label>
          <textarea name="description_en" rows="3"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">{{ old('description_en', $document->description_en) }}</textarea>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ລາຍລະອຽດ (ZH)</label>
          <textarea name="description_zh" rows="3"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">{{ old('description_zh', $document->description_zh) }}</textarea>
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
          <label class="block text-xs font-semibold mb-1">ໝວດໝູ່</label>
          <select name="category_id"
                  class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
            <option value="">— ບໍ່ລະບຸ —</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}" {{ old('category_id', $document->category_id) == $cat->id ? 'selected' : '' }}>
                {{ $cat->name_lo }}
              </option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ວັນທີ-ເວລາເຜີຍແຜ່</label>
          <input type="datetime-local" name="published_at"
                 value="{{ old('published_at', $document->published_at?->format('Y-m-d\TH:i')) }}"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
      </div>
      <div class="flex flex-wrap items-center gap-6">
        <label class="flex items-center gap-2.5 cursor-pointer select-none">
          <input type="checkbox" name="publish_now" value="1" class="w-4 h-4 rounded accent-primary">
          <div>
            <p class="text-sm font-semibold">ເຜີຍແຜ່ທັນທີ</p>
            <p class="text-xs text-outline">ຕັ້ງ published_at = now()</p>
          </div>
        </label>
        @if($document->published_at)
        <label class="flex items-center gap-2.5 cursor-pointer select-none">
          <input type="checkbox" name="unpublish" value="1" class="w-4 h-4 rounded accent-primary">
          <div>
            <p class="text-sm font-semibold text-red-600">ຍົກເລີກການເຜີຍແຜ່</p>
            <p class="text-xs text-outline">ປ່ຽນກັບເປັນຮ່າງ</p>
          </div>
        </label>
        @endif
        <label class="flex items-center gap-2.5 cursor-pointer select-none">
          <input type="checkbox" name="is_public" value="1"
                 {{ old('is_public', $document->is_public) ? 'checked' : '' }}
                 class="w-4 h-4 rounded accent-primary">
          <div>
            <p class="text-sm font-semibold">ສາທາລະນະ</p>
            <p class="text-xs text-outline">ທຸກຄົນດາວໂຫຼດໄດ້</p>
          </div>
        </label>
      </div>
    </div>

    {{-- Actions --}}
    <div class="flex gap-3">
      <button type="submit"
              class="inline-flex items-center gap-2 primary-gradient text-white font-semibold px-6 py-2.5 rounded-lg hover:opacity-90 transition text-sm">
        <i class="fas fa-save text-xs"></i> ບັນທຶກການປ່ຽນແປງ
      </button>
      <a href="{{ route('admin.documents.show', $document) }}"
         class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg border border-surface-container-high text-on-surface-variant hover:bg-surface-container transition text-sm">
        <i class="fas fa-times text-xs"></i> ຍົກເລີກ
      </a>
    </div>

  </form>
</div>
@endsection
