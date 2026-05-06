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
  @include('front._partials.banner', ['position' => 'top'])

  <main id="main-content" class="flex-1 w-full flex flex-col">
    @yield('content')
  </main>

  @include('front._partials.banner', ['position' => 'bottom'])
  @include('front._partials.footer')
  @include('front._partials.banner', ['position' => 'popup'])

  {{-- Back to top --}}
  <button id="back-to-top"
    aria-label="Back to top"
    onclick="window.scrollTo({ top: 0, behavior: 'smooth' })"
    class="fixed bottom-6 right-6 z-50 cursor-pointer w-12 h-12 rounded-full
           bg-primary text-secondary border border-secondary/20 shadow-lg shadow-primary/30
           flex items-center justify-center hover:bg-primary-container hover:-translate-y-1
           active:scale-95 transition-all duration-300
           opacity-0 pointer-events-none translate-y-4"
    style="transition: opacity 0.3s, transform 0.3s">
    <i class="fas fa-arrow-up text-sm"></i>
  </button>
  <script>
  (function(){
    var btn = document.getElementById('back-to-top');
    window.addEventListener('scroll', function(){
      if (window.scrollY > 450) {
        btn.classList.remove('opacity-0','pointer-events-none','translate-y-4');
        btn.classList.add('opacity-100','translate-y-0');
      } else {
        btn.classList.add('opacity-0','pointer-events-none','translate-y-4');
        btn.classList.remove('opacity-100','translate-y-0');
      }
    }, { passive: true });
  }());
  </script>

  @stack('scripts')
</body>
</html>
