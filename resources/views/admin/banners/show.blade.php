@extends('admin.layouts.app')

@section('page_title', $banner->title_lo)

@section('content')
<div class="max-w-4xl mx-auto space-y-5">

  {{-- Breadcrumb --}}
  <div class="flex items-center gap-2 text-xs text-outline">
    <a href="{{ route('admin.banners.index') }}" class="hover:text-primary">Banners</a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <span class="truncate max-w-[200px]">{{ $banner->title_lo }}</span>
  </div>

  {{-- Banner Preview --}}
  @php
    $st = $styles[$banner->style] ?? $styles['banner-blue'];
    $po = $positions[$banner->position] ?? $positions['sidebar'];
  @endphp
  <div class="bg-gradient-to-br {{ $st['preview'] }} rounded-xl overflow-hidden shadow-[0px_4px_16px_rgba(26,28,29,0.12)]">
    <div class="flex flex-col sm:flex-row items-center gap-5 p-6">
      @if($banner->image_url)
        <img src="{{ $banner->image_url }}" alt="{{ $banner->title_lo }}"
             class="w-full sm:w-40 h-28 object-cover rounded-lg shadow shrink-0">
      @endif
      <div class="flex-1 min-w-0 {{ $st['text'] }}">
        <p class="text-lg font-extrabold leading-snug">{{ $banner->title_lo }}</p>
        @if($banner->title_en)
          <p class="text-sm opacity-80 mt-0.5">{{ $banner->title_en }}</p>
        @endif
        @if($banner->subtitle_lo)
          <p class="text-sm opacity-70 mt-2 leading-relaxed">{{ Str::limit($banner->subtitle_lo, 120) }}</p>
        @endif
        @if($banner->btn_text_lo && $banner->btn_url)
          <div class="mt-4">
            <span class="inline-block px-4 py-1.5 rounded-lg text-xs font-bold bg-white/20 border border-white/30 {{ $st['text'] }}">
              {{ $banner->btn_text_lo }} →
            </span>
          </div>
        @endif
      </div>
    </div>
  </div>

  {{-- Header actions --}}
  <div class="flex items-center justify-between">
    <div class="flex flex-wrap items-center gap-2">
      <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-surface-container text-on-surface-variant">
        <i class="fas {{ $po['icon'] }} text-[9px]"></i> {{ $po['lo'] }}
      </span>
      @if($banner->is_active)
        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
          <i class="fas fa-circle text-[7px]"></i> ໃຊ້ງານ
        </span>
      @else
        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-500">
          <i class="fas fa-circle text-[7px]"></i> ປິດໃຊ້
        </span>
      @endif
      <span class="text-xs text-outline">ລຳດັບ: <strong>{{ $banner->sort_order }}</strong></span>
    </div>
    <div class="flex items-center gap-2">
      <a href="{{ route('admin.banners.edit', $banner) }}"
         class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg border border-surface-container-high text-sm font-semibold hover:bg-surface-container transition-colors">
        <i class="fas fa-edit text-xs text-yellow-600"></i> ແກ້ໄຂ
      </a>
      <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST"
            onsubmit="return confirm('ລຶບ Banner «{{ $banner->title_lo }}» ແທ້ບໍ?')">
        @csrf @method('DELETE')
        <button type="submit"
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg border border-red-200 text-sm font-semibold text-red-600 hover:bg-red-50 transition-colors">
          <i class="fas fa-trash text-xs"></i> ລຶບ
        </button>
      </form>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Left: Content detail --}}
    <div class="lg:col-span-2 space-y-5">

      {{-- Titles --}}
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
        <h3 class="font-bold text-sm text-on-surface mb-3 flex items-center gap-2">
          <i class="fas fa-font text-primary text-xs"></i> ຫົວຂໍ້
        </h3>
        <dl class="space-y-3">
          <div>
            <dt class="text-[10px] text-outline uppercase tracking-wide mb-0.5">ລາວ</dt>
            <dd class="text-sm font-semibold text-on-surface">{{ $banner->title_lo }}</dd>
          </div>
          @if($banner->title_en)
          <div>
            <dt class="text-[10px] text-outline uppercase tracking-wide mb-0.5">EN</dt>
            <dd class="text-sm text-on-surface-variant">{{ $banner->title_en }}</dd>
          </div>
          @endif
          @if($banner->title_zh)
          <div>
            <dt class="text-[10px] text-outline uppercase tracking-wide mb-0.5">ZH</dt>
            <dd class="text-sm text-on-surface-variant">{{ $banner->title_zh }}</dd>
          </div>
          @endif
        </dl>
      </div>

      {{-- Subtitle --}}
      @if($banner->subtitle_lo || $banner->subtitle_en || $banner->subtitle_zh)
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5"
           x-data="{ lang: '{{ $banner->subtitle_lo ? 'lo' : ($banner->subtitle_en ? 'en' : 'zh') }}' }">
        <div class="flex items-center justify-between mb-3">
          <h3 class="font-bold text-sm text-on-surface flex items-center gap-2">
            <i class="fas fa-align-left text-primary text-xs"></i> ຄຳບັນຍາຍ
          </h3>
          <div class="flex gap-1">
            @if($banner->subtitle_lo)
              <button @click="lang='lo'" :class="lang==='lo' ? 'primary-gradient text-white' : 'border border-surface-container-high hover:bg-surface-container'"
                      class="px-2.5 py-1 rounded text-[11px] font-semibold transition-colors">LO</button>
            @endif
            @if($banner->subtitle_en)
              <button @click="lang='en'" :class="lang==='en' ? 'primary-gradient text-white' : 'border border-surface-container-high hover:bg-surface-container'"
                      class="px-2.5 py-1 rounded text-[11px] font-semibold transition-colors">EN</button>
            @endif
            @if($banner->subtitle_zh)
              <button @click="lang='zh'" :class="lang==='zh' ? 'primary-gradient text-white' : 'border border-surface-container-high hover:bg-surface-container'"
                      class="px-2.5 py-1 rounded text-[11px] font-semibold transition-colors">ZH</button>
            @endif
          </div>
        </div>
        @if($banner->subtitle_lo)
          <div x-show="lang==='lo'" class="text-sm text-on-surface-variant leading-relaxed whitespace-pre-wrap">{{ $banner->subtitle_lo }}</div>
        @endif
        @if($banner->subtitle_en)
          <div x-show="lang==='en'" class="text-sm text-on-surface-variant leading-relaxed whitespace-pre-wrap">{{ $banner->subtitle_en }}</div>
        @endif
        @if($banner->subtitle_zh)
          <div x-show="lang==='zh'" class="text-sm text-on-surface-variant leading-relaxed whitespace-pre-wrap">{{ $banner->subtitle_zh }}</div>
        @endif
      </div>
      @endif

      {{-- Button CTA --}}
      @if($banner->btn_text_lo || $banner->btn_url)
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
        <h3 class="font-bold text-sm text-on-surface mb-3 flex items-center gap-2">
          <i class="fas fa-mouse-pointer text-primary text-xs"></i> ປຸ່ມ CTA
        </h3>
        <dl class="space-y-2">
          @if($banner->btn_text_lo)
          <div class="flex items-center gap-2">
            <dt class="text-[10px] text-outline uppercase tracking-wide w-8">LO</dt>
            <dd class="text-sm text-on-surface">{{ $banner->btn_text_lo }}</dd>
          </div>
          @endif
          @if($banner->btn_text_en)
          <div class="flex items-center gap-2">
            <dt class="text-[10px] text-outline uppercase tracking-wide w-8">EN</dt>
            <dd class="text-sm text-on-surface">{{ $banner->btn_text_en }}</dd>
          </div>
          @endif
          @if($banner->btn_text_zh)
          <div class="flex items-center gap-2">
            <dt class="text-[10px] text-outline uppercase tracking-wide w-8">ZH</dt>
            <dd class="text-sm text-on-surface">{{ $banner->btn_text_zh }}</dd>
          </div>
          @endif
          @if($banner->btn_url)
          <div class="pt-1 border-t border-surface-container-high">
            <dt class="text-[10px] text-outline uppercase tracking-wide mb-1">URL</dt>
            <a href="{{ $banner->btn_url }}" target="_blank" rel="noopener noreferrer"
               class="text-xs text-primary hover:underline break-all">
              <i class="fas fa-external-link-alt text-[9px]"></i> {{ $banner->btn_url }}
            </a>
          </div>
          @endif
        </dl>
      </div>
      @endif

    </div>

    {{-- Right sidebar --}}
    <div class="space-y-5">

      {{-- Style preview --}}
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
        <h3 class="font-bold text-sm text-on-surface mb-3 flex items-center gap-2">
          <i class="fas fa-palette text-primary text-xs"></i> ຮູບແບບ
        </h3>
        <div class="h-10 rounded-lg bg-gradient-to-r {{ $st['preview'] }} mb-2"></div>
        <p class="text-xs font-semibold text-on-surface">{{ $st['lo'] }}</p>
        <p class="text-[10px] text-outline font-mono">{{ $banner->style }}</p>
      </div>

      {{-- Image --}}
      @if($banner->image_url)
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
        <h3 class="font-bold text-sm text-on-surface mb-3 flex items-center gap-2">
          <i class="fas fa-image text-primary text-xs"></i> ຮູບພາບ
        </h3>
        <img src="{{ $banner->image_url }}" alt="{{ $banner->title_lo }}"
             class="w-full rounded-lg object-cover border border-surface-container-high max-h-36">
        <p class="text-[10px] text-outline mt-2 break-all">{{ $banner->image_url }}</p>
      </div>
      @endif

      {{-- System info --}}
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
        <h3 class="font-bold text-sm text-on-surface mb-3 flex items-center gap-2">
          <i class="fas fa-info-circle text-primary text-xs"></i> ຂໍ້ມູນລະບົບ
        </h3>
        <dl class="space-y-2.5">
          <div>
            <dt class="text-[10px] text-outline uppercase tracking-wide">Position</dt>
            <dd class="text-xs font-semibold text-on-surface-variant mt-0.5 flex items-center gap-1">
              <i class="fas {{ $po['icon'] }} text-[9px]"></i> {{ $po['lo'] }}
            </dd>
          </div>
          <div>
            <dt class="text-[10px] text-outline uppercase tracking-wide">Sort Order</dt>
            <dd class="text-xs text-on-surface-variant mt-0.5">{{ $banner->sort_order }}</dd>
          </div>
          <div>
            <dt class="text-[10px] text-outline uppercase tracking-wide">ສ້າງວັນທີ</dt>
            <dd class="text-xs text-on-surface-variant mt-0.5">{{ $banner->created_at->format('d/m/Y H:i') }}</dd>
          </div>
          <div>
            <dt class="text-[10px] text-outline uppercase tracking-wide">ອັບເດດລ່າສຸດ</dt>
            <dd class="text-xs text-on-surface-variant mt-0.5">{{ $banner->updated_at->format('d/m/Y H:i') }}</dd>
          </div>
        </dl>
      </div>

      {{-- Back --}}
      <a href="{{ route('admin.banners.index') }}"
         class="flex items-center gap-2 text-sm text-on-surface-variant hover:text-primary transition-colors">
        <i class="fas fa-arrow-left text-xs"></i> ກັບໄປລາຍການ
      </a>

    </div>
  </div>

</div>
@endsection
