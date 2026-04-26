@php
  $L = app()->getLocale();
  $t = fn($lo,$en,$zh) => match($L){'zh'=>$zh,'en'=>$en,default=>$lo};

  $all     = $partners->values();
  $count   = max(1, $all->count());
  $repeats = (int) ceil(max(14, $count * 2) / $count);

  $half = collect(array_fill(0, $repeats, null))->flatMap(fn() => $all);
  $row  = $half->concat($half);

  $speed = max(40, $count * 10);
@endphp

@if($partners->isNotEmpty())
<section class="py-10 bg-surface-container-lowest border-y border-outline-variant/20 overflow-hidden">

  {{-- Header --}}
  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 mb-6 text-center">
    <span class="inline-flex items-center gap-2 text-xs font-bold text-on-surface-variant
                  uppercase tracking-[0.18em] mb-2">
      <span class="w-6 h-px bg-outline-variant"></span>
      {{ $t('ເຄືອຂ່າຍ & ຄູ່ຮ່ວມງານ','Network & Partners','網絡與合作夥伴') }}
      <span class="w-6 h-px bg-outline-variant"></span>
    </span>
    <h3 class="text-xl md:text-2xl font-serif font-bold text-on-surface">
      {{ $t('ອົງການຄູ່ຮ່ວມງານສາກົນ','International Partner Organizations','國際合作夥伴機構') }}
    </h3>
  </div>

  {{-- Single scrolling row --}}
  <div class="relative">
    <div class="absolute left-0 inset-y-0 w-24 md:w-48 z-10 pointer-events-none
                bg-gradient-to-r from-surface-container-lowest to-transparent"></div>
    <div class="absolute right-0 inset-y-0 w-24 md:w-48 z-10 pointer-events-none
                bg-gradient-to-l from-surface-container-lowest to-transparent"></div>

    <div class="flex gap-4 w-max"
         style="animation: scroll-x {{ $speed }}s linear infinite">
      @foreach($row as $p)
        @include('front.home._partner-card', ['p' => $p])
      @endforeach
    </div>
  </div>

</section>
@endif
