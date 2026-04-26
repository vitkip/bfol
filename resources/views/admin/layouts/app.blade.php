<!DOCTYPE html>
<html lang="lo">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('page_title', 'Dashboard') — BFOL Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@400;500;600&family=Phetsarath+OT&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'surface': '#f9f9fa',
            'surface-container-lowest': '#ffffff',
            'surface-container-low': '#f3f3f4',
            'surface-container': '#eeeeef',
            'surface-container-high': '#e8e8e9',
            'surface-container-highest': '#e2e2e3',
            'outline-variant': '#c2c6d4',
            'outline': '#727783',
            'on-surface': '#1a1c1d',
            'on-surface-variant': '#424752',
            'on-primary': '#ffffff',
            'primary': '#00488d',
            'primary-container': '#005fb8',
          },
          fontFamily: { sans: ['Phetsarath OT', 'Inter', 'sans-serif'] },
          screens: {
            'xs': '480px',
          },
        }
      }
    }
  </script>
  <style>
    body { font-family: 'Phetsarath OT', Inter, sans-serif; }

    .material-symbols-outlined {
      font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }

    .ghost-border { border: 1px solid rgba(194, 198, 212, 0.30); }

    .primary-gradient { background: linear-gradient(135deg, #00488d 0%, #005fb8 100%); }

    .nav-active {
      background: #e8e8e9;
      border-left: 3px solid #00488d !important;
      color: #00488d;
      font-weight: 600;
    }

    .nav-link { border-left: 3px solid transparent; }

    .nav-link:hover { background: #eeeeef; color: #00488d; }

    ::-webkit-scrollbar { width: 4px; }
    ::-webkit-scrollbar-thumb { background: #c2c6d4; border-radius: 9999px; }

    /* Smooth sidebar transition */
    #sidebar { transition: transform 0.25s ease; }
  </style>
  @stack('styles')
</head>

<body class="bg-surface text-on-surface min-h-screen" x-data="{ open: false }">

  {{-- ═══ MOBILE OVERLAY ═══ --}}
  <div x-show="open"
       x-transition:enter="transition-opacity ease-out duration-200"
       x-transition:enter-start="opacity-0"
       x-transition:enter-end="opacity-100"
       x-transition:leave="transition-opacity ease-in duration-150"
       x-transition:leave-start="opacity-100"
       x-transition:leave-end="opacity-0"
       @click="open = false"
       class="fixed inset-0 bg-black/40 z-30 lg:hidden"
       style="display:none">
  </div>

  {{-- ═══ SIDEBAR ═══ --}}
  <aside id="sidebar"
         :class="open ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
         class="fixed inset-y-0 left-0 w-64 bg-surface-container-low flex flex-col z-40 overflow-hidden -translate-x-full lg:translate-x-0">

    {{-- Logo --}}
    <div class="flex items-center gap-3 px-5 py-5 border-b border-surface-container-high flex-shrink-0">
      <div class="w-9 h-9 primary-gradient rounded-lg flex items-center justify-center flex-shrink-0">
        <i class="fas fa-dharmachakra text-white"></i>
      </div>
      <div class="flex-1 min-w-0">
        <p class="text-sm font-extrabold text-on-surface leading-tight">BFOL Admin</p>
        <p class="text-[10px] text-outline leading-tight">ກັມມາທິການຕ່າງປະເທດ</p>
      </div>
      <button @click="open = false" class="lg:hidden text-outline hover:text-on-surface p-1 flex-shrink-0" aria-label="ປິດເມນູ">
        <i class="fas fa-times text-sm"></i>
      </button>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 overflow-y-auto py-3 px-3 space-y-0.5 text-sm">

      <a href="{{ route('admin.dashboard') }}"
         class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'nav-active' : 'text-on-surface-variant' }}">
        <i class="fas fa-tachometer-alt w-4 text-center text-xs"></i><span>Dashboard</span>
      </a>

      <p class="px-3 pt-4 pb-1 text-[10px] font-bold text-outline uppercase tracking-widest">ເນື້ອຫາ</p>
      @foreach([
            ['admin.news.*',      route('admin.news.index'),      'fa-newspaper',   'ຂ່າວສານ'],
            ['admin.events.*',    route('admin.events.index'),    'fa-calendar',    'ກິດຈະກໍາ'],
            ['admin.pages.*',     route('admin.pages.index'),     'fa-file-alt',    'ໜ້າຂໍ້ມູນ'],
            ['admin.media.*',     route('admin.media.index'),     'fa-photo-video', 'ສື່ທໍາ'],
            ['admin.documents.*', route('admin.documents.index'), 'fa-file-pdf',    'ເອກະສານ'],
            ['admin.categories.*',route('admin.categories.index'),'fa-layer-group', 'Categories'],
            ['admin.tags.*',      route('admin.tags.index'),      'fa-tags',        'Tags'],
          ] as [$route, $url, $icon, $label])
        <a href="{{ $url }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs($route) ? 'nav-active' : 'text-on-surface-variant' }}">
          <i class="fas {{ $icon }} w-4 text-center text-xs"></i><span>{{ $label }}</span>
        </a>
      @endforeach

      <p class="px-3 pt-4 pb-1 text-[10px] font-bold text-outline uppercase tracking-widest">ພາລະກິດ</p>
      @foreach([
          ['admin.partners.*',      route('admin.partners.index'),      'fa-globe',         'ຄູ່ຮ່ວມມື'],
          ['admin.mou.*',           route('admin.mou.index'),           'fa-file-signature', 'MOU'],
          ['admin.monk-programs.*', route('admin.monk-programs.index'), 'fa-exchange-alt',  'ແລກປ່ຽນພຣະ'],
          ['admin.aid-projects.*',  route('admin.aid-projects.index'),  'fa-hands-helping', 'ຊ່ວຍເຫຼືອ'],
        ] as [$route, $url, $icon, $label])
        <a href="{{ $url }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs($route) ? 'nav-active' : 'text-on-surface-variant' }}">
          <i class="fas {{ $icon }} w-4 text-center text-xs"></i><span>{{ $label }}</span>
        </a>
      @endforeach

      <p class="px-3 pt-4 pb-1 text-[10px] font-bold text-outline uppercase tracking-widest">ໜ້າຫຼັກ</p>
      @foreach([
          ['admin.navigation.*', route('admin.navigation.index'), 'fa-bars',   'ຈັດການເມນູ'],
          ['admin.slides.*',     route('admin.slides.index'),     'fa-images', 'Slides'],
          ['admin.banners.*',    route('admin.banners.index'),    'fa-ad',     'Banners'],
          ['admin.committee.*',  route('admin.committee.index'),  'fa-users',  'ຄະນະກໍາມະການ'],
        ] as [$route, $url, $icon, $label])
        <a href="{{ $url }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs($route) ? 'nav-active' : 'text-on-surface-variant' }}">
          <i class="fas {{ $icon }} w-4 text-center text-xs"></i><span>{{ $label }}</span>
        </a>
      @endforeach

      <p class="px-3 pt-4 pb-1 text-[10px] font-bold text-outline uppercase tracking-widest">ລະບົບ</p>

      <a href="{{ route('admin.contacts.index') }}"
         class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.contacts.*') ? 'nav-active' : 'text-on-surface-variant' }}">
        <i class="fas fa-envelope w-4 text-center text-xs"></i>
        <span class="flex-1">ຕິດຕໍ່</span>
        @php $unread = \App\Models\ContactMessage::where('is_read', 0)->count(); @endphp
        @if($unread > 0)
          <span class="w-5 h-5 flex items-center justify-center primary-gradient text-white text-[10px] font-bold rounded-full">{{ $unread }}</span>
        @endif
      </a>

      @foreach([
          ['admin.users.*',    route('admin.users.index'),    'fa-user-cog', 'ຜູ້ໃຊ້'],
          ['admin.settings.*', route('admin.settings.index'), 'fa-cog',      'ຕັ້ງຄ່າ'],
        ] as [$route, $url, $icon, $label])
        <a href="{{ $url }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs($route) ? 'nav-active' : 'text-on-surface-variant' }}">
          <i class="fas {{ $icon }} w-4 text-center text-xs"></i><span>{{ $label }}</span>
        </a>
      @endforeach

    </nav>

    {{-- User footer --}}
    <div class="flex-shrink-0 px-4 py-4 border-t border-surface-container-high">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 primary-gradient rounded-full flex items-center justify-center flex-shrink-0">
          <span class="text-white text-xs font-bold">{{ mb_substr(auth()->user()->full_name_lo, 0, 1) }}</span>
        </div>
        <div class="flex-1 min-w-0">
          <p class="text-xs font-semibold text-on-surface truncate">{{ auth()->user()->full_name_lo }}</p>
          <p class="text-[10px] text-outline truncate">{{ auth()->user()->role }}</p>
        </div>
        <form method="POST" action="{{ route('admin.logout') }}">
          @csrf
          <button type="submit" class="text-outline hover:text-red-500 transition-colors p-1" title="ອອກຈາກລະບົບ">
            <i class="fas fa-sign-out-alt text-sm"></i>
          </button>
        </form>
      </div>
    </div>
  </aside>

  {{-- ═══ MAIN ═══ --}}
  <div class="lg:ml-64 flex flex-col min-h-screen overflow-x-hidden">

    {{-- Topbar --}}
    <header class="sticky top-0 z-20 flex items-center gap-3 px-4 sm:px-6 py-4 bg-surface-container-lowest/90 backdrop-blur-xl border-b border-surface-container-high">
      {{-- Hamburger (mobile only) --}}
      <button @click="open = true"
              class="lg:hidden -ml-1 p-1.5 text-on-surface-variant hover:text-on-surface hover:bg-surface-container rounded-lg transition-colors"
              aria-label="ເປີດເມນູ">
        <i class="fas fa-bars text-base"></i>
      </button>

      <h1 class="flex-1 text-base sm:text-lg font-bold tracking-tight truncate">@yield('page_title', 'Dashboard')</h1>

      <a href="{{ url('/') }}" target="_blank"
         class="flex items-center gap-2 text-xs text-on-surface-variant hover:text-primary transition-colors px-3 py-1.5 rounded-full bg-surface-container-low ghost-border whitespace-nowrap flex-shrink-0">
        <i class="fas fa-globe text-xs"></i>
        <span class="hidden sm:inline">ເວັບໄຊ</span>
      </a>
    </header>

    {{-- Flash messages --}}
    @if(session('success'))
      <div class="mx-4 sm:mx-6 mt-4 flex items-center gap-3 px-4 py-3 bg-green-50 border border-green-100 text-green-700 rounded-lg text-sm">
        <i class="fas fa-check-circle flex-shrink-0"></i>
        <span>{{ session('success') }}</span>
      </div>
    @endif
    @if(session('error'))
      <div class="mx-4 sm:mx-6 mt-4 flex items-center gap-3 px-4 py-3 bg-red-50 border border-red-100 text-red-600 rounded-lg text-sm">
        <i class="fas fa-exclamation-circle flex-shrink-0"></i>
        <span>{{ session('error') }}</span>
      </div>
    @endif

    {{-- Content --}}
    <main class="flex-1 p-4 sm:p-6">
      @yield('content')
    </main>

  </div>

@stack('scripts')
</body>
</html>
