@extends('front.layouts.app')

@php
  $L  = app()->getLocale();
  $t  = fn($lo,$en,$zh) => match($L){'zh'=>$zh,'en'=>$en,default=>$lo};
  $tf = fn($m,$f) => $m->{$f.'_'.$L} ?? $m->{$f.'_lo'} ?? '';

  $langNames = [
    'lo'=>$t('ລາວ','Lao','老撾語'), 'en'=>$t('ອັງກິດ','English','英語'),
    'zh'=>$t('ຈີນ','Chinese','中文'),  'th'=>$t('ໄທ','Thai','泰語'),
    'my'=>$t('ພະມ້າ','Burmese','緬語'), 'km'=>$t('ຂະແໝນ','Khmer','高棉語'),
    'ja'=>$t('ຍີ່ປຸ່ນ','Japanese','日語'), 'ko'=>$t('ເກົາຫຼີ','Korean','韓語'),
    'vi'=>$t('ຫວຽດ','Vietnamese','越語'), 'pi'=>$t('ບາລີ','Pali','巴利語'),
    'sa'=>$t('ສັນສະກຣິດ','Sanskrit','梵語'), 'fr'=>$t('ຝຣັ່ງ','French','法語'),
    'de'=>$t('ເຢຍລະມັນ','German','德語'), 'ru'=>$t('ຣັດ','Russian','俄語'),
    'ar'=>$t('ອາຣັບ','Arabic','阿拉伯語'),
  ];

  $statusCfg = [
    'in_progress' => ['bg'=>'bg-blue-100',   'text'=>'text-blue-700',   'label'=>$t('ດຳເນີນ','In Progress','進行中')],
    'reviewing'   => ['bg'=>'bg-amber-100',  'text'=>'text-amber-700',  'label'=>$t('ກວດທານ','Reviewing','審核中')],
    'completed'   => ['bg'=>'bg-emerald-100','text'=>'text-emerald-700','label'=>$t('ສຳເລັດ','Completed','已完成')],
    'published'   => ['bg'=>'bg-primary/10', 'text'=>'text-primary',    'label'=>$t('ເຜີຍແຜ່','Published','已發佈')],
  ];
@endphp

@section('title', $t('ໂຄງການແປ','Translation Projects','翻譯項目').' - '.($settings->site_name_lo ?: 'BFOL'))
@section('meta_description', $t('ໂຄງການແປເອກະສານ ສາສະໜາ ຂອງ ອພສ','BFOL Buddhist text translation projects','老撾佛協佛典翻譯項目'))

@push('styles')
<style>
  .dot-pattern { background-image:radial-gradient(circle,rgba(255,255,255,.12) 1px,transparent 1px); background-size:28px 28px; }
</style>
@endpush

@section('content')

{{-- ═══ HERO ═══ --}}
<section class="relative bg-primary overflow-hidden">
  <div class="dot-pattern absolute inset-0 pointer-events-none"></div>
  <div class="absolute -top-32 -right-32 w-96 h-96 bg-secondary/10 rounded-full blur-3xl pointer-events-none"></div>
  <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>

  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-14 relative z-10">

    <div class="flex items-center gap-2 text-on-primary/60 text-xs mb-5">
      <a href="{{ route('front.home') }}" class="hover:text-on-primary transition-colors">{{ $t('ໜ້າຫຼັກ','Home','首頁') }}</a>
      <i class="fas fa-chevron-right text-[9px]"></i>
      <span class="text-on-primary/90">{{ $t('ໂຄງການແປ','Translations','翻譯') }}</span>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-end gap-6">
      <div class="flex-1">
        <span class="inline-flex items-center gap-1.5 text-xs font-bold text-secondary uppercase tracking-widest mb-3">
          <span class="w-1.5 h-1.5 rounded-full bg-secondary inline-block"></span>
          {{ $t('ການໂຄງການແປ ສ.ຊ.ພ.ລ','BFOL Translation Projects','老撾佛協翻譯項目') }}
        </span>
        <h1 class="text-3xl sm:text-4xl font-serif font-bold text-on-primary mb-2">
          {{ $t('ໂຄງການແປພາສາ','Translation Projects','翻譯項目') }}
        </h1>
        <p class="text-on-primary/70 text-sm max-w-xl">
          {{ $t(
            'ໂຄງການແປຄຳສອນ, ເອກະສານ ສາດ ຈາກ ແລະ ໄປ ຫຼາຍພາສາ',
            'Projects translating Buddhist scriptures and documents across languages',
            '佛典與文獻的多語言翻譯項目'
          ) }}
        </p>
      </div>
      <div class="flex gap-3 shrink-0">
        <div class="bg-white/10 backdrop-blur-sm rounded-2xl px-5 py-3 text-center border border-white/20">
          <p class="text-2xl font-extrabold text-on-primary">{{ $counts['completed'] }}</p>
          <p class="text-[11px] text-on-primary/70 mt-0.5">{{ $t('ສຳເລັດ','Completed','已完成') }}</p>
        </div>
        <div class="bg-white/10 backdrop-blur-sm rounded-2xl px-5 py-3 text-center border border-white/20">
          <p class="text-2xl font-extrabold text-on-primary">{{ $counts['total'] }}</p>
          <p class="text-[11px] text-on-primary/70 mt-0.5">{{ $t('ທັງໝົດ','Total','總計') }}</p>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ═══ FILTER + CONTENT ═══ --}}
<section class="bg-surface py-10">
  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Language filter chips --}}
    @if($languages->isNotEmpty())
      <div class="flex flex-wrap gap-2 mb-8">
        <a href="{{ route('front.translations.index') }}"
           class="inline-flex items-center gap-1.5 px-4 py-1.5 text-xs font-semibold rounded-full border-2 transition-all
                  {{ !request('lang') ? 'border-primary bg-primary text-on-primary' : 'border-outline-variant text-on-surface-variant hover:border-primary/40' }}">
          {{ $t('ທັງໝົດ','All','全部') }}
        </a>
        @foreach($languages as $code)
          <a href="{{ route('front.translations.index', ['lang'=>$code]) }}"
             class="inline-flex items-center gap-1.5 px-4 py-1.5 text-xs font-semibold rounded-full border-2 transition-all
                    {{ request('lang') === $code ? 'border-primary bg-primary text-on-primary' : 'border-outline-variant text-on-surface-variant hover:border-primary/40' }}">
            {{ $langNames[$code] ?? strtoupper($code) }}
          </a>
        @endforeach
      </div>
    @endif

    {{-- Cards --}}
    @if($projects->isEmpty())
      <div class="text-center py-20 text-on-surface-variant">
        <i class="fas fa-language text-5xl opacity-20 mb-4"></i>
        <p class="text-lg font-semibold">{{ $t('ຍັງບໍ່ມີໂຄງການ','No projects yet','暫無項目') }}</p>
      </div>
    @else
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($projects as $p)
          @php
            $sc = $statusCfg[$p->status] ?? $statusCfg['published'];
            $src = $langNames[$p->source_language] ?? strtoupper($p->source_language ?? '');
            $tgt = $langNames[$p->target_language] ?? strtoupper($p->target_language ?? '');
          @endphp
          <div class="group bg-surface-container-lowest rounded-2xl shadow-sm hover:shadow-lg
                      border border-outline-variant/20 hover:border-primary/20
                      transition-all duration-300 hover:-translate-y-0.5 flex flex-col">

            {{-- Header band --}}
            <div class="h-1.5 bg-gradient-to-r from-primary to-secondary rounded-t-2xl"></div>

            <div class="p-5 flex flex-col flex-1">
              {{-- Lang arrows --}}
              @if($src || $tgt)
                <div class="flex items-center gap-2 mb-3">
                  @if($src)
                    <span class="px-2.5 py-1 bg-primary/8 text-primary text-xs font-bold rounded-lg">{{ $src }}</span>
                  @endif
                  @if($src && $tgt)
                    <i class="fas fa-arrow-right text-outline text-xs"></i>
                  @endif
                  @if($tgt)
                    <span class="px-2.5 py-1 bg-secondary/10 text-secondary text-xs font-bold rounded-lg">{{ $tgt }}</span>
                  @endif
                </div>
              @endif

              {{-- Title --}}
              <h3 class="font-bold text-sm text-on-surface leading-snug line-clamp-2 mb-2 group-hover:text-primary transition-colors flex-1">
                {{ $tf($p,'title') }}
              </h3>

              {{-- Desc --}}
              @if($tf($p,'description'))
                <p class="text-xs text-on-surface-variant leading-relaxed line-clamp-2 mb-3">
                  {{ $tf($p,'description') }}
                </p>
              @endif

              {{-- Footer meta --}}
              <div class="flex items-center justify-between pt-3 border-t border-outline-variant/15 mt-auto">
                <div class="flex items-center gap-2">
                  <span class="inline-flex items-center px-2.5 py-0.5 text-[10px] font-bold rounded-full {{ $sc['bg'] }} {{ $sc['text'] }}">
                    {{ $sc['label'] }}
                  </span>
                  @if($p->year)
                    <span class="text-xs text-outline font-mono">{{ $p->year }}</span>
                  @endif
                </div>
                @if($p->document_url)
                  <a href="{{ $p->document_url }}" target="_blank" rel="noopener noreferrer"
                     class="inline-flex items-center gap-1.5 text-xs font-semibold text-primary
                            hover:text-secondary transition-colors">
                    <i class="fas fa-download text-[10px]"></i>
                    {{ $t('ດາວໂຫຼດ','Download','下載') }}
                  </a>
                @endif
              </div>

              @if($p->translator)
                <p class="mt-2 text-[11px] text-outline">
                  <i class="fas fa-user-edit text-[9px] mr-1"></i>{{ $p->translator }}
                </p>
              @endif
            </div>
          </div>
        @endforeach
      </div>

      {{-- Pagination --}}
      @if($projects->hasPages())
        <div class="mt-10">{{ $projects->links() }}</div>
      @endif
    @endif
  </div>
</section>

@endsection
