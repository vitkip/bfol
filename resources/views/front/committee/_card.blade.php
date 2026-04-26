@php
  $L  = app()->getLocale();
  $tr = fn($f) => $member->{$f.'_'.$L} ?? $member->{$f.'_lo'} ?? '';

  // FA6-free compatible icons + design tokens per gender
  $g = match($member->gender) {
    'monk'   => [
      'ring'   => 'ring-amber-300',
      'bgBadge'=> 'bg-amber-500',
      'strip'  => 'from-amber-400 via-amber-500 to-amber-600',
      'pill'   => 'bg-amber-50 text-amber-800 border-amber-200',
      'avatar' => 'bg-gradient-to-br from-amber-50 to-amber-100',
      'aIcon'  => 'fas fa-hands-praying text-amber-400',  // FA6 ✓
      'bIcon'  => 'fas fa-hands-praying',                 // FA6 ✓
      'label'  => match($L){'zh'=>'僧侣','en'=>'Monk',default=>'ພຣະສົງ'},
    ],
    'female' => [
      'ring'   => 'ring-rose-300',
      'bgBadge'=> 'bg-rose-500',
      'strip'  => 'from-rose-400 via-rose-500 to-rose-600',
      'pill'   => 'bg-rose-50 text-rose-800 border-rose-200',
      'avatar' => 'bg-gradient-to-br from-rose-50 to-rose-100',
      'aIcon'  => 'fas fa-person-dress text-rose-400',    // FA6 ✓
      'bIcon'  => 'fas fa-person-dress',                  // FA6 ✓
      'label'  => match($L){'zh'=>'女性','en'=>'Female',default=>'ຍິງ'},
    ],
    default  => [
      'ring'   => 'ring-blue-300',
      'bgBadge'=> 'bg-blue-500',
      'strip'  => 'from-blue-400 via-blue-500 to-blue-600',
      'pill'   => 'bg-blue-50 text-blue-800 border-blue-200',
      'avatar' => 'bg-gradient-to-br from-blue-50 to-blue-100',
      'aIcon'  => 'fas fa-person text-blue-400',          // FA6 ✓
      'bIcon'  => 'fas fa-person',                        // FA6 ✓
      'label'  => match($L){'zh'=>'男性','en'=>'Male',default=>'ຊາຍ'},
    ],
  };

  // Serialise once – dispatched via vanilla JS (no Alpine scope needed on card)
  $payload = Js::from([
    'name'       => $tr('name'),
    'position'   => $tr('position'),
    'dept'       => $member->department
                      ? ($member->department->{'name_'.$L} ?? $member->department->name_lo)
                      : '',
    'bio'        => $tr('bio'),
    'education'  => $tr('education'),
    'village'    => $tr('birth_village'),
    'district'   => $tr('district'),
    'province'   => $tr('province'),
    'temple'     => $tr('current_temple'),
    'email'      => $member->email      ?? '',
    'phone'      => $member->phone      ?? '',
    'facebook'   => $member->facebook   ?? '',
    'photo_url'  => $member->photo_url  ?? '',
    'gender'     => $member->gender     ?? 'male',
    'pansa'      => $member->pansa,
    'dob'        => $member->date_of_birth      ? $member->date_of_birth->format('d/m/Y')      : '',
    'ordination' => $member->date_of_ordination ? $member->date_of_ordination->format('d/m/Y') : '',
  ]);
@endphp

{{--
  KEY FIX: vanilla window.dispatchEvent instead of Alpine $dispatch.
  $dispatch only works inside x-data scope. Cards are rendered in a plain
  Blade loop with no parent x-data, so we dispatch directly on window.
  The modal uses x-on:open-member.window which listens on window — matches perfectly.
--}}
<article
  class="group relative flex flex-col h-full bg-white rounded-3xl
         border border-slate-100 shadow-[0_4px_24px_-8px_rgba(3,22,50,0.10)]
         hover:shadow-[0_16px_48px_-8px_rgba(3,22,50,0.18)] hover:-translate-y-2
         transition-all duration-400 overflow-hidden cursor-pointer
         focus:outline-none focus:ring-2 focus:ring-secondary/50"
  tabindex="0"
  role="button"
  aria-label="{{ $tr('name') }}"
  onclick="window.dispatchEvent(new CustomEvent('open-member',{detail:{{ $payload }}}))"
  onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();window.dispatchEvent(new CustomEvent('open-member',{detail:{{ $payload }}}))}">

  {{-- Gradient top strip --}}
  <div class="h-1.5 bg-gradient-to-r {{ $g['strip'] }} shrink-0"></div>

  {{-- Monk: warm saffron glow --}}
  @if($member->gender === 'monk')
    <div class="absolute inset-0 bg-gradient-to-b from-amber-50/50 via-transparent to-transparent pointer-events-none"></div>
  @endif

  {{-- ── Card body ──────────────────────────────────────────── --}}
  <div class="relative flex flex-col items-center text-center px-5 pt-7 pb-5 gap-3 flex-1">

    {{-- Avatar --}}
    <div class="relative shrink-0">
      <div class="w-[90px] h-[90px] rounded-full p-[3px]
                  bg-gradient-to-br {{ $g['strip'] }}
                  shadow-lg group-hover:scale-105 transition-transform duration-400">
        <div class="w-full h-full rounded-full overflow-hidden {{ $g['avatar'] }}">
          @if($member->photo_url)
            <img src="{{ $member->photo_url }}"
                 alt="{{ $tr('name') }}"
                 class="w-full h-full object-cover"
                 loading="lazy" />
          @else
            <div class="w-full h-full flex items-center justify-center">
              <i class="{{ $g['aIcon'] }} text-4xl"></i>
            </div>
          @endif
        </div>
      </div>

      {{-- Gender badge --}}
      <span class="absolute -bottom-1 -right-1
                   w-7 h-7 rounded-full {{ $g['bgBadge'] }}
                   border-[3px] border-white shadow-md
                   flex items-center justify-center text-white text-[10px]
                   transition-transform duration-300 group-hover:scale-110">
        <i class="{{ $g['bIcon'] }}"></i>
      </span>
    </div>

    {{-- Name --}}
    <div class="w-full space-y-1 min-w-0">
      <h3 class="font-serif font-bold text-primary text-[15px] leading-snug line-clamp-2
                 group-hover:text-secondary transition-colors duration-300">
        {{ $tr('name') }}
      </h3>

      <p class="text-[11px] font-semibold text-secondary/90 line-clamp-1 uppercase tracking-wide">
        {{ $tr('position') }}
      </p>

      {{-- Gender + pansa pill --}}
      <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full
                   text-[10px] font-bold border {{ $g['pill'] }}">
        <i class="{{ $g['bIcon'] }} text-[8px]"></i>
        {{ $g['label'] }}
        @if($member->pansa)
          <span class="opacity-40 mx-0.5">·</span>
          {{ $member->pansa }}{{ match($L){'zh'=>'安','en'=>'P',default=>'ພ'} }}.
        @endif
      </span>
    </div>

    {{-- Province chip --}}
    @if($tr('province'))
      <p class="text-[10px] text-slate-500 flex items-center gap-1 font-medium
                bg-slate-50 px-3 py-1 rounded-full border border-slate-100 -mt-1">
        <i class="fas fa-location-dot text-[9px] text-secondary/70"></i>
        {{ $tr('province') }}
      </p>
    @endif

  </div>

  {{-- ── Footer CTA ─────────────────────────────────────────── --}}
  <div class="mt-auto shrink-0 border-t border-slate-100
              px-5 py-3 flex items-center justify-between
              bg-slate-50/60 group-hover:bg-primary transition-colors duration-400">
    <span class="text-[10px] font-bold uppercase tracking-widest
                 text-slate-400 group-hover:text-white/70 transition-colors duration-400">
      {{ match($L){'zh'=>'查看详情','en'=>'View Profile',default=>'ເບິ່ງຂໍ້ມູນ'} }}
    </span>
    <span class="w-6 h-6 rounded-full bg-white shadow-sm
                 flex items-center justify-center text-primary
                 group-hover:translate-x-1 transition-all duration-400">
      <i class="fas fa-chevron-right text-[8px]"></i>
    </span>
  </div>

</article>
