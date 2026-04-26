<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="ltr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', $settings->site_name_lo ?: 'BFOL')</title>
  <meta name="description" content="@yield('meta_description', '')">
  @if($settings->favicon_url)
  <link rel="icon" type="image/x-icon" href="{{ $settings->favicon_url }}" />
  @endif

  {{-- Google Fonts (Lao serif) --}}
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Noto+Serif+Lao:wdth,wght@100,300..900&display=swap" />

  {{-- Font Awesome --}}
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @stack('styles')
</head>
<body class="min-h-screen flex flex-col bg-surface-container-lowest text-on-surface">

  {{-- Skip to content --}}
  <a href="#main-content"
     class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-[9999]
            focus:bg-secondary focus:text-on-secondary focus:font-bold focus:text-sm
            focus:px-6 focus:py-3 focus:rounded-md focus:shadow-2xl focus:outline-none">
    ຂ້າມໄປຍັງເນື້ອໃນ
  </a>

  @include('front._partials.topbar')
  @include('front._partials.navbar')

  <main id="main-content" class="flex-1 w-full flex flex-col">
    @yield('content')
  </main>

  @include('front._partials.footer')

  {{-- Back to top --}}
  <div x-data="{ show: false }"
       x-init="window.addEventListener('scroll', () => { show = window.scrollY > 450 }, { passive: true })">
    <button
      x-show="show"
      x-transition:enter="transition ease-out duration-300"
      x-transition:enter-start="opacity-0 translate-y-4"
      x-transition:enter-end="opacity-100 translate-y-0"
      x-transition:leave="transition ease-in duration-200"
      x-transition:leave-end="opacity-0 translate-y-4"
      @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
      aria-label="Back to top"
      class="fixed bottom-6 right-6 z-50 cursor-pointer w-12 h-12 rounded-full
             bg-primary text-secondary border border-secondary/20 shadow-lg shadow-primary/30
             flex items-center justify-center hover:bg-primary-container hover:-translate-y-1
             active:scale-95 transition-all duration-300">
      <i class="fas fa-arrow-up text-sm"></i>
    </button>
  </div>

  @stack('scripts')
</body>
</html>
