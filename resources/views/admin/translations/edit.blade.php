@extends('admin.layouts.app')

@section('page_title', 'ແກ້ໄຂໂຄງການແປ')

@section('content')
<div class="max-w-3xl mx-auto">

  <div class="flex items-center gap-2 text-xs text-outline mb-4">
    <a href="{{ route('admin.translations.index') }}" class="hover:text-primary">ໂຄງການແປ</a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <span class="truncate max-w-[180px]">{{ $project->title_lo }}</span>
  </div>

  @php
    $languages = [
      'lo'=>'ລາວ','en'=>'ອັງກິດ','zh'=>'ຈີນ','th'=>'ໄທ','my'=>'ພະມ້າ',
      'km'=>'ຂະແໝນ','ja'=>'ຍີ່ປຸ່ນ','ko'=>'ເກົາຫຼີ','vi'=>'ຫວຽດນາມ',
      'fr'=>'ຝຣັ່ງ','de'=>'ເຢຍລະມັນ','ru'=>'ຣັດເຊຍ','ar'=>'ອາຣັບ',
      'pi'=>'ບາລີ','sa'=>'ສັນສະກຣິດ',
    ];
  @endphp

  <form action="{{ route('admin.translations.update', $project) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
    @csrf @method('PUT')

    {{-- ── ຊື່ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-language text-primary text-xs"></i> ຊື່ໂຄງການ
      </h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (ລາວ) <span class="text-red-500">*</span></label>
          <input type="text" name="title_lo" value="{{ old('title_lo', $project->title_lo) }}" maxlength="300" required
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 @error('title_lo') border-red-400 @enderror">
          @error('title_lo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (EN)</label>
          <input type="text" name="title_en" value="{{ old('title_en', $project->title_en) }}" maxlength="300"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (ZH)</label>
          <input type="text" name="title_zh" value="{{ old('title_zh', $project->title_zh) }}" maxlength="300"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
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
              <option value="{{ $code }}" {{ old('source_language', $project->source_language) === $code ? 'selected' : '' }}>{{ $name }} ({{ $code }})</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ພາສາເປົ້າໝາຍ</label>
          <select name="target_language" class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
            <option value="">— ເລືອກ —</option>
            @foreach($languages as $code => $name)
              <option value="{{ $code }}" {{ old('target_language', $project->target_language) === $code ? 'selected' : '' }}>{{ $name }} ({{ $code }})</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຜູ້ແປ / ທີມ</label>
          <input type="text" name="translator" value="{{ old('translator', $project->translator) }}" maxlength="200"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ປີ</label>
          <input type="number" name="year" value="{{ old('year', $project->year) }}" min="1900" max="2100"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 font-mono">
        </div>
      </div>

      {{-- Status --}}
      <div class="mt-4">
        <label class="block text-xs font-semibold mb-2">ສະຖານະ <span class="text-red-500">*</span></label>
        <div class="flex flex-wrap gap-2">
          @foreach($statuses as $val => [$label, $cls])
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="radio" name="status" value="{{ $val }}" class="sr-only peer"
                     {{ old('status', $project->status) === $val ? 'checked' : '' }}>
              <span class="px-3 py-1.5 text-xs font-semibold rounded-full border-2 border-transparent
                           peer-checked:border-current transition-all {{ $cls }}">
                {{ $label }}
              </span>
            </label>
          @endforeach
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
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">{{ old('description_'.$lang, $project->{'description_'.$lang}) }}</textarea>
        </div>
      @endforeach
    </div>

    {{-- ── ເອກະສານ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-file-pdf text-primary text-xs"></i> ເອກະສານ
      </h3>
      @if($project->document_url)
        <div class="flex items-center gap-3 p-3 bg-surface-container rounded-lg mb-3">
          <i class="fas fa-file-pdf text-red-500 text-lg"></i>
          <div class="flex-1 min-w-0">
            <p class="text-xs font-semibold text-on-surface truncate">ເອກະສານປັດຈຸບັນ</p>
            <a href="{{ $project->document_url }}" target="_blank"
               class="text-xs text-primary hover:underline truncate block">{{ $project->document_url }}</a>
          </div>
          <label class="flex items-center gap-2 cursor-pointer text-xs text-red-500">
            <input type="checkbox" name="remove_doc" value="1" class="rounded">
            ລຶບ
          </label>
        </div>
      @endif
      <p class="text-xs font-semibold mb-2 text-outline">ອັບໂຫຼດໄຟລ໌ໃໝ່ (ຖ້າຕ້ອງການປ່ຽນ):</p>
      <input type="file" name="doc_file" accept=".pdf,.doc,.docx"
             class="block w-full text-sm text-on-surface-variant file:mr-3 file:py-1.5 file:px-3
                    file:rounded-lg file:border-0 file:text-xs file:font-semibold
                    file:bg-surface-container file:text-on-surface-variant hover:file:bg-surface-container-high
                    border border-surface-container-high rounded-lg cursor-pointer" />
    </div>

    {{-- Actions --}}
    <div class="flex items-center justify-between pt-2">
      <form action="{{ route('admin.translations.destroy', $project) }}" method="POST"
            onsubmit="return confirm('ລຶບໂຄງການ \'{{ addslashes($project->title_lo) }}\'?')">
        @csrf @method('DELETE')
        <button type="submit" class="inline-flex items-center gap-2 text-sm text-red-500 hover:text-red-700 px-4 py-2.5 rounded-lg hover:bg-red-50 transition-colors">
          <i class="fas fa-trash text-xs"></i> ລຶບ
        </button>
      </form>
      <div class="flex items-center gap-3">
        <a href="{{ route('admin.translations.index') }}"
           class="px-5 py-2.5 text-sm rounded-lg border border-surface-container-high hover:bg-surface-container transition-colors">
          ຍົກເລີກ
        </a>
        <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold text-white primary-gradient rounded-lg hover:opacity-90 transition">
          <i class="fas fa-save text-xs"></i> ບັນທຶກ
        </button>
      </div>
    </div>
  </form>
</div>
@endsection
