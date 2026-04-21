@extends('admin.layouts.app')

@section('page_title', 'ລາຍລະອຽດອົງກອນ')

@section('content')
@php
  $tm = $types[$partner->type]      ?? $types['other'];
  $st = $statuses[$partner->status] ?? $statuses['active'];
  $hasDesc = $partner->description_lo || $partner->description_en || $partner->description_zh;
@endphp

<div class="max-w-4xl mx-auto">

  {{-- Breadcrumb --}}
  <div class="flex items-center gap-2 text-xs text-outline mb-4">
    <a href="{{ route('admin.partners.index') }}" class="hover:text-primary">ອົງກອນຄູ່ຮ່ວມ</a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <span class="truncate max-w-[240px]">{{ $partner->name_lo }}</span>
  </div>

  @if(session('success'))
    <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 mb-4 text-sm">
      <i class="fas fa-check-circle text-green-500"></i> {{ session('success') }}
    </div>
  @endif

  {{-- Action bar --}}
  <div class="flex items-center justify-between mb-5 flex-wrap gap-3">
    <div class="flex items-center gap-2 flex-wrap">
      <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full {{ $tm['class'] }}">
        <i class="fas {{ $tm['icon'] }} text-[9px]"></i> {{ $tm['lo'] }}
      </span>
      <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full {{ $st['class'] }}">
        {{ $st['lo'] }}
      </span>
    </div>
    <div class="flex gap-2">
      <a href="{{ route('admin.partners.edit', $partner) }}"
         class="inline-flex items-center gap-2 primary-gradient text-white font-semibold px-4 py-2 rounded-lg hover:opacity-90 transition text-sm">
        <i class="fas fa-edit text-xs"></i> ແກ້ໄຂ
      </a>
      <form action="{{ route('admin.partners.destroy', $partner) }}" method="POST"
            onsubmit="return confirm('ລຶບ «{{ $partner->name_lo }}» ແທ້ບໍ?')">
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

      {{-- ບັດອົງກອນ --}}
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-6">
        <div class="flex items-start gap-5">
          {{-- Logo --}}
          <div class="w-20 h-20 rounded-xl border border-surface-container-high bg-white flex items-center justify-center flex-shrink-0 p-2">
            @if($partner->logo_url)
              <img src="{{ $partner->logo_url }}" alt="{{ $partner->name_lo }}"
                   class="max-w-full max-h-full object-contain"
                   onerror="this.style.display='none'; this.parentElement.innerHTML='<i class=\'fas fa-building text-outline text-3xl\'></i>'">
            @else
              <i class="fas fa-building text-outline text-3xl"></i>
            @endif
          </div>
          <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 flex-wrap">
              @if($partner->acronym)
                <span class="text-xs font-bold px-2 py-0.5 rounded bg-primary/10 text-primary">{{ $partner->acronym }}</span>
              @endif
              <h2 class="text-lg font-bold text-on-surface leading-tight">{{ $partner->name_lo }}</h2>
            </div>
            @if($partner->name_en)
              <p class="text-sm text-outline mt-0.5">{{ $partner->name_en }}</p>
            @endif
            @if($partner->name_zh)
              <p class="text-sm text-outline">{{ $partner->name_zh }}</p>
            @endif
            <div class="flex flex-wrap items-center gap-3 mt-2 text-xs text-outline">
              <span class="flex items-center gap-1">
                <span class="font-bold font-mono bg-surface-container px-1.5 py-0.5 rounded text-on-surface-variant">{{ $partner->country_code }}</span>
                {{ $partner->country_name_lo }}
              </span>
              @if($partner->partnership_since)
                <span>· ຮ່ວມງານຕັ້ງແຕ່ ປີ {{ $partner->partnership_since }}</span>
              @endif
            </div>
          </div>
        </div>
      </div>

      {{-- ລາຍລະອຽດ --}}
      @if($hasDesc)
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5"
           x-data="{ tab: '{{ $partner->description_lo ? 'lo' : ($partner->description_en ? 'en' : 'zh') }}' }">
        <h3 class="font-bold text-sm text-on-surface mb-3 flex items-center gap-2">
          <i class="fas fa-align-left text-primary text-xs"></i> ກ່ຽວກັບອົງກອນ
        </h3>
        <div class="flex gap-1 border-b border-surface-container-high mb-3">
          @foreach(['lo'=>'ລາວ','en'=>'English','zh'=>'中文'] as $k=>$lbl)
            @if($partner->{'description_'.$k})
              <button @click="tab='{{ $k }}'"
                      :class="tab==='{{ $k }}' ? 'border-primary text-primary font-semibold' : 'border-transparent text-outline'"
                      class="px-3 py-1.5 text-xs border-b-2 transition-colors -mb-px">{{ $lbl }}</button>
            @endif
          @endforeach
        </div>
        @foreach(['lo','en','zh'] as $k)
          @if($partner->{'description_'.$k})
            <div x-show="tab==='{{ $k }}'">
              <p class="text-sm text-on-surface-variant leading-relaxed whitespace-pre-line">{{ $partner->{'description_'.$k} }}</p>
            </div>
          @endif
        @endforeach
      </div>
      @endif

      {{-- MOU ທີ່ຜູກພັນ --}}
      @if($partner->mouAgreements->isNotEmpty())
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
        <div class="flex items-center justify-between mb-3">
          <h3 class="font-bold text-sm text-on-surface flex items-center gap-2">
            <i class="fas fa-file-signature text-primary text-xs"></i>
            ສັນຍາ MOU ({{ $partner->mou_agreements_count }})
          </h3>
          <a href="{{ route('admin.mou.create') }}"
             class="inline-flex items-center gap-1 text-xs font-semibold text-primary hover:underline">
            <i class="fas fa-plus text-[9px]"></i> ເພີ່ມ MOU
          </a>
        </div>
        <div class="space-y-2">
          @foreach($partner->mouAgreements->take(5) as $mou)
          @php
            $mouStatuses = ['active'=>'bg-green-100 text-green-700','pending'=>'bg-amber-100 text-amber-700','renewed'=>'bg-blue-100 text-blue-700','expired'=>'bg-gray-100 text-gray-500','terminated'=>'bg-red-100 text-red-600'];
            $mouLabels   = ['active'=>'ໃຊ້ງານ','pending'=>'ລໍຖ້າ','renewed'=>'ຕໍ່ອາຍຸ','expired'=>'ໝົດ','terminated'=>'ຍົກເລີກ'];
          @endphp
          <div class="flex items-center gap-3 p-3 rounded-lg bg-surface-container-low border border-surface-container-high hover:bg-surface-container transition-colors">
            <div class="flex-1 min-w-0">
              <a href="{{ route('admin.mou.show', $mou) }}" class="text-sm font-semibold text-on-surface hover:text-primary truncate block">
                {{ $mou->title_lo }}
              </a>
              <p class="text-xs text-outline">ລົງນາມ {{ $mou->signed_date->format('d/m/Y') }}</p>
            </div>
            <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full flex-shrink-0 {{ $mouStatuses[$mou->status] ?? 'bg-gray-100 text-gray-500' }}">
              {{ $mouLabels[$mou->status] ?? $mou->status }}
            </span>
          </div>
          @endforeach
          @if($partner->mouAgreements->count() > 5)
            <a href="{{ route('admin.mou.index', ['partner_id' => $partner->id]) }}"
               class="text-xs text-primary hover:underline flex items-center gap-1 pt-1">
              ເບິ່ງທັງໝົດ {{ $partner->mou_agreements_count }} ລາຍການ
              <i class="fas fa-arrow-right text-[9px]"></i>
            </a>
          @endif
        </div>
      </div>
      @else
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
        <h3 class="font-bold text-sm text-on-surface mb-2 flex items-center gap-2">
          <i class="fas fa-file-signature text-primary text-xs"></i> ສັນຍາ MOU
        </h3>
        <p class="text-xs text-outline mb-3">ຍັງບໍ່ມີ MOU ກັບອົງກອນນີ້</p>
        <a href="{{ route('admin.mou.create') }}"
           class="inline-flex items-center gap-1.5 text-xs font-semibold text-primary hover:underline">
          <i class="fas fa-plus text-[9px]"></i> ເພີ່ມ MOU ໃໝ່
        </a>
      </div>
      @endif

    </div>

    {{-- ── ຖັນຂວາ ── --}}
    <div class="space-y-5">

      {{-- ຂໍ້ມູນຕິດຕໍ່ --}}
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
        <h3 class="font-bold text-sm text-on-surface mb-3 flex items-center gap-2">
          <i class="fas fa-address-card text-primary text-xs"></i> ຂໍ້ມູນຕິດຕໍ່
        </h3>
        <div class="space-y-2.5 text-xs">
          @if($partner->contact_person)
            <div class="flex items-center gap-2">
              <i class="fas fa-user text-outline w-4 text-center text-[10px]"></i>
              <span class="text-on-surface-variant">{{ $partner->contact_person }}</span>
            </div>
          @endif
          @if($partner->contact_email)
            <div class="flex items-center gap-2">
              <i class="fas fa-envelope text-outline w-4 text-center text-[10px]"></i>
              <a href="mailto:{{ $partner->contact_email }}" class="text-primary hover:underline break-all">{{ $partner->contact_email }}</a>
            </div>
          @endif
          @if($partner->contact_phone)
            <div class="flex items-center gap-2">
              <i class="fas fa-phone text-outline w-4 text-center text-[10px]"></i>
              <a href="tel:{{ $partner->contact_phone }}" class="text-on-surface-variant">{{ $partner->contact_phone }}</a>
            </div>
          @endif
          @if($partner->website_url)
            <div class="flex items-center gap-2">
              <i class="fas fa-globe text-outline w-4 text-center text-[10px]"></i>
              <a href="{{ $partner->website_url }}" target="_blank" class="text-primary hover:underline break-all">
                {{ parse_url($partner->website_url, PHP_URL_HOST) }}
              </a>
            </div>
          @endif
          @if(!$partner->contact_person && !$partner->contact_email && !$partner->contact_phone && !$partner->website_url)
            <p class="text-outline italic">ບໍ່ມີຂໍ້ມູນຕິດຕໍ່</p>
          @endif
        </div>
      </div>

      {{-- ຂໍ້ມູນລະບົບ --}}
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
        <h3 class="font-bold text-sm text-on-surface mb-3 flex items-center gap-2">
          <i class="fas fa-info-circle text-primary text-xs"></i> ຂໍ້ມູນ
        </h3>
        <div class="space-y-3 text-xs">
          <div>
            <p class="text-outline mb-0.5">ລະຫັດ</p>
            <p class="font-semibold">{{ $partner->id }}</p>
          </div>
          <div>
            <p class="text-outline mb-0.5">ປະເທດ</p>
            <div class="flex items-center gap-1.5">
              <span class="font-bold font-mono bg-surface-container px-1.5 py-0.5 rounded">{{ $partner->country_code }}</span>
              <span class="text-on-surface-variant">{{ $partner->country_name_lo }}</span>
            </div>
          </div>
          <div>
            <p class="text-outline mb-0.5">ປະເພດ</p>
            <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-semibold rounded-full {{ $tm['class'] }}">
              <i class="fas {{ $tm['icon'] }} text-[8px]"></i> {{ $tm['lo'] }}
            </span>
          </div>
          <div>
            <p class="text-outline mb-0.5">ສະຖານະ</p>
            <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-semibold rounded-full {{ $st['class'] }}">
              {{ $st['lo'] }}
            </span>
          </div>
          @if($partner->partnership_since)
          <div>
            <p class="text-outline mb-0.5">ຮ່ວມມືຕັ້ງແຕ່</p>
            <p class="font-semibold">ປີ {{ $partner->partnership_since }}</p>
          </div>
          @endif
          <div>
            <p class="text-outline mb-0.5">ລຳດັບ</p>
            <p class="font-semibold">{{ $partner->sort_order }}</p>
          </div>
          <div>
            <p class="text-outline mb-0.5">ສ້າງເມື່ອ</p>
            <p class="text-on-surface-variant">{{ $partner->created_at->format('d/m/Y H:i') }}</p>
          </div>
          <div>
            <p class="text-outline mb-0.5">ແກ້ໄຂລ່າສຸດ</p>
            <p class="text-on-surface-variant">{{ $partner->updated_at->format('d/m/Y H:i') }}</p>
          </div>
        </div>
      </div>

      {{-- ກັບຄືນ --}}
      <a href="{{ route('admin.partners.index') }}"
         class="flex items-center justify-center gap-2 w-full px-4 py-2.5 rounded-lg border border-surface-container-high text-on-surface-variant hover:bg-surface-container transition text-sm font-semibold">
        <i class="fas fa-arrow-left text-xs"></i> ກັບຄືນລາຍການ
      </a>
    </div>

  </div>
</div>
@endsection
