<!DOCTYPE html>
<html lang="lo">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>ເຂົ້າສູ່ລະບົບ — BFOL Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Phetsarath:wght@400;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
  <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
  <script>
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "surface-container-lowest": "#ffffff",
            "surface-container-low":    "#f3f3f4",
            "surface-container-high":   "#e8e8e9",
            "surface-container-highest":"#e2e2e3",
            "outline-variant":          "#c2c6d4",
            "primary-container":        "#005fb8",
            "on-surface":               "#1a1c1d",
            "on-surface-variant":       "#424752",
            "on-primary":               "#ffffff",
            "outline":                  "#727783",
            "primary":                  "#00488d",
            "surface":                  "#f9f9fa",
            "surface-bright":           "#f9f9fa",
          },
          borderRadius: {
            DEFAULT: "0.5rem",
            lg:      "0.5rem",
            xl:      "0.75rem",
            full:    "9999px",
          },
          fontFamily: {
            headline: ["Phetsarath", "Manrope", "sans-serif"],
            body:     ["Phetsarath", "Inter", "sans-serif"],
          }
        }
      }
    }
  </script>
  <style>
    body { font-family: 'Phetsarath', sans-serif; }
    h1, h2, h3 { font-family: 'Phetsarath', 'Manrope', sans-serif; }
    .material-symbols-outlined { font-variation-settings: 'FILL' 0,'wght' 400,'GRAD' 0,'opsz' 24; }
    .ghost-border  { border: 1px solid rgba(194,198,212,0.30); }
    .primary-gradient { background: linear-gradient(135deg, #00488d 0%, #005fb8 100%); }
  </style>
</head>
<body class="bg-surface text-on-surface min-h-screen flex flex-col overflow-x-hidden">

  {{-- Top Bar --}}
  <header class="fixed top-0 left-0 w-full z-50 flex justify-between items-center px-4 sm:px-8 py-4 bg-transparent backdrop-blur-xl">
    <div class="flex items-center gap-2">
      <span class="material-symbols-outlined text-primary text-2xl">brightness_alert</span>
      <span class="text-xl font-extrabold tracking-tight text-on-surface">BFOL Admin</span>
    </div>
    <div class="flex items-center gap-2 text-sm font-medium text-primary">
      <span class="material-symbols-outlined text-lg">language</span>
      <span>ລາວ · EN · 中文</span>
    </div>
  </header>

  {{-- Main --}}
  <main class="flex-grow flex items-center justify-center pt-16 px-4">
    <div class="w-full max-w-[1100px] h-auto md:h-[680px] grid grid-cols-1 md:grid-cols-2 bg-surface-container-lowest rounded-xl overflow-hidden shadow-[0px_12px_32px_rgba(26,28,29,0.08)]">

      {{-- Left: Image Panel --}}
      <div class="hidden md:block relative overflow-hidden">
        
        <div class="absolute inset-0 bg-primary/30 flex flex-col items-start p-12">
          
        <div class="bg-white/10 backdrop-blur-md p-8 rounded-xl border border-white/10 flex flex-col items-center">
            <img
              src="/storage/logo.png"
              alt="BFOL Logo"
              class="w-32 h-32 object-contain bg-white rounded-full mb-6 mt-2 shadow-lg"
              style="position:static"
              onerror="this.style.background='linear-gradient(135deg,#00488d,#005fb8)';this.removeAttribute('src')"
            />
            <h2 class="text-3xl font-bold text-white mb-2 leading-tight text-center">
              ສູນກາງອົງການພຸດທະສາສະນາສັມພັນແຫ່ງ ສປປ ລາວ
            </h2>
            <p class="text-white/80 text-base font-light leading-relaxed text-center">
              Central Buddhist Fellowship Organization of Lao PDR
            </p>
          </div>
        </div>
      </div>

      {{-- Right: Form Panel --}}
      <div class="flex flex-col justify-center px-8 md:px-14 lg:px-20 py-12 bg-surface-container-lowest">

        {{-- Heading --}}
        <div class="mb-10">
          <h1 class="text-3xl font-extrabold text-on-surface mb-2 tracking-tight leading-snug">
            ຍິນດີຕ້ອນຮັບເຂົ້າສູ່ລະບົບ
          </h1>
          <p class="text-on-surface-variant text-sm leading-relaxed">
            ລະບົບບໍລິຫານຈັດການຂໍມູນຂ່າວສານ ຂອງກັມມາທິການ ການຕ່າງປະເທດ
          </p>
        </div>

        {{-- Error Alert --}}
        @if($errors->any())
          <div class="mb-6 flex items-start gap-3 px-4 py-3 bg-red-50 rounded-lg border border-red-100 text-red-700 text-sm">
            <span class="material-symbols-outlined text-base mt-0.5">error</span>
            <span>{{ $errors->first() }}</span>
          </div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-5">
          @csrf

          {{-- Email --}}
          <div class="space-y-1.5">
            <label for="email" class="block text-sm font-semibold text-on-surface-variant ml-1">
              ອີເມວ
            </label>
            <div class="relative">
              <input
                id="email" name="email" type="email"
                value="{{ old('email') }}"
                required autofocus
                placeholder="admin@bfol.la"
                class="w-full px-4 py-3.5 bg-surface-container-low rounded-lg ghost-border focus:ring-2 focus:ring-primary/40 focus:border-primary/40 transition-all outline-none text-on-surface placeholder-outline/50 pr-11"
              />
              <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-outline/50 select-none">mail</span>
            </div>
          </div>

          {{-- Password --}}
          <div class="space-y-1.5">
            <div class="flex justify-between items-center px-1">
              <label for="password" class="text-sm font-semibold text-on-surface-variant">
                ລະຫັດຜ່ານ
              </label>
            </div>
            <div class="relative">
              <input
                id="password" name="password" type="password"
                required
                placeholder="••••••••"
                class="w-full px-4 py-3.5 bg-surface-container-low rounded-lg ghost-border focus:ring-2 focus:ring-primary/40 focus:border-primary/40 transition-all outline-none text-on-surface placeholder-outline/50 pr-11"
              />
              <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-outline/50 select-none">lock</span>
            </div>
          </div>

          {{-- Remember Me --}}
          <div class="flex items-center gap-3 px-1">
            <input
              id="remember" name="remember" type="checkbox"
              class="w-4 h-4 rounded border-outline-variant text-primary focus:ring-primary/20"
            />
            <label for="remember" class="text-sm text-on-surface-variant cursor-pointer select-none">
              ຈື່ການເຂົ້າສູ່ລະບົບ
            </label>
          </div>

          {{-- Submit --}}
          <button
            type="submit"
            class="w-full primary-gradient text-on-primary font-bold py-4 rounded-lg shadow-lg shadow-primary/20 hover:opacity-90 active:scale-[0.98] transition-all flex items-center justify-center gap-2 text-base"
          >
            <span>ເຂົ້າສູ່ລະບົບ</span>
            <span class="material-symbols-outlined text-xl">arrow_forward</span>
          </button>
        </form>

        {{-- Footer note --}}
        <p class="mt-8 text-center text-xs text-outline">
          ກໍມາທິການການຕ່າງປະເທດສູນກາງອພສ &mdash; ລະບົບພາຍໃນ
        </p>
      </div>

    </div>
  </main>

  {{-- Footer --}}
  <footer class="w-full py-6 px-8 flex flex-col md:flex-row justify-between items-center gap-3 bg-surface-container-low mt-auto">
    <p class="text-xs text-outline tracking-wide">&copy; {{ date('Y') }} BFOL. ສະຫງວນລິຂະສິດ.</p>
    <p class="text-xs text-outline">ກໍມາທິການການຕ່າງປະເທດສູນກາງອົງການພຸດທ໌ແຫ່ງລາວ</p>
  </footer>

  {{-- Ambient blobs --}}
  <div class="fixed -bottom-32 -left-32 w-96 h-96 bg-primary/5 rounded-full blur-3xl pointer-events-none -z-10"></div>
  <div class="fixed -top-32 -right-32 w-96 h-96 bg-primary/5 rounded-full blur-3xl pointer-events-none -z-10"></div>

</body>
</html>
