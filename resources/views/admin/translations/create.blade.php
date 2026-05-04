@extends('admin.layouts.app')

@section('page_title', 'ເພີ່ມໂຄງການແປ')

@section('content')
<div class="max-w-3xl mx-auto">

  <div class="flex items-center gap-2 text-xs text-outline mb-4">
    <a href="{{ route('admin.translations.index') }}" class="hover:text-primary">ໂຄງການແປ</a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <span>ເພີ່ມໃໝ່</span>
  </div>

  @php
    $languages = [
      'lo'=>'ລາວ','en'=>'ອັງກິດ','zh'=>'ຈີນ','th'=>'ໄທ','my'=>'ພະມ້າ',
      'km'=>'ຂະແໝນ','ja'=>'ຍີ່ປຸ່ນ','ko'=>'ເກົາຫຼີ','vi'=>'ຫວຽດນາມ',
      'fr'=>'ຝຣັ່ງ','de'=>'ເຢຍລະມັນ','ru'=>'ຣັດເຊຍ','ar'=>'ອາຣັບ',
      'pi'=>'ບາລີ','sa'=>'ສັນສະກຣິດ',
    ];
  @endphp

  <form action="{{ route('admin.translations.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
    @csrf

    {{-- ── ຊື່ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-language text-primary text-xs"></i> ຊື່ໂຄງການ
      </h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (ລາວ) <span class="text-red-500">*</span></label>
          <input type="text" name="title_lo" value="{{ old('title_lo') }}" maxlength="300" required
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 @error('title_lo') border-red-400 @enderror"
                 placeholder="ຊື່ໂຄງການ">
          @error('title_lo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (EN)</label>
          <input type="text" name="title_en" value="{{ old('title_en') }}" maxlength="300"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                 placeholder="Project title">
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (ZH)</label>
          <input type="text" name="title_zh" value="{{ old('title_zh') }}" maxlength="300"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                 placeholder="项目名称">
        </div>
      </div>
    </div>

    {{-- ── ພາສາ & ຂໍ້ມູນ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-exchange-alt text-primary text-xs"></i> ຂໍ້ມູນການແປ
      </h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ພາສາຕົ້ນສະບັບ</label>
          <select name="source_language" class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
            <option value="">— ເລືອກ —</option>
            @foreach($languages as $code => $name)
              <option value="{{ $code }}" {{ old('source_language') === $code ? 'selected' : '' }}>{{ $name }} ({{ $code }})</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ພາສາເປົ້າໝາຍ</label>
          <select name="target_language" class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
            <option value="">— ເລືອກ —</option>
            @foreach($languages as $code => $name)
              <option value="{{ $code }}" {{ old('target_language') === $code ? 'selected' : '' }}>{{ $name }} ({{ $code }})</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຜູ້ແປ / ທີມ</label>
          <input type="text" name="translator" value="{{ old('translator') }}" maxlength="200"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                 placeholder="ຊື່ຜູ້ແປ">
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ປີ</label>
          <input type="number" name="year" value="{{ old('year', date('Y')) }}" min="1900" max="2100"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 font-mono"
                 placeholder="{{ date('Y') }}">
        </div>
      </div>

      {{-- Status --}}
      <div class="mt-4">
        <label class="block text-xs font-semibold mb-2">ສະຖານະ <span class="text-red-500">*</span></label>
        <div class="flex flex-wrap gap-2">
          @foreach($statuses as $val => [$label, $cls])
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="radio" name="status" value="{{ $val }}" class="sr-only peer"
                     {{ old('status','in_progress') === $val ? 'checked' : '' }}>
              <span class="px-3 py-1.5 text-xs font-semibold rounded-full border-2 border-transparent
                           peer-checked:border-current transition-all {{ $cls }}">
                {{ $label }}
              </span>
            </label>
          @endforeach
        </div>
        @error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
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

    {{-- ── ເອກະສານ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5"
         x-data="{ mode: 'file' }">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-file-pdf text-primary text-xs"></i> ເອກະສານ (ຖ້າມີ)
      </h3>
      <div class="flex gap-3 mb-3">
        <button type="button" @click="mode='file'"
                :class="mode==='file' ? 'primary-gradient text-white' : 'border border-surface-container-high'"
                class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-colors">
          <i class="fas fa-upload text-[10px] mr-1"></i> ອັບໂຫຼດໄຟລ໌
        </button>
        <button type="button" @click="mode='url'"
                :class="mode==='url' ? 'primary-gradient text-white' : 'border border-surface-container-high'"
                class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-colors">
          <i class="fas fa-link text-[10px] mr-1"></i> URL
        </button>
      </div>
      <div x-show="mode==='file'">
        <label class="block cursor-pointer">
          <div class="border-2 border-dashed border-surface-container-high rounded-xl p-5 text-center hover:border-primary/40 transition-colors"
               x-data="{ name: '' }" @dragover.prevent @drop.prevent="name=$event.dataTransfer.files[0]?.name">
            <i class="fas fa-file-upload text-xl text-outline/50 mb-1"></i>
            <p class="text-xs text-outline" x-show="!name">PDF / DOC / DOCX — ສູງສຸດ 20MB</p>
            <p class="text-xs font-semibold text-primary" x-show="name" x-text="name"></p>
          </div>
          <input type="file" name="doc_file" accept=".pdf,.doc,.docx" class="hidden"
                 @change="$el.closest('div').querySelector('p[x-show=name]').innerHTML = $el.files[0]?.name" />
        </label>
      </div>
      <div x-show="mode==='url'" style="display:none">
        <input type="text" name="document_url" value="{{ old('document_url') }}" maxlength="500"
               class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
               placeholder="https://...">
      </div>
    </div>

    {{-- Actions --}}
    <div class="flex items-center justify-end gap-3 pt-2">
      <a href="{{ route('admin.translations.index') }}"
         class="px-5 py-2.5 text-sm rounded-lg border border-surface-container-high hover:bg-surface-container transition-colors">
        ຍົກເລີກ
      </a>
      <button type="submit"
              class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold text-white primary-gradient rounded-lg hover:opacity-90 transition">
        <i class="fas fa-save text-xs"></i> ບັນທຶກ
      </button>
    </div>
  </form>
</div>
@endsection
