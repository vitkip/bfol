{{--
  Icon Picker Component
  Usage:
    @include('admin.navigation._icon_picker', ['currentIcon' => $navigation->icon ?? ''])
  Outputs a hidden-capable input[name="icon"] with live preview + searchable modal.
--}}
@php $currentIcon = $currentIcon ?? old('icon', ''); @endphp

<div x-data="iconPicker('{{ $currentIcon }}')" class="space-y-2">

  <label class="block text-xs font-semibold">Icon (Font Awesome class)</label>

  {{-- Input row --}}
  <div class="flex items-center gap-2">

    {{-- Live preview --}}
    <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center shrink-0 border border-slate-200">
      <i :class="value || 'fas fa-question'" class="text-blue-600 text-sm"></i>
    </div>

    {{-- Manual text input --}}
    <input type="text" name="icon" id="icon-input"
           x-model="value"
           class="flex-1 rounded-lg border border-surface-container-high px-3 py-2 text-sm
                  focus:outline-none focus:ring-2 focus:ring-primary/30"
           placeholder="fas fa-globe">

    {{-- Browse button --}}
    <button type="button" @click="open = true"
            class="shrink-0 inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold
                   bg-blue-50 text-blue-700 border border-blue-200 rounded-lg
                   hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-colors">
      <i class="fas fa-th text-[10px]"></i> ເລືອກ
    </button>

    {{-- Clear button --}}
    <button type="button" @click="value = ''" x-show="value"
            class="shrink-0 p-2 text-slate-400 hover:text-red-500 transition-colors rounded-lg hover:bg-red-50">
      <i class="fas fa-times text-xs"></i>
    </button>
  </div>

  <p class="text-xs text-outline">ພິມ class Font Awesome ໂດຍກົງ ຫຼື ກົດ <strong>ເລືອກ</strong> ເພື່ອຄົ້ນຫາ icon</p>

  {{-- ── Modal overlay ── --}}
  <div x-show="open" x-cloak
       class="fixed inset-0 z-[9999] flex items-start justify-center pt-[5vh] px-4"
       @keydown.escape.window="open = false">

    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="open = false"></div>

    {{-- Panel --}}
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl flex flex-col max-h-[88vh] overflow-hidden"
         @click.stop>

      {{-- Header --}}
      <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
        <div>
          <h3 class="font-bold text-sm text-slate-800">ເລືອກ Icon</h3>
          <p class="text-xs text-slate-400 mt-0.5">Font Awesome 6 Free</p>
        </div>
        <button type="button" @click="open = false"
                class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors">
          <i class="fas fa-times text-sm"></i>
        </button>
      </div>

      {{-- Search --}}
      <div class="px-4 py-3 border-b border-slate-100 bg-slate-50">
        <div class="flex items-center gap-2 bg-white rounded-lg border border-slate-200 px-3 py-2">
          <i class="fas fa-search text-slate-400 text-xs shrink-0"></i>
          <input type="text" x-model="search" placeholder="ຄົ້ນຫາ icon… (ອັງກິດ)"
                 class="flex-1 text-sm bg-transparent focus:outline-none text-slate-700 placeholder:text-slate-400"
                 @keydown.escape.stop="search = ''" autofocus>
          <button type="button" x-show="search" @click="search = ''"
                  class="text-slate-300 hover:text-slate-500 transition-colors">
            <i class="fas fa-times text-xs"></i>
          </button>
        </div>
        <p class="text-xs text-slate-400 mt-1.5">
          ພົບ <strong x-text="filtered.length"></strong> icons
          <span x-show="search"> ສຳລັບ "<span x-text="search" class="text-blue-600"></span>"</span>
        </p>
      </div>

      {{-- Icon grid --}}
      <div class="flex-1 overflow-y-auto p-3">

        {{-- Category headers shown only when not searching --}}
        <template x-if="!search">
          <div>
            <template x-for="cat in categories" :key="cat.name">
              <div class="mb-4">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-1 mb-1.5" x-text="cat.name"></p>
                <div class="grid grid-cols-8 sm:grid-cols-10 gap-1">
                  <template x-for="ic in cat.icons" :key="ic">
                    <button type="button"
                            @click="pick(ic)"
                            :title="ic"
                            :class="value === ic ? 'ring-2 ring-blue-500 bg-blue-50' : 'hover:bg-slate-100'"
                            class="aspect-square flex flex-col items-center justify-center rounded-lg transition-colors group p-1.5">
                      <i :class="ic" class="text-slate-600 group-hover:text-blue-600 text-base leading-none"></i>
                    </button>
                  </template>
                </div>
              </div>
            </template>
          </div>
        </template>

        {{-- Flat grid when searching --}}
        <template x-if="search">
          <div>
            <template x-if="filtered.length === 0">
              <div class="text-center py-12 text-slate-400">
                <i class="fas fa-search text-3xl mb-2 block opacity-30"></i>
                <p class="text-sm">ບໍ່ພົບ icon "<span x-text="search"></span>"</p>
              </div>
            </template>
            <div class="grid grid-cols-8 sm:grid-cols-10 gap-1">
              <template x-for="ic in filtered" :key="ic">
                <button type="button"
                        @click="pick(ic)"
                        :title="ic"
                        :class="value === ic ? 'ring-2 ring-blue-500 bg-blue-50' : 'hover:bg-slate-100'"
                        class="aspect-square flex flex-col items-center justify-center rounded-lg transition-colors group p-1.5">
                  <i :class="ic" class="text-slate-600 group-hover:text-blue-600 text-base leading-none"></i>
                </button>
              </template>
            </div>
          </div>
        </template>
      </div>

      {{-- Footer --}}
      <div class="px-5 py-3 border-t border-slate-100 bg-slate-50 flex items-center gap-3">
        <div class="flex items-center gap-2 flex-1">
          <div class="w-7 h-7 rounded bg-white border border-slate-200 flex items-center justify-center shrink-0">
            <i :class="value || 'fas fa-question'" class="text-blue-600 text-sm"></i>
          </div>
          <code class="text-xs text-slate-600 truncate" x-text="value || '(ຍັງບໍ່ໄດ້ເລືອກ)'"></code>
        </div>
        <button type="button" @click="open = false"
                class="px-4 py-1.5 text-xs font-semibold bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
          ຢືນຢັນ
        </button>
      </div>

    </div>
  </div>
</div>

@once
@push('scripts')
<script>
function iconPicker(initial) {
  const ALL_ICONS = [
    // ── General / Interface ──────────────────────────────────────────────
    'fas fa-house','fas fa-home','fas fa-bars','fas fa-xmark','fas fa-times',
    'fas fa-check','fas fa-check-circle','fas fa-circle-check',
    'fas fa-plus','fas fa-minus','fas fa-trash','fas fa-trash-alt',
    'fas fa-pen','fas fa-edit','fas fa-pencil-alt','fas fa-pen-to-square',
    'fas fa-save','fas fa-floppy-disk','fas fa-upload','fas fa-download',
    'fas fa-search','fas fa-magnifying-glass',
    'fas fa-filter','fas fa-sort','fas fa-list','fas fa-grip',
    'fas fa-ellipsis','fas fa-ellipsis-v','fas fa-ellipsis-h',
    'fas fa-cog','fas fa-gear','fas fa-gears','fas fa-sliders',
    'fas fa-lock','fas fa-lock-open','fas fa-unlock',
    'fas fa-eye','fas fa-eye-slash','fas fa-bell','fas fa-bell-slash',
    'fas fa-star','fas fa-heart','fas fa-bookmark','fas fa-flag',
    'fas fa-tag','fas fa-tags','fas fa-paperclip','fas fa-link',
    'fas fa-external-link-alt','fas fa-arrow-up-right-from-square',
    'fas fa-copy','fas fa-paste','fas fa-cut','fas fa-print',
    'fas fa-undo','fas fa-redo','fas fa-rotate-left','fas fa-rotate-right',
    'fas fa-share','fas fa-share-alt','fas fa-share-nodes',
    'fas fa-info','fas fa-info-circle','fas fa-circle-info',
    'fas fa-question','fas fa-question-circle','fas fa-circle-question',
    'fas fa-exclamation','fas fa-exclamation-circle','fas fa-exclamation-triangle',
    'fas fa-circle-exclamation','fas fa-triangle-exclamation',
    'fas fa-ban','fas fa-times-circle','fas fa-circle-xmark',

    // ── Navigation / Arrows ──────────────────────────────────────────────
    'fas fa-chevron-up','fas fa-chevron-down','fas fa-chevron-left','fas fa-chevron-right',
    'fas fa-angle-up','fas fa-angle-down','fas fa-angle-left','fas fa-angle-right',
    'fas fa-angles-up','fas fa-angles-down','fas fa-angles-left','fas fa-angles-right',
    'fas fa-arrow-up','fas fa-arrow-down','fas fa-arrow-left','fas fa-arrow-right',
    'fas fa-arrows-alt','fas fa-arrows-up-down-left-right',
    'fas fa-circle','fas fa-dot-circle','fas fa-circle-dot',
    'fas fa-sitemap','fas fa-th','fas fa-grid-3','fas fa-table-cells',
    'fas fa-th-list','fas fa-table-list','fas fa-th-large','fas fa-table-cells-large',
    'fas fa-map','fas fa-map-marked','fas fa-map-location-dot',
    'fas fa-compass','fas fa-location-arrow','fas fa-crosshairs',

    // ── People / Users ───────────────────────────────────────────────────
    'fas fa-user','fas fa-user-circle','fas fa-circle-user',
    'fas fa-users','fas fa-user-group','fas fa-people-group',
    'fas fa-user-tie','fas fa-user-graduate','fas fa-user-check',
    'fas fa-user-plus','fas fa-user-minus','fas fa-user-slash',
    'fas fa-user-shield','fas fa-user-lock','fas fa-user-cog',
    'fas fa-id-card','fas fa-address-card','fas fa-id-badge',
    'fas fa-person','fas fa-person-chalkboard',
    'fas fa-child','fas fa-baby','fas fa-person-walking',

    // ── Communication ────────────────────────────────────────────────────
    'fas fa-envelope','fas fa-envelope-open','fas fa-envelope-open-text',
    'fas fa-phone','fas fa-phone-alt','fas fa-phone-flip',
    'fas fa-fax','fas fa-mobile','fas fa-mobile-alt','fas fa-mobile-screen',
    'fas fa-comment','fas fa-comments','fas fa-comment-dots',
    'fas fa-comment-alt','fas fa-message','fas fa-messages',
    'fas fa-inbox','fas fa-paper-plane','fas fa-reply',
    'fas fa-at','fas fa-broadcast-tower','fas fa-tower-broadcast',
    'fas fa-wifi','fas fa-rss','fas fa-satellite',

    // ── Media / Content ──────────────────────────────────────────────────
    'fas fa-image','fas fa-images','fas fa-photo-film',
    'fas fa-video','fas fa-film','fas fa-clapperboard',
    'fas fa-camera','fas fa-camera-retro','fas fa-photo-video',
    'fas fa-music','fas fa-headphones','fas fa-volume-up','fas fa-volume-high',
    'fas fa-play','fas fa-pause','fas fa-stop','fas fa-forward','fas fa-backward',
    'fas fa-podcast','fas fa-microphone','fas fa-microphone-alt','fas fa-microphone-lines',
    'fas fa-tv','fas fa-display','fas fa-desktop',

    // ── Files & Documents ────────────────────────────────────────────────
    'fas fa-file','fas fa-file-alt','fas fa-file-lines',
    'fas fa-file-pdf','fas fa-file-word','fas fa-file-excel',
    'fas fa-file-powerpoint','fas fa-file-image','fas fa-file-video',
    'fas fa-file-audio','fas fa-file-code','fas fa-file-csv',
    'fas fa-file-archive','fas fa-file-zipper',
    'fas fa-folder','fas fa-folder-open','fas fa-folder-plus','fas fa-folder-minus',
    'fas fa-folder-tree','fas fa-copy','fas fa-file-signature',
    'fas fa-file-contract','fas fa-file-invoice','fas fa-receipt',
    'fas fa-clipboard','fas fa-clipboard-list','fas fa-clipboard-check',
    'fas fa-newspaper','fas fa-book','fas fa-book-open','fas fa-book-reader',
    'fas fa-books','fas fa-bookmark','fas fa-scroll',
    'fas fa-feather','fas fa-feather-alt','fas fa-feather-pointed',
    'fas fa-pen-fancy','fas fa-signature',

    // ── Education / Knowledge ────────────────────────────────────────────
    'fas fa-graduation-cap','fas fa-chalkboard','fas fa-chalkboard-teacher',
    'fas fa-chalkboard-user','fas fa-school','fas fa-university',
    'fas fa-building-columns','fas fa-atom','fas fa-flask',
    'fas fa-microscope','fas fa-dna','fas fa-brain',
    'fas fa-lightbulb','fas fa-award','fas fa-medal',
    'fas fa-certificate','fas fa-trophy','fas fa-star',

    // ── Religion / Buddhist ──────────────────────────────────────────────
    'fas fa-dharmachakra','fas fa-khanda','fas fa-om',
    'fas fa-mosque','fas fa-church','fas fa-synagogue',
    'fas fa-praying-hands','fas fa-hands-praying',
    'fas fa-cross','fas fa-star-of-david',
    'fas fa-place-of-worship','fas fa-hands-holding',

    // ── Business / Finance ───────────────────────────────────────────────
    'fas fa-briefcase','fas fa-building','fas fa-city',
    'fas fa-landmark','fas fa-bank','fas fa-building-columns',
    'fas fa-chart-bar','fas fa-chart-line','fas fa-chart-pie',
    'fas fa-chart-area','fas fa-chart-column',
    'fas fa-dollar-sign','fas fa-euro-sign','fas fa-coins',
    'fas fa-wallet','fas fa-credit-card','fas fa-money-bill',
    'fas fa-money-bill-wave','fas fa-piggy-bank',
    'fas fa-handshake','fas fa-hands-helping','fas fa-hand-holding-heart',
    'fas fa-hand-holding-usd','fas fa-hand-holding-dollar',
    'fas fa-donate','fas fa-gift','fas fa-box','fas fa-boxes',
    'fas fa-shopping-cart','fas fa-cart-shopping',
    'fas fa-store','fas fa-shop',

    // ── Health / Medical ─────────────────────────────────────────────────
    'fas fa-heart','fas fa-heart-pulse','fas fa-heartbeat',
    'fas fa-hospital','fas fa-clinic-medical','fas fa-house-medical',
    'fas fa-stethoscope','fas fa-syringe','fas fa-pills',
    'fas fa-first-aid','fas fa-kit-medical','fas fa-plus-square',
    'fas fa-ambulance','fas fa-truck-medical',

    // ── Travel / Location ────────────────────────────────────────────────
    'fas fa-globe','fas fa-globe-asia','fas fa-globe-americas','fas fa-globe-europe',
    'fas fa-earth-asia','fas fa-earth-americas','fas fa-earth-europe',
    'fas fa-map-marker','fas fa-map-marker-alt','fas fa-location-dot',
    'fas fa-map-pin','fas fa-route','fas fa-road',
    'fas fa-plane','fas fa-plane-departure','fas fa-plane-arrival',
    'fas fa-car','fas fa-bus','fas fa-train','fas fa-ship',
    'fas fa-bicycle','fas fa-walking','fas fa-person-walking',
    'fas fa-passport','fas fa-suitcase','fas fa-luggage-cart',
    'fas fa-hotel','fas fa-flag','fas fa-flag-checkered',
    'fas fa-mountain','fas fa-tree','fas fa-leaf',

    // ── Technology / IT ──────────────────────────────────────────────────
    'fas fa-laptop','fas fa-computer','fas fa-tablet','fas fa-tablet-alt',
    'fas fa-keyboard','fas fa-mouse','fas fa-server','fas fa-database',
    'fas fa-network-wired','fas fa-cloud','fas fa-cloud-upload-alt',
    'fas fa-cloud-download-alt','fas fa-code','fas fa-code-branch',
    'fas fa-terminal','fas fa-bug','fas fa-robot','fas fa-microchip',
    'fas fa-memory','fas fa-hard-drive','fas fa-hdd',
    'fas fa-ethernet','fas fa-shield','fas fa-shield-alt','fas fa-shield-halved',
    'fas fa-lock','fas fa-key','fas fa-fingerprint',
    'fas fa-qrcode','fas fa-barcode',

    // ── Social / International ───────────────────────────────────────────
    'fas fa-exchange-alt','fas fa-right-left','fas fa-arrows-rotate',
    'fas fa-language','fas fa-translate','fas fa-spell-check',
    'fas fa-hands','fas fa-handshake-angle','fas fa-people-arrows',
    'fas fa-signature','fas fa-file-signature','fas fa-pen-nib',
    'fas fa-bullhorn','fas fa-megaphone','fas fa-volume-up',
    'fas fa-calendar','fas fa-calendar-alt','fas fa-calendar-days',
    'fas fa-calendar-check','fas fa-calendar-plus','fas fa-clock',
    'fas fa-hourglass','fas fa-hourglass-half',

    // ── Brands / Social Media ────────────────────────────────────────────
    'fab fa-facebook','fab fa-facebook-f','fab fa-facebook-square',
    'fab fa-twitter','fab fa-x-twitter',
    'fab fa-instagram','fab fa-youtube','fab fa-tiktok',
    'fab fa-linkedin','fab fa-linkedin-in',
    'fab fa-whatsapp','fab fa-telegram','fab fa-line','fab fa-wechat',
    'fab fa-github','fab fa-gitlab','fab fa-google',
    'fab fa-wordpress','fab fa-wikipedia-w',
  ];

  const CATEGORIES = [
    { name: 'ທົ່ວໄປ / Interface',    slice: [0,  44] },
    { name: 'Navigation / ລູກສອນ',   slice: [44, 72] },
    { name: 'ຄົນ / Users',            slice: [72, 99] },
    { name: 'ສື່ສານ / Communication', slice: [99, 128] },
    { name: 'ສື່ / Media',            slice: [128, 149] },
    { name: 'ໄຟລ໌ / Documents',       slice: [149, 200] },
    { name: 'ການສຶກສາ / Education',   slice: [200, 220] },
    { name: 'ສາສະໜາ / Religion',      slice: [220, 234] },
    { name: 'ທຸລະກິດ / Business',     slice: [234, 274] },
    { name: 'ສຸຂະພາບ / Health',        slice: [274, 288] },
    { name: 'ເດີນທາງ / Travel',        slice: [288, 318] },
    { name: 'ເທັກໂນໂລຊີ / IT',          slice: [318, 346] },
    { name: 'ສັງຄົມ / Social',          slice: [346, 370] },
    { name: 'ໂຊຊຽລ / Brands',           slice: [370, 400] },
  ];

  return {
    value: initial,
    open: false,
    search: '',

    get filtered() {
      if (!this.search) return ALL_ICONS;
      const q = this.search.toLowerCase().replace(/^(fas?|fab|far)\s*/, '');
      return ALL_ICONS.filter(ic => ic.replace('fas ', '').replace('fab ', '').replace('far ', '').includes(q));
    },

    get categories() {
      return CATEGORIES.map(c => ({
        name: c.name,
        icons: ALL_ICONS.slice(c.slice[0], c.slice[1]),
      }));
    },

    pick(ic) {
      this.value = ic;
      this.open = false;
      this.search = '';
    },
  };
}
</script>
@endpush
@endonce
