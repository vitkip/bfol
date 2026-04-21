@extends('admin.layouts.app')

@section('page_title', 'ລາຍລະອຽດໂຄງການ')

@section('content')
@php
  $st      = $statuses[$program->status] ?? $statuses['draft'];
  $hasDesc = $program->description_lo || $program->description_en || $program->description_zh;
  $hasReqs = $program->requirements_lo || $program->requirements_en || $program->requirements_zh;
  $quotaPct = $program->monks_quota > 0
              ? min(100, round($program->monks_selected / $program->monks_quota * 100))
              : 0;
  $deadlinePassed = $program->application_deadline && $program->application_deadline->lt(now());
  $isOpen = $program->status === 'open' && !$deadlinePassed;
@endphp

<div class="max-w-4xl mx-auto">

  {{-- Breadcrumb --}}
  <div class="flex items-center gap-2 text-xs text-outline mb-4">
    <a href="{{ route('admin.monk-programs.index') }}" class="hover:text-primary">ໂຄງການແລກປ່ຽນ</a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <span class="truncate max-w-[240px]">{{ $program->title_lo }}</span>
  </div>

  @if(session('success'))
    <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 mb-4 text-sm">
      <i class="fas fa-check-circle text-green-500"></i> {{ session('success') }}
    </div>
  @endif

  {{-- Action bar --}}
  <div class="flex items-center justify-between mb-5 flex-wrap gap-3">
    <div class="flex items-center gap-2 flex-wrap">
      <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full {{ $st['class'] }}">
        <i class="fas {{ $st['icon'] }} text-[9px]"></i> {{ $st['lo'] }}
      </span>
      @if($program->is_featured)
        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-700">
          <i class="fas fa-star text-[9px]"></i> ໂດດເດັ່ນ
        </span>
      @endif
      <span class="text-xs text-outline font-semibold">ປີ {{ $program->year }}</span>
    </div>
    <div class="flex gap-2">
      <a href="{{ route('admin.monk-programs.edit', $program) }}"
         class="inline-flex items-center gap-2 primary-gradient text-white font-semibold px-4 py-2 rounded-lg hover:opacity-90 transition text-sm">
        <i class="fas fa-edit text-xs"></i> ແກ້ໄຂ
      </a>
      <form action="{{ route('admin.monk-programs.destroy', $program) }}" method="POST"
            onsubmit="return confirm('ລຶບໂຄງການ «{{ $program->title_lo }}» ແທ້ບໍ?')">
        @csrf @method('DELETE')
        <button class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-red-200 text-red-600 hover:bg-red-50 transition text-sm font-semibold">
          <i class="fas fa-trash text-xs"></i> ລຶບ
        </button>
      </form>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- ── ຖັນຊ້າຍ ── --}}
    <div class="lg:col-span-2 space-y-5">

      {{-- ບັດໂຄງການ --}}
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-6">
        <div class="flex items-start gap-4">
          <div class="w-14 h-14 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-dharmachakra text-amber-500 text-2xl"></i>
          </div>
          <div class="flex-1 min-w-0">
            <h2 class="text-lg font-bold text-on-surface leading-tight">{{ $program->title_lo }}</h2>
            @if($program->title_en) <p class="text-sm text-outline mt-0.5">{{ $program->title_en }}</p> @endif
            @if($program->title_zh) <p class="text-sm text-outline">{{ $program->title_zh }}</p> @endif
            <div class="flex flex-wrap items-center gap-3 mt-2 text-xs text-outline">
              <span class="flex items-center gap-1">
                <i class="fas fa-map-marker-alt text-[10px]"></i> {{ $program->destination_country }}
              </span>
              @if($program->partnerOrganization)
                <span class="flex items-center gap-1">
                  <i class="fas fa-building text-[10px]"></i>
                  {{ $program->partnerOrganization->acronym ?? $program->partnerOrganization->name_lo }}
                </span>
              @endif
            </div>
          </div>
        </div>

        {{-- ໂຄຕ້າ Progress --}}
        @if($program->monks_quota)
          <div class="mt-5 p-4 rounded-xl bg-surface-container-low border border-surface-container-high">
            <div class="flex items-center justify-between mb-2">
              <span class="text-xs font-semibold text-on-surface">ຈຳນວນຜ່ານຄັດເລືອກ</span>
              <span class="text-sm font-bold {{ $quotaPct >= 100 ? 'text-green-600' : 'text-on-surface' }}">
                {{ $program->monks_selected }} / {{ $program->monks_quota }} ຮູບ
              </span>
            </div>
            <div class="h-2.5 rounded-full bg-surface-container-high overflow-hidden">
              <div class="h-2.5 rounded-full transition-all duration-500
                          {{ $quotaPct >= 100 ? 'bg-green-500' : ($quotaPct >= 70 ? 'bg-amber-400' : 'bg-primary') }}"
                   style="width:{{ $quotaPct }}%"></div>
            </div>
            <p class="text-xs text-outline mt-1">{{ $quotaPct }}% ຂອງໂຄຕ້າ</p>
          </div>
        @endif
      </div>

      {{-- ກຳນົດເວລາ Timeline --}}
      @if($program->application_open || $program->application_deadline || $program->program_start || $program->program_end)
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
        <h3 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
          <i class="fas fa-calendar-alt text-primary text-xs"></i> ກຳນົດການ
        </h3>
        <div class="relative pl-6 space-y-4">
          <div class="absolute left-2 top-1 bottom-1 w-px bg-surface-container-high"></div>
          @foreach([
            [$program->application_open,     'ເປີດຮັບສະໝັກ',  'fa-door-open',   'text-green-500',  'bg-green-50'],
            [$program->application_deadline,  'ປິດຮັບສະໝັກ',   'fa-door-closed', 'text-red-400',    'bg-red-50'],
            [$program->program_start,         'ເລີ່ມໂຄງການ',   'fa-play-circle', 'text-blue-500',   'bg-blue-50'],
            [$program->program_end,           'ສິ້ນສຸດໂຄງການ', 'fa-flag-checkered','text-purple-500','bg-purple-50'],
          ] as [$date, $label, $icon, $col, $bg])
            @if($date)
            <div class="relative flex items-start gap-3">
              <div class="absolute -left-6 w-4 h-4 rounded-full {{ $bg }} border-2 border-white flex items-center justify-center">
                <i class="fas {{ $icon }} {{ $col }} text-[7px]"></i>
              </div>
              <div>
                <p class="text-xs font-semibold text-on-surface">{{ $label }}</p>
                <p class="text-sm font-bold {{ $date->lt(now()) ? 'text-outline' : 'text-on-surface' }}">
                  {{ $date->format('d/m/Y') }}
                  @if($date->isToday()) <span class="text-[10px] font-normal text-green-600">(ວັນນີ້)</span> @endif
                </p>
              </div>
            </div>
            @endif
          @endforeach
        </div>
      </div>
      @endif

      {{-- ລາຍລະອຽດ --}}
      @if($hasDesc)
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5"
           x-data="{ tab: '{{ $program->description_lo ? 'lo' : ($program->description_en ? 'en' : 'zh') }}' }">
        <h3 class="font-bold text-sm text-on-surface mb-3 flex items-center gap-2">
          <i class="fas fa-align-left text-primary text-xs"></i> ລາຍລະອຽດ
        </h3>
        <div class="flex gap-1 border-b border-surface-container-high mb-3">
          @foreach(['lo'=>'ລາວ','en'=>'English','zh'=>'中文'] as $k=>$lbl)
            @if($program->{'description_'.$k})
              <button @click="tab='{{ $k }}'"
                      :class="tab==='{{ $k }}' ? 'border-primary text-primary font-semibold' : 'border-transparent text-outline'"
                      class="px-3 py-1.5 text-xs border-b-2 transition-colors -mb-px">{{ $lbl }}</button>
            @endif
          @endforeach
        </div>
        @foreach(['lo','en','zh'] as $k)
          @if($program->{'description_'.$k})
            <div x-show="tab==='{{ $k }}'">
              <p class="text-sm text-on-surface-variant leading-relaxed whitespace-pre-line">{{ $program->{'description_'.$k} }}</p>
            </div>
          @endif
        @endforeach
      </div>
      @endif

      {{-- ຄຸນສົມບັດ --}}
      @if($hasReqs)
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5"
           x-data="{ tab: '{{ $program->requirements_lo ? 'lo' : ($program->requirements_en ? 'en' : 'zh') }}' }">
        <h3 class="font-bold text-sm text-on-surface mb-3 flex items-center gap-2">
          <i class="fas fa-list-check text-primary text-xs"></i> ຄຸນສົມບັດຜູ້ສະໝັກ
        </h3>
        <div class="flex gap-1 border-b border-surface-container-high mb-3">
          @foreach(['lo'=>'ລາວ','en'=>'English','zh'=>'中文'] as $k=>$lbl)
            @if($program->{'requirements_'.$k})
              <button @click="tab='{{ $k }}'"
                      :class="tab==='{{ $k }}' ? 'border-primary text-primary font-semibold' : 'border-transparent text-outline'"
                      class="px-3 py-1.5 text-xs border-b-2 transition-colors -mb-px">{{ $lbl }}</button>
            @endif
          @endforeach
        </div>
        @foreach(['lo','en','zh'] as $k)
          @if($program->{'requirements_'.$k})
            <div x-show="tab==='{{ $k }}'">
              <p class="text-sm text-on-surface-variant leading-relaxed whitespace-pre-line">{{ $program->{'requirements_'.$k} }}</p>
            </div>
          @endif
        @endforeach
      </div>
      @endif

    </div>

    {{-- ── ຖັນຂວາ ── --}}
    <div class="space-y-5">

      {{-- ການສະໝັກ --}}
      @if($program->application_url || $program->contact_email)
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
        <h3 class="font-bold text-sm text-on-surface mb-3 flex items-center gap-2">
          <i class="fas fa-paper-plane text-primary text-xs"></i> ການສະໝັກ
        </h3>
        @if($program->application_url)
          <a href="{{ $program->application_url }}" target="_blank"
             class="flex items-center justify-center gap-2 w-full py-2.5 text-sm font-semibold rounded-lg
                    {{ $isOpen ? 'bg-green-600 text-white hover:bg-green-700' : 'bg-surface-container text-outline cursor-not-allowed' }}
                    transition mb-2">
            <i class="fas fa-external-link-alt text-xs"></i>
            {{ $isOpen ? 'ສະໝັກເຂົ້າຮ່ວມ' : 'ລິ້ງສະໝັກ' }}
          </a>
        @endif
        @if($program->contact_email)
          <a href="mailto:{{ $program->contact_email }}"
             class="flex items-center justify-center gap-2 w-full py-2 text-xs font-semibold text-outline hover:text-primary transition">
            <i class="fas fa-envelope text-[10px]"></i> {{ $program->contact_email }}
          </a>
        @endif
      </div>
      @endif

      {{-- ຂໍ້ມູນ --}}
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
        <h3 class="font-bold text-sm text-on-surface mb-3 flex items-center gap-2">
          <i class="fas fa-info-circle text-primary text-xs"></i> ຂໍ້ມູນ
        </h3>
        <div class="space-y-3 text-xs">
          <div>
            <p class="text-outline mb-0.5">ປີ</p>
            <p class="font-bold text-on-surface text-base">{{ $program->year }}</p>
          </div>
          <div>
            <p class="text-outline mb-0.5">ປະເທດ</p>
            <p class="font-semibold">{{ $program->destination_country }}</p>
          </div>
          @if($program->partnerOrganization)
          <div>
            <p class="text-outline mb-0.5">ຄູ່ຮ່ວມ</p>
            <a href="{{ route('admin.partners.show', $program->partnerOrganization) }}"
               class="font-semibold text-primary hover:underline">
              {{ $program->partnerOrganization->name_lo }}
            </a>
          </div>
          @endif
          @if($program->monks_quota)
          <div>
            <p class="text-outline mb-0.5">ໂຄຕ້າ</p>
            <p class="font-semibold">{{ $program->monks_selected }} / {{ $program->monks_quota }} ຮູບ</p>
          </div>
          @endif
          <div>
            <p class="text-outline mb-0.5">ສະຖານະ</p>
            <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-semibold rounded-full {{ $st['class'] }}">
              <i class="fas {{ $st['icon'] }} text-[8px]"></i> {{ $st['lo'] }}
            </span>
          </div>
          @if($program->author)
          <div>
            <p class="text-outline mb-0.5">ຜູ້ສ້າງ</p>
            <p class="font-semibold">{{ $program->author->full_name_lo ?? $program->author->name }}</p>
          </div>
          @endif
          <div>
            <p class="text-outline mb-0.5">ສ້າງເມື່ອ</p>
            <p class="text-on-surface-variant">{{ $program->created_at->format('d/m/Y H:i') }}</p>
          </div>
          <div>
            <p class="text-outline mb-0.5">ແກ້ໄຂລ່າສຸດ</p>
            <p class="text-on-surface-variant">{{ $program->updated_at->format('d/m/Y H:i') }}</p>
          </div>
        </div>
      </div>

      {{-- ກັບຄືນ --}}
      <a href="{{ route('admin.monk-programs.index') }}"
         class="flex items-center justify-center gap-2 w-full px-4 py-2.5 rounded-lg border border-surface-container-high text-on-surface-variant hover:bg-surface-container transition text-sm font-semibold">
        <i class="fas fa-arrow-left text-xs"></i> ກັບຄືນລາຍການ
      </a>
    </div>

  </div>
</div>
@endsection
