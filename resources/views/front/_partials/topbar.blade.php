<div class="bg-surface-container-lowest text-on-surface-variant text-xs py-2 hidden md:block border-b border-surface-container-highest">
  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">

    {{-- Left: phone + email --}}
    <div class="flex gap-6 items-center">
      @if($settings->site_phone)
        <span class="flex items-center gap-2 cursor-default">
          <i class="fas fa-phone text-secondary text-[10px]"></i>
          {{ $settings->site_phone }}
        </span>
      @endif
      @if($settings->site_email)
        <a href="mailto:{{ $settings->site_email }}"
           class="flex items-center gap-2 hover:text-primary transition-colors">
          <i class="fas fa-envelope text-secondary text-[10px]"></i>
          {{ $settings->site_email }}
        </a>
      @endif
    </div>

    {{-- Right: social + lang --}}
    <div class="flex items-center gap-4">

      {{-- Social icons --}}
      <div class="flex items-center gap-3">
        @if($settings->site_facebook)
          <a href="{{ $settings->site_facebook }}" target="_blank" rel="noreferrer"
             class="hover:text-primary transition-colors" aria-label="Facebook">
            <i class="fab fa-facebook-f"></i>
          </a>
        @endif
        @if($settings->site_youtube)
          <a href="{{ $settings->site_youtube }}" target="_blank" rel="noreferrer"
             class="hover:text-primary transition-colors" aria-label="YouTube">
            <i class="fab fa-youtube"></i>
          </a>
        @endif
        @if($settings->site_line)
          <a href="{{ $settings->site_line }}" target="_blank" rel="noreferrer"
             class="hover:text-primary transition-colors" aria-label="Line">
            <i class="fab fa-line"></i>
          </a>
        @endif
      </div>

      <div class="w-px h-3 bg-outline-variant"></div>

      {{-- Language switcher --}}
      <div class="flex gap-2 font-medium">
        @foreach(['lo' => 'ລາວ', 'en' => 'EN', 'zh' => '中'] as $code => $label)
          <a href="{{ route('lang.switch', $code) }}"
             class="uppercase tracking-wider transition-colors
                    {{ $locale === $code ? 'text-secondary font-bold' : 'hover:text-primary' }}">
            {{ $label }}
          </a>
        @endforeach
      </div>
    </div>

  </div>
</div>
