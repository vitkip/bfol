@extends('front.layouts.app')

@php
  $L  = app()->getLocale();
  $t  = fn($lo,$en,$zh) => match($L){'zh'=>$zh,'en'=>$en,default=>$lo};
  $tf = fn($m,$f) => $m->{$f.'_'.$L} ?? $m->{$f.'_lo'} ?? '';

  $typeConfig = [
    'PDF'   => ['icon'=>'fas fa-file-pdf',        'bg'=>'bg-red-100',    'text'=>'text-red-600',    'badge'=>'bg-red-600',    'preview'=>true],
    'Word'  => ['icon'=>'fas fa-file-word',        'bg'=>'bg-blue-100',   'text'=>'text-blue-600',   'badge'=>'bg-blue-600',   'preview'=>false],
    'Excel' => ['icon'=>'fas fa-file-excel',       'bg'=>'bg-green-100',  'text'=>'text-green-600',  'badge'=>'bg-green-600',  'preview'=>false],
    'PPT'   => ['icon'=>'fas fa-file-powerpoint',  'bg'=>'bg-orange-100', 'text'=>'text-orange-600', 'badge'=>'bg-orange-600', 'preview'=>false],
    'ZIP'   => ['icon'=>'fas fa-file-zipper',      'bg'=>'bg-yellow-100', 'text'=>'text-yellow-600', 'badge'=>'bg-yellow-600', 'preview'=>false],
    'RAR'   => ['icon'=>'fas fa-file-zipper',      'bg'=>'bg-yellow-100', 'text'=>'text-yellow-600', 'badge'=>'bg-yellow-600', 'preview'=>false],
    'Text'  => ['icon'=>'fas fa-file-lines',       'bg'=>'bg-slate-100',  'text'=>'text-slate-600',  'badge'=>'bg-slate-600',  'preview'=>true],
  ];
  $defaultType = ['icon'=>'fas fa-file', 'bg'=>'bg-primary-container/30', 'text'=>'text-on-primary-container', 'badge'=>'bg-primary', 'preview'=>false];

  function fmtSize(int $kb): string {
    if ($kb >= 1024) return round($kb/1024,1).' MB';
    return $kb.' KB';
  }
@endphp

@section('title', $t('ເອກະສານ','Documents','文件') . ' - ' . ($settings->site_name_lo ?: 'BFOL'))
@section('meta_description', $t('ດາວໂຫຼດເອກະສານ, PDF, ແລະໄຟລ໌ຂອງ ອພສ','Download BFOL official documents, PDFs and files','下載老撾佛教協會官方文件、PDF及檔案'))

@push('styles')
<style>
  [x-cloak]   { display:none !important; }
  .dot-pattern {
    background-image: radial-gradient(circle, rgba(255,255,255,.12) 1px, transparent 1px);
    background-size: 28px 28px;
  }
  /* iframe loading shimmer */
  .iframe-wrap::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(90deg,#f0f0f0 25%,#e0e0e0 50%,#f0f0f0 75%);
    background-size: 200% 100%;
    animation: shimmer 1.4s infinite;
    border-radius: 0 0 .75rem .75rem;
    z-index: 1;
  }
  .iframe-wrap.loaded::before { display: none; }
  @keyframes shimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }
</style>
@endpush

@section('content')

{{-- ═══════════════════════════════════════════
     WRAPPER — Alpine preview state lives here
     ═══════════════════════════════════════════ --}}
<div x-data="{
  show: false,
  loading: true,
  doc: null,
  open(d) { this.doc = d; this.show = true; this.loading = true; document.body.style.overflow='hidden'; },
  close() { this.show = false; this.doc = null; document.body.style.overflow=''; },
  get canPreview() { return this.doc && this.doc.previewable; }
}"
@keydown.escape.window="close()">

{{-- ═══ HERO ═══ --}}
<section class="relative bg-primary overflow-hidden">
  <div class="dot-pattern absolute inset-0 pointer-events-none"></div>
  <div class="absolute -top-32 -right-32 w-96 h-96 bg-secondary/10 rounded-full blur-3xl pointer-events-none"></div>
  <div class="absolute -bottom-20 -left-20 w-72 h-72 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>

  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-14 relative z-10">
    <div class="flex flex-col md:flex-row items-center gap-8">
      <div class="flex-1 text-center md:text-left">
        <span class="inline-flex items-center gap-1.5 text-xs font-bold text-secondary uppercase tracking-widest mb-4">
          <span class="w-1.5 h-1.5 rounded-full bg-secondary inline-block"></span>
          {{ $t('ຫ້ອງສະໝຸດດິຈິຕອລ','Digital Library','數字圖書館') }}
        </span>
        <h1 class="text-3xl sm:text-4xl font-serif font-bold text-on-primary mb-3">
          {{ $t('ເອກະສານ & ໄຟລ໌','Documents & Files','文件與檔案') }}
        </h1>
        <p class="text-on-primary/70 text-sm max-w-lg">
          {{ $t(
            'ດາວໂຫຼດເອກະສານທາງການ, PDF, ບົດລາຍງານ ແລະໄຟລ໌ຕ່າງໆຂອງ ອພສ',
            'Download official BFOL documents, reports, PDFs and other files',
            '下載老撾佛教協會官方文件、報告、PDF等各類檔案'
          ) }}
        </p>
      </div>

      <div class="flex flex-wrap justify-center md:justify-end gap-3">
        <div class="flex items-center gap-2 bg-white/10 border border-white/15 rounded-xl px-4 py-2.5">
          <i class="fas fa-folder-open text-secondary text-base"></i>
          <div class="leading-tight">
            <div class="text-on-primary font-bold text-lg leading-none">{{ $totalCount }}</div>
            <div class="text-on-primary/60 text-[11px]">{{ $t('ເອກະສານທັງໝົດ','Total Files','全部文件') }}</div>
          </div>
        </div>
        @foreach($fileTypes->take(3) as $type => $count)
          @php $cfg = $typeConfig[$type] ?? $defaultType; @endphp
          <div class="flex items-center gap-2 bg-white/10 border border-white/15 rounded-xl px-4 py-2.5">
            <i class="{{ $cfg['icon'] }} {{ $cfg['text'] }} text-base"></i>
            <div class="leading-tight">
              <div class="text-on-primary font-bold text-lg leading-none">{{ $count }}</div>
              <div class="text-on-primary/60 text-[11px]">{{ $type }}</div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</section>

{{-- ═══ FILTERS + LIST ═══ --}}
<section class="bg-surface-container-lowest py-10 min-h-[60vh]">
  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Filter bar --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-8">
      <form method="GET" action="{{ route('front.documents.index') }}"
            class="flex-1 flex items-center gap-2 bg-white border border-surface-container-highest rounded-xl px-4 py-2.5 shadow-sm
                   focus-within:border-primary/50 focus-within:ring-2 focus-within:ring-primary/10 transition-all">
        <i class="fas fa-search text-on-surface-variant/50 text-sm shrink-0"></i>
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="{{ $t('ຄົ້ນຫາເອກະສານ...','Search documents...','搜索文件...') }}"
               class="flex-1 bg-transparent text-sm text-on-surface placeholder-on-surface-variant/40 outline-none" />
        @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
        @if(request('type'))     <input type="hidden" name="type"     value="{{ request('type') }}">     @endif
        @if(request('search'))
          <a href="{{ route('front.documents.index', array_filter(['category'=>request('category'),'type'=>request('type')])) }}"
             class="text-on-surface-variant/50 hover:text-on-surface transition-colors">
            <i class="fas fa-times text-xs"></i>
          </a>
        @endif
      </form>

      @if($fileTypes->count() > 1)
        <div class="flex items-center gap-1.5 flex-wrap">
          <a href="{{ route('front.documents.index', array_filter(['search'=>request('search'),'category'=>request('category')])) }}"
             class="px-3 py-2 rounded-lg text-xs font-semibold transition-all
                    {{ !request('type') ? 'bg-primary text-on-primary shadow-sm' : 'bg-white border border-surface-container-highest text-on-surface-variant hover:bg-surface-container-low' }}">
            {{ $t('ທັງໝົດ','All','全部') }}
          </a>
          @foreach($fileTypes as $type => $count)
            @php $cfg = $typeConfig[$type] ?? $defaultType; @endphp
            <a href="{{ route('front.documents.index', array_filter(['search'=>request('search'),'category'=>request('category'),'type'=>$type])) }}"
               class="flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all
                      {{ request('type') === $type
                         ? 'bg-primary text-on-primary shadow-sm'
                         : 'bg-white border border-surface-container-highest text-on-surface-variant hover:bg-surface-container-low' }}">
              <i class="{{ $cfg['icon'] }} text-[10px]"></i>{{ $type }}
            </a>
          @endforeach
        </div>
      @endif
    </div>

    {{-- Category tabs --}}
    @if($categories->count())
      <div class="flex items-center gap-2 flex-wrap mb-8 pb-6 border-b border-surface-container-high">
        <a href="{{ route('front.documents.index', array_filter(['search'=>request('search'),'type'=>request('type')])) }}"
           class="px-4 py-1.5 rounded-full text-sm font-semibold transition-all
                  {{ !request('category') ? 'bg-secondary text-on-secondary shadow-sm' : 'bg-white border border-surface-container-highest text-on-surface-variant hover:bg-surface-container-low' }}">
          {{ $t('ທັງໝົດ','All Categories','全部') }}
        </a>
        @foreach($categories as $cat)
          <a href="{{ route('front.documents.index', array_filter(['search'=>request('search'),'type'=>request('type'),'category'=>$cat->slug])) }}"
             class="px-4 py-1.5 rounded-full text-sm font-semibold transition-all
                    {{ request('category') === $cat->slug
                       ? 'bg-secondary text-on-secondary shadow-sm'
                       : 'bg-white border border-surface-container-highest text-on-surface-variant hover:bg-surface-container-low' }}">
            {{ $tf($cat,'name') }}
          </a>
        @endforeach
      </div>
    @endif

    {{-- Results count --}}
    <div class="flex items-center justify-between mb-4">
      <p class="text-xs text-on-surface-variant">
        {{ $t('ພົບ','Found','找到') }}
        <span class="font-bold text-on-surface">{{ $documents->total() }}</span>
        {{ $t('ລາຍການ','result(s)','個結果') }}
        @if(request('search'))
          {{ $t('ສຳລັບ','for','關於') }} "<span class="text-primary font-medium">{{ request('search') }}</span>"
        @endif
      </p>
      <span class="text-xs text-on-surface-variant">
        {{ $t('ໜ້າ','Page','頁') }} {{ $documents->currentPage() }}/{{ $documents->lastPage() }}
      </span>
    </div>

    {{-- Document list --}}
    <div class="flex flex-col gap-3">
      @forelse($documents as $doc)
        @php
          $cfg        = $typeConfig[$doc->file_type ?? ''] ?? $defaultType;
          $title      = $tf($doc,'title') ?: $doc->title_lo;
          $desc       = $tf($doc,'description');
          $catName    = $doc->category ? $tf($doc->category,'name') : null;
          $sizeStr    = $doc->file_size_kb ? fmtSize((int)$doc->file_size_kb) : '';
          $previewUrl = route('front.documents.preview', $doc);
          $dlUrl      = route('front.documents.download', $doc);
          $docData    = json_encode([
            'title'      => $title,
            'type'       => $doc->file_type ?? 'FILE',
            'size'       => $sizeStr,
            'category'   => $catName ?? '',
            'date'       => $doc->published_at ? $doc->published_at->format('d/m/Y') : '',
            'downloads'  => $doc->download_count,
            'icon'       => $cfg['icon'],
            'iconBg'     => $cfg['bg'],
            'iconText'   => $cfg['text'],
            'badge'      => $cfg['badge'],
            'previewable'=> (bool)$cfg['preview'],
            'previewUrl' => $previewUrl,
            'dlUrl'      => $dlUrl,
          ]);
        @endphp

        <div class="group bg-white border border-surface-container-high rounded-2xl p-5
                    hover:shadow-lg hover:shadow-primary/8 hover:border-primary/20
                    transition-all duration-300 flex items-start gap-4">

          {{-- File icon --}}
          <div class="shrink-0 w-14 h-14 rounded-xl {{ $cfg['bg'] }} flex items-center justify-center
                      group-hover:scale-105 transition-transform duration-300">
            <i class="{{ $cfg['icon'] }} {{ $cfg['text'] }} text-2xl"></i>
          </div>

          {{-- Main content --}}
          <div class="flex-1 min-w-0">
            <div class="flex flex-wrap items-center gap-2 mb-1.5">
              <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold text-white {{ $cfg['badge'] }}">
                {{ $doc->file_type ?? 'FILE' }}
              </span>
              @if($catName)
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium
                             bg-surface-container text-on-surface-variant border border-surface-container-highest">
                  <i class="fas fa-tag text-[8px]"></i>{{ $catName }}
                </span>
              @endif
              @if($doc->published_at)
                <span class="text-[11px] text-on-surface-variant/60">
                  <i class="far fa-calendar text-[9px] mr-0.5"></i>
                  {{ $doc->published_at->format('d/m/Y') }}
                </span>
              @endif
            </div>

            <h3 class="font-semibold text-on-surface text-[15px] leading-snug mb-1 line-clamp-2
                       group-hover:text-primary transition-colors duration-200">
              {{ $title }}
            </h3>

            @if($desc)
              <p class="text-xs text-on-surface-variant/80 line-clamp-2 mb-2 leading-relaxed">{{ $desc }}</p>
            @endif

            <div class="flex items-center gap-4 text-[11px] text-on-surface-variant/60">
              @if($sizeStr)
                <span class="flex items-center gap-1">
                  <i class="fas fa-weight-hanging text-[9px]"></i>{{ $sizeStr }}
                </span>
              @endif
              <span class="flex items-center gap-1">
                <i class="fas fa-download text-[9px]"></i>
                {{ number_format($doc->download_count) }} {{ $t('ດາວໂຫຼດ','downloads','次下載') }}
              </span>
            </div>
          </div>

          {{-- Action buttons --}}
          <div class="shrink-0 flex flex-col sm:flex-row items-end sm:items-center gap-2">

            {{-- Preview button --}}
            <button type="button"
                    @click="open({{ $docData }})"
                    class="flex items-center gap-1.5 px-3 py-2.5
                           bg-surface-container border border-surface-container-highest
                           text-on-surface-variant text-xs font-semibold rounded-xl
                           hover:bg-primary/10 hover:border-primary/30 hover:text-primary
                           transition-all duration-200 cursor-pointer"
                    title="{{ $t('ເບິ່ງຕົວຢ່າງ','Preview','預覽') }}">
              <i class="fas fa-eye text-[11px]"></i>
              <span class="hidden sm:inline">{{ $t('ເບິ່ງ','Preview','預覽') }}</span>
            </button>

            {{-- Download button --}}
            <a href="{{ $dlUrl }}"
               class="flex items-center gap-1.5 px-4 py-2.5
                      bg-primary text-on-primary text-xs font-bold rounded-xl
                      hover:bg-secondary hover:text-on-secondary
                      hover:-translate-y-0.5 hover:shadow-md hover:shadow-primary/30
                      active:scale-95 transition-all duration-200"
               title="{{ $t('ດາວໂຫຼດ','Download','下載') }}">
              <i class="fas fa-download text-[11px]"></i>
              <span class="hidden sm:inline">{{ $t('ດາວໂຫຼດ','Download','下載') }}</span>
            </a>
          </div>

        </div>

      @empty
        <div class="py-20 text-center">
          <div class="w-20 h-20 mx-auto rounded-2xl bg-surface-container flex items-center justify-center mb-5">
            <i class="fas fa-folder-open text-on-surface-variant/30 text-4xl"></i>
          </div>
          <h3 class="text-base font-bold text-on-surface mb-1">
            {{ $t('ບໍ່ພົບເອກະສານ','No Documents Found','未找到文件') }}
          </h3>
          <p class="text-sm text-on-surface-variant/70 mb-6">
            {{ $t('ລອງປ່ຽນຄຳຄົ້ນຫາ ຫຼື ໝວດໝູ່','Try changing your search or category filter','請嘗試更改搜索詞或類別') }}
          </p>
          <a href="{{ route('front.documents.index') }}"
             class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-on-primary text-sm font-bold rounded-xl
                    hover:bg-secondary hover:text-on-secondary transition-all">
            <i class="fas fa-rotate-left text-xs"></i>
            {{ $t('ລ້າງຕົວກອງ','Clear Filters','清除篩選') }}
          </a>
        </div>
      @endforelse
    </div>

    @if($documents->hasPages())
      <div class="mt-10 flex justify-center">
        {{ $documents->links() }}
      </div>
    @endif

  </div>
</section>

{{-- ═══ PREVIEW MODAL ═══ --}}
<div x-show="show"
     x-cloak
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-[300] flex items-end sm:items-center justify-center p-0 sm:p-4"
     @click.self="close()">

  {{-- Backdrop --}}
  <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

  {{-- Panel --}}
  <div x-show="show"
       x-transition:enter="transition ease-out duration-250"
       x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
       x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
       x-transition:leave="transition ease-in duration-150"
       x-transition:leave-end="opacity-0 translate-y-6 sm:scale-95"
       class="relative z-10 w-full sm:max-w-5xl bg-white rounded-t-3xl sm:rounded-2xl shadow-2xl
              flex flex-col overflow-hidden"
       style="height: 92dvh; max-height: 92dvh;"
       @click.stop>

    {{-- Header --}}
    <div class="flex items-center gap-3 px-5 py-4 border-b border-surface-container-high shrink-0 bg-white">

      {{-- File type icon --}}
      <template x-if="doc">
        <div class="shrink-0 w-10 h-10 rounded-lg flex items-center justify-center"
             :class="doc.iconBg">
          <i :class="doc.icon + ' ' + doc.iconText + ' text-xl'"></i>
        </div>
      </template>

      {{-- Info --}}
      <div class="flex-1 min-w-0" x-show="doc">
        <h2 class="font-bold text-on-surface text-sm leading-snug line-clamp-1" x-text="doc?.title"></h2>
        <div class="flex items-center gap-3 mt-0.5 flex-wrap">
          <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold text-white"
                :class="doc?.badge" x-text="doc?.type"></span>
          <span class="text-[11px] text-on-surface-variant/60" x-show="doc?.size" x-text="doc?.size"></span>
          <span class="text-[11px] text-on-surface-variant/60" x-show="doc?.category" x-text="doc?.category"></span>
          <span class="text-[11px] text-on-surface-variant/60" x-show="doc?.date">
            <i class="far fa-calendar text-[9px] mr-0.5"></i><span x-text="doc?.date"></span>
          </span>
        </div>
      </div>

      {{-- Actions --}}
      <div class="flex items-center gap-2 shrink-0">
        {{-- Open in new tab --}}
        <template x-if="doc && canPreview">
          <a :href="doc.previewUrl" target="_blank" rel="noreferrer"
             class="flex items-center gap-1.5 px-3 py-2 rounded-lg bg-surface-container border border-surface-container-highest
                    text-on-surface-variant text-xs font-semibold hover:bg-primary/10 hover:text-primary
                    transition-colors cursor-pointer">
            <i class="fas fa-arrow-up-right-from-square text-[10px]"></i>
            <span class="hidden sm:inline">{{ $t('ຂະຫຍາຍ','Expand','展開') }}</span>
          </a>
        </template>

        {{-- Download --}}
        <template x-if="doc">
          <a :href="doc.dlUrl"
             class="flex items-center gap-1.5 px-3 py-2 rounded-lg bg-primary text-on-primary
                    text-xs font-bold hover:bg-secondary hover:text-on-secondary
                    transition-colors cursor-pointer">
            <i class="fas fa-download text-[10px]"></i>
            <span class="hidden sm:inline">{{ $t('ດາວໂຫຼດ','Download','下載') }}</span>
          </a>
        </template>

        {{-- Close --}}
        <button @click="close()"
                class="w-9 h-9 rounded-lg flex items-center justify-center
                       text-on-surface-variant hover:bg-surface-container hover:text-on-surface
                       transition-colors cursor-pointer">
          <i class="fas fa-times text-sm"></i>
        </button>
      </div>
    </div>

    {{-- Preview body --}}
    <div class="flex-1 overflow-hidden relative bg-surface-container-lowest">

      {{-- PDF / Text — iframe --}}
      <template x-if="doc && canPreview">
        <div class="absolute inset-0 iframe-wrap" :class="{ 'loaded': !loading }">
          {{-- Spinner overlay --}}
          <div x-show="loading"
               class="absolute inset-0 z-10 flex flex-col items-center justify-center bg-surface-container-lowest gap-3">
            <div class="w-10 h-10 rounded-full border-4 border-primary/20 border-t-primary animate-spin"></div>
            <p class="text-xs text-on-surface-variant">{{ $t('ກຳລັງໂຫຼດ...','Loading...','載入中...') }}</p>
          </div>
          <iframe
            :src="doc.previewUrl"
            class="w-full h-full border-0 relative z-0"
            @load="loading = false; $el.parentElement.classList.add('loaded')"
            allowfullscreen>
          </iframe>
        </div>
      </template>

      {{-- Non-previewable types --}}
      <template x-if="doc && !canPreview">
        <div class="absolute inset-0 flex flex-col items-center justify-center gap-5 p-8 text-center">
          <div class="w-20 h-20 rounded-2xl flex items-center justify-center"
               :class="doc.iconBg">
            <i :class="doc.icon + ' ' + doc.iconText + ' text-4xl'"></i>
          </div>
          <div>
            <h3 class="font-bold text-on-surface text-base mb-1"
                x-text="doc.title"></h3>
            <p class="text-sm text-on-surface-variant/70 max-w-sm">
              {{ $t(
                'ໄຟລ໌ປະເພດນີ້ບໍ່ສາມາດ preview ໄດ້ໃນ browser ກະລຸນາດາວໂຫຼດເພື່ອເປີດ',
                'This file type cannot be previewed in the browser. Please download to open it.',
                '此文件類型無法在瀏覽器中預覽，請下載後開啟。'
              ) }}
            </p>
          </div>

          <div class="flex flex-wrap justify-center gap-3">
            <a :href="doc.dlUrl"
               class="flex items-center gap-2 px-6 py-3 bg-primary text-on-primary font-bold rounded-xl
                      hover:bg-secondary hover:text-on-secondary transition-all hover:-translate-y-0.5
                      hover:shadow-md hover:shadow-primary/30 cursor-pointer">
              <i class="fas fa-download"></i>
              {{ $t('ດາວໂຫຼດໄຟລ໌','Download File','下載文件') }}
              <span class="text-on-primary/60 font-normal text-xs" x-show="doc.size" x-text="'('+doc.size+')'"></span>
            </a>
          </div>

          <div class="flex items-center gap-2 text-xs text-on-surface-variant/50 mt-2">
            <i class="fas fa-shield-halved text-[10px]"></i>
            {{ $t('ໄຟລ໌ທາງການຈາກ ອພສ','Official BFOL document','老撾佛教協會官方文件') }}
            <span class="text-on-surface-variant/30">•</span>
            <span x-text="doc.downloads + ' {{ $t('ດາວໂຫຼດ','downloads','次下載') }}'"></span>
          </div>
        </div>
      </template>

    </div>

  </div>
</div>

</div>{{-- /x-data wrapper --}}

@endsection
