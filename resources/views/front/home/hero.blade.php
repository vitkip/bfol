@php
  $L = app()->getLocale();
  $t = fn($lo,$en,$zh) => match($L){'zh'=>$zh,'en'=>$en,default=>$lo};
  $count = max($slides->count(), 1);
@endphp

<section
  x-data="heroSlider({{ $count }})"
  @mouseenter="pause()" @mouseleave="resume()"
  class="relative h-[600px] md:h-[720px] overflow-hidden bg-primary"
>

  {{-- ── Slide backgrounds ────────────────────────────────────────────── --}}
  @forelse($slides as $i => $slide)
    <div class="absolute inset-0 transition-opacity duration-1000"
         :class="current === {{ $i }} ? 'opacity-100' : 'opacity-0'">

      {{-- Background image with Ken Burns zoom --}}
      @if($slide->image_url)
        <img src="{{ $slide->image_url }}"
             alt="{{ $slide->trans('title') }}"
             class="absolute inset-0 w-full h-full object-cover
                    transition-transform duration-[8000ms] ease-in-out will-change-transform"
             :class="current === {{ $i }} ? 'scale-110' : 'scale-100'" />
      @else
        <div class="absolute inset-0 bg-gradient-to-br from-primary-container to-primary">
          <div class="absolute top-1/3 left-1/2 -translate-x-1/2 w-[600px] h-[600px]
                      rounded-full bg-secondary/8 blur-[160px] animate-float"></div>
        </div>
      @endif

      {{-- Overlay: gradient only — keeps image visible --}}
      <div class="absolute inset-0 bg-gradient-to-t
                  from-slate-900/80 via-slate-900/30 to-slate-900/10"></div>
      {{-- Side vignette for depth --}}
      <div class="absolute inset-0 bg-gradient-to-r
                  from-slate-900/40 via-transparent to-slate-900/20"></div>
    </div>
  @empty
    {{-- No slides: branded gradient fallback --}}
    <div class="absolute inset-0 bg-gradient-to-br from-primary via-primary-container to-primary">
      <div class="absolute top-1/3 left-1/2 -translate-x-1/2 w-[600px] h-[600px]
                  rounded-full bg-secondary/10 blur-[160px] animate-float"></div>
      <div class="absolute bottom-0 left-0 right-0 h-1/2
                  bg-gradient-to-t from-slate-900/60 to-transparent"></div>
    </div>
  @endforelse

  {{-- ── Slide content ──────────────────────────────────────────────── --}}
  <div class="relative z-10 h-full flex items-center pb-0">
    <div class="max-w-[1200px] mx-auto px-6 sm:px-8 lg:px-10 w-full">

      @forelse($slides as $i => $slide)
        <div x-show="current === {{ $i }}"
             x-transition:enter="transition ease-out duration-700 delay-300"
             x-transition:enter-start="opacity-0 translate-y-8"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="{{ $i > 0 ? 'display:none' : '' }}"
             class="max-w-2xl">

          {{-- Tag badge --}}
          @if($slide->trans('tag'))
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full
                         bg-secondary/90 text-on-secondary text-xs font-bold tracking-wider
                         uppercase mb-5 shadow-md">
              <span class="w-1.5 h-1.5 rounded-full bg-on-secondary/60 animate-pulse"></span>
              {{ $slide->trans('tag') }}
            </div>
          @endif

          {{-- Title --}}
          <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-serif font-bold text-white
                      leading-[1.15] mb-5"
              style="text-shadow: 0 2px 12px rgba(0,0,0,0.55), 0 1px 3px rgba(0,0,0,0.4);">
            {{ $slide->trans('title') }}
          </h1>

          {{-- Subtitle --}}
          @if($slide->trans('subtitle'))
            <p class="text-white/90 text-base md:text-lg mb-8 leading-relaxed max-w-xl"
               style="text-shadow: 0 1px 6px rgba(0,0,0,0.5);">
              {{ $slide->trans('subtitle') }}
            </p>
          @endif

          {{-- Buttons --}}
          <div class="flex flex-wrap gap-3 mt-2">

            {{-- Primary: ລາຍລະອຽດ --}}
            @if($slide->trans('btn1_text'))
              <a href="{{ $slide->btn1_url ?: '#' }}"
                 class="group relative inline-flex items-center gap-2.5
                         px-6 py-3 rounded-full
                         bg-secondary text-on-secondary font-bold text-sm tracking-wide
                         shadow-lg shadow-black/30
                         hover:shadow-xl hover:shadow-black/40
                         hover:-translate-y-0.5 active:translate-y-0
                         transition-all duration-250 overflow-hidden">
                {{-- shine sweep --}}
                <span class="absolute inset-0 translate-x-[-100%] group-hover:translate-x-[100%]
                              bg-gradient-to-r from-transparent via-white/20 to-transparent
                              transition-transform duration-500 ease-in-out pointer-events-none"></span>
                <i class="fas fa-book-open text-xs opacity-80"></i>
                {{ $slide->trans('btn1_text') }}
                <i class="fas fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform duration-200"></i>
              </a>
            @endif

            {{-- Secondary: ຄູ່ຮ່ວມມື --}}
            @if($slide->trans('btn2_text'))
              <a href="{{ $slide->btn2_url ?: '#' }}"
                 class="group inline-flex items-center gap-2.5
                         px-6 py-3 rounded-full
                         bg-white/10 backdrop-blur-md
                         border border-white/25 text-white font-semibold text-sm tracking-wide
                         hover:bg-white/20 hover:border-white/40
                         hover:-translate-y-0.5 active:translate-y-0
                         transition-all duration-250">
                <i class="fas fa-handshake text-xs opacity-70"></i>
                {{ $slide->trans('btn2_text') }}
              </a>
            @endif

          </div>
        </div>

      @empty
        {{-- Static fallback --}}
        <div class="max-w-2xl">
          <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-serif font-bold text-white
                      leading-[1.15] mb-5"
              style="text-shadow: 0 2px 12px rgba(0,0,0,0.55), 0 1px 3px rgba(0,0,0,0.4);">
            {{ $t('ສະຫະພັນພຸດທະສາສະໜາລາວ',
                  'Buddhist Federation of Laos',
                  '老撾佛教聯合會') }}
          </h1>
          <p class="text-white/90 text-base md:text-lg mb-8 leading-relaxed max-w-xl"
             style="text-shadow: 0 1px 6px rgba(0,0,0,0.5);">
            {{ $t('ຮ່ວມກັນສົ່ງເສີມ ແລະ ພັດທະນາ ດ້ານສາສະໜາ ທາງ ລາວ ໃນ ລະດັບ ຊາດ ແລະ ສາກົນ',
                  'Promoting and developing Lao Buddhism at national and international levels.',
                  '共同推廣和發展國家和國際層面的老撾佛教。') }}
          </p>
        </div>
      @endforelse
    </div>
  </div>

  {{-- ── Navigation arrows ──────────────────────────────────────────── --}}
  @if($slides->count() > 1)
    {{-- Slide counter --}}
    <div class="absolute top-6 right-6 z-20 hidden md:flex items-center gap-1.5
                bg-black/30 backdrop-blur-md rounded-lg px-3.5 py-2 border border-white/10">
      <span class="text-secondary font-bold text-lg leading-none font-mono"
            x-text="String(current+1).padStart(2,'0')">01</span>
      <span class="text-white/20 text-sm">/</span>
      <span class="text-white/50 text-sm leading-none font-mono">
        {{ str_pad($slides->count(), 2, '0', STR_PAD_LEFT) }}
      </span>
    </div>

    {{-- Prev --}}
    <button @click="prev()" aria-label="{{ $t('ກ່ອນໜ້າ','Previous','上一個') }}"
            class="absolute left-4 md:left-6 top-1/2 -translate-y-1/2 z-20
                   w-11 h-11 rounded-full cursor-pointer
                   bg-black/25 backdrop-blur-md border border-white/15 text-white
                   flex items-center justify-center
                   hover:bg-secondary hover:border-secondary hover:text-on-secondary
                   hover:scale-110 transition-all duration-200">
      <i class="fas fa-chevron-left text-sm"></i>
    </button>

    {{-- Next --}}
    <button @click="next()" aria-label="{{ $t('ຕໍ່ໄປ','Next','下一個') }}"
            class="absolute right-4 md:right-6 top-1/2 -translate-y-1/2 z-20
                   w-11 h-11 rounded-full cursor-pointer
                   bg-black/25 backdrop-blur-md border border-white/15 text-white
                   flex items-center justify-center
                   hover:bg-secondary hover:border-secondary hover:text-on-secondary
                   hover:scale-110 transition-all duration-200">
      <i class="fas fa-chevron-right text-sm"></i>
    </button>

    {{-- Dot indicators --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-20 flex items-center gap-2">
      @foreach($slides as $i => $_)
        <button @click="goto({{ $i }})"
                :class="current === {{ $i }}
                  ? 'w-7 h-2 bg-secondary shadow-sm shadow-secondary/50'
                  : 'w-2 h-2 bg-white/35 hover:bg-white/60'"
                class="rounded-full transition-all duration-300 cursor-pointer">
        </button>
      @endforeach
    </div>
  @endif

  {{-- ── Progress bar ────────────────────────────────────────────────── --}}
  <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-white/10 z-20">
    <div :key="current"
         :class="paused ? '' : 'progress-running'"
         class="h-full bg-gradient-to-r from-secondary to-secondary-container origin-left"></div>
  </div>

</section>
