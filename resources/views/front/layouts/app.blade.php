<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="ltr" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', \App\Models\SiteSetting::get('site_name_'.app()->getLocale()) ?? __('app.site_name'))</title>
  <meta name="description" content="@yield('meta_description', '')">
  
  <!-- Fonts & Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.1/css/all.min.css" />
  
  <!-- Alpine Plugins -->
  <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex flex-col min-h-screen bg-slate-50 text-slate-800 font-sans selection:bg-gold-500/30">

  {{-- Top Bar --}}
  <div class="bg-midnight-950 text-slate-300 py-2 text-sm border-b border-white/10">
    <div class="container flex flex-wrap justify-between items-center gap-4">
      <div class="flex items-center gap-6">
        @if(\App\Models\SiteSetting::get('site_phone'))
          <a href="tel:{{ \App\Models\SiteSetting::get('site_phone') }}" class="hover:text-gold-400 transition-colors flex items-center gap-2">
            <i class="fas fa-phone-alt text-xs text-gold-500"></i> {{ \App\Models\SiteSetting::get('site_phone') }}
          </a>
        @endif
        @if(\App\Models\SiteSetting::get('site_email'))
          <a href="mailto:{{ \App\Models\SiteSetting::get('site_email') }}" class="hover:text-gold-400 transition-colors flex items-center gap-2 hidden sm:flex">
            <i class="fas fa-envelope text-xs text-gold-500"></i> {{ \App\Models\SiteSetting::get('site_email') }}
          </a>
        @endif
      </div>
      <div class="flex items-center gap-3">
        @foreach(['lo','en','zh'] as $lang)
          <a href="{{ route('lang.switch', $lang) }}"
             class="px-2 py-0.5 rounded text-xs font-medium uppercase transition-colors {{ app()->getLocale() === $lang ? 'bg-gold-500 text-midnight-950' : 'hover:bg-white/10' }}">
            {{ $lang }}
          </a>
        @endforeach
      </div>
    </div>
  </div>

  {{-- Main Navigation --}}
  <header class="bg-white/80 backdrop-blur-xl border-b border-slate-200 sticky top-0 z-50 shadow-sm" x-data="{ mobileMenuOpen: false }">
    <div class="container py-4 flex items-center justify-between gap-8">
      
      {{-- Logo --}}
      <a href="{{ route('front.home') }}" class="flex items-center gap-3 group shrink-0">
        @php $logo = \App\Models\SiteSetting::get('logo_url'); @endphp
        @if($logo)
          <img src="{{ $logo }}" alt="Logo" class="w-10 h-10 object-contain group-hover:scale-105 transition-transform">
        @else
          <div class="w-10 h-10 rounded-xl bg-midnight-900 text-gold-500 flex items-center justify-center shadow-md shadow-midnight-900/20 group-hover:scale-105 transition-transform">
            <i class="fas fa-dharmachakra text-xl"></i>
          </div>
        @endif
        <div>
          <h1 class="text-xl font-serif font-bold text-midnight-950 leading-none">{{ \App\Models\SiteSetting::get('site_name_'.app()->getLocale()) ?? __('app.site_short') }}</h1>
          <p class="text-[10px] text-slate-500 font-medium uppercase tracking-widest mt-1">SaaS Pro Max Edition</p>
        </div>
      </a>

      {{-- Desktop Nav --}}
      <nav class="hidden lg:flex items-center gap-8 font-medium text-slate-600">
        <a href="{{ route('front.home') }}" class="hover:text-midnight-900 transition-colors">{{ __('app.nav.home') }}</a>
        <a href="{{ route('front.news.index') }}" class="hover:text-midnight-900 transition-colors">{{ __('app.nav.news') }}</a>
        <a href="{{ route('front.events.index') }}" class="hover:text-midnight-900 transition-colors">{{ __('app.nav.events') }}</a>
        <a href="{{ route('front.media.index') }}" class="hover:text-midnight-900 transition-colors">{{ __('app.nav.media') }}</a>
        <a href="{{ route('front.contact') }}" class="hover:text-midnight-900 transition-colors">{{ __('app.contact.title') ?? 'Contact' }}</a>
      </nav>

      {{-- Search & Actions --}}
      <div class="hidden lg:flex items-center gap-4">
        <form action="{{ route('front.search') }}" method="GET" class="relative group">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 group-focus-within:text-midnight-600">
            <i class="fas fa-search text-sm"></i>
          </div>
          <input type="text" name="q" placeholder="{{ __('app.nav.search') }}..." value="{{ request('q') }}"
            class="pl-9 pr-4 py-2 w-48 lg:w-64 bg-slate-100 border-transparent focus:bg-white focus:border-midnight-500 focus:ring-2 focus:ring-midnight-500/20 rounded-lg text-sm transition-all" />
        </form>
        <a href="#" class="btn btn-primary">
          {{ __('app.nav.donate') ?? 'Donate' }} <i class="fas fa-heart text-gold-500 ml-2"></i>
        </a>
      </div>

      {{-- Mobile Menu Toggle --}}
      <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 text-slate-600 hover:text-midnight-900">
        <i class="fas fa-bars text-xl" x-show="!mobileMenuOpen"></i>
        <i class="fas fa-times text-xl" x-show="mobileMenuOpen" x-cloak></i>
      </button>
    </div>

    {{-- Mobile Nav --}}
    <div x-show="mobileMenuOpen" x-collapse class="lg:hidden border-t border-slate-100 bg-white" x-cloak>
      <div class="container py-4 flex flex-col gap-4">
        <a href="{{ route('front.home') }}" class="block px-4 py-2 text-slate-700 hover:bg-slate-50 rounded-lg">{{ __('app.nav.home') }}</a>
        <a href="{{ route('front.news.index') }}" class="block px-4 py-2 text-slate-700 hover:bg-slate-50 rounded-lg">{{ __('app.nav.news') }}</a>
        <a href="{{ route('front.events.index') }}" class="block px-4 py-2 text-slate-700 hover:bg-slate-50 rounded-lg">{{ __('app.nav.events') }}</a>
        <a href="{{ route('front.media.index') }}" class="block px-4 py-2 text-slate-700 hover:bg-slate-50 rounded-lg">{{ __('app.nav.media') }}</a>
        <a href="{{ route('front.contact') }}" class="block px-4 py-2 text-slate-700 hover:bg-slate-50 rounded-lg">{{ __('app.contact.title') ?? 'Contact' }}</a>
        
        <form action="{{ route('front.search') }}" method="GET" class="relative mt-2 px-4">
          <div class="absolute inset-y-0 left-4 pl-3 flex items-center pointer-events-none text-slate-400">
            <i class="fas fa-search text-sm"></i>
          </div>
          <input type="text" name="q" placeholder="{{ __('app.nav.search') }}..." value="{{ request('q') }}"
            class="pl-9 pr-4 py-2 w-full bg-slate-100 border-transparent rounded-lg text-sm" />
        </form>
      </div>
    </div>
  </header>

  {{-- Main Content --}}
  <main class="flex-grow">
    @yield('content')
  </main>

  {{-- Footer --}}
  <footer class="bg-midnight-950 text-slate-300 pt-16 pb-8 border-t-[4px] border-gold-500 mt-20">
    <div class="container">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
        <div class="md:col-span-2">
          <div class="flex items-center gap-3 mb-6">
            @php $footerLogo = \App\Models\SiteSetting::get('logo_url'); @endphp
            @if($footerLogo)
              <img src="{{ $footerLogo }}" alt="Logo" class="h-10 object-contain">
            @else
              <i class="fas fa-dharmachakra text-3xl text-gold-500"></i>
            @endif
            <h2 class="text-2xl font-serif font-bold text-white">{{ \App\Models\SiteSetting::get('site_name_'.app()->getLocale()) ?? __('app.site_name') }}</h2>
          </div>
          <p class="text-slate-400 max-w-md leading-relaxed">
            Promoting religious diplomacy, cultural exchange, and international Dhamma cooperation for the Lao PDR.
          </p>
        </div>
        
        <div>
          <h3 class="text-white font-serif font-bold mb-6 tracking-wide uppercase text-sm">{{ __('app.nav.about') }}</h3>
          <ul class="space-y-3">
            <li><a href="{{ route('front.home') }}" class="hover:text-gold-400 transition-colors">{{ __('app.nav.home') }}</a></li>
            <li><a href="{{ route('front.news.index') }}" class="hover:text-gold-400 transition-colors">{{ __('app.nav.news') }}</a></li>
            <li><a href="{{ route('front.events.index') }}" class="hover:text-gold-400 transition-colors">{{ __('app.nav.events') }}</a></li>
            <li><a href="{{ route('front.contact') }}" class="hover:text-gold-400 transition-colors">{{ __('app.contact.title') ?? 'Contact' }}</a></li>
          </ul>
        </div>

        <div>
          <h3 class="text-white font-serif font-bold mb-6 tracking-wide uppercase text-sm">{{ __('app.contact.title') ?? 'Contact' }}</h3>
          <ul class="space-y-3">
            <li class="flex items-start gap-3">
              <i class="fas fa-map-marker-alt mt-1 text-gold-500"></i>
              <span>{{ \App\Models\SiteSetting::get('site_address_'.app()->getLocale()) ?? \App\Models\SiteSetting::get('site_address_lo') ?? 'Vientiane, Lao PDR' }}</span>
            </li>
            @if(\App\Models\SiteSetting::get('site_email'))
              <li class="flex items-center gap-3">
                <i class="fas fa-envelope text-gold-500"></i>
                <a href="mailto:{{ \App\Models\SiteSetting::get('site_email') }}" class="hover:text-gold-400">{{ \App\Models\SiteSetting::get('site_email') }}</a>
              </li>
            @endif
          </ul>
        </div>
      </div>
      
      <div class="pt-8 border-t border-white/10 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-slate-500">
        <p>&copy; {{ date('Y') }} {{ \App\Models\SiteSetting::get('site_name_'.app()->getLocale()) ?? __('app.site_name') }}. All rights reserved.</p>
        <div class="flex items-center gap-4">
          @if(\App\Models\SiteSetting::get('site_facebook'))
            <a href="{{ \App\Models\SiteSetting::get('site_facebook') }}" target="_blank" class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center hover:bg-gold-500 hover:text-midnight-950 transition-all"><i class="fab fa-facebook-f"></i></a>
          @endif
          @if(\App\Models\SiteSetting::get('site_youtube'))
            <a href="{{ \App\Models\SiteSetting::get('site_youtube') }}" target="_blank" class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center hover:bg-gold-500 hover:text-midnight-950 transition-all"><i class="fab fa-youtube"></i></a>
          @endif
          @if(\App\Models\SiteSetting::get('site_whatsapp'))
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', \App\Models\SiteSetting::get('site_whatsapp')) }}" target="_blank" class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center hover:bg-gold-500 hover:text-midnight-950 transition-all"><i class="fab fa-whatsapp"></i></a>
          @endif
        </div>
      </div>
    </div>
  </footer>

</body>
</html>
