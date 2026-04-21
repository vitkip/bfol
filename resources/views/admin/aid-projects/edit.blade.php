@extends('admin.layouts.app')

@section('page_title', 'ແກ້ໄຂໂຄງການ')

@section('content')
<div class="max-w-3xl mx-auto">

  {{-- Breadcrumb --}}
  <div class="flex items-center gap-2 text-xs text-outline mb-4">
    <a href="{{ route('admin.aid-projects.index') }}" class="hover:text-primary">ໂຄງການຊ່ວຍເຫຼືອ</a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <a href="{{ route('admin.aid-projects.show', $project) }}" class="hover:text-primary truncate max-w-[180px]">{{ $project->title_lo }}</a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <span>ແກ້ໄຂ</span>
  </div>

  <form action="{{ route('admin.aid-projects.update', $project) }}" method="POST" class="space-y-5">
    @csrf @method('PUT')

    {{-- ── ຊື່ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-hands-helping text-primary text-xs"></i> ຊື່ໂຄງການ
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

    {{-- ── ຂໍ້ມູນໂຄງການ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-info-circle text-primary text-xs"></i> ຂໍ້ມູນໂຄງການ
      </h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ປະເທດ <span class="text-red-500">*</span></label>
          <input type="text" name="country" value="{{ old('country', $project->country) }}" maxlength="100" required
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 @error('country') border-red-400 @enderror">
          @error('country')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ອົງກອນຄູ່ຮ່ວມ</label>
          <select name="partner_org_id"
                  class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
            <option value="">— ບໍ່ລະບຸ —</option>
            @foreach($partners as $partner)
              <option value="{{ $partner->id }}" {{ old('partner_org_id', $project->partner_org_id) == $partner->id ? 'selected' : '' }}>
                {{ $partner->acronym ? "[{$partner->acronym}] " : '' }}{{ $partner->name_lo }}
              </option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="mb-4">
        <label class="block text-xs font-semibold mb-2">ປະເພດ <span class="text-red-500">*</span></label>
        <div class="flex flex-wrap gap-2">
          @foreach($types as $key => $meta)
            <label class="flex items-center gap-2 px-3 py-2 rounded-lg border cursor-pointer transition-colors
                          {{ old('type', $project->type) === $key ? 'border-primary bg-primary/5' : 'border-surface-container-high hover:bg-surface-container-low' }}">
              <input type="radio" name="type" value="{{ $key }}" class="accent-primary"
                     {{ old('type', $project->type) === $key ? 'checked' : '' }}>
              <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2 py-0.5 rounded-full {{ $meta['class'] }}">
                <i class="fas {{ $meta['icon'] }} text-[8px]"></i> {{ $meta['lo'] }}
              </span>
            </label>
          @endforeach
        </div>
      </div>

      <div>
        <label class="block text-xs font-semibold mb-2">ສະຖານະ <span class="text-red-500">*</span></label>
        <div class="flex flex-wrap gap-2">
          @foreach($statuses as $key => $meta)
            <label class="flex items-center gap-2 px-3 py-2 rounded-lg border cursor-pointer transition-colors
                          {{ old('status', $project->status) === $key ? 'border-primary bg-primary/5' : 'border-surface-container-high hover:bg-surface-container-low' }}">
              <input type="radio" name="status" value="{{ $key }}" class="accent-primary"
                     {{ old('status', $project->status) === $key ? 'checked' : '' }}>
              <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2 py-0.5 rounded-full {{ $meta['class'] }}">
                <i class="fas {{ $meta['icon'] }} text-[8px]"></i> {{ $meta['lo'] }}
              </span>
            </label>
          @endforeach
        </div>
      </div>
    </div>

    {{-- ── ງົບ & ໄລຍະ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-calendar-alt text-primary text-xs"></i> ງົບປະມານ & ໄລຍະ
      </h3>
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ງົບປະມານ (USD)</label>
          <div class="relative">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-outline font-semibold">$</span>
            <input type="number" name="budget_usd" value="{{ old('budget_usd', $project->budget_usd) }}"
                   min="0" max="999999999" step="0.01"
                   class="w-full rounded-lg border border-surface-container-high pl-7 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
          </div>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ວັນເລີ່ມ</label>
          <input type="date" name="start_date" value="{{ old('start_date', $project->start_date?->format('Y-m-d')) }}"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ວັນສຸດ</label>
          <input type="date" name="end_date" value="{{ old('end_date', $project->end_date?->format('Y-m-d')) }}"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
          @error('end_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
      </div>
    </div>

    {{-- ── ລາຍງານ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-file-alt text-primary text-xs"></i> ລາຍງານໂຄງການ
      </h3>
      <input type="url" name="report_url" value="{{ old('report_url', $project->report_url) }}"
             class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
             placeholder="https://drive.google.com/...">
      @error('report_url')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    {{-- ── ລາຍລະອຽດ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-align-left text-primary text-xs"></i> ລາຍລະອຽດ
      </h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ລາຍລະອຽດ (ລາວ)</label>
          <textarea name="description_lo" rows="5"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 resize-y">{{ old('description_lo', $project->description_lo) }}</textarea>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ລາຍລະອຽດ (EN)</label>
          <textarea name="description_en" rows="5"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 resize-y">{{ old('description_en', $project->description_en) }}</textarea>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ລາຍລະອຽດ (ZH)</label>
          <textarea name="description_zh" rows="5"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 resize-y">{{ old('description_zh', $project->description_zh) }}</textarea>
        </div>
      </div>
    </div>

    {{-- Actions --}}
    <div class="flex gap-3">
      <button type="submit"
              class="inline-flex items-center gap-2 primary-gradient text-white font-semibold px-6 py-2.5 rounded-lg hover:opacity-90 transition text-sm">
        <i class="fas fa-save text-xs"></i> ບັນທຶກການປ່ຽນແປງ
      </button>
      <a href="{{ route('admin.aid-projects.show', $project) }}"
         class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg border border-surface-container-high text-on-surface-variant hover:bg-surface-container transition text-sm">
        <i class="fas fa-times text-xs"></i> ຍົກເລີກ
      </a>
    </div>

  </form>
</div>
@endsection
