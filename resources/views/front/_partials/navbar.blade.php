@php
  $L = app()->getLocale();
  $t = fn($lo,$en,$zh) => match($L){'zh'=>$zh,'en'=>$en,default=>$lo};
  $R = fn($name,...$p) => route('front.'.$name,...$p);

  // ─── Build menu from DB (navigation_menus table) if admin has set items ────
  // $navMenus is shared by AppServiceProvider to all front.* views.
  // If the table has entries, use them. Otherwise fall back to the hardcoded default.

  $buildMenuFromDb = function() use ($L, $t) {
    $dbMenus = $GLOBALS['navMenus'] ?? null;
    // $navMenus is a Blade variable — access via the view's data
    return null; // signal: use the Blade variable path below
  };

  $labelFn = fn($m) => match($L) {
    'zh'    => $m->label_zh ?: $m->label_lo,
    'en'    => $m->label_en ?: $m->label_lo,
    default => $m->label_lo,
  };

  $isExternal = fn($m) => $m->target === '_blank' || str_starts_with((string)$m->url, 'http');

  if (isset($navMenus) && $navMenus->isNotEmpty()) {
    // ─── DB-driven menu ────────────────────────────────────────────────────
    $menu = $navMenus->map(fn($m) => [
      'label'    => $labelFn($m),
      'url'      => $m->url ?: null,
      'external' => $isExternal($m),
      'items'    => $m->children->map(fn($c) => [
        'icon'     => $c->icon ?: 'fas fa-circle',
        'label'    => $labelFn($c),
        'url'      => $c->url ?: '#',
        'external' => $isExternal($c),
      ])->all(),
    ])->all();
  } else {
    // ─── Hardcoded default (shown until admin adds items via ຈັດການເມນູ) ───
    $menu = [
      ['label' => $t('ໜ້າຫຼັກ','Home','首頁'),        'url' => $R('home'),       'items' => []],
      ['label' => $t('ກ່ຽວກັບ ອພສ','About BFOL','關於我們'), 'url' => null, 'items' => [
        ['icon'=>'fas fa-landmark',          'label'=>$t('ປະຫວັດຄວາມເປັນມາ','History','歷史'),   'url'=>$R('page.show','history')],
        ['icon'=>'fas fa-bullseye',          'label'=>$t('ວິໄສທັດ & ພັນທະກິດ','Mission','使命'),'url'=>$R('page.show','mission')],
        ['icon'=>'fas fa-sitemap',           'label'=>$t('ໂຄງສ້າງອົງການ','Structure','組織結構'), 'url'=>$R('structure')],
        ['icon'=>'fas fa-users',             'label'=>$t('ຄະນະກຳມະການ','Committee','委員會'),    'url'=>$R('committee')],
      ]],
      ['label' => $t('ດ້ານການສຶກສາ','Education','教育'), 'url' => null, 'items' => [
        ['icon'=>'fas fa-dharmachakra',      'label'=>$t('ຮຽນສີລສະມາທິທັມ','Sila & Dhamma','戒定慧'),'url'=>$R('page.show','sila-dhamma')],
        ['icon'=>'fas fa-chalkboard-teacher','label'=>$t('ດ້ານການສອນ','Teaching','教學'),         'url'=>$R('page.show','teaching')],
        ['icon'=>'fas fa-microscope',        'label'=>$t('ທັດທະ & ວິໄຊ','Research','研究'),      'url'=>$R('page.show','research')],
        ['icon'=>'fas fa-hands-helping',     'label'=>$t('ສາສາ & ສັງຄົມ','Society','社會'),      'url'=>$R('page.show','society')],
      ]],
      ['label' => $t('ການຕ່າງປະເທດ','International','國際關係'), 'url' => null, 'items' => [
        ['icon'=>'fas fa-globe-asia',        'label'=>$t('ຄູ່ຮ່ວມມືສາກົນ','Partners','國際合作夥伴'),   'url'=>$R('partners.index')],
        ['icon'=>'fas fa-exchange-alt',      'label'=>$t('ແລກປ່ຽນ ສາກົນ','Exchange','國際交流'),         'url'=>$R('monk-programs.index')],
        ['icon'=>'fas fa-file-signature',    'label'=>$t('MOU ຕ່າງປະເທດ','MOU','MOU協議'),               'url'=>$R('mou.index')],
        ['icon'=>'fas fa-hand-holding-heart','label'=>$t('ໂຄງການ ຊ່ວຍເຫຼືອ','Aid Projects','援助項目'),  'url'=>$R('aid-projects.index')],
      ]],
      ['label' => $t('ສື່ສາ','Media','媒體'), 'url' => null, 'items' => [
        ['icon'=>'fab fa-youtube',           'label'=>'DhammaOnLen','url'=>'https://www.youtube.com/@DhammaOnLen','external'=>true],
        ['icon'=>'fas fa-images',            'label'=>$t('ຮູບພາບ ກິດຈະກຳ','Gallery','活動相冊'), 'url'=>$R('media.index')],
        ['icon'=>'fas fa-file-lines',        'label'=>$t('ເອກະສານ','Documents','文件'),           'url'=>$R('documents.index')],
      ]],
      ['label' => $t('ຂ່າວສານ','News','新聞'),      'url' => $R('news.index'), 'items' => []],
      ['label' => $t('ຕິດຕໍ່','Contact','聯繫我們'), 'url' => $R('contact'),    'items' => []],
    ];
  }
@endphp

<header
  x-data="{ open: false, drop: null, scrolled: false }"
  x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 20, { passive: true })"
  :class="scrolled ? 'bg-primary/95 backdrop-blur-md shadow-sm border-b border-primary-container' : 'bg-primary'"
  class="sticky top-0 z-[100] w-full transition-all duration-500"
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
          <div class="font-serif font-bold text-[15px] truncate text-on-primary group-hover:text-secondary transition-colors">
            {{ $t($settings->site_name_lo, $settings->site_name_en, $settings->site_name_zh) ?: 'ອພສ · BFOL' }}
          </div>
          <div class="text-[11px] truncate text-on-primary/60">
            {{ $settings->site_name_en ?: 'BFOL' }}
          </div>
        </div>
      </a>

      {{-- Desktop nav --}}
      <nav class="hidden lg:flex items-center gap-0.5 xl:gap-1">
        @foreach($menu as $item)
          @if(!empty($item['items']))
            {{-- Dropdown --}}
            <div class="relative group"
                 @mouseenter="drop = '{{ $item['label'] }}'"
                 @mouseleave="drop = null">
              <button class="flex items-center gap-1.5 px-3 py-2 text-[13px] font-semibold rounded-lg
                             transition-all duration-200 cursor-pointer
                             text-on-primary/80 hover:text-secondary hover:bg-white/10">
                {{ $item['label'] }}
                <i class="fas fa-chevron-down text-[9px] opacity-60 transition-transform duration-200"
                   :class="drop === '{{ $item['label'] }}' ? 'rotate-180' : ''"></i>
              </button>
              {{-- Dropdown panel --}}
              <div class="absolute top-full left-1/2 -translate-x-1/2 pt-2 z-50 transition-all duration-200"
                   :class="drop === '{{ $item['label'] }}' ? 'opacity-100 visible translate-y-0' : 'opacity-0 invisible -translate-y-1'">
                <div class="bg-white rounded-2xl shadow-xl border border-slate-100 p-2 min-w-[220px]">
                  @foreach($item['items'] as $sub)
                    <a href="{{ $sub['url'] }}"
                       @if(!empty($sub['external'])) target="_blank" rel="noreferrer" @endif
                       class="flex items-center gap-3 px-4 py-2.5 rounded-md hover:bg-surface-container-low
                              group/sub transition-colors cursor-pointer">
                      <div class="w-8 h-8 rounded-full bg-surface-container flex items-center justify-center
                                  group-hover/sub:bg-secondary-container transition-colors shrink-0">
                        <i class="{{ $sub['icon'] }} text-on-surface-variant group-hover/sub:text-on-secondary-container text-sm"></i>
                      </div>
                      <span class="text-sm font-medium text-on-surface group-hover/sub:text-primary">
                        {{ $sub['label'] }}
                      </span>
                    </a>
                  @endforeach
                </div>
              </div>
            </div>
          @elseif($item['url'])
            <a href="{{ $item['url'] }}"
               @if(!empty($item['external'])) target="_blank" rel="noreferrer" @endif
               class="px-3 py-2 text-[13px] font-semibold rounded-lg transition-all duration-200 cursor-pointer
                      {{ request()->url() === $item['url'] ? 'text-secondary' : 'text-on-primary/80 hover:text-secondary hover:bg-white/10' }}">
              {{ $item['label'] }}
            </a>
          @endif
        @endforeach

        {{-- CTA --}}
        <a href="{{ $R('contact') }}"
           class="ml-3 px-5 py-2.5 bg-secondary text-on-secondary text-[13px] font-bold rounded-md cursor-pointer
                  shadow-sm hover:bg-secondary-container hover:text-on-secondary-container hover:shadow-md
                  hover:-translate-y-0.5 transition-all duration-200">
          {{ $t('ຕິດຕໍ່ພວກເຮົາ','Contact Us','聯繫我們') }}
        </a>
      </nav>

      {{-- Mobile hamburger --}}
      <button @click="open = !open"
              class="lg:hidden p-2.5 rounded-md transition-colors cursor-pointer text-on-primary hover:bg-white/10">
        <i x-show="!open"  class="fas fa-bars text-lg"></i>
        <i x-show="open"   class="fas fa-times text-lg" style="display:none"></i>
      </button>
    </div>

    {{-- Mobile menu --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 max-h-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-end="opacity-0"
         class="lg:hidden overflow-hidden pb-4"
         style="display:none">
      <div class="bg-white rounded-2xl p-2 flex flex-col gap-0.5 max-h-[75vh] overflow-y-auto
                  border border-slate-100 shadow-xl mt-2">
        @foreach($menu as $item)
          @if(!empty($item['items']))
            <div class="rounded-xl overflow-hidden"
                 x-data="{ sub: false }">
              <button @click="sub = !sub"
                      class="w-full flex justify-between items-center px-4 py-3 text-sm font-semibold
                             text-slate-700 hover:text-blue-600 hover:bg-slate-50 transition-colors
                             cursor-pointer rounded-xl">
                {{ $item['label'] }}
                <i class="fas fa-chevron-down text-[10px] transition-transform duration-300"
                   :class="sub ? 'rotate-180 text-blue-600' : 'text-slate-400'"></i>
              </button>
              <div x-show="sub" class="px-2 pb-1 flex flex-col gap-0.5" style="display:none">
                @foreach($item['items'] as $sub)
                  <a href="{{ $sub['url'] }}"
                     @if(!empty($sub['external'])) target="_blank" rel="noreferrer" @endif
                     @click="open = false"
                     class="flex items-center gap-3 px-4 py-2.5 text-[13px] font-medium
                            text-slate-600 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-colors">
                    <span class="w-6 h-6 rounded-full bg-blue-50 flex items-center justify-center shrink-0">
                      <i class="{{ $sub['icon'] }} text-blue-500 text-[9px]"></i>
                    </span>
                    {{ $sub['label'] }}
                  </a>
                @endforeach
              </div>
            </div>
          @elseif($item['url'])
            <a href="{{ $item['url'] }}"
               @if(!empty($item['external'])) target="_blank" rel="noreferrer" @endif
               @click="open = false"
               class="block px-4 py-3 text-sm font-medium rounded-xl transition-all cursor-pointer
                      {{ request()->url() === $item['url'] ? 'bg-blue-50 text-blue-600' : 'text-slate-600 hover:bg-slate-50 hover:text-blue-600' }}">
              {{ $item['label'] }}
            </a>
          @endif
        @endforeach
        {{-- Language switcher (mobile only) --}}
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
          <a href="{{ $R('contact') }}" @click="open = false"
             class="block text-center py-3 bg-secondary text-on-secondary text-sm font-bold rounded-md
                    hover:bg-secondary-container hover:text-on-secondary-container transition-colors cursor-pointer">
            {{ $t('ຕິດຕໍ່ພວກເຮົາ','Contact Us','聯繫我們') }}
          </a>
        </div>
      </div>
    </div>

  </div>
</header>
