@php
/**
 * Banner partial — include with ['position' => 'top|bottom|sidebar|inline|popup']
 * $bannersByPos is shared globally by AppServiceProvider (keyed by position).
 */
$L     = app()->getLocale();
$bT    = fn($b) => $b->{'title_'.$L} ?? $b->{'title_lo'} ?? '';
$bSub  = fn($b, $f='subtitle') => $b->{$f.'_'.$L} ?? $b->{$f.'_lo'} ?? '';
$bBtn  = fn($b) => $b->{'btn_text_'.$L} ?? $b->{'btn_text_lo'} ?? '';

$items = ($bannersByPos[$position] ?? collect())->values();
if ($items->isEmpty()) return;

$styleMap = [
  'banner-blue'  => ['grad'=>'from-primary to-primary-container',        'text'=>'text-white',     'sub'=>'text-white/75',  'btn'=>'bg-white text-primary hover:bg-white/90',                             'dot'=>'bg-white/40','dotA'=>'bg-white'],
  'banner-green' => ['grad'=>'from-emerald-600 to-emerald-800',           'text'=>'text-white',     'sub'=>'text-white/75',  'btn'=>'bg-white text-emerald-700 hover:bg-emerald-50',                       'dot'=>'bg-white/40','dotA'=>'bg-white'],
  'banner-gold'  => ['grad'=>'from-amber-500 to-amber-700',               'text'=>'text-white',     'sub'=>'text-white/75',  'btn'=>'bg-white text-amber-700 hover:bg-amber-50',                           'dot'=>'bg-white/40','dotA'=>'bg-white'],
  'banner-dark'  => ['grad'=>'from-gray-800 to-gray-900',                 'text'=>'text-white',     'sub'=>'text-white/70',  'btn'=>'bg-white text-gray-800 hover:bg-gray-100',                            'dot'=>'bg-white/40','dotA'=>'bg-white'],
  'banner-light' => ['grad'=>'from-slate-50 to-slate-100',                'text'=>'text-gray-800',  'sub'=>'text-gray-500',  'btn'=>'bg-primary text-on-primary hover:bg-secondary hover:text-on-secondary','dot'=>'bg-slate-300','dotA'=>'bg-primary'],
  'banner-red'   => ['grad'=>'from-red-600 to-red-800',                   'text'=>'text-white',     'sub'=>'text-white/75',  'btn'=>'bg-white text-red-700 hover:bg-red-50',                               'dot'=>'bg-white/40','dotA'=>'bg-white'],
];
$n = $items->count();
@endphp

{{-- ═══════════════════════════════════════════
     TOP / BOTTOM  — horizontal sliding strip
════════════════════════════════════════════ --}}
@if($position === 'top' || $position === 'bottom')

<div x-data="{
       cur:0,
       n:{{ $n }},
       _t:null,
       go(i){ this.cur=((i%this.n)+this.n)%this.n; this._restart(); },
       next(){ this.go(this.cur+1); },
       prev(){ this.go(this.cur-1); },
       _restart(){
         clearInterval(this._t);
         if(this.n>1) this._t=setInterval(()=>this.next(),6000);
       }
     }"
     x-init="_restart()"
     class="relative overflow-hidden">

  {{-- Slides wrapper (horizontal translate) --}}
  <div class="flex transition-transform duration-500 ease-in-out"
       :style="`transform:translateX(-${cur*100}%)`">

    @foreach($items as $banner)
      @php
        $s   = $styleMap[$banner->style] ?? $styleMap['banner-blue'];
        $tit = $bT($banner);
        $sub = $bSub($banner);
        $btn = $bBtn($banner);
        $img = $banner->image_url;
        $ext = $banner->btn_url && (str_starts_with($banner->btn_url,'http'));
      @endphp

      <div class="w-full shrink-0 bg-gradient-to-r {{ $s['grad'] }} relative">
        {{-- Image background overlay --}}
        @if($img)
          <div class="absolute inset-0 opacity-15 pointer-events-none">
            <img src="{{ $img }}" class="w-full h-full object-cover" alt="" />
          </div>
        @endif

        <div class="relative z-10 max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8
                    py-3.5 flex items-center gap-4">
          {{-- Text --}}
          <div class="flex-1 min-w-0">
            @if($tit)
              <p class="font-bold {{ $s['text'] }} text-sm leading-snug truncate">{{ $tit }}</p>
            @endif
            @if($sub)
              <p class="text-xs {{ $s['sub'] }} truncate mt-0.5">{{ $sub }}</p>
            @endif
          </div>

          {{-- Thumbnail --}}
          @if($img)
            <div class="hidden md:block w-12 h-12 rounded-lg overflow-hidden shrink-0 border border-white/20 shadow-sm">
              <img src="{{ $img }}" class="w-full h-full object-cover" alt="{{ $tit }}" />
            </div>
          @endif

          {{-- CTA Button --}}
          @if($btn && $banner->btn_url)
            <a href="{{ $banner->btn_url }}"
               @if($ext) target="_blank" rel="noreferrer" @endif
               class="shrink-0 inline-flex items-center gap-1.5 px-4 py-1.5 rounded-lg
                      text-xs font-bold {{ $s['btn'] }} transition-all whitespace-nowrap">
              {{ $btn }}
              <i class="fas {{ $ext ? 'fa-external-link-alt' : 'fa-arrow-right' }} text-[9px]"></i>
            </a>
          @endif

          {{-- Nav (dots + arrows) — only when multiple banners --}}
          @if($n > 1)
            <div class="hidden sm:flex items-center gap-1 shrink-0 ml-2">
              <button @click="prev()" aria-label="Previous"
                      class="w-5 h-5 rounded-full bg-black/10 hover:bg-black/20 flex items-center justify-center transition-colors cursor-pointer">
                <i class="fas fa-chevron-left text-[8px] {{ $s['text'] }}"></i>
              </button>
              @for($d = 0; $d < $n; $d++)
                <button @click="go({{ $d }})"
                        :class="cur === {{ $d }} ? '{{ $s['dotA'] }} scale-110' : '{{ $s['dot'] }}'"
                        class="w-1.5 h-1.5 rounded-full transition-all cursor-pointer"></button>
              @endfor
              <button @click="next()" aria-label="Next"
                      class="w-5 h-5 rounded-full bg-black/10 hover:bg-black/20 flex items-center justify-center transition-colors cursor-pointer">
                <i class="fas fa-chevron-right text-[8px] {{ $s['text'] }}"></i>
              </button>
            </div>
          @endif
        </div>
      </div>
    @endforeach

  </div>{{-- /slides --}}
</div>

{{-- ═══════════════════════════════════════════
     SIDEBAR  — stacked compact cards
════════════════════════════════════════════ --}}
@elseif($position === 'sidebar')

<div class="flex flex-col gap-3">
  @foreach($items as $banner)
    @php
      $s   = $styleMap[$banner->style] ?? $styleMap['banner-blue'];
      $tit = $bT($banner);
      $sub = $bSub($banner);
      $btn = $bBtn($banner);
      $img = $banner->image_url;
      $ext = $banner->btn_url && str_starts_with($banner->btn_url,'http');
    @endphp
    <div class="rounded-2xl overflow-hidden border border-slate-100 shadow-sm bg-gradient-to-br {{ $s['grad'] }} relative">
      @if($img)
        <img src="{{ $img }}" alt="{{ $tit }}"
             class="w-full h-32 object-cover opacity-80 block" />
      @endif
      <div class="p-4 relative z-10">
        @if($tit)
          <p class="font-bold {{ $s['text'] }} text-sm leading-snug mb-1">{{ $tit }}</p>
        @endif
        @if($sub)
          <p class="text-xs {{ $s['sub'] }} mb-3 leading-snug">{{ $sub }}</p>
        @endif
        @if($btn && $banner->btn_url)
          <a href="{{ $banner->btn_url }}"
             @if($ext) target="_blank" rel="noreferrer" @endif
             class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold
                    {{ $s['btn'] }} transition-all">
            {{ $btn }}
            <i class="fas {{ $ext ? 'fa-external-link-alt' : 'fa-arrow-right' }} text-[9px]"></i>
          </a>
        @endif
      </div>
    </div>
  @endforeach
</div>

{{-- ═══════════════════════════════════════════
     INLINE  — horizontal card(s)
════════════════════════════════════════════ --}}
@elseif($position === 'inline')

<div class="flex flex-col gap-4">
  @foreach($items as $banner)
    @php
      $s   = $styleMap[$banner->style] ?? $styleMap['banner-blue'];
      $tit = $bT($banner);
      $sub = $bSub($banner);
      $btn = $bBtn($banner);
      $img = $banner->image_url;
      $ext = $banner->btn_url && str_starts_with($banner->btn_url,'http');
    @endphp
    <div class="rounded-2xl overflow-hidden border border-slate-100 shadow-sm
                bg-gradient-to-r {{ $s['grad'] }} flex items-center gap-0">
      @if($img)
        <div class="w-28 h-20 shrink-0 overflow-hidden">
          <img src="{{ $img }}" alt="{{ $tit }}" class="w-full h-full object-cover" />
        </div>
      @endif
      <div class="flex-1 min-w-0 px-5 py-3.5 flex items-center gap-4">
        <div class="flex-1 min-w-0">
          @if($tit)
            <p class="font-bold {{ $s['text'] }} text-sm leading-snug">{{ $tit }}</p>
          @endif
          @if($sub)
            <p class="text-xs {{ $s['sub'] }} mt-0.5 line-clamp-1">{{ $sub }}</p>
          @endif
        </div>
        @if($btn && $banner->btn_url)
          <a href="{{ $banner->btn_url }}"
             @if($ext) target="_blank" rel="noreferrer" @endif
             class="shrink-0 inline-flex items-center gap-1.5 px-4 py-2 rounded-xl
                    text-xs font-bold {{ $s['btn'] }} transition-all whitespace-nowrap">
            {{ $btn }}
            <i class="fas {{ $ext ? 'fa-external-link-alt' : 'fa-arrow-right' }} text-[9px]"></i>
          </a>
        @endif
      </div>
    </div>
  @endforeach
</div>

{{-- ═══════════════════════════════════════════
     POPUP  — modal shown once per session
════════════════════════════════════════════ --}}
@elseif($position === 'popup')
  @php
    $popup = $items->first();
    $s     = $styleMap[$popup->style] ?? $styleMap['banner-blue'];
    $tit   = $bT($popup);
    $sub   = $bSub($popup);
    $btn   = $bBtn($popup);
    $img   = $popup->image_url;
    $ext   = $popup->btn_url && str_starts_with($popup->btn_url,'http');
    $sid   = 'bfol_popup_'.$popup->id;
  @endphp

  <div x-data="{ open: false }"
       x-init="if(!sessionStorage.getItem('{{ $sid }}')){
                 setTimeout(()=>{ open=true; },800);
               }"
       x-cloak>

    {{-- Backdrop --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-end="opacity-0"
         @click="open=false; sessionStorage.setItem('{{ $sid }}',1)"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[900]"></div>

    {{-- Modal --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-90"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-end="opacity-0 scale-90"
         class="fixed inset-0 z-[901] flex items-center justify-center p-4 pointer-events-none">

      <div class="pointer-events-auto w-full max-w-md rounded-3xl overflow-hidden shadow-2xl
                  bg-gradient-to-br {{ $s['grad'] }} relative">

        {{-- Close button --}}
        <button @click="open=false; sessionStorage.setItem('{{ $sid }}',1)"
                aria-label="Close"
                class="absolute top-3 right-3 z-10 w-8 h-8 rounded-full bg-black/15
                       hover:bg-black/30 flex items-center justify-center transition-colors cursor-pointer">
          <i class="fas fa-times text-xs {{ $s['text'] }}"></i>
        </button>

        @if($img)
          <div class="w-full h-52 overflow-hidden">
            <img src="{{ $img }}" alt="{{ $tit }}" class="w-full h-full object-cover" />
          </div>
        @endif

        <div class="p-6">
          @if($tit)
            <h3 class="font-serif font-bold {{ $s['text'] }} text-xl leading-snug mb-2">{{ $tit }}</h3>
          @endif
          @if($sub)
            <p class="text-sm {{ $s['sub'] }} leading-relaxed mb-4">{{ $sub }}</p>
          @endif
          <div class="flex items-center gap-3">
            @if($btn && $popup->btn_url)
              <a href="{{ $popup->btn_url }}"
                 @if($ext) target="_blank" rel="noreferrer" @endif
                 @click="sessionStorage.setItem('{{ $sid }}',1)"
                 class="flex-1 inline-flex items-center justify-center gap-2 px-5 py-2.5
                        rounded-xl text-sm font-bold {{ $s['btn'] }} transition-all">
                {{ $btn }}
                <i class="fas {{ $ext ? 'fa-external-link-alt' : 'fa-arrow-right' }} text-xs"></i>
              </a>
            @endif
            <button @click="open=false; sessionStorage.setItem('{{ $sid }}',1)"
                    class="px-4 py-2.5 rounded-xl text-sm font-semibold {{ $s['text'] }}
                           bg-black/10 hover:bg-black/20 transition-colors cursor-pointer">
              {{ app()->getLocale() === 'lo' ? 'ປິດ' : (app()->getLocale() === 'zh' ? '關閉' : 'Close') }}
            </button>
          </div>
        </div>
      </div>
    </div>

  </div>

@endif
