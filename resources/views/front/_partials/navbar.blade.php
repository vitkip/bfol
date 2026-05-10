@php
  $L = app()->getLocale();
  $t = fn($lo,$en,$zh) => match($L){'zh'=>$zh,'en'=>$en,default=>$lo};
  $R = fn($name,...$p) => route('front.'.$name,...$p);

  $labelFn = fn($m) => match($L) {
    'zh'    => $m->label_zh ?: $m->label_lo,
    'en'    => $m->label_en ?: $m->label_lo,
    default => $m->label_lo,
  };

  $isExternal = fn($m) => $m->target === '_blank' || ($m->target !== '_self' && str_starts_with((string)$m->url, 'http'));

  // Recursive tree builder — keys: label, icon, url, external, children[]
  $buildTree = null;
  $buildTree = function($items) use (&$buildTree, $labelFn, $isExternal): array {
      return $items->map(fn($m) => [
          'label'    => $labelFn($m),
          'icon'     => $m->icon ?: null,
          'url'      => $m->url ?: null,
          'external' => $isExternal($m),
          'children' => $m->children->isNotEmpty() ? $buildTree($m->children) : [],
      ])->all();
  };

  if (isset($navMenus) && $navMenus->isNotEmpty()) {
      $menu = $buildTree($navMenus);
  } else {
      $menu = [
          ['label'=>$t('ໜ້າຫຼັກ','Home','首頁'),            'icon'=>null,'url'=>$R('home'),       'external'=>false,'children'=>[]],
          ['label'=>$t('ກ່ຽວກັບ ອພສ','About BFOL','關於我們'),'icon'=>null,'url'=>null,            'external'=>false,'children'=>[
              ['icon'=>'fas fa-landmark',          'label'=>$t('ປະຫວັດ','History','歷史'),     'url'=>$R('page.show','history'),  'external'=>false,'children'=>[]],
              ['icon'=>'fas fa-bullseye',          'label'=>$t('ວິໄສທັດ','Mission','使命'),    'url'=>$R('page.show','mission'),  'external'=>false,'children'=>[]],
              ['icon'=>'fas fa-sitemap',           'label'=>$t('ໂຄງສ້າງ','Structure','結構'),  'url'=>$R('structure'),            'external'=>false,'children'=>[]],
              ['icon'=>'fas fa-users',             'label'=>$t('ຄະນະກຳ','Committee','委員會'), 'url'=>$R('committee'),            'external'=>false,'children'=>[]],
          ]],
          ['label'=>$t('ດ້ານການສຶກສາ','Education','教育'),    'icon'=>null,'url'=>null,            'external'=>false,'children'=>[
              ['icon'=>'fas fa-dharmachakra',      'label'=>$t('ສີລ & ທຳ','Sila & Dhamma','戒定慧'),'url'=>$R('page.show','sila-dhamma'),'external'=>false,'children'=>[]],
              ['icon'=>'fas fa-chalkboard-teacher','label'=>$t('ການສອນ','Teaching','教學'),     'url'=>$R('page.show','teaching'),   'external'=>false,'children'=>[]],
              ['icon'=>'fas fa-microscope',        'label'=>$t('ວິໄຊ','Research','研究'),       'url'=>$R('page.show','research'),   'external'=>false,'children'=>[]],
              ['icon'=>'fas fa-hands-helping',     'label'=>$t('ສັງຄົມ','Society','社會'),      'url'=>$R('page.show','society'),    'external'=>false,'children'=>[]],
          ]],
          ['label'=>$t('ຕ່າງປະເທດ','International','國際'),   'icon'=>null,'url'=>null,            'external'=>false,'children'=>[
              ['icon'=>'fas fa-globe-asia',        'label'=>$t('ຄູ່ຮ່ວມ','Partners','合作夥伴'),   'url'=>$R('partners.index'),      'external'=>false,'children'=>[]],
              ['icon'=>'fas fa-exchange-alt',      'label'=>$t('ແລກປ່ຽນ','Exchange','交流'),       'url'=>$R('monk-programs.index'), 'external'=>false,'children'=>[]],
              ['icon'=>'fas fa-file-signature',    'label'=>'MOU',                                   'url'=>$R('mou.index'),           'external'=>false,'children'=>[]],
              ['icon'=>'fas fa-hand-holding-heart','label'=>$t('ຊ່ວຍເຫຼືອ','Aid Projects','援助'), 'url'=>$R('aid-projects.index'), 'external'=>false,'children'=>[]],
          ]],
          ['label'=>$t('ສື່ສາ','Media','媒體'),               'icon'=>null,'url'=>null,            'external'=>false,'children'=>[
              ['icon'=>'fab fa-youtube',           'label'=>'DhammaOnLen',                           'url'=>'https://www.youtube.com/@DhammaOnLen','external'=>true,'children'=>[]],
              ['icon'=>'fas fa-photo-video',       'label'=>$t('ສື່','Media','媒體'),               'url'=>$R('media.index'),         'external'=>false,'children'=>[]],
              ['icon'=>'fas fa-images',            'label'=>$t('ຮູບພາບ','Gallery','相冊'),           'url'=>$R('gallery.index'),       'external'=>false,'children'=>[]],
              ['icon'=>'fas fa-language',          'label'=>$t('ແປພາສາ','Translation','翻譯'),       'url'=>$R('translations.index'),  'external'=>false,'children'=>[]],
              ['icon'=>'fas fa-file-lines',        'label'=>$t('ເອກະສານ','Documents','文件'),        'url'=>$R('documents.index'),     'external'=>false,'children'=>[]],
          ]],
          ['label'=>$t('ຂ່າວສານ','News','新聞'),              'icon'=>null,'url'=>$R('news.index'), 'external'=>false,'children'=>[]],
          ['label'=>$t('ຕິດຕໍ່','Contact','聯繫'),             'icon'=>null,'url'=>$R('contact'),    'external'=>false,'children'=>[]],
      ];
  }
@endphp

<header id="site-header"
  class="sticky top-0 z-[100] w-full transition-all duration-500"
  style="background-color:#031632"
>
  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between py-4">

      {{-- Logo --}}
      <a href="{{ $R('home') }}" class="flex items-center gap-3 min-w-0 group cursor-pointer">
        @if($settings->logo_url)
          <img src="{{ $settings->logo_url }}" alt="{{ $settings->site_name_lo }}"
               class="w-11 h-11 rounded-lg object-contain bg-white p-0.5 border border-white/30 shrink-0
                      group-hover:scale-105 transition-transform" />
        @else
          <div class="w-11 h-11 rounded-lg bg-gradient-to-br from-secondary to-secondary-container
                      flex items-center justify-center text-on-secondary-container text-xl shrink-0
                      shadow-lg shadow-secondary/20 group-hover:scale-105 transition-transform">
            <i class="fas fa-dharmachakra"></i>
          </div>
        @endif
        <div class="leading-tight min-w-0 max-w-[200px]">
          <div class="font-serif font-bold text-[15px] truncate text-white group-hover:text-secondary transition-colors">
            {{ $t($settings->site_name_lo, $settings->site_name_en, $settings->site_name_zh) ?: 'ອພສ · BFOL' }}
          </div>
          <div class="text-[11px] truncate text-white/60">
            {{ $settings->site_name_en ?: 'BFOL' }}
          </div>
        </div>
      </a>

      {{-- ── Desktop nav ────────────────────────────────────────────────────── --}}
      <nav class="hidden lg:flex items-center gap-0.5 xl:gap-1">
        @foreach($menu as $menuIdx => $item)

          @if(!empty($item['children']))
            {{-- L1 Dropdown --}}
            <div class="relative nav-dropdown">
              <button type="button"
                      class="nav-dd-btn flex items-center gap-1.5 px-3 py-2 text-[13px] font-semibold rounded-lg
                             transition-all duration-200 cursor-pointer text-white/80 hover:text-secondary hover:bg-white/10">
                @if(!empty($item['icon']))
                  <i class="{{ $item['icon'] }} text-[11px] opacity-70"></i>
                @endif
                {{ $item['label'] }}
                <i class="nav-dd-icon fas fa-chevron-down text-[9px] opacity-60 transition-transform duration-200"></i>
              </button>

              {{-- Split-panel: L2 left column + L3 right column side-by-side --}}
              <div class="nav-dd-panel hidden absolute top-full left-0 pt-2 z-50">
                <div class="flex bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">

                  {{-- ── L2 column ── --}}
                  <div class="p-2 w-[230px] shrink-0">
                    @foreach($item['children'] as $subIdx => $sub)
                      @if(!empty($sub['children']))
                        {{-- L2 item with L3 children → click to reveal L3 column --}}
                        <button type="button"
                                data-l3="m{{ $menuIdx }}s{{ $subIdx }}"
                                class="nav-dd-sub-btn w-full flex items-center justify-between gap-2 px-4 py-2.5
                                       rounded-md hover:bg-slate-50 transition-colors cursor-pointer text-left">
                          <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center shrink-0">
                              <i class="{{ $sub['icon'] ?? 'fas fa-circle' }} text-slate-500 text-sm"></i>
                            </div>
                            <span class="text-sm font-medium text-slate-700">{{ $sub['label'] }}</span>
                          </div>
                          <i class="fas fa-chevron-right text-[9px] text-slate-300 shrink-0 transition-all duration-150"></i>
                        </button>
                      @else
                        {{-- L2 direct link --}}
                        <a href="{{ $sub['url'] ?? '#' }}"
                           @if(!empty($sub['external'])) target="_blank" rel="noreferrer" @endif
                           class="nav-dd-link flex items-center gap-3 px-4 py-2.5 rounded-md hover:bg-slate-50
                                  group/s2 transition-colors cursor-pointer">
                          <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center
                                      group-hover/s2:bg-secondary/20 transition-colors shrink-0">
                            <i class="{{ $sub['icon'] ?? 'fas fa-circle' }} text-slate-500 group-hover/s2:text-secondary text-sm"></i>
                          </div>
                          <span class="text-sm font-medium text-slate-700 group-hover/s2:text-primary">
                            {{ $sub['label'] }}
                          </span>
                        </a>
                      @endif
                    @endforeach
                  </div>

                  {{-- ── L3 columns (one per L2-with-children, shown on demand) ── --}}
                  @foreach($item['children'] as $subIdx => $sub)
                    @if(!empty($sub['children']))
                      <div data-l3-panel="m{{ $menuIdx }}s{{ $subIdx }}"
                           class="nav-l3-panel hidden border-l border-slate-100 p-2 w-[210px] shrink-0 bg-slate-50/40">
                        {{-- L3 category header --}}
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest px-3 py-1.5">
                          <i class="{{ $sub['icon'] ?? 'fas fa-layer-group' }} mr-1 opacity-60"></i>
                          {{ $sub['label'] }}
                        </p>
                        @foreach($sub['children'] as $subsub)
                          <a href="{{ $subsub['url'] ?? '#' }}"
                             @if(!empty($subsub['external'])) target="_blank" rel="noreferrer" @endif
                             class="nav-dd-link flex items-center gap-3 px-3 py-2.5 rounded-md hover:bg-white
                                    group/s3 transition-colors cursor-pointer">
                            <div class="w-7 h-7 rounded-full bg-white flex items-center justify-center
                                        group-hover/s3:bg-secondary/20 transition-colors shrink-0 border border-slate-100">
                              <i class="{{ $subsub['icon'] ?? 'fas fa-circle' }} text-slate-500 group-hover/s3:text-secondary text-xs"></i>
                            </div>
                            <span class="text-sm font-medium text-slate-700 group-hover/s3:text-primary whitespace-nowrap">
                              {{ $subsub['label'] }}
                            </span>
                          </a>
                        @endforeach
                      </div>
                    @endif
                  @endforeach

                </div>
              </div>
            </div>

          @elseif($item['url'])
            {{-- L1 direct link --}}
            <a href="{{ $item['url'] }}"
               @if(!empty($item['external'])) target="_blank" rel="noreferrer" @endif
               class="flex items-center gap-1.5 px-3 py-2 text-[13px] font-semibold rounded-lg
                      transition-all duration-200 cursor-pointer
                      {{ request()->url() === $item['url'] ? 'text-secondary' : 'text-white/80 hover:text-secondary hover:bg-white/10' }}">
              @if(!empty($item['icon']))
                <i class="{{ $item['icon'] }} text-[11px] opacity-70"></i>
              @endif
              {{ $item['label'] }}
            </a>
          @endif

        @endforeach

        {{-- Search --}}
        <div class="relative ml-1">
          <button id="search-toggle" type="button"
                  class="p-2.5 rounded-lg text-white/70 hover:text-secondary hover:bg-white/10
                         transition-all duration-200 cursor-pointer"
                  aria-label="{{ $t('ຄົ້ນຫາ','Search','搜索') }}">
            <i id="search-icon" class="fas fa-search text-[14px]"></i>
          </button>
          <div id="search-panel" class="hidden absolute top-full right-0 mt-2 w-72 z-50">
            <form action="{{ $R('search') }}" method="GET"
                  class="bg-white rounded-xl shadow-xl border border-slate-100 overflow-hidden flex">
              <input id="search-input"
                     type="text" name="q"
                     placeholder="{{ $t('ຄົ້ນຫາ...','Search...','搜索...') }}"
                     class="flex-1 px-4 py-2.5 text-sm text-slate-800
                            focus:outline-none placeholder:text-slate-400" />
              <button type="submit"
                      class="px-4 py-2.5 bg-primary text-on-primary text-sm
                             hover:bg-secondary hover:text-on-secondary transition-colors cursor-pointer">
                <i class="fas fa-search text-xs"></i>
              </button>
            </form>
          </div>
        </div>

        {{-- CTA --}}
        <a href="{{ $R('contact') }}"
           class="ml-2 px-5 py-2.5 bg-secondary text-on-secondary text-[13px] font-bold rounded-md cursor-pointer
                  shadow-sm hover:bg-secondary-container hover:text-on-secondary-container hover:shadow-md
                  hover:-translate-y-0.5 transition-all duration-200">
          {{ $t('ຕິດຕໍ່ພວກເຮົາ','Contact Us','聯繫我們') }}
        </a>
      </nav>

      {{-- Mobile: search + hamburger --}}
      <div class="lg:hidden flex items-center gap-1">
        <a href="{{ $R('search') }}"
           class="p-2.5 rounded-md transition-colors cursor-pointer text-white/80 hover:bg-white/10">
          <i class="fas fa-search text-base"></i>
        </a>
        <button id="mobile-menu-toggle" type="button"
                class="p-2.5 rounded-md transition-colors cursor-pointer text-white hover:bg-white/10">
          <i class="fas fa-bars text-lg"></i>
        </button>
      </div>
    </div>

    {{-- ── Mobile menu ─────────────────────────────────────────────────────── --}}
    <div id="mobile-menu" class="hidden lg:hidden overflow-hidden pb-4">
      <div class="bg-white rounded-2xl p-2 flex flex-col gap-0.5 max-h-[75vh] overflow-y-auto
                  border border-slate-100 shadow-xl mt-2">

        {{-- Mobile search --}}
        <div class="px-2 pb-2 pt-1">
          <form action="{{ $R('search') }}" method="GET"
                class="flex items-center gap-2 bg-slate-50 rounded-xl border border-slate-200 overflow-hidden px-3">
            <i class="fas fa-search text-slate-400 text-xs shrink-0"></i>
            <input type="text" name="q"
                   placeholder="{{ $t('ຄົ້ນຫາ...','Search...','搜索...') }}"
                   class="flex-1 py-2.5 text-sm bg-transparent text-slate-800
                          focus:outline-none placeholder:text-slate-400" />
            <button type="submit" class="text-primary hover:text-secondary transition-colors cursor-pointer">
              <i class="fas fa-arrow-right text-xs"></i>
            </button>
          </form>
        </div>

        @foreach($menu as $item)

          @if(!empty($item['children']))
            {{-- L1 accordion --}}
            <div class="rounded-xl overflow-hidden">
              <button type="button"
                      class="mobile-sub-toggle w-full flex justify-between items-center px-4 py-3 text-sm font-semibold
                             text-slate-700 hover:text-primary hover:bg-slate-50 transition-colors
                             cursor-pointer rounded-xl">
                <span class="flex items-center gap-2">
                  @if(!empty($item['icon']))
                    <i class="{{ $item['icon'] }} text-primary text-[11px]"></i>
                  @endif
                  {{ $item['label'] }}
                </span>
                <i class="mobile-sub-icon fas fa-chevron-down text-[10px] text-slate-400 transition-transform duration-300"></i>
              </button>

              <div class="mobile-sub-panel hidden px-2 pb-1 flex flex-col gap-0.5">
                @foreach($item['children'] as $sub)

                  @if(!empty($sub['children']))
                    {{-- L2 with L3 nested accordion --}}
                    <div class="rounded-xl overflow-hidden">
                      <button type="button"
                              class="mobile-sub-toggle-2 w-full flex justify-between items-center px-3 py-2.5
                                     text-[13px] font-medium text-slate-600 hover:text-primary hover:bg-slate-50
                                     transition-colors cursor-pointer rounded-xl">
                        <span class="flex items-center gap-2">
                          <span class="w-6 h-6 rounded-full bg-blue-50 flex items-center justify-center shrink-0">
                            <i class="{{ $sub['icon'] ?? 'fas fa-circle' }} text-primary text-[9px]"></i>
                          </span>
                          {{ $sub['label'] }}
                        </span>
                        <i class="mobile-sub-icon-2 fas fa-chevron-down text-[9px] text-slate-400 transition-transform duration-300"></i>
                      </button>

                      <div class="mobile-sub-panel-2 hidden pl-3 pb-1 flex flex-col gap-0.5">
                        @foreach($sub['children'] as $subsub)
                          <a href="{{ $subsub['url'] ?? '#' }}"
                             @if(!empty($subsub['external'])) target="_blank" rel="noreferrer" @endif
                             class="mobile-link flex items-center gap-2 px-3 py-2 text-[12px] font-medium
                                    text-slate-500 hover:text-primary hover:bg-blue-50 rounded-xl transition-colors">
                            <span class="w-5 h-5 rounded-full bg-blue-50 flex items-center justify-center shrink-0">
                              <i class="{{ $subsub['icon'] ?? 'fas fa-circle' }} text-primary text-[8px]"></i>
                            </span>
                            {{ $subsub['label'] }}
                          </a>
                        @endforeach
                      </div>
                    </div>

                  @else
                    {{-- L2 direct link --}}
                    <a href="{{ $sub['url'] ?? '#' }}"
                       @if(!empty($sub['external'])) target="_blank" rel="noreferrer" @endif
                       class="mobile-link flex items-center gap-3 px-4 py-2.5 text-[13px] font-medium
                              text-slate-600 hover:text-primary hover:bg-blue-50 rounded-xl transition-colors">
                      <span class="w-6 h-6 rounded-full bg-blue-50 flex items-center justify-center shrink-0">
                        <i class="{{ $sub['icon'] ?? 'fas fa-circle' }} text-primary text-[9px]"></i>
                      </span>
                      {{ $sub['label'] }}
                    </a>
                  @endif

                @endforeach
              </div>
            </div>

          @elseif($item['url'])
            {{-- L1 direct link --}}
            <a href="{{ $item['url'] }}"
               @if(!empty($item['external'])) target="_blank" rel="noreferrer" @endif
               class="mobile-link flex items-center gap-2 px-4 py-3 text-sm font-medium rounded-xl transition-all cursor-pointer
                      {{ request()->url() === $item['url'] ? 'bg-blue-50 text-primary' : 'text-slate-600 hover:bg-slate-50 hover:text-primary' }}">
              @if(!empty($item['icon']))
                <i class="{{ $item['icon'] }} text-[11px] opacity-60"></i>
              @endif
              {{ $item['label'] }}
            </a>
          @endif

        @endforeach

        {{-- Language switcher (mobile) --}}
        <div class="px-2 pt-2 border-t border-slate-100 mt-1">
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.15em] px-1 mb-2">
            {{ $t('ພາສາ','Language','语言') }}
          </p>
          <div class="flex gap-1.5 bg-slate-50 p-1 rounded-xl">
            @foreach(['lo' => 'ລາວ', 'en' => 'English', 'zh' => '中文'] as $code => $label)
              <a href="{{ route('lang.switch', $code) }}"
                 class="flex-1 text-center py-2 text-[13px] font-semibold rounded-lg transition-all
                        {{ $locale === $code
                           ? 'bg-white text-primary shadow-sm'
                           : 'text-slate-400 hover:text-slate-600 hover:bg-white/60' }}">
                {{ $label }}
              </a>
            @endforeach
          </div>
        </div>

        <div class="pt-2 px-2">
          <a href="{{ $R('contact') }}"
             class="mobile-link block text-center py-3 bg-secondary text-on-secondary text-sm font-bold rounded-md
                    hover:bg-secondary-container hover:text-on-secondary-container transition-colors cursor-pointer">
            {{ $t('ຕິດຕໍ່ພວກເຮົາ','Contact Us','聯繫我們') }}
          </a>
        </div>
      </div>
    </div>

  </div>
</header>

<script>
(function () {
  // ── Scroll shadow ──────────────────────────────────────────────────────
  var header = document.getElementById('site-header');
  window.addEventListener('scroll', function () {
    header.style.boxShadow = window.scrollY > 20 ? '0 1px 8px rgba(0,0,0,0.35)' : '';
  }, { passive: true });

  // ── Close everything ───────────────────────────────────────────────────
  function closeAllDropdowns() {
    document.querySelectorAll('.nav-dd-panel').forEach(function (p) { p.classList.add('hidden'); });
    document.querySelectorAll('.nav-l3-panel').forEach(function (p) { p.classList.add('hidden'); });
    document.querySelectorAll('.nav-dd-icon').forEach(function (i) { i.classList.remove('rotate-180'); });
    document.querySelectorAll('.nav-dd-btn').forEach(function (b) { b.classList.remove('text-secondary','bg-white/10'); });
    document.querySelectorAll('.nav-dd-sub-btn').forEach(function (b) {
      b.classList.remove('bg-slate-100','text-primary');
      var arrow = b.querySelector('.fa-chevron-right');
      if (arrow) { arrow.style.transform = ''; arrow.classList.remove('text-primary'); }
    });
  }

  // ── L1 → L2 dropdown (click) ──────────────────────────────────────────
  document.querySelectorAll('.nav-dropdown').forEach(function (dd) {
    var btn   = dd.querySelector('.nav-dd-btn');
    var panel = dd.querySelector('.nav-dd-panel');
    var icon  = dd.querySelector('.nav-dd-icon');

    btn.addEventListener('click', function (e) {
      e.stopPropagation();
      var isOpen = !panel.classList.contains('hidden');
      closeAllDropdowns();
      if (!isOpen) {
        panel.classList.remove('hidden');
        icon.classList.add('rotate-180');
        btn.classList.add('text-secondary', 'bg-white/10');
        // Auto-flip panel if it goes off the right edge
        requestAnimationFrame(function () {
          var rect = panel.getBoundingClientRect();
          if (rect.right > window.innerWidth - 8) {
            panel.style.left  = 'auto';
            panel.style.right = '0';
          } else {
            panel.style.left  = '';
            panel.style.right = '';
          }
        });
      }
    });

    panel.querySelectorAll('.nav-dd-link').forEach(function (link) {
      link.addEventListener('click', function () { closeAllDropdowns(); });
    });
  });

  // ── L2 → L3 split-panel (click) ───────────────────────────────────────
  document.querySelectorAll('.nav-dd-sub-btn').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      e.stopPropagation();
      var key      = btn.dataset.l3;
      var ddPanel  = btn.closest('.nav-dd-panel');
      var l3Target = ddPanel.querySelector('[data-l3-panel="' + key + '"]');
      var isOpen   = l3Target && !l3Target.classList.contains('hidden');

      // Reset all L3 panels + sub-btn styles inside this dropdown
      ddPanel.querySelectorAll('.nav-l3-panel').forEach(function (p) { p.classList.add('hidden'); });
      ddPanel.querySelectorAll('.nav-dd-sub-btn').forEach(function (b) {
        b.classList.remove('bg-slate-100','text-primary');
        var a = b.querySelector('.fa-chevron-right');
        if (a) { a.style.transform = ''; a.classList.remove('text-primary'); }
      });

      if (!isOpen && l3Target) {
        l3Target.classList.remove('hidden');
        btn.classList.add('bg-slate-100','text-primary');
        var arrow = btn.querySelector('.fa-chevron-right');
        if (arrow) { arrow.style.transform = 'rotate(90deg)'; arrow.classList.add('text-primary'); }
      }
    });
  });

  // Close on outside click / Escape
  document.addEventListener('click', closeAllDropdowns);
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') { closeAllDropdowns(); closeSearch(); }
  });

  // ── Search toggle ──────────────────────────────────────────────────────
  var searchToggle = document.getElementById('search-toggle');
  var searchPanel  = document.getElementById('search-panel');
  var searchInput  = document.getElementById('search-input');
  var searchIcon   = document.getElementById('search-icon');

  function closeSearch() {
    if (!searchPanel) return;
    searchPanel.classList.add('hidden');
    if (searchIcon) searchIcon.className = 'fas fa-search text-[14px]';
  }

  if (searchToggle) {
    searchToggle.addEventListener('click', function (e) {
      e.stopPropagation();
      closeAllDropdowns();
      var isOpen = !searchPanel.classList.contains('hidden');
      if (isOpen) { closeSearch(); } else {
        searchPanel.classList.remove('hidden');
        searchInput && searchInput.focus();
      }
    });
    searchPanel && searchPanel.addEventListener('click', function (e) { e.stopPropagation(); });
  }

  // ── Mobile menu toggle ─────────────────────────────────────────────────
  var mobileToggle = document.getElementById('mobile-menu-toggle');
  var mobileMenu   = document.getElementById('mobile-menu');

  if (mobileToggle) {
    mobileToggle.addEventListener('click', function () {
      mobileMenu.classList.toggle('hidden');
    });
  }

  // Close mobile menu when leaf link is clicked
  document.querySelectorAll('.mobile-link').forEach(function (link) {
    link.addEventListener('click', function () {
      mobileMenu && mobileMenu.classList.add('hidden');
    });
  });

  // ── Mobile L1 accordion ────────────────────────────────────────────────
  document.querySelectorAll('.mobile-sub-toggle').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var panel = btn.nextElementSibling;
      var icon  = btn.querySelector('.mobile-sub-icon');
      panel.classList.toggle('hidden');
      icon.classList.toggle('rotate-180');
      icon.classList.toggle('text-primary');
    });
  });

  // ── Mobile L2 accordion ────────────────────────────────────────────────
  document.querySelectorAll('.mobile-sub-toggle-2').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var panel = btn.nextElementSibling;
      var icon  = btn.querySelector('.mobile-sub-icon-2');
      panel.classList.toggle('hidden');
      icon.classList.toggle('rotate-180');
      icon.classList.toggle('text-primary');
    });
  });
}());
</script>
