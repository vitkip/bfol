@php
  $L = app()->getLocale();
  $t = fn($lo,$en,$zh) => match($L){'zh'=>$zh,'en'=>$en,default=>$lo};
  $R = fn($name,...$p) => route('front.'.$name,...$p);

  $aboutLinks = [
    ['lo'=>'ປະຫວັດຄວາມເປັນມາ','en'=>'History',   'zh'=>'歷史',   'url'=>'#'],
    ['lo'=>'ວິສາຫະກິດ & ຄາລະກິດ','en'=>'Mission', 'zh'=>'使命',   'url'=>'#'],
    ['lo'=>'ໂຄງສ້າງອົງການ','en'=>'Structure',      'zh'=>'組織結構','url'=>'#'],
    ['lo'=>'ຄະນະກຳມະການ','en'=>'Committee',        'zh'=>'委員會', 'url'=>'#'],
  ];
  $activityLinks = [
    ['lo'=>'ຂ່າວສານ','en'=>'News',          'zh'=>'新聞',   'url'=>$R('news.index')],
    ['lo'=>'MOU ຕ່າງປະເທດ','en'=> "Int'l MOU",'zh'=>'MOU協議','url'=>'#'],
    ['lo'=>'ໂຄງການ ຊ່ວຍເຫຼືອ','en'=>'Aid Projects','zh'=>'援助項目','url'=>'#'],
    ['lo'=>'ແລກປ່ຽນ ສາກົນ','en'=>"Int'l Exchange",'zh'=>'國際交流','url'=>'#'],
  ];
@endphp

<footer class="bg-primary-container text-on-primary-container">
  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-8">

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">

      {{-- Brand --}}
      <div class="lg:col-span-1">
        <a href="{{ $R('home') }}" class="flex items-center gap-3 mb-5 group cursor-pointer">
          @if($settings->logo_url)
            <img src="{{ $settings->logo_url }}" alt="{{ $settings->site_name_lo }}"
                 class="w-12 h-12 rounded-lg object-contain bg-white/10 p-0.5 border border-white/10
                         group-hover:border-secondary/30 transition-all" />
          @else
            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-secondary to-secondary-container
                         flex items-center justify-center shadow-lg shadow-secondary/20 text-on-secondary-container text-xl">
              <i class="fas fa-dharmachakra"></i>
            </div>
          @endif
          <div>
            <div class="font-serif font-bold text-on-primary text-base leading-tight">
              {{ $t($settings->site_name_lo, $settings->site_name_en, $settings->site_name_zh) ?: 'ອພສ · BFOL' }}
            </div>
            <div class="text-xs text-on-primary-container mt-0.5">{{ $settings->site_name_en ?: 'BFOL' }}</div>
          </div>
        </a>

        <p class="text-sm text-on-primary-container/80 leading-relaxed mb-6">
          {{ $t(
            'ອົງການສາສະໜາ ທີ່ ຮ່ວມກັນ ສົ່ງເສີມ ແລະ ພັດທະນາ ດ້ານ ສາສະໜາ ທາງ ລາວ ໃນ ລະດັບ ຊາດ ແລະ ສາກົນ.',
            'A Buddhist organization promoting and developing Lao Buddhism at national and international levels.',
            '致力於在國家和國際層面推廣和發展老撾佛教的宗教組織。'
          ) }}
        </p>

        <div class="flex gap-2 flex-wrap">
          @foreach([
            ['icon'=>'fab fa-facebook-f','href'=>$settings->site_facebook, 'label'=>'Facebook'],
            ['icon'=>'fab fa-youtube',   'href'=>$settings->site_youtube,  'label'=>'YouTube'],
            ['icon'=>'fab fa-whatsapp',  'href'=>$settings->site_whatsapp, 'label'=>'WhatsApp'],
            ['icon'=>'fab fa-line',      'href'=>$settings->site_line,     'label'=>'Line'],
            ['icon'=>'fab fa-weixin',    'href'=>$settings->site_wechat,   'label'=>'WeChat'],
          ] as $sc)
            @if($sc['href'])
              <a href="{{ $sc['href'] }}" target="_blank" rel="noreferrer" aria-label="{{ $sc['label'] }}"
                 class="w-9 h-9 rounded-full bg-white/5 border border-white/10 text-on-primary-container
                         hover:bg-primary hover:border-primary hover:text-secondary
                         flex items-center justify-center text-sm transition-all duration-200 hover:scale-110">
                <i class="{{ $sc['icon'] }}"></i>
              </a>
            @endif
          @endforeach
        </div>
      </div>

      {{-- About links --}}
      <div>
        <h4 class="text-on-primary font-bold mb-5 text-xs uppercase tracking-[0.15em] flex items-center gap-2">
          <span class="w-4 h-px bg-secondary"></span>
          {{ $t('ກ່ຽວກັບ ອພສ','About BFOL','關於BFOL') }}
        </h4>
        <ul class="space-y-2.5">
          @foreach($aboutLinks as $l)
            <li>
              <a href="{{ $l['url'] }}"
                 class="group flex items-center gap-2 text-sm text-on-primary-container
                         hover:text-secondary transition-colors duration-200">
                <i class="fas fa-angle-right text-on-primary-container/50 text-xs
                           group-hover:text-secondary group-hover:translate-x-0.5 transition-all"></i>
                {{ $t($l['lo'], $l['en'], $l['zh']) }}
              </a>
            </li>
          @endforeach
        </ul>
      </div>

      {{-- Activity links --}}
      <div>
        <h4 class="text-on-primary font-bold mb-5 text-xs uppercase tracking-[0.15em] flex items-center gap-2">
          <span class="w-4 h-px bg-secondary"></span>
          {{ $t('ກິດຈະກຳ','Activities','活動') }}
        </h4>
        <ul class="space-y-2.5">
          @foreach($activityLinks as $l)
            <li>
              <a href="{{ $l['url'] }}"
                 class="group flex items-center gap-2 text-sm text-on-primary-container
                         hover:text-secondary transition-colors duration-200">
                <i class="fas fa-angle-right text-on-primary-container/50 text-xs
                           group-hover:text-secondary group-hover:translate-x-0.5 transition-all"></i>
                {{ $t($l['lo'], $l['en'], $l['zh']) }}
              </a>
            </li>
          @endforeach
        </ul>
      </div>

      {{-- Contact --}}
      <div>
        <h4 class="text-on-primary font-bold mb-5 text-xs uppercase tracking-[0.15em] flex items-center gap-2">
          <span class="w-4 h-px bg-secondary"></span>
          {{ $t('ຕິດຕໍ່','Contact','聯繫') }}
        </h4>
        <ul class="space-y-3.5 text-sm">
          @if($settings->site_address_lo)
            <li class="flex gap-3 text-on-primary-container">
              <div class="w-8 h-8 rounded-full bg-white/5 border border-white/10 flex items-center justify-center shrink-0 mt-0.5">
                <i class="fas fa-location-dot text-secondary text-xs"></i>
              </div>
              <span class="leading-relaxed">
                {{ $t($settings->site_address_lo, $settings->site_address_lo, $settings->site_address_zh ?: $settings->site_address_lo) }}
              </span>
            </li>
          @endif
          @if($settings->site_phone)
            <li>
              <a href="tel:{{ $settings->site_phone }}"
                 class="flex gap-3 text-on-primary-container hover:text-secondary transition-colors group">
                <div class="w-8 h-8 rounded-full bg-white/5 border border-white/10 flex items-center justify-center shrink-0">
                  <i class="fas fa-phone text-secondary text-xs"></i>
                </div>
                <span class="self-center">{{ $settings->site_phone }}</span>
              </a>
            </li>
          @endif
          @if($settings->site_email)
            <li>
              <a href="mailto:{{ $settings->site_email }}"
                 class="flex gap-3 text-on-primary-container hover:text-secondary transition-colors group">
                <div class="w-8 h-8 rounded-full bg-white/5 border border-white/10 flex items-center justify-center shrink-0">
                  <i class="fas fa-envelope text-secondary text-xs"></i>
                </div>
                <span class="self-center break-all">{{ $settings->site_email }}</span>
              </a>
            </li>
          @endif
          @if($settings->office_hours_lo)
            <li class="flex gap-3 text-on-primary-container">
              <div class="w-8 h-8 rounded-full bg-white/5 border border-white/10 flex items-center justify-center shrink-0">
                <i class="fas fa-clock text-secondary text-xs"></i>
              </div>
              <span class="self-center">{{ $settings->office_hours_lo }}</span>
            </li>
          @endif
        </ul>
      </div>

    </div>

    {{-- Bottom bar --}}
    <div class="border-t border-white/5 pt-6 flex flex-col sm:flex-row justify-between items-center gap-3
                text-xs text-on-primary-container/80">
      <span>
        &copy; {{ date('Y') }}
        {{ $t($settings->site_name_lo, $settings->site_name_en, $settings->site_name_zh) ?: 'ອພສ · BFOL' }}
        — {{ $t('ສະຫງວນລິຂະສິດ','All rights reserved','版權所有') }}
      </span>
      <div class="flex items-center gap-4">
        <a href="{{ $R('contact') }}" class="hover:text-secondary transition-colors">
          {{ $t('ຕິດຕໍ່','Contact','聯繫') }}
        </a>
        <span class="text-white/10">•</span>
        <a href="{{ $R('news.index') }}" class="hover:text-secondary transition-colors">
          {{ $t('ຂ່າວ','News','新聞') }}
        </a>
      </div>
    </div>

  </div>
</footer>
