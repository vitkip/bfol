@extends('front.layouts.app')

@php
  $L  = app()->getLocale();
  $t  = fn($lo,$en,$zh) => match($L){'zh'=>$zh,'en'=>$en,default=>$lo};
  $tf = fn($m,$f) => $m->{$f.'_'.$L} ?? $m->{$f.'_lo'} ?? '';

  $typeConfig = [
    'video' => ['icon'=>'fas fa-play-circle', 'color'=>'text-red-500',  'bg'=>'bg-red-50',   'label'=>$t('ວິດີໂອ','Video','視頻')],
    'image' => ['icon'=>'fas fa-images',      'color'=>'text-blue-500', 'bg'=>'bg-blue-50',  'label'=>$t('ຮູບພາບ','Photo','相片')],
    'audio' => ['icon'=>'fas fa-music',       'color'=>'text-purple-500','bg'=>'bg-purple-50','label'=>$t('ສຽງ','Audio','音頻')],
  ];
@endphp

@section('title', $t('ສື່ສາ & ກິດຈະກຳ','Media & Activities','媒體活動').' - '.($settings->site_name_lo ?: 'BFOL'))
@section('meta_description', $t('ຮູບພາບ ວິດີໂອ ແລະ ສື່ຕ່າງໆ ຂອງ ສ.ຊ.ພ.ລ','BFOL media gallery — photos, videos and activities','老撾佛協媒體庫'))

@push('styles')
<style>
  .dot-pattern { background-image:radial-gradient(circle,rgba(255,255,255,.12) 1px,transparent 1px); background-size:28px 28px; }
  .card-hover:hover .thumb-img { transform:scale(1.06); }
  .thumb-img { transition:transform .5s ease; }
  [x-cloak] { display:none !important; }

  .yt-embed { position:relative; padding-bottom:56.25%; height:0; overflow:hidden; }
  .yt-embed iframe { position:absolute; inset:0; width:100%; height:100%; border:0; }
</style>
@endpush

@section('content')

{{-- ═══ HERO ═══ --}}
<section class="relative bg-primary overflow-hidden">
  <div class="dot-pattern absolute inset-0 pointer-events-none"></div>
  <div class="absolute -top-32 -right-32 w-96 h-96 bg-secondary/10 rounded-full blur-3xl pointer-events-none"></div>
  <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-secondary/8 rounded-full blur-2xl pointer-events-none"></div>

  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-14 relative z-10">
    <div class="flex flex-col md:flex-row items-center gap-8">
      <div class="flex-1 text-center md:text-left">
        <span class="inline-flex items-center gap-1.5 text-xs font-bold text-secondary uppercase tracking-widest mb-4">
          <span class="w-1.5 h-1.5 rounded-full bg-secondary inline-block"></span>
          {{ $t('ສ.ຊ.ພ.ລ — ສື່ ແລະ ກິດຈະກຳ','BFOL — Media & Activities','老撾佛協媒體庫') }}
        </span>
        <h1 class="text-3xl sm:text-4xl font-serif font-bold text-on-primary mb-3">
          {{ $t('ສື່ສາ & ກິດຈະກຳ','Media & Activities','媒體活動') }}
        </h1>
        <p class="text-on-primary/70 text-sm max-w-lg">
          {{ $t(
            'ຮູບພາບ, ວິດີໂອ ແລະ ສື່ຕ່າງໆ ຈາກກິດຈະກຳ ແລະ ງານຂອງ ສ.ຊ.ພ.ລ',
            'Photos, videos and media from BFOL events and activities',
            '老撾佛協各項活動的圖片、影片和媒體資料'
          ) }}
        </p>
      </div>

      {{-- Stats --}}
      <div class="flex flex-wrap justify-center gap-3">
        <div class="flex items-center gap-2 bg-white/10 border border-white/15 rounded-xl px-4 py-2.5">
          <i class="fas fa-images text-secondary text-base"></i>
          <div class="leading-tight">
            <div class="text-on-primary font-bold text-xl leading-none">{{ $items->total() }}</div>
            <div class="text-on-primary/60 text-[11px]">{{ $t('ລາຍການ','Items','項目') }}</div>
          </div>
        </div>
        @if($categories->isNotEmpty())
          <div class="flex items-center gap-2 bg-white/10 border border-white/15 rounded-xl px-4 py-2.5">
            <i class="fas fa-tags text-secondary text-base"></i>
            <div class="leading-tight">
              <div class="text-on-primary font-bold text-xl leading-none">{{ $categories->count() }}</div>
              <div class="text-on-primary/60 text-[11px]">{{ $t('ໝວດໝູ່','Categories','分類') }}</div>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
</section>

{{-- ═══ FILTER BAR ═══ --}}
<div class="bg-white border-b border-slate-100 sticky top-[60px] z-30 shadow-sm">
  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center gap-3 overflow-x-auto py-3 scrollbar-hide">

      {{-- Type filters --}}
      <a href="{{ route('front.media.index', request()->except(['type','page'])) }}"
         class="flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-colors
                {{ !request('type') ? 'bg-primary text-on-primary' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
        <i class="fas fa-th text-[10px]"></i>{{ $t('ທັງໝົດ','All','全部') }}
      </a>
      @foreach($typeConfig as $typeKey => $tc)
        <a href="{{ route('front.media.index', array_merge(request()->except(['type','page']), ['type' => $typeKey])) }}"
           class="flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-colors
                  {{ request('type') === $typeKey ? 'bg-primary text-on-primary' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
          <i class="{{ $tc['icon'] }} text-[10px]"></i>{{ $tc['label'] }}
        </a>
      @endforeach

      @if($categories->isNotEmpty())
        <div class="w-px h-5 bg-slate-200 mx-1 shrink-0"></div>
        {{-- Category filters --}}
        <a href="{{ route('front.media.index', request()->except(['category','page'])) }}"
           class="flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-colors
                  {{ !request('category') ? 'bg-secondary/90 text-on-secondary' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
          {{ $t('ທຸກໝວດ','All Categories','所有分類') }}
        </a>
        @foreach($categories as $cat)
          <a href="{{ route('front.media.index', array_merge(request()->except(['category','page']), ['category' => $cat->slug])) }}"
             class="flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-colors
                    {{ request('category') === $cat->slug ? 'bg-secondary/90 text-on-secondary' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
            {{ $cat->{'name_'.$L} ?? $cat->name_lo }}
          </a>
        @endforeach
      @endif
    </div>
  </div>
</div>

{{-- ═══ GRID ═══ --}}
<section class="bg-slate-50 py-10 min-h-[400px]"
         x-data="{ modal: null }">
  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">

    @forelse($items as $item)
      @php
        $tc      = $typeConfig[$item->type] ?? $typeConfig['image'];
        $title   = $tf($item,'title');
        $desc    = $tf($item,'description');
        $catName = $item->category ? ($item->category->{'name_'.$L} ?? $item->category->name_lo) : '';
      @endphp

      @if($loop->first)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
      @endif

      <div class="card-hover bg-white rounded-2xl border border-slate-100
                  shadow-[0_2px_12px_-4px_rgba(0,0,0,.08)]
                  hover:shadow-[0_6px_24px_-4px_rgba(3,22,50,.13)]
                  hover:-translate-y-0.5 transition-all duration-300 overflow-hidden group cursor-pointer"
           @click="modal = {{ Js::from([
             'title'      => $title,
             'desc'       => $desc,
             'type'       => $item->type,
             'thumb'      => $item->thumbnail_url,
             'platform'   => $item->platform,
             'ext_url'    => $item->external_url,
             'file_url'   => $item->file_url,
             'yt_id'      => ($item->platform === 'youtube' && $item->external_url)
                               ? (preg_match('/(?:v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $item->external_url, $m) ? $m[1] : null)
                               : null,
             'duration'   => $item->duration_sec,
             'cat'        => $catName,
           ]) }}">

        {{-- Thumbnail --}}
        <div class="relative h-48 bg-slate-100 overflow-hidden">
          @if($item->thumbnail_url)
            <img src="{{ $item->thumbnail_url }}" alt="{{ $title }}"
                 class="thumb-img w-full h-full object-cover" loading="lazy" />
          @else
            <div class="w-full h-full {{ $tc['bg'] }} flex items-center justify-center">
              <i class="{{ $tc['icon'] }} {{ $tc['color'] }} text-5xl opacity-30"></i>
            </div>
          @endif

          {{-- Type badge --}}
          <div class="absolute top-2 left-2 flex items-center gap-1.5
                      bg-white/90 backdrop-blur-sm rounded-full px-2.5 py-1 shadow-sm">
            <i class="{{ $tc['icon'] }} {{ $tc['color'] }} text-[11px]"></i>
            <span class="text-[10px] font-bold text-slate-600">{{ $tc['label'] }}</span>
          </div>

          {{-- Platform badge for YouTube --}}
          @if($item->platform === 'youtube')
            <div class="absolute top-2 right-2 bg-red-600 text-white rounded-full px-2 py-1
                        text-[10px] font-bold flex items-center gap-1">
              <i class="fab fa-youtube text-[10px]"></i> YouTube
            </div>
          @endif

          {{-- Play overlay for videos --}}
          @if($item->type === 'video')
            <div class="absolute inset-0 flex items-center justify-center
                        bg-black/0 group-hover:bg-black/25 transition-colors duration-300">
              <div class="w-12 h-12 rounded-full bg-white/0 group-hover:bg-white/90
                          flex items-center justify-center
                          scale-75 group-hover:scale-100 opacity-0 group-hover:opacity-100
                          transition-all duration-300 shadow-xl">
                <i class="fas fa-play text-red-500 text-base ml-0.5"></i>
              </div>
            </div>
          @endif

          {{-- Duration badge --}}
          @if($item->duration_sec)
            @php
              $mins = floor($item->duration_sec / 60);
              $secs = $item->duration_sec % 60;
            @endphp
            <div class="absolute bottom-2 right-2 bg-black/70 text-white text-[10px] font-bold
                        px-1.5 py-0.5 rounded font-mono">
              {{ str_pad($mins,2,'0',STR_PAD_LEFT) }}:{{ str_pad($secs,2,'0',STR_PAD_LEFT) }}
            </div>
          @endif
        </div>

        {{-- Card body --}}
        <div class="p-4">
          @if($catName)
            <span class="text-[10px] font-bold text-primary/70 uppercase tracking-wide">{{ $catName }}</span>
          @endif
          <h3 class="font-bold text-on-surface text-sm mt-1 leading-snug line-clamp-2 group-hover:text-primary transition-colors">
            {{ $title ?: $t('ບໍ່ມີຫົວຂໍ້','Untitled','無標題') }}
          </h3>
          @if($desc)
            <p class="text-[11px] text-on-surface-variant/70 mt-1.5 line-clamp-2 leading-relaxed">{{ $desc }}</p>
          @endif
          <div class="flex items-center gap-3 mt-3 text-[10px] text-outline">
            @if($item->published_at)
              <span><i class="far fa-calendar mr-1"></i>{{ $item->published_at->format('d/m/Y') }}</span>
            @endif
            @if($item->view_count)
              <span><i class="far fa-eye mr-1"></i>{{ number_format($item->view_count) }}</span>
            @endif
          </div>
        </div>
      </div>

      @if($loop->last)
        </div>
      @endif

    @empty
      <div class="flex flex-col items-center justify-center py-24 text-center">
        <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center mb-4">
          <i class="fas fa-images text-slate-300 text-3xl"></i>
        </div>
        <p class="text-on-surface-variant font-semibold">{{ $t('ຍັງບໍ່ມີສື່','No media yet','暫無媒體') }}</p>
        <p class="text-sm text-outline mt-1">{{ $t('ກວດເບິ່ງໃໝ່ໃນພາຍຫຼັງ','Check back later','請稍後再試') }}</p>
      </div>
    @endforelse

    {{-- Pagination --}}
    @if($items->hasPages())
      <div class="flex justify-center mt-10">
        <div class="flex items-center gap-1">
          @if($items->onFirstPage())
            <span class="px-3 py-2 rounded-lg text-outline bg-white border border-slate-100 cursor-not-allowed text-sm">←</span>
          @else
            <a href="{{ $items->previousPageUrl() }}" class="px-3 py-2 rounded-lg text-on-surface-variant bg-white border border-slate-100 hover:bg-surface-container text-sm transition-colors">←</a>
          @endif
          @foreach($items->getUrlRange(max(1,$items->currentPage()-2), min($items->lastPage(),$items->currentPage()+2)) as $page => $url)
            <a href="{{ $url }}"
               class="px-3.5 py-2 rounded-lg text-sm transition-colors
                      {{ $page === $items->currentPage()
                         ? 'bg-primary text-on-primary font-bold shadow-sm'
                         : 'bg-white border border-slate-100 text-on-surface-variant hover:bg-surface-container' }}">
              {{ $page }}
            </a>
          @endforeach
          @if($items->hasMorePages())
            <a href="{{ $items->nextPageUrl() }}" class="px-3 py-2 rounded-lg text-on-surface-variant bg-white border border-slate-100 hover:bg-surface-container text-sm transition-colors">→</a>
          @else
            <span class="px-3 py-2 rounded-lg text-outline bg-white border border-slate-100 cursor-not-allowed text-sm">→</span>
          @endif
        </div>
      </div>
    @endif

  </div>

  {{-- ═══ MODAL ═══ --}}
  <div x-show="modal"
       x-transition:enter="transition ease-out duration-200"
       x-transition:enter-start="opacity-0"
       x-transition:enter-end="opacity-100"
       x-transition:leave="transition ease-in duration-150"
       x-transition:leave-end="opacity-0"
       @keydown.escape.window="modal = null"
       @click.self="modal = null"
       class="fixed inset-0 z-[200] bg-black/70 backdrop-blur-sm flex items-center justify-center p-4"
       style="display:none">
    <div x-show="modal"
         x-transition:enter="transition ease-out duration-250"
         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-end="opacity-0 scale-95"
         class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col">

      {{-- Modal header --}}
      <div class="flex items-start justify-between gap-3 p-4 border-b border-slate-100">
        <div class="min-w-0">
          <template x-if="modal?.cat">
            <span class="text-[10px] font-bold text-primary/70 uppercase tracking-wide" x-text="modal?.cat"></span>
          </template>
          <h3 class="font-bold text-on-surface text-sm leading-snug mt-0.5 line-clamp-2" x-text="modal?.title"></h3>
        </div>
        <button @click="modal = null"
                class="p-1.5 rounded-lg text-outline hover:text-on-surface hover:bg-slate-100 transition-colors shrink-0">
          <i class="fas fa-times text-sm"></i>
        </button>
      </div>

      {{-- Media content --}}
      <div class="flex-1 overflow-auto">
        {{-- YouTube embed --}}
        <template x-if="modal?.yt_id">
          <div class="yt-embed bg-black">
            <iframe :src="'https://www.youtube.com/embed/' + modal.yt_id + '?autoplay=1'"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
          </div>
        </template>
        {{-- Image --}}
        <template x-if="modal?.type === 'image' && modal?.thumb">
          <img :src="modal.thumb" :alt="modal.title"
               class="w-full max-h-[60vh] object-contain bg-slate-50" />
        </template>
        {{-- Other (external link) --}}
        <template x-if="!modal?.yt_id && modal?.type !== 'image' && modal?.ext_url">
          <div class="p-8 flex flex-col items-center text-center gap-4">
            <i class="fas fa-external-link-alt text-4xl text-primary/40"></i>
            <a :href="modal.ext_url" target="_blank" rel="noreferrer"
               class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-on-primary font-bold rounded-xl
                      hover:bg-secondary hover:text-on-secondary transition-colors">
              <i class="fas fa-external-link-alt text-xs"></i>
              {{ $t('ເບິ່ງໃນເວທີ','View on Platform','在平台查看') }}
            </a>
          </div>
        </template>
        {{-- Description --}}
        <template x-if="modal?.desc">
          <div class="px-5 py-4 border-t border-slate-100">
            <p class="text-sm text-on-surface-variant leading-relaxed" x-text="modal.desc"></p>
          </div>
        </template>
      </div>

      {{-- Footer: external link --}}
      <template x-if="modal?.ext_url">
        <div class="px-5 py-3 border-t border-slate-100 flex justify-end">
          <a :href="modal.ext_url" target="_blank" rel="noreferrer"
             class="flex items-center gap-1.5 text-xs font-semibold text-primary hover:underline">
            <i class="fas fa-external-link-alt text-[10px]"></i>
            {{ $t('ເປີດລິ້ງຕົ້ນສະບັບ','Open original link','打開原始連結') }}
          </a>
        </div>
      </template>

    </div>
  </div>

</section>

@endsection
