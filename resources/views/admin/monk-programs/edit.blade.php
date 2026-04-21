@extends('admin.layouts.app')

@section('page_title', 'ແກ້ໄຂໂຄງການ')

@section('content')
<div class="max-w-3xl mx-auto">

  {{-- Breadcrumb --}}
  <div class="flex items-center gap-2 text-xs text-outline mb-4">
    <a href="{{ route('admin.monk-programs.index') }}" class="hover:text-primary">ໂຄງການແລກປ່ຽນ</a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <a href="{{ route('admin.monk-programs.show', $program) }}" class="hover:text-primary truncate max-w-[180px]">{{ $program->title_lo }}</a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <span>ແກ້ໄຂ</span>
  </div>

  <form action="{{ route('admin.monk-programs.update', $program) }}" method="POST" class="space-y-5">
    @csrf @method('PUT')

    {{-- ── ຊື່ໂຄງການ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-dharmachakra text-primary text-xs"></i> ຊື່ໂຄງການ
      </h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (ລາວ) <span class="text-red-500">*</span></label>
          <input type="text" name="title_lo" value="{{ old('title_lo', $program->title_lo) }}" maxlength="300" required
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 @error('title_lo') border-red-400 @enderror">
          @error('title_lo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (EN)</label>
          <input type="text" name="title_en" value="{{ old('title_en', $program->title_en) }}" maxlength="300"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຊື່ (ZH)</label>
          <input type="text" name="title_zh" value="{{ old('title_zh', $program->title_zh) }}" maxlength="300"
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
          <label class="block text-xs font-semibold mb-1">ປະເທດປາຍທາງ <span class="text-red-500">*</span></label>
          <input type="text" name="destination_country" value="{{ old('destination_country', $program->destination_country) }}" maxlength="100" required
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 @error('destination_country') border-red-400 @enderror">
          @error('destination_country')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ອົງກອນຄູ່ຮ່ວມ</label>
          <select name="partner_org_id"
                  class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
            <option value="">— ບໍ່ລະບຸ —</option>
            @foreach($partners as $partner)
              <option value="{{ $partner->id }}" {{ old('partner_org_id', $program->partner_org_id) == $partner->id ? 'selected' : '' }}>
                {{ $partner->acronym ? "[{$partner->acronym}] " : '' }}{{ $partner->name_lo }}
              </option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ປີ <span class="text-red-500">*</span></label>
          <input type="number" name="year" value="{{ old('year', $program->year) }}" required
                 min="2000" max="{{ date('Y') + 5 }}"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
        <div>
          <label class="block text-xs font-semibold mb-2">ສະຖານະ</label>
          <div class="grid grid-cols-2 gap-2">
            @foreach($statuses as $key => $meta)
              <label class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg border cursor-pointer transition-colors text-xs
                            {{ old('status', $program->status) === $key ? 'border-primary bg-primary/5' : 'border-surface-container-high hover:bg-surface-container-low' }}">
                <input type="radio" name="status" value="{{ $key }}" class="accent-primary"
                       {{ old('status', $program->status) === $key ? 'checked' : '' }}>
                <span class="inline-flex items-center gap-1 font-semibold px-1.5 py-0.5 rounded-full {{ $meta['class'] }}">
                  <i class="fas {{ $meta['icon'] }} text-[7px]"></i> {{ $meta['lo'] }}
                </span>
              </label>
            @endforeach
          </div>
        </div>
      </div>
      <label class="flex items-center gap-2.5 cursor-pointer select-none">
        <input type="checkbox" name="is_featured" value="1"
               {{ old('is_featured', $program->is_featured) ? 'checked' : '' }}
               class="w-4 h-4 rounded accent-primary">
        <div>
          <p class="text-sm font-semibold flex items-center gap-1.5">
            <i class="fas fa-star text-amber-400 text-xs"></i> ໂດດເດັ່ນ
          </p>
          <p class="text-xs text-outline">ສະແດງໃນຫ້ອງ Featured ໜ້າຫຼັກ</p>
        </div>
      </label>
    </div>

    {{-- ── ວັນທີ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-calendar-alt text-primary text-xs"></i> ກຳນົດວັນທີ
      </h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ເປີດຮັບສະໝັກ</label>
          <input type="date" name="application_open" value="{{ old('application_open', $program->application_open?->format('Y-m-d')) }}"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ປິດຮັບສະໝັກ</label>
          <input type="date" name="application_deadline" value="{{ old('application_deadline', $program->application_deadline?->format('Y-m-d')) }}"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ວັນເລີ່ມໂຄງການ</label>
          <input type="date" name="program_start" value="{{ old('program_start', $program->program_start?->format('Y-m-d')) }}"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ວັນສຸດໂຄງການ</label>
          <input type="date" name="program_end" value="{{ old('program_end', $program->program_end?->format('Y-m-d')) }}"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
      </div>
    </div>

    {{-- ── ໂຄຕ້າ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-users text-primary text-xs"></i> ໂຄຕ້າ & ການຄັດເລືອກ
      </h3>
      <div class="grid grid-cols-2 gap-4 max-w-xs">
        <div>
          <label class="block text-xs font-semibold mb-1">ໂຄຕ້າທັງໝົດ</label>
          <input type="number" name="monks_quota" value="{{ old('monks_quota', $program->monks_quota) }}"
                 min="1" max="9999"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຜ່ານຄັດເລືອກ</label>
          <input type="number" name="monks_selected" value="{{ old('monks_selected', $program->monks_selected) }}"
                 min="0" max="9999"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
      </div>
    </div>

    {{-- ── ຕິດຕໍ່ & ສະໝັກ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-paper-plane text-primary text-xs"></i> ການສະໝັກ & ຕິດຕໍ່
      </h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ລິ້ງສະໝັກ</label>
          <input type="url" name="application_url" value="{{ old('application_url', $program->application_url) }}"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ອີເມວຕິດຕໍ່</label>
          <input type="email" name="contact_email" value="{{ old('contact_email', $program->contact_email) }}"
                 class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
      </div>
    </div>

    {{-- ── ລາຍລະອຽດ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-align-left text-primary text-xs"></i> ລາຍລະອຽດໂຄງການ
      </h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ລາຍລະອຽດ (ລາວ)</label>
          <textarea name="description_lo" rows="4"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 resize-y">{{ old('description_lo', $program->description_lo) }}</textarea>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ລາຍລະອຽດ (EN)</label>
          <textarea name="description_en" rows="4"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 resize-y">{{ old('description_en', $program->description_en) }}</textarea>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ລາຍລະອຽດ (ZH)</label>
          <textarea name="description_zh" rows="4"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 resize-y">{{ old('description_zh', $program->description_zh) }}</textarea>
        </div>
      </div>
    </div>

    {{-- ── ຄຸນສົມບັດ ── --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
      <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
        <i class="fas fa-list-check text-primary text-xs"></i> ຄຸນສົມບັດຜູ້ສະໝັກ
      </h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-xs font-semibold mb-1">ຄຸນສົມບັດ (ລາວ)</label>
          <textarea name="requirements_lo" rows="4"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 resize-y">{{ old('requirements_lo', $program->requirements_lo) }}</textarea>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຄຸນສົມບັດ (EN)</label>
          <textarea name="requirements_en" rows="4"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 resize-y">{{ old('requirements_en', $program->requirements_en) }}</textarea>
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1">ຄຸນສົມບັດ (ZH)</label>
          <textarea name="requirements_zh" rows="4"
                    class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 resize-y">{{ old('requirements_zh', $program->requirements_zh) }}</textarea>
        </div>
      </div>
    </div>

    {{-- Actions --}}
    <div class="flex gap-3">
      <button type="submit"
              class="inline-flex items-center gap-2 primary-gradient text-white font-semibold px-6 py-2.5 rounded-lg hover:opacity-90 transition text-sm">
        <i class="fas fa-save text-xs"></i> ບັນທຶກການປ່ຽນແປງ
      </button>
      <a href="{{ route('admin.monk-programs.show', $program) }}"
         class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg border border-surface-container-high text-on-surface-variant hover:bg-surface-container transition text-sm">
        <i class="fas fa-times text-xs"></i> ຍົກເລີກ
      </a>
    </div>

  </form>
</div>
@endsection
