@php
  $L = app()->getLocale();
  $t = fn($lo,$en,$zh) => match($L){'zh'=>$zh,'en'=>$en,default=>$lo};
  $R = fn($name,...$p) => route('front.'.$name,...$p);

  $featured = $latest_news->first();
  $rest     = $latest_news->slice(1, 4);
@endphp

<section class="py-14 bg-white">
  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="flex items-end justify-between mb-8">
      <div>
        <span class="inline-flex items-center gap-1.5 text-xs font-bold text-primary
                      uppercase tracking-widest bg-primary/5 border border-primary/10
                      px-3 py-1 rounded-full mb-3">
          <i class="fas fa-circle text-[5px]"></i>
          {{ $t('ຂ່າວລ່າສຸດ','Latest News','最新消息') }}
        </span>
        <h2 class="text-3xl font-serif font-extrabold text-primary">
          {{ $t('ຂ່າວ ແລະ ກິດຈະກຳ','News & Events','新聞與活動') }}
        </h2>
      </div>
      <a href="{{ $R('news.index') }}"
         class="group flex items-center gap-2 text-sm font-semibold text-primary
                hover:text-secondary transition-colors shrink-0">
        {{ $t('ທັງໝົດ','View all','查看全部') }}
        <span class="w-8 h-8 rounded-full border border-outline-variant flex items-center justify-center
                      group-hover:bg-secondary group-hover:border-secondary group-hover:text-on-secondary
                      transition-all duration-200">
          <i class="fas fa-arrow-right text-xs"></i>
        </span>
      </a>
    </div>

    {{-- Grid --}}
    @if($latest_news->isEmpty())
      <p class="text-center text-on-surface-variant py-12">
        {{ $t('ບໍ່ມີຂ່າວ','No news yet','暫無新聞') }}
      </p>
    @else
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        {{-- Featured --}}
        @if($featured)
          <a href="{{ $R('news.show', $featured->slug) }}"
             class="lg:col-span-7 group block rounded-lg overflow-hidden cursor-pointer
                     shadow-sm hover:shadow-xl transition-all duration-300">
            <div class="relative overflow-hidden bg-gradient-to-br from-primary to-primary-container"
                 style="aspect-ratio:16/10">
              @if($featured->thumbnail)
                <img src="{{ Storage::url($featured->thumbnail) }}"
                     alt="{{ $featured->trans('title') }}"
                     class="absolute inset-0 w-full h-full object-cover opacity-80
                            group-hover:scale-105 transition-transform duration-700" />
              @else
                <div class="absolute inset-0 flex items-center justify-center">
                  <i class="fas fa-newspaper text-6xl text-white/10"></i>
                </div>
              @endif
              <div class="absolute inset-0 bg-gradient-to-t from-primary/90 via-primary/30 to-transparent"></div>

              <div class="absolute top-4 left-4">
                <span class="px-3 py-1 rounded-full bg-secondary text-on-secondary text-xs font-bold">
                  {{ $featured->category?->trans('name') ?: $t('ຂ່າວສານ','News','新聞') }}
                </span>
              </div>

              <div class="absolute bottom-6 left-6 right-6">
                <h3 class="text-2xl font-serif font-bold text-white leading-tight line-clamp-2 mb-2
                            group-hover:text-secondary transition-colors">
                  {{ $featured->trans('title') }}
                </h3>
                <p class="text-white/80 text-sm line-clamp-2 leading-relaxed">
                  {{ $featured->trans('excerpt') ?: $featured->published_at?->translatedFormat('d M Y') }}
                </p>
                <span class="text-xs text-white/60 mt-2 block">
                  {{ $featured->published_at?->translatedFormat('d M Y') }}
                </span>
              </div>
            </div>
          </a>
        @endif

        {{-- Sidebar list --}}
        <div class="lg:col-span-5 flex flex-col gap-4">
          @foreach($rest as $item)
            <a href="{{ $R('news.show', $item->slug) }}"
               class="group flex gap-4 items-start bg-surface-container-lowest rounded-lg p-4 cursor-pointer
                       border border-outline-variant/30 shadow-sm
                       hover:shadow-md hover:-translate-y-0.5 hover:border-secondary/30 transition-all duration-200">
              <div class="w-20 h-16 rounded-md overflow-hidden shrink-0 bg-surface-container">
                @if($item->thumbnail)
                  <img src="{{ Storage::url($item->thumbnail) }}"
                       alt="{{ $item->trans('title') }}"
                       class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                @else
                  <div class="w-full h-full flex items-center justify-center">
                    <i class="fas fa-newspaper text-outline/40 text-xl"></i>
                  </div>
                @endif
              </div>
              <div class="flex-1 min-w-0">
                <span class="text-[10px] font-bold text-secondary uppercase tracking-wider">
                  {{ $item->category?->trans('name') ?: $t('ຂ່າວ','News','新聞') }}
                </span>
                <h4 class="font-serif font-bold text-on-surface text-sm leading-snug mt-0.5 line-clamp-2
                            group-hover:text-primary transition-colors">
                  {{ $item->trans('title') }}
                </h4>
                <span class="text-xs text-on-surface-variant/70 mt-1 block">
                  {{ $item->published_at?->translatedFormat('d M Y') }}
                </span>
              </div>
            </a>
          @endforeach
        </div>

      </div>
    @endif

  </div>
</section>
