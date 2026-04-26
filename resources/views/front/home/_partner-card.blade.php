<a href="{{ $p->website_url ?: '#' }}"
   @if($p->website_url) target="_blank" rel="noreferrer" @endif
   class="flex-shrink-0 group flex items-center gap-3
           px-4 py-3 rounded-xl
           bg-white border border-outline-variant/25 shadow-sm
           hover:border-secondary/40 hover:shadow-md hover:-translate-y-0.5
           transition-all duration-250 cursor-pointer w-[200px]">

  {{-- Logo --}}
  <div class="w-11 h-11 rounded-lg bg-surface-container-lowest border border-outline-variant/20
               flex items-center justify-center shrink-0 overflow-hidden
               group-hover:border-secondary/30 transition-colors">
    @if($p->logo_url)
      <img src="{{ $p->logo_url }}" alt="{{ $p->trans('name') }}"
           class="w-full h-full object-contain p-1.5
                  grayscale opacity-60
                  group-hover:grayscale-0 group-hover:opacity-100
                  transition-all duration-300" />
    @else
      <i class="fas fa-globe text-outline/50 group-hover:text-primary text-base transition-colors"></i>
    @endif
  </div>

  {{-- Name --}}
  <div class="min-w-0 flex-1">
    <p class="text-xs font-bold text-on-surface leading-tight truncate
               group-hover:text-primary transition-colors">
      {{ $p->acronym ?: \Illuminate\Support\Str::limit($p->trans('name'), 14) }}
    </p>
    @if($p->country_name_lo || $p->country_name_en)
      <p class="text-[10px] text-on-surface-variant/70 mt-0.5 truncate">
        {{ match(app()->getLocale()) {
             'en' => $p->country_name_en,
             'zh' => $p->country_name_zh ?: $p->country_name_en,
             default => $p->country_name_lo ?: $p->country_name_en,
           } }}
      </p>
    @endif
  </div>

  {{-- Arrow indicator --}}
  <i class="fas fa-arrow-up-right-from-square text-[9px] text-outline/30
             group-hover:text-secondary transition-colors shrink-0"></i>

</a>
