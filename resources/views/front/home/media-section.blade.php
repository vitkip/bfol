@php
  $L = app()->getLocale();
  $t = fn($lo,$en,$zh) => match($L){'zh'=>$zh,'en'=>$en,default=>$lo};

  $ytUrl  = $settings->site_youtube  ?: '#';
  $fbUrl  = $settings->site_facebook ?: '#';

  // Extract handle from URL for display: "@channel" or last path segment
  $ytHandle = $settings->site_youtube
    ? (preg_match('/@[\w.-]+/', $settings->site_youtube, $m) ? $m[0] : basename(rtrim($settings->site_youtube,'/')))
    : 'YouTube';
  $fbHandle = $settings->site_facebook
    ? basename(rtrim($settings->site_facebook, '/'))
    : 'Facebook';
@endphp

<section class="py-14 bg-primary text-on-primary overflow-hidden">
  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="text-center mb-8">
      <span class="inline-flex items-center gap-1.5 text-xs font-bold text-secondary uppercase tracking-widest mb-3">
        <span class="w-1.5 h-1.5 rounded-full bg-secondary inline-block"></span>
        {{ $t('ສື່ ດິຈິຕອລ','Digital Media','數字媒體') }}
      </span>
      <h2 class="text-3xl font-serif font-bold text-on-primary">
        {{ $t('ສື່ ແລະ ກິດຈະກຳ','Media & Events','媒體與活動') }}
      </h2>
      <div class="h-0.5 w-12 bg-secondary/40 mx-auto mt-4 rounded-full"></div>
    </div>

    {{-- Bento grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 md:[grid-template-rows:200px_200px] gap-4">

      {{-- YouTube — spans 2×2 --}}
      <a href="{{ $ytUrl }}"
         target="_blank" rel="noreferrer"
         class="min-h-[220px] md:min-h-0 md:col-span-2 md:row-span-2 group relative rounded-lg
                overflow-hidden bg-primary-container cursor-pointer border border-white/5">
        <div class="absolute inset-0 bg-gradient-to-br from-primary-container to-primary"></div>
        <div class="absolute inset-0 opacity-[0.03]"
             style="background-image:linear-gradient(#fff 1px,transparent 1px),linear-gradient(90deg,#fff 1px,transparent 1px);background-size:40px 40px"></div>

        <div class="absolute inset-0 flex flex-col items-center justify-center gap-4">
          <div class="w-20 h-20 rounded-full bg-red-600/90 flex items-center justify-center
                       group-hover:scale-110 group-hover:bg-red-500 transition-all duration-300
                       shadow-2xl shadow-red-900/50">
            <i class="fab fa-youtube text-white text-4xl"></i>
          </div>
          <div class="text-center z-10">
            <p class="text-on-primary font-bold text-lg">{{ $ytHandle }}</p>
            <p class="text-on-primary-container/80 text-sm mt-0.5">
              {{ $t('ຊ່ອງ YouTube ທາງການ','Official YouTube Channel','官方YouTube頻道') }}
            </p>
          </div>
        </div>

        <div class="absolute inset-0 bg-red-900/0 group-hover:bg-red-900/10 transition-colors duration-300"></div>
        <div class="absolute bottom-0 left-0 right-0 p-5 bg-gradient-to-t from-black/60 to-transparent">
          <div class="flex items-center gap-2">
            <span class="bg-red-600 text-white text-[10px] font-bold px-2 py-0.5 rounded">YouTube</span>
            <span class="flex items-center gap-1 text-[11px] text-red-400 font-medium">
              <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse inline-block"></span>
              LIVE
            </span>
          </div>
        </div>
      </a>

      {{-- Gallery --}}
      <div class="min-h-[160px] md:min-h-0 group relative rounded-lg overflow-hidden
                  bg-white/5 border border-white/10 cursor-pointer hover:bg-white/10 transition-colors">
        <div class="absolute inset-0 flex flex-col items-center justify-center gap-3">
          <i class="fas fa-images text-on-primary/30 text-5xl
                    group-hover:scale-110 transition-transform duration-300 group-hover:text-secondary"></i>
          <p class="text-on-primary font-bold text-base group-hover:text-secondary transition-colors duration-200">
            {{ $t('ຮູບພາບ ກິດຈະກຳ','Photo Gallery','活動相冊') }}
          </p>
        </div>
      </div>

      {{-- Documents --}}
      <a href="{{ route('front.documents.index') }}"
         class="min-h-[160px] md:min-h-0 group relative rounded-lg overflow-hidden
                bg-white/5 border border-white/10 cursor-pointer hover:bg-white/10 transition-colors">
        <div class="absolute inset-0 flex flex-col items-center justify-center gap-3">
          <i class="fas fa-file-lines text-on-primary/30 text-5xl
                    group-hover:scale-110 transition-transform duration-300 group-hover:text-secondary"></i>
          <p class="text-on-primary font-bold text-base group-hover:text-secondary transition-colors duration-200">
            {{ $t('ເອກະສານ & PDF','Documents & PDF','文件與PDF') }}
          </p>
        </div>
      </a>

    </div>

    {{-- Channel quick links --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-4">
      @foreach([
        ['icon'=>'fab fa-youtube',    'label'=>'YouTube',  'sub'=>$ytHandle, 'color'=>'text-red-400',  'href'=>$ytUrl],
        ['icon'=>'fab fa-facebook-f', 'label'=>'Facebook', 'sub'=>$fbHandle, 'color'=>'text-blue-400', 'href'=>$fbUrl],
        ['icon'=>'fas fa-images',     'label'=>$t('ຮູບພາບ','Gallery','相冊'), 'sub'=>$t('ກິດຈະກຳ','Activities','活動'), 'color'=>'text-secondary','href'=>'#'],
        ['icon'=>'fas fa-file-lines', 'label'=>$t('ເອກະສານ','Documents','文件'),'sub'=>$t('PDF & ໄຟລ໌','PDF & Files','PDF文件'),'color'=>'text-secondary','href'=>route('front.documents.index')],
      ] as $m)
        <a href="{{ $m['href'] }}"
           @if(str_starts_with($m['href'],'http')) target="_blank" rel="noreferrer" @endif
           class="group flex items-center gap-3 bg-white/5 border border-white/10 rounded-lg px-4 py-3.5
                   hover:bg-white/10 transition-all duration-200 cursor-pointer">
          <div class="w-9 h-9 rounded-md bg-white/10 flex items-center justify-center shrink-0
                       group-hover:scale-110 transition-transform duration-200">
            <i class="{{ $m['icon'] }} {{ $m['color'] }} text-base"></i>
          </div>
          <div class="min-w-0 flex-1">
            <p class="text-on-primary text-sm font-bold leading-tight truncate">{{ $m['label'] }}</p>
            <p class="text-on-primary-container/80 text-[11px] truncate mt-0.5">{{ $m['sub'] }}</p>
          </div>
          <i class="fas fa-arrow-right text-[10px] {{ $m['color'] }} opacity-30 group-hover:opacity-80
                     group-hover:translate-x-0.5 transition-all duration-200"></i>
        </a>
      @endforeach
    </div>

  </div>
</section>
