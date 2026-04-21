@extends('admin.layouts.app')

@section('page_title', 'ລາຍລະອຽດ Slide')

@section('content')
<div class="max-w-4xl mx-auto">

  {{-- Breadcrumb --}}
  <div class="flex items-center gap-2 text-xs text-outline mb-4">
    <a href="{{ route('admin.slides.index') }}" class="hover:text-primary transition-colors">Hero Slides</a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <span class="truncate max-w-[240px]">{{ $slide->title_lo }}</span>
  </div>

  {{-- Action bar --}}
  <div class="flex items-center justify-between mb-5">
    <div class="flex items-center gap-2">
      @if($slide->is_active)
        <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
          <i class="fas fa-circle text-[6px]"></i> ໃຊ້ງານ
        </span>
      @else
        <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-500">
          <i class="fas fa-circle text-[6px]"></i> ປິດ
        </span>
      @endif
      <span class="text-xs text-outline">ລຳດັບ: {{ $slide->sort_order }}</span>
    </div>
    <div class="flex gap-2">
      <a href="{{ route('admin.slides.edit', $slide) }}"
         class="inline-flex items-center gap-2 primary-gradient text-white font-semibold px-4 py-2 rounded-lg hover:opacity-90 transition text-sm">
        <i class="fas fa-edit text-xs"></i> ແກ້ໄຂ
      </a>
      <form action="{{ route('admin.slides.destroy', $slide) }}" method="POST"
            onsubmit="return confirm('ທ່ານຕ້ອງການລຶບ Slide «{{ $slide->title_lo }}» ແທ້ບໍ?')">
        @csrf
        @method('DELETE')
        <button type="submit"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-red-200 text-red-600 hover:bg-red-50 transition text-sm font-semibold">
          <i class="fas fa-trash text-xs"></i> ລຶບ
        </button>
      </form>
    </div>
  </div>

  {{-- Slide Preview Card --}}
  <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] overflow-hidden mb-5">
    {{-- Hero image with overlay --}}
    <div class="relative h-56 md:h-72 bg-surface-container-high overflow-hidden">
      <img src="{{ Str::startsWith($slide->image_url, 'http') ? $slide->image_url : asset($slide->image_url) }}"
           class="w-full h-full object-cover" alt="{{ $slide->title_lo }}"
           onerror="this.parentElement.querySelector('.placeholder').style.display='flex'; this.style.display='none';">
      <div class="placeholder hidden absolute inset-0 items-center justify-center bg-surface-container">
        <i class="fas fa-image text-4xl text-outline opacity-30"></i>
      </div>
      {{-- Overlay preview --}}
      <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent flex flex-col justify-end p-6">
        @if($slide->tag_lo)
          <span class="inline-block self-start px-2 py-0.5 text-xs font-bold bg-primary text-white rounded mb-2">
            {{ $slide->tag_lo }}
          </span>
        @endif
        <h2 class="text-white text-xl font-bold leading-tight">{{ $slide->title_lo }}</h2>
        @if($slide->subtitle_lo)
          <p class="text-white/80 text-xs mt-1 line-clamp-2">{{ $slide->subtitle_lo }}</p>
        @endif
        @if($slide->btn1_text_lo || $slide->btn2_text_lo)
          <div class="flex gap-2 mt-3">
            @if($slide->btn1_text_lo)
              <span class="px-3 py-1.5 text-xs font-semibold rounded-full bg-white text-primary">
                {{ $slide->btn1_text_lo }}
              </span>
            @endif
            @if($slide->btn2_text_lo)
              <span class="px-3 py-1.5 text-xs font-semibold rounded-full border border-white text-white">
                {{ $slide->btn2_text_lo }}
              </span>
            @endif
          </div>
        @endif
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- ── ຖັນຊ້າຍ: ເນື້ອໃນ ── --}}
    <div class="lg:col-span-2 space-y-5">

      {{-- ຊື່ ແລະ Tag --}}
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
        <h3 class="font-bold text-sm text-on-surface mb-3 flex items-center gap-2">
          <i class="fas fa-heading text-primary text-xs"></i> ຫົວຂໍ້ ແລະ Tag
        </h3>
        <div x-data="{ tab: 'lo' }">
          <div class="flex gap-1 border-b border-surface-container-high mb-3">
            @foreach(['lo' => 'ລາວ', 'en' => 'English', 'zh' => '中文'] as $key => $label)
            <button type="button" @click="tab = '{{ $key }}'"
                    :class="tab === '{{ $key }}' ? 'border-primary text-primary font-semibold' : 'border-transparent text-outline'"
                    class="px-3 py-1.5 text-xs border-b-2 transition-colors -mb-px">
              {{ $label }}
            </button>
            @endforeach
          </div>

          @foreach(['lo' => 'ລາວ', 'en' => 'EN', 'zh' => 'ZH'] as $key => $label)
          <div x-show="tab === '{{ $key }}'" class="space-y-3">
            <div>
              <p class="text-xs text-outline mb-0.5">Tag</p>
              <p class="text-sm text-on-surface">{{ $slide->{'tag_' . $key} ?: '—' }}</p>
            </div>
            <div>
              <p class="text-xs text-outline mb-0.5">ຫົວຂໍ້</p>
              <p class="text-sm font-semibold text-on-surface">{{ $slide->{'title_' . $key} ?: '—' }}</p>
            </div>
            <div>
              <p class="text-xs text-outline mb-0.5">ຄຳບັນຍາຍ</p>
              <p class="text-sm text-on-surface-variant">{{ $slide->{'subtitle_' . $key} ?: '—' }}</p>
            </div>
          </div>
          @endforeach
        </div>
      </div>

      {{-- ປຸ່ມ CTA --}}
      @if($slide->btn1_text_lo || $slide->btn2_text_lo)
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
        <h3 class="font-bold text-sm text-on-surface mb-3 flex items-center gap-2">
          <i class="fas fa-mouse-pointer text-primary text-xs"></i> ປຸ່ມ Call-to-Action
        </h3>
        <div class="space-y-4">
          @if($slide->btn1_text_lo || $slide->btn1_url)
          <div class="p-3 rounded-lg bg-surface-container-low border border-surface-container-high">
            <p class="text-xs font-bold text-outline uppercase mb-2">
              <i class="fas fa-circle text-primary text-[8px] mr-1"></i> ປຸ່ມທີ 1
            </p>
            <div class="grid grid-cols-3 gap-3 text-xs mb-2">
              <div><span class="text-outline block">ລາວ</span> {{ $slide->btn1_text_lo ?: '—' }}</div>
              <div><span class="text-outline block">EN</span> {{ $slide->btn1_text_en ?: '—' }}</div>
              <div><span class="text-outline block">ZH</span> {{ $slide->btn1_text_zh ?: '—' }}</div>
            </div>
            @if($slide->btn1_url)
              <p class="text-xs text-outline mt-1">URL:
                <a href="{{ $slide->btn1_url }}" target="_blank" class="text-primary hover:underline">
                  {{ $slide->btn1_url }}
                </a>
              </p>
            @endif
          </div>
          @endif

          @if($slide->btn2_text_lo || $slide->btn2_url)
          <div class="p-3 rounded-lg bg-surface-container-low border border-surface-container-high">
            <p class="text-xs font-bold text-outline uppercase mb-2">
              <i class="fas fa-circle text-outline text-[8px] mr-1"></i> ປຸ່ມທີ 2
            </p>
            <div class="grid grid-cols-3 gap-3 text-xs mb-2">
              <div><span class="text-outline block">ລາວ</span> {{ $slide->btn2_text_lo ?: '—' }}</div>
              <div><span class="text-outline block">EN</span> {{ $slide->btn2_text_en ?: '—' }}</div>
              <div><span class="text-outline block">ZH</span> {{ $slide->btn2_text_zh ?: '—' }}</div>
            </div>
            @if($slide->btn2_url)
              <p class="text-xs text-outline mt-1">URL:
                <a href="{{ $slide->btn2_url }}" target="_blank" class="text-primary hover:underline">
                  {{ $slide->btn2_url }}
                </a>
              </p>
            @endif
          </div>
          @endif
        </div>
      </div>
      @endif

    </div>

    {{-- ── ຖັນຂວາ ── --}}
    <div class="space-y-5">

      {{-- ຂໍ້ມູນລະບົບ --}}
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
        <h3 class="font-bold text-sm text-on-surface mb-3 flex items-center gap-2">
          <i class="fas fa-info-circle text-primary text-xs"></i> ຂໍ້ມູນລະບົບ
        </h3>
        <div class="space-y-3 text-xs">
          <div>
            <p class="text-outline mb-0.5">ລະຫັດ</p>
            <p class="font-semibold text-on-surface">{{ $slide->id }}</p>
          </div>
          <div>
            <p class="text-outline mb-0.5">ລຳດັບ (Sort Order)</p>
            <p class="font-semibold text-on-surface">{{ $slide->sort_order }}</p>
          </div>
          <div>
            <p class="text-outline mb-0.5">ສະຖານະ</p>
            @if($slide->is_active)
              <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                <i class="fas fa-circle text-[6px]"></i> ໃຊ້ງານ
              </span>
            @else
              <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-semibold rounded-full bg-gray-100 text-gray-500">
                <i class="fas fa-circle text-[6px]"></i> ປິດ
              </span>
            @endif
          </div>
          <div>
            <p class="text-outline mb-0.5">URL ຮູບ</p>
            <p class="font-mono text-[10px] text-on-surface-variant break-all bg-surface-container px-2 py-1 rounded">
              {{ $slide->image_url }}
            </p>
          </div>
          <div>
            <p class="text-outline mb-0.5">ສ້າງເມື່ອ</p>
            <p class="text-on-surface-variant">{{ $slide->created_at->format('d/m/Y H:i') }}</p>
          </div>
          <div>
            <p class="text-outline mb-0.5">ແກ້ໄຂລ່າສຸດ</p>
            <p class="text-on-surface-variant">{{ $slide->updated_at->format('d/m/Y H:i') }}</p>
          </div>
        </div>
      </div>

      {{-- ກັບຄືນ --}}
      <a href="{{ route('admin.slides.index') }}"
         class="flex items-center justify-center gap-2 w-full px-4 py-2.5 rounded-lg border border-surface-container-high text-on-surface-variant hover:bg-surface-container transition text-sm font-semibold">
        <i class="fas fa-arrow-left text-xs"></i> ກັບຄືນລາຍການ
      </a>

    </div>
  </div>

</div>
@endsection
