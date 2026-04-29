@extends('front.layouts.app')

@php
  $L = app()->getLocale();
  $t = fn($lo,$en,$zh) => match($L){'zh'=>$zh,'en'=>$en,default=>$lo};
@endphp

@section('title', $t('ຜົນການຄົ້ນຫາ','Search Results','搜索結果').' - '.($settings->site_name_lo ?: 'BFOL'))

@push('styles')
<style>
  .dot-pattern { background-image:radial-gradient(circle,rgba(255,255,255,.12) 1px,transparent 1px); background-size:28px 28px; }
</style>
@endpush

@section('content')

{{-- ═══ HERO + SEARCH BAR ═══ --}}
<section class="relative bg-primary overflow-hidden">
  <div class="dot-pattern absolute inset-0 pointer-events-none"></div>
  <div class="absolute -top-24 -right-24 w-80 h-80 bg-secondary/10 rounded-full blur-3xl pointer-events-none"></div>
  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-12 relative z-10 text-center">
    <h1 class="text-3xl sm:text-4xl font-serif font-bold text-on-primary mb-2">
      {{ $t('ຜົນການຄົ້ນຫາ','Search Results','搜索結果') }}
    </h1>
    <p class="text-on-primary/70 text-sm mb-6">
      {{ $t('ພົບ','Found','找到') }}
      <strong class="text-secondary">{{ count($results) }}</strong>
      {{ $t('ຜົນ ສຳລັບ','results for','條結果，搜索') }}
      "<em class="text-secondary not-italic font-semibold">{{ $q }}</em>"
    </p>
    {{-- Re-search --}}
    <form action="{{ route('front.search') }}" method="GET" class="relative max-w-xl mx-auto">
      <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-outline text-sm pointer-events-none"></i>
      <input type="text" name="q" value="{{ $q }}"
             placeholder="{{ $t('ຄົ້ນຫາໃໝ່...','Search again...','重新搜索...') }}"
             class="w-full pl-10 pr-28 py-3 bg-white rounded-xl text-sm text-on-surface
                    focus:ring-2 focus:ring-primary/30 transition-colors" />
      <button type="submit"
              class="absolute right-1.5 top-1.5 px-4 py-2 bg-primary text-on-primary text-xs font-bold rounded-lg
                     hover:bg-secondary hover:text-on-secondary transition-colors">
        {{ $t('ຄົ້ນຫາ','Search','搜索') }}
      </button>
    </form>
  </div>
</section>

{{-- ═══ RESULTS ═══ --}}
<section class="bg-slate-50 py-10">
  <div class="max-w-[900px] mx-auto px-4 sm:px-6 lg:px-8">

    @if(count($results) > 0)
      <div class="flex flex-col gap-4">
        @foreach($results as $result)
          @php
            $type = class_basename($result);
            $link = '#';
            $icon = 'fas fa-file-alt';
            $badgeBg = 'bg-slate-100'; $badgeText = 'text-slate-600';
            $typeLabel = $type;

            if ($type === 'News') {
                $link       = route('front.news.show', $result->slug);
                $icon       = 'fas fa-newspaper';
                $badgeBg    = 'bg-blue-100'; $badgeText = 'text-blue-700';
                $typeLabel  = $t('ຂ່າວ','News','新聞');
            } elseif ($type === 'Event') {
                $link       = route('front.events.show', $result->slug);
                $icon       = 'far fa-calendar-alt';
                $badgeBg    = 'bg-violet-100'; $badgeText = 'text-violet-700';
                $typeLabel  = $t('ກິດຈະກຳ','Event','活動');
            } elseif ($type === 'Page') {
                $link       = route('front.page.show', $result->slug);
                $icon       = 'fas fa-file-signature';
                $badgeBg    = 'bg-emerald-100'; $badgeText = 'text-emerald-700';
                $typeLabel  = $t('ໜ້າ','Page','頁面');
            }

            $title   = $result->trans('title') ?: ($result->trans('name') ?: '—');
            $content = Str::limit(strip_tags($result->trans('content') ?: $result->trans('description') ?: ''), 180);
          @endphp

          <a href="{{ $link }}"
             class="group bg-white rounded-2xl border border-slate-100 shadow-[0_2px_12px_-4px_rgba(0,0,0,.07)]
                    hover:shadow-[0_6px_24px_-4px_rgba(3,22,50,.12)] hover:-translate-y-0.5 hover:border-primary/20
                    transition-all duration-300 p-5 flex items-start gap-4">
            <div class="w-11 h-11 rounded-xl bg-surface-container flex items-center justify-center shrink-0
                        group-hover:bg-primary/8 transition-colors">
              <i class="{{ $icon }} text-outline group-hover:text-primary text-base transition-colors"></i>
            </div>
            <div class="flex-1 min-w-0">
              <div class="flex items-center gap-2 mb-1.5">
                <span class="px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider rounded-md {{ $badgeBg }} {{ $badgeText }}">
                  {{ $typeLabel }}
                </span>
              </div>
              <h3 class="font-bold text-on-surface text-sm leading-snug mb-1.5 group-hover:text-primary transition-colors line-clamp-2">
                {{ $title }}
              </h3>
              @if($content)
                <p class="text-[12px] text-on-surface-variant/70 line-clamp-2 leading-relaxed">{{ $content }}</p>
              @endif
              <span class="inline-flex items-center gap-1 mt-2 text-xs font-bold text-primary group-hover:text-secondary transition-colors">
                {{ $t('ເບິ່ງລາຍລະອຽດ','View Details','查看詳情') }}
                <i class="fas fa-arrow-right text-[10px] group-hover:translate-x-0.5 transition-transform"></i>
              </span>
            </div>
          </a>
        @endforeach
      </div>
    @else
      <div class="py-24 flex flex-col items-center text-center">
        <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center mb-4">
          <i class="fas fa-search text-slate-300 text-3xl"></i>
        </div>
        <p class="font-semibold text-on-surface-variant mb-1">{{ $t('ບໍ່ພົບຜົນ','No results found','未找到結果') }}</p>
        <p class="text-sm text-outline mb-6">
          {{ $t('ລອງຄຳຄົ້ນຫາອື່ນ','Try different keywords','請嘗試不同的關鍵詞') }}
        </p>
        <div class="flex gap-3 flex-wrap justify-center">
          <a href="{{ route('front.news.index') }}"
             class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-on-primary text-sm font-bold rounded-xl
                    hover:bg-secondary hover:text-on-secondary transition-colors">
            <i class="fas fa-newspaper text-xs"></i>{{ $t('ຂ່າວ','News','新聞') }}
          </a>
          <a href="{{ route('front.events.index') }}"
             class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-slate-200 text-on-surface-variant text-sm font-semibold rounded-xl
                    hover:bg-surface-container transition-colors">
            <i class="fas fa-calendar-alt text-xs"></i>{{ $t('ກິດຈະກຳ','Events','活動') }}
          </a>
        </div>
      </div>
    @endif

  </div>
</section>

@endsection
