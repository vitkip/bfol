@php
  $L = app()->getLocale();
  $t = fn($lo,$en,$zh) => match($L){'zh'=>$zh,'en'=>$en,default=>$lo};
@endphp

<div class="relative z-20 max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 -mt-24 md:-mt-24 mb-10">
  <div class="bg-white rounded-2xl shadow-2xl border border-outline-variant/20 overflow-hidden lg:-mx-8 xl:-mx-16">

    {{-- Accent bar --}}
    <div class="h-1 bg-gradient-to-r from-primary via-secondary to-primary"></div>

    <div class="grid grid-cols-2 md:grid-cols-4 divide-y-2 md:divide-y-0 md:divide-x-2 divide-outline-variant/15">

      @forelse($statistics as $stat)
        <div x-data="statsCounter({{ (int) $stat->value }})"
             class="group flex flex-col items-center text-center px-6 py-8 md:py-10
                    hover:bg-primary/[0.025] transition-colors duration-300 cursor-default">

          {{-- Icon --}}
          <div class="w-14 h-14 rounded-full bg-gradient-to-br from-primary to-primary-container
                      flex items-center justify-center mb-4 shadow-md shadow-primary/25
                      group-hover:scale-110 group-hover:shadow-lg group-hover:shadow-primary/30
                      transition-all duration-300">
            <i class="{{ $stat->icon ?: 'fas fa-star' }} text-on-primary text-xl"></i>
          </div>

          {{-- Number --}}
          <div class="flex items-end gap-0.5 mb-2 leading-none">
            <span class="text-4xl lg:text-5xl font-serif font-extrabold text-primary tabular-nums"
                  x-text="count.toLocaleString()">0</span>
            <span class="text-2xl font-bold text-secondary mb-0.5">{{ $stat->suffix ?: '+' }}</span>
          </div>

          {{-- Label --}}
          <p class="text-sm font-semibold text-on-surface-variant leading-snug max-w-[120px]">
            {{ $stat->trans('label') }}
          </p>
        </div>

      @empty
        @foreach([
          ['icon'=>'fas fa-globe-asia',    'value'=>25,  'suffix'=>'+','lo'=>'ປະເທດຄູ່ຮ່ວມ',     'en'=>'Partner Countries'],
          ['icon'=>'fas fa-hands-praying', 'value'=>500, 'suffix'=>'+','lo'=>'ພຣະສົງສາກົນ',      'en'=>'International Monks'],
          ['icon'=>'fas fa-calendar-check','value'=>120, 'suffix'=>'+','lo'=>'ກິດຈະກຳ / ປີ',    'en'=>'Events per Year'],
          ['icon'=>'fas fa-dharmachakra',  'value'=>40,  'suffix'=>'+','lo'=>'ປີດຳເນີນການ',      'en'=>'Years Active'],
        ] as $f)
          <div x-data="statsCounter({{ $f['value'] }})"
               class="group flex flex-col items-center text-center px-6 py-8 md:py-10
                      hover:bg-primary/[0.025] transition-colors duration-300 cursor-default">
            <div class="w-14 h-14 rounded-full bg-gradient-to-br from-primary to-primary-container
                        flex items-center justify-center mb-4 shadow-md shadow-primary/25
                        group-hover:scale-110 transition-all duration-300">
              <i class="{{ $f['icon'] }} text-on-primary text-xl"></i>
            </div>
            <div class="flex items-end gap-0.5 mb-2 leading-none">
              <span class="text-4xl lg:text-5xl font-serif font-extrabold text-primary tabular-nums"
                    x-text="count.toLocaleString()">0</span>
              <span class="text-2xl font-bold text-secondary mb-0.5">{{ $f['suffix'] }}</span>
            </div>
            <p class="text-sm font-semibold text-on-surface-variant leading-snug max-w-[120px]">
              {{ $t($f['lo'], $f['en'], $f['en']) }}
            </p>
          </div>
        @endforeach
      @endforelse

    </div>

  </div>
</div>
