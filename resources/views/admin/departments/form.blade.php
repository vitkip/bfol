@extends('admin.layouts.app')

@section('page_title', $department->exists ? 'ແກ້ໄຂພະແນກ' : 'ເພີ່ມພະແນກໃໝ່')

@section('content')

<form method="POST"
      action="{{ $department->exists ? route('admin.departments.update', $department) : route('admin.departments.store') }}"
      x-data="{ tab: 'lo' }">
  @csrf
  @if($department->exists) @method('PUT') @endif

  {{-- Breadcrumb --}}
  <div class="flex items-center gap-2 text-xs text-outline mb-5">
    <a href="{{ route('admin.departments.index') }}" class="hover:text-primary transition-colors">ພະແນກ</a>
    <i class="fas fa-chevron-right text-[9px]"></i>
    <span class="text-on-surface-variant">{{ $department->exists ? $department->name_lo : 'ເພີ່ມໃໝ່' }}</span>
  </div>

  @if($errors->any())
    <div class="flex items-start gap-3 px-4 py-3 bg-red-50 border border-red-100 text-red-700 rounded-lg text-sm mb-5">
      <i class="fas fa-exclamation-circle mt-0.5 flex-shrink-0"></i>
      <ul class="space-y-0.5 list-disc list-inside">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
      </ul>
    </div>
  @endif

  <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

    {{-- ═══ LEFT ═══ --}}
    <div class="xl:col-span-2">
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] overflow-hidden">

        {{-- Language tabs --}}
        <div class="flex items-center gap-0 border-b border-surface-container-high px-5 pt-4 bg-surface-container-low">
          @foreach(['lo'=>'ລາວ *','en'=>'English','zh'=>'中文'] as $lang=>$lbl)
            <button type="button" @click="tab='{{ $lang }}'"
                    :class="tab==='{{ $lang }}' ? 'border-primary text-primary font-semibold' : 'border-transparent text-on-surface-variant hover:text-on-surface'"
                    class="px-4 py-2.5 text-sm border-b-2 transition-colors -mb-px mr-1 whitespace-nowrap">
              {{ $lbl }}
            </button>
          @endforeach
        </div>

        <div class="p-5 space-y-4">

          {{-- LO --}}
          <div x-show="tab==='lo'" x-cloak>
            <div>
              <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">ຊື່ພະແນກ (ລາວ) <span class="text-red-500">*</span></label>
              <input name="name_lo" type="text" value="{{ old('name_lo', $department->name_lo) }}" required
                     placeholder="ຊື່ພະແນກ..."
                     class="w-full px-3 py-2.5 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30 @error('name_lo') border-red-300 @enderror" />
              @error('name_lo')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="mt-4">
              <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">ຄຳອະທິບາຍ (ລາວ)</label>
              <textarea name="description_lo" rows="4" placeholder="ອະທິບາຍໜ້າທີ່ຂອງພະແນກ..."
                        class="w-full px-3 py-2.5 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30 resize-y">{{ old('description_lo', $department->description_lo) }}</textarea>
            </div>
          </div>

          {{-- EN --}}
          <div x-show="tab==='en'" x-cloak>
            <div>
              <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">Department Name (EN)</label>
              <input name="name_en" type="text" value="{{ old('name_en', $department->name_en) }}"
                     placeholder="Department name..."
                     class="w-full px-3 py-2.5 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30" />
            </div>
            <div class="mt-4">
              <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">Description (EN)</label>
              <textarea name="description_en" rows="4" placeholder="Department description..."
                        class="w-full px-3 py-2.5 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30 resize-y">{{ old('description_en', $department->description_en) }}</textarea>
            </div>
          </div>

          {{-- ZH --}}
          <div x-show="tab==='zh'" x-cloak>
            <div>
              <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">部门名称 (中文)</label>
              <input name="name_zh" type="text" value="{{ old('name_zh', $department->name_zh) }}"
                     placeholder="部门名称..."
                     class="w-full px-3 py-2.5 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30" />
            </div>
            <div class="mt-4">
              <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">描述 (中文)</label>
              <textarea name="description_zh" rows="4" placeholder="部门描述..."
                        class="w-full px-3 py-2.5 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30 resize-y">{{ old('description_zh', $department->description_zh) }}</textarea>
            </div>
          </div>

        </div>
      </div>

      {{-- Members (edit mode) --}}
      @if($department->exists && $department->members()->exists())
        <div class="mt-5 bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] overflow-hidden">
          <div class="px-5 py-4 border-b border-surface-container-high flex items-center justify-between">
            <div class="flex items-center gap-2">
              <i class="fas fa-users text-primary text-sm"></i>
              <h3 class="font-bold text-sm text-on-surface">ສະມາຊິກໃນພະແນກ</h3>
            </div>
            <span class="text-xs text-outline">{{ $department->members()->count() }} ຄົນ</span>
          </div>
          <div class="divide-y divide-surface-container-high">
            @foreach($department->members()->orderBy('sort_order')->get() as $m)
              <div class="px-5 py-3 flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 rounded-full bg-surface-container-high flex items-center justify-center overflow-hidden flex-shrink-0">
                    @if($m->photo_url)
                      <img src="{{ $m->photo_url }}" class="w-full h-full object-cover" alt="" />
                    @else
                      <i class="fas fa-user text-outline/30 text-sm"></i>
                    @endif
                  </div>
                  <div>
                    <p class="text-sm font-semibold text-on-surface">
                      {{ implode(' ', array_filter([$m->title_lo, $m->first_name_lo ?? $m->name_lo, $m->last_name_lo])) }}
                    </p>
                    <p class="text-xs text-outline">{{ $m->position_lo }}</p>
                  </div>
                </div>
                <a href="{{ route('admin.committee.edit', $m) }}"
                   class="text-xs text-primary hover:underline">ແກ້ໄຂ</a>
              </div>
            @endforeach
          </div>
        </div>
      @endif
    </div>

    {{-- ═══ RIGHT Sidebar ═══ --}}
    <div class="space-y-5">

      {{-- Actions --}}
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
        <div class="flex flex-col gap-2.5">
          <button type="submit"
                  class="w-full flex items-center justify-center gap-2 text-sm font-semibold text-white primary-gradient px-4 py-2.5 rounded-lg hover:opacity-90 active:scale-[0.98] transition-all">
            <i class="fas fa-save text-xs"></i>
            {{ $department->exists ? 'ບັນທຶກ' : 'ເພີ່ມພະແນກ' }}
          </button>
          <a href="{{ route('admin.departments.index') }}"
             class="w-full flex items-center justify-center text-sm text-on-surface-variant bg-surface-container-low border border-surface-container-high px-4 py-2.5 rounded-lg hover:bg-surface-container transition-colors">
            ຍົກເລີກ
          </a>
          @if($department->exists)
            <a href="{{ route('admin.committee.create') }}"
               class="w-full flex items-center justify-center gap-2 text-sm text-primary bg-primary/5 border border-primary/20 px-4 py-2.5 rounded-lg hover:bg-primary/10 transition-colors">
              <i class="fas fa-user-plus text-xs"></i> ເພີ່ມສະມາຊິກໃນພະແນກ
            </a>
          @endif
        </div>
      </div>

      {{-- Settings --}}
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] overflow-hidden">
        <div class="px-5 py-4 border-b border-surface-container-high">
          <h3 class="text-sm font-bold text-on-surface">ການຕັ້ງຄ່າ</h3>
        </div>
        <div class="p-5 space-y-4">

          <label class="flex items-center gap-3 cursor-pointer"
                 x-data="{ on: {{ old('is_active', $department->is_active ?? true) ? 'true' : 'false' }} }">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" x-model="on" class="sr-only">
            <button type="button" @click="on=!on"
                    :class="on ? 'bg-primary' : 'bg-surface-container-high'"
                    class="relative inline-flex h-6 w-11 rounded-full transition-colors focus:outline-none flex-shrink-0">
              <span :class="on ? 'translate-x-5' : 'translate-x-0.5'"
                    class="inline-block h-5 w-5 transform rounded-full bg-white shadow transition-transform mt-0.5"></span>
            </button>
            <div>
              <span class="text-sm font-semibold" x-text="on ? 'ເປີດໃຊ້ງານ' : 'ປິດໃຊ້ງານ'"></span>
              <p class="text-[11px] text-outline">ສະແດງໃນ dropdown ສະມາຊິກ</p>
            </div>
          </label>

          <div>
            <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">ລຳດັບ (Sort order)</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $department->sort_order ?? 0) }}"
                   min="0" max="9999"
                   class="w-full px-3 py-2 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30" />
            <p class="mt-1 text-[10px] text-outline">ຕ່ຳສຸດ = ສະແດງກ່ອນ</p>
          </div>

        </div>
      </div>

    </div>
  </div>
</form>

@endsection
