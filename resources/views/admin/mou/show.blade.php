@extends('admin.layouts.app')

@section('page_title', 'ລາຍລະອຽດ MOU')

@section('content')
@php
  $st = $statuses[$mou->status] ?? $statuses['active'];
  $isExpired    = $mou->expiry_date && $mou->expiry_date->lt(now());
  $isExpiring   = $mou->expiry_date && !$isExpired && $mou->expiry_date->lt(now()->addDays(30));
  $hasDesc      = $mou->description_lo || $mou->description_en || $mou->description_zh;
  $hasSigners   = $mou->signers_lo || $mou->signers_en || $mou->signers_zh;
  $hasScope     = $mou->scope_lo || $mou->scope_en || $mou->scope_zh;
@endphp

<div class="max-w-4xl mx-auto">

  {{-- Breadcrumb --}}
  <div class="flex items-center gap-2 text-xs text-outline mb-4">
    <a href="{{ route('admin.mou.index') }}" class="hover:text-primary">ສັນຍາ MOU</a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <span class="truncate max-w-[240px]">{{ $mou->title_lo }}</span>
  </div>

  @if(session('success'))
    <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 mb-4 text-sm">
      <i class="fas fa-check-circle text-green-500"></i> {{ session('success') }}
    </div>
  @endif

  @if($isExpiringSoon ?? $isExpiring)
    <div class="flex items-center gap-3 bg-amber-50 border border-amber-200 text-amber-800 rounded-xl px-4 py-3 mb-4 text-sm">
      <i class="fas fa-exclamation-triangle text-amber-500"></i>
      MOU ນີ້ຈະໝົດອາຍຸໃນ <strong>{{ now()->diffInDays($mou->expiry_date) }} ວັນ</strong> ({{ $mou->expiry_date->format('d/m/Y') }})
    </div>
  @endif

  {{-- Action bar --}}
  <div class="flex items-center justify-between mb-5 flex-wrap gap-3">
    <div class="flex items-center gap-2 flex-wrap">
      <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full {{ $st['class'] }}">
        <i class="fas {{ $st['icon'] }} text-[9px]"></i> {{ $st['lo'] }}
      </span>
    </div>
    <div class="flex gap-2">
      @if($mou->document_url)
        <a href="{{ $mou->document_url }}" target="_blank"
           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
          <i class="fas fa-file-pdf text-xs"></i> ເອກະສານ
        </a>
      @endif
      <a href="{{ route('admin.mou.edit', $mou) }}"
         class="inline-flex items-center gap-2 primary-gradient text-white font-semibold px-4 py-2 rounded-lg hover:opacity-90 transition text-sm">
        <i class="fas fa-edit text-xs"></i> ແກ້ໄຂ
      </a>
      <form action="{{ route('admin.mou.destroy', $mou) }}" method="POST"
            onsubmit="return confirm('ລຶບ MOU «{{ $mou->title_lo }}» ແທ້ບໍ?')">
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

      {{-- ບັດ MOU --}}
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-6">
        <div class="flex items-start gap-4">
          <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-file-signature text-primary text-2xl"></i>
          </div>
          <div class="flex-1 min-w-0">
            <h2 class="text-lg font-bold text-on-surface leading-tight">{{ $mou->title_lo }}</h2>
            @if($mou->title_en)
              <p class="text-sm text-outline mt-0.5">{{ $mou->title_en }}</p>
            @endif
            @if($mou->title_zh)
              <p class="text-sm text-outline">{{ $mou->title_zh }}</p>
            @endif
            @if($mou->partnerOrganization)
              <div class="flex items-center gap-1.5 mt-2">
                <i class="fas fa-building text-xs text-outline"></i>
                <span class="text-sm font-semibold text-on-surface-variant">
                  {{ $mou->partnerOrganization->acronym ? "[{$mou->partnerOrganization->acronym}] " : '' }}{{ $mou->partnerOrganization->name_lo }}
                </span>
              </div>
            @endif
          </div>
        </div>
      </div>

      {{-- ລາຍລະອຽດ --}}
      @if($hasDesc)
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5"
           x-data="{ tab: '{{ $mou->description_lo ? 'lo' : ($mou->description_en ? 'en' : 'zh') }}' }">
        <h3 class="font-bold text-sm text-on-surface mb-3 flex items-center gap-2">
          <i class="fas fa-align-left text-primary text-xs"></i> ລາຍລະອຽດ
        </h3>
        <div class="flex gap-1 border-b border-surface-container-high mb-3">
          @foreach(['lo'=>'ລາວ','en'=>'English','zh'=>'中文'] as $k=>$lbl)
            @if($mou->{'description_'.$k})
              <button @click="tab='{{ $k }}'"
                      :class="tab==='{{ $k }}' ? 'border-primary text-primary font-semibold' : 'border-transparent text-outline'"
                      class="px-3 py-1.5 text-xs border-b-2 transition-colors -mb-px">{{ $lbl }}</button>
            @endif
          @endforeach
        </div>
        @foreach(['lo','en','zh'] as $k)
          @if($mou->{'description_'.$k})
            <div x-show="tab==='{{ $k }}'">
              <p class="text-sm text-on-surface-variant leading-relaxed whitespace-pre-line">{{ $mou->{'description_'.$k} }}</p>
            </div>
          @endif
        @endforeach
      </div>
      @endif

      {{-- ຜູ້ລົງນາມ --}}
      @if($hasSigners)
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5"
           x-data="{ tab: '{{ $mou->signers_lo ? 'lo' : ($mou->signers_en ? 'en' : 'zh') }}' }">
        <h3 class="font-bold text-sm text-on-surface mb-3 flex items-center gap-2">
          <i class="fas fa-user-check text-primary text-xs"></i> ຜູ້ລົງນາມ
        </h3>
        <div class="flex gap-1 border-b border-surface-container-high mb-3">
          @foreach(['lo'=>'ລາວ','en'=>'English','zh'=>'中文'] as $k=>$lbl)
            @if($mou->{'signers_'.$k})
              <button @click="tab='{{ $k }}'"
                      :class="tab==='{{ $k }}' ? 'border-primary text-primary font-semibold' : 'border-transparent text-outline'"
                      class="px-3 py-1.5 text-xs border-b-2 transition-colors -mb-px">{{ $lbl }}</button>
            @endif
          @endforeach
        </div>
        @foreach(['lo','en','zh'] as $k)
          @if($mou->{'signers_'.$k})
            <div x-show="tab==='{{ $k }}'">
              <p class="text-sm text-on-surface-variant leading-relaxed whitespace-pre-line">{{ $mou->{'signers_'.$k} }}</p>
            </div>
          @endif
        @endforeach
      </div>
      @endif

      {{-- ຂອບເຂດ --}}
      @if($hasScope)
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5"
           x-data="{ tab: '{{ $mou->scope_lo ? 'lo' : ($mou->scope_en ? 'en' : 'zh') }}' }">
        <h3 class="font-bold text-sm text-on-surface mb-3 flex items-center gap-2">
          <i class="fas fa-list-ul text-primary text-xs"></i> ຂອບເຂດຄວາມຮ່ວມມື
        </h3>
        <div class="flex gap-1 border-b border-surface-container-high mb-3">
          @foreach(['lo'=>'ລາວ','en'=>'English','zh'=>'中文'] as $k=>$lbl)
            @if($mou->{'scope_'.$k})
              <button @click="tab='{{ $k }}'"
                      :class="tab==='{{ $k }}' ? 'border-primary text-primary font-semibold' : 'border-transparent text-outline'"
                      class="px-3 py-1.5 text-xs border-b-2 transition-colors -mb-px">{{ $lbl }}</button>
            @endif
          @endforeach
        </div>
        @foreach(['lo','en','zh'] as $k)
          @if($mou->{'scope_'.$k})
            <div x-show="tab==='{{ $k }}'">
              <p class="text-sm text-on-surface-variant leading-relaxed whitespace-pre-line">{{ $mou->{'scope_'.$k} }}</p>
            </div>
          @endif
        @endforeach
      </div>
      @endif

    </div>

    {{-- ── ຖັນຂວາ ── --}}
    <div class="space-y-5">

      {{-- ອົງກອນຄູ່ຮ່ວມ --}}
      @if($mou->partnerOrganization)
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
        <h3 class="font-bold text-sm text-on-surface mb-3 flex items-center gap-2">
          <i class="fas fa-building text-primary text-xs"></i> ອົງກອນຄູ່ຮ່ວມ
        </h3>
        @php $org = $mou->partnerOrganization; @endphp
        <div class="flex items-center gap-3 mb-3">
          @if($org->logo_url)
            <img src="{{ $org->logo_url }}" alt="" class="w-12 h-12 object-contain rounded-lg border border-surface-container-high bg-white">
          @else
            <div class="w-12 h-12 rounded-lg bg-surface-container flex items-center justify-center flex-shrink-0">
              <i class="fas fa-building text-outline text-xl"></i>
            </div>
          @endif
          <div class="flex-1 min-w-0">
            @if($org->acronym)
              <p class="text-[10px] font-bold text-primary">[{{ $org->acronym }}]</p>
            @endif
            <p class="text-sm font-semibold text-on-surface leading-tight">{{ $org->name_lo }}</p>
            @if($org->name_en)
              <p class="text-xs text-outline">{{ $org->name_en }}</p>
            @endif
          </div>
        </div>
        @if($org->country_name_lo)
          <div class="flex items-center gap-2 text-xs text-outline">
            <i class="fas fa-globe text-[10px]"></i>
            <span>{{ $org->country_name_lo }}</span>
          </div>
        @endif
        @if($org->contact_email)
          <div class="flex items-center gap-2 text-xs text-outline mt-1">
            <i class="fas fa-envelope text-[10px]"></i>
            <a href="mailto:{{ $org->contact_email }}" class="hover:text-primary">{{ $org->contact_email }}</a>
          </div>
        @endif
        @if($org->website_url)
          <a href="{{ $org->website_url }}" target="_blank"
             class="inline-flex items-center gap-1 mt-3 text-xs font-semibold text-primary hover:underline">
            <i class="fas fa-external-link-alt text-[9px]"></i> ເວັບໄຊ
          </a>
        @endif
      </div>
      @endif

      {{-- ຂໍ້ມູນລະບົບ --}}
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
        <h3 class="font-bold text-sm text-on-surface mb-3 flex items-center gap-2">
          <i class="fas fa-info-circle text-primary text-xs"></i> ຂໍ້ມູນ
        </h3>
        <div class="space-y-3 text-xs">
          <div>
            <p class="text-outline mb-0.5">ວັນທີລົງນາມ</p>
            <p class="font-semibold text-on-surface">{{ $mou->signed_date->format('d/m/Y') }}</p>
          </div>
          <div>
            <p class="text-outline mb-0.5">ວັນໝົດອາຍຸ</p>
            @if($mou->expiry_date)
              <p class="font-semibold {{ $isExpired ? 'text-red-600' : ($isExpiring ? 'text-amber-600' : 'text-on-surface') }}">
                {{ $mou->expiry_date->format('d/m/Y') }}
                @if($isExpired) <span class="text-[10px]">(ໝົດອາຍຸແລ້ວ)</span>
                @elseif($isExpiring) <span class="text-[10px]">(ໃກ້ໝົດ)</span>
                @endif
              </p>
            @else
              <p class="text-outline">ບໍ່ກຳນົດ</p>
            @endif
          </div>
          <div>
            <p class="text-outline mb-0.5">ສະຖານະ</p>
            <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[11px] font-semibold rounded-full {{ $st['class'] }}">
              <i class="fas {{ $st['icon'] }} text-[8px]"></i> {{ $st['lo'] }}
            </span>
          </div>
          @if($mou->document_url)
          <div>
            <p class="text-outline mb-0.5">ເອກະສານ</p>
            <a href="{{ $mou->document_url }}" target="_blank"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition-colors border border-red-100">
              <i class="fas fa-file-pdf"></i> ເປີດ / ດາວໂຫຼດ
            </a>
          </div>
          @endif
          <div>
            <p class="text-outline mb-0.5">ສ້າງເມື່ອ</p>
            <p class="text-on-surface-variant">{{ $mou->created_at->format('d/m/Y H:i') }}</p>
          </div>
          <div>
            <p class="text-outline mb-0.5">ແກ້ໄຂລ່າສຸດ</p>
            <p class="text-on-surface-variant">{{ $mou->updated_at->format('d/m/Y H:i') }}</p>
          </div>
        </div>
      </div>

      {{-- ກັບຄືນ --}}
      <a href="{{ route('admin.mou.index') }}"
         class="flex items-center justify-center gap-2 w-full px-4 py-2.5 rounded-lg border border-surface-container-high text-on-surface-variant hover:bg-surface-container transition text-sm font-semibold">
        <i class="fas fa-arrow-left text-xs"></i> ກັບຄືນລາຍການ
      </a>
    </div>
  </div>
</div>
@endsection
