<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="ltr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', __('app.site_name'))</title>
  <meta name="description" content="@yield('meta_description', '')">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

  {{-- Top Bar --}}
  <div class="topbar">
    <div class="container topbar-inner">
      <div class="topbar-left">
        <span>{{ config('settings.site_phone', '021-000-000') }}</span>
        <span>{{ config('settings.site_email', 'bfol.foreign@gmail.com') }}</span>
      </div>
      <div class="topbar-right">
        @foreach(['lo','en','zh'] as $lang)
          <a href="{{ url($lang . '/' . request()->path()) }}"
             class="lang-btn {{ app()->getLocale() === $lang ? 'active' : '' }}">
            {{ strtoupper($lang) }}
          </a>
        @endforeach
      </div>
    </div>
  </div>

  {{-- Header --}}
  <header class="header">
    <div class="container header-inner">
      <a href="{{ route('home') }}" class="logo">
        <span class="logo-main">{{ __('app.site_short') }}</span>
      </a>
      <nav class="navbar">
        <ul class="nav-list">
          <li><a href="{{ route('home') }}">{{ __('app.nav.home') }}</a></li>
          <li><a href="#">{{ __('app.nav.about') }}</a></li>
          <li><a href="#">{{ __('app.nav.mission') }}</a></li>
          <li><a href="{{ route('news.index') }}">{{ __('app.nav.news') }}</a></li>
          <li><a href="{{ route('events.index') }}">{{ __('app.nav.events') }}</a></li>
          <li><a href="{{ route('media.index') }}">{{ __('app.nav.media') }}</a></li>
          <li><a href="#">{{ __('app.nav.contact') }}</a></li>
        </ul>
        <form action="{{ route('search') }}" method="GET" class="nav-search">
          <input type="text" name="q" placeholder="{{ __('app.nav.search') }}..." value="{{ request('q') }}" />
          <button type="submit"><i class="fas fa-search"></i></button>
        </form>
      </nav>
    </div>
  </header>

  {{-- Main Content --}}
  <main>
    @yield('content')
  </main>

  {{-- Footer --}}
  <footer class="footer">
    <div class="container footer-bottom-inner">
      <p>&copy; {{ date('Y') }} {{ __('app.site_name') }} — {{ __('app.footer.rights') }}</p>
    </div>
  </footer>

</body>
</html>
