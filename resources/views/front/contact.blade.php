@extends('front.layouts.app')

@php
  $L = app()->getLocale();
  $t = fn($lo,$en,$zh) => match($L){'zh'=>$zh,'en'=>$en,default=>$lo};

  $address = \App\Models\SiteSetting::get('site_address_'.$L)
          ?: \App\Models\SiteSetting::get('site_address_lo');
  $email   = \App\Models\SiteSetting::get('site_email');
  $phone   = \App\Models\SiteSetting::get('site_phone');
@endphp

@section('title', $t('ຕິດຕໍ່ພວກເຮົາ','Contact Us','聯繫我們').' - '.($settings->site_name_lo ?: 'BFOL'))
@section('meta_description', $t('ຕິດຕໍ່ ສ.ຊ.ພ.ລ','Contact BFOL','聯繫老撾佛協'))

@push('styles')
<style>
  .dot-pattern { background-image:radial-gradient(circle,rgba(255,255,255,.12) 1px,transparent 1px); background-size:28px 28px; }
  input:focus,textarea:focus,select:focus { outline:none; }
</style>
@endpush

@section('content')

{{-- ═══ HERO ═══ --}}
<section class="relative bg-primary overflow-hidden">
  <div class="dot-pattern absolute inset-0 pointer-events-none"></div>
  <div class="absolute -top-24 -right-24 w-80 h-80 bg-secondary/10 rounded-full blur-3xl pointer-events-none"></div>
  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-12 relative z-10 text-center">
    <h1 class="text-3xl sm:text-4xl font-serif font-bold text-on-primary mb-3">
      {{ $t('ຕິດຕໍ່ພວກເຮົາ','Contact Us','聯繫我們') }}
    </h1>
    <p class="text-on-primary/70 text-sm max-w-lg mx-auto">
      {{ $t(
        'ຕິດຕໍ່ ສ.ຊ.ພ.ລ — ພວກເຮົາຍິນດີ ຮັບຟັງ ຄຳຖາມ ແລະ ຄຳສະເໜີ ຂອງທ່ານ',
        'Get in touch with BFOL — we are happy to answer your questions and suggestions.',
        '聯繫老撾佛協，我們樂意回答您的問題和建議。'
      ) }}
    </p>
  </div>
</section>

{{-- ═══ CONTENT ═══ --}}
<section class="bg-slate-50 py-10">
  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

      {{-- ── Info cards ── --}}
      <div class="lg:col-span-2 flex flex-col gap-4">
        @foreach([
          ['icon'=>'fas fa-map-marker-alt', 'title'=>$t('ທີ່ຢູ່','Address','地址'),        'val'=>$address],
          ['icon'=>'fas fa-envelope',       'title'=>$t('ອີເມວ','Email','電子郵件'),        'val'=>$email,  'href'=>"mailto:$email"],
          ['icon'=>'fas fa-phone-alt',      'title'=>$t('ໂທລະສັບ','Phone','電話'),          'val'=>$phone,  'href'=>"tel:$phone"],
        ] as $info)
          @if($info['val'])
            <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_2px_12px_-4px_rgba(0,0,0,.08)] p-5
                        flex items-start gap-4 hover:shadow-md transition-shadow">
              <div class="w-10 h-10 rounded-xl bg-primary/8 flex items-center justify-center shrink-0">
                <i class="{{ $info['icon'] }} text-primary text-sm"></i>
              </div>
              <div>
                <p class="text-[10px] font-bold text-outline uppercase tracking-wide mb-0.5">{{ $info['title'] }}</p>
                @if(!empty($info['href']))
                  <a href="{{ $info['href'] }}"
                     class="text-sm font-semibold text-on-surface hover:text-primary transition-colors">
                    {{ $info['val'] }}
                  </a>
                @else
                  <p class="text-sm font-semibold text-on-surface leading-snug">{{ $info['val'] }}</p>
                @endif
              </div>
            </div>
          @endif
        @endforeach

        {{-- Social --}}
        @php
          $facebook  = \App\Models\SiteSetting::get('social_facebook');
          $youtube   = \App\Models\SiteSetting::get('social_youtube');
        @endphp
        @if($facebook || $youtube)
          <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_2px_12px_-4px_rgba(0,0,0,.08)] p-5">
            <p class="text-[10px] font-bold text-outline uppercase tracking-wide mb-3">{{ $t('ສັງຄົມອອນລາຍ','Social Media','社交媒體') }}</p>
            <div class="flex gap-3">
              @if($facebook)
                <a href="{{ $facebook }}" target="_blank" rel="noreferrer"
                   class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700 transition-colors">
                  <i class="fab fa-facebook-f text-sm"></i>
                </a>
              @endif
              @if($youtube)
                <a href="{{ $youtube }}" target="_blank" rel="noreferrer"
                   class="w-10 h-10 rounded-xl bg-red-600 text-white flex items-center justify-center hover:bg-red-700 transition-colors">
                  <i class="fab fa-youtube text-sm"></i>
                </a>
              @endif
            </div>
          </div>
        @endif
      </div>

      {{-- ── Contact form ── --}}
      <div class="lg:col-span-3">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_2px_12px_-4px_rgba(0,0,0,.08)] p-6 md:p-8">

          <h2 class="font-serif font-bold text-on-surface text-xl mb-1">
            {{ $t('ສົ່ງຂໍ້ຄວາມ','Send us a message','發送訊息') }}
          </h2>
          <p class="text-sm text-outline mb-6">
            {{ $t('ຂໍ້ຄວາມຂອງທ່ານ ຈະຖືກຕອບກັບ ໂດຍໄວ','We will reply as soon as possible.','我們將盡快回覆。') }}
          </p>

          @if(session('success'))
            <div class="flex items-start gap-3 bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 mb-6 text-sm">
              <i class="fas fa-check-circle text-green-500 mt-0.5 shrink-0"></i>
              <p>{{ session('success') }}</p>
            </div>
          @endif

          @if($errors->any())
            <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-6 text-sm">
              <i class="fas fa-exclamation-circle text-red-500 mt-0.5 shrink-0"></i>
              <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
              </ul>
            </div>
          @endif

          <form action="{{ route('front.contact.submit') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="language" value="{{ $L }}">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              {{-- Name --}}
              <div>
                <label class="block text-xs font-bold text-on-surface-variant mb-1.5">
                  {{ $t('ຊື່-ນາມສະກຸນ','Full Name','姓名') }} <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                  <i class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-outline text-xs pointer-events-none"></i>
                  <input type="text" name="name" value="{{ old('name') }}" required
                         placeholder="{{ $t('ຊື່ ນາມສະກຸນ','Your name','您的姓名') }}"
                         class="w-full pl-9 pr-3 py-2.5 text-sm bg-surface-container-low border border-surface-container-high rounded-lg
                                focus:ring-2 focus:ring-primary/30 focus:border-primary/50 transition-colors
                                @error('name') border-red-300 @enderror" />
                </div>
              </div>

              {{-- Email --}}
              <div>
                <label class="block text-xs font-bold text-on-surface-variant mb-1.5">
                  {{ $t('ອີເມວ','Email','電子郵件') }} <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                  <i class="fas fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-outline text-xs pointer-events-none"></i>
                  <input type="email" name="email" value="{{ old('email') }}" required
                         placeholder="email@example.com"
                         class="w-full pl-9 pr-3 py-2.5 text-sm bg-surface-container-low border border-surface-container-high rounded-lg
                                focus:ring-2 focus:ring-primary/30 focus:border-primary/50 transition-colors
                                @error('email') border-red-300 @enderror" />
                </div>
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              {{-- Phone --}}
              <div>
                <label class="block text-xs font-bold text-on-surface-variant mb-1.5">{{ $t('ໂທລະສັບ','Phone','電話') }}</label>
                <div class="relative">
                  <i class="fas fa-phone-alt absolute left-3 top-1/2 -translate-y-1/2 text-outline text-xs pointer-events-none"></i>
                  <input type="text" name="phone" value="{{ old('phone') }}"
                         placeholder="+856 20..."
                         class="w-full pl-9 pr-3 py-2.5 text-sm bg-surface-container-low border border-surface-container-high rounded-lg
                                focus:ring-2 focus:ring-primary/30 focus:border-primary/50 transition-colors" />
                </div>
              </div>

              {{-- Subject --}}
              <div>
                <label class="block text-xs font-bold text-on-surface-variant mb-1.5">{{ $t('ຫົວຂໍ້','Subject','主題') }}</label>
                <div class="relative">
                  <i class="fas fa-tag absolute left-3 top-1/2 -translate-y-1/2 text-outline text-xs pointer-events-none"></i>
                  <input type="text" name="subject" value="{{ old('subject') }}"
                         placeholder="{{ $t('ຫົວຂໍ້ຂໍ້ຄວາມ','Subject','主題') }}"
                         class="w-full pl-9 pr-3 py-2.5 text-sm bg-surface-container-low border border-surface-container-high rounded-lg
                                focus:ring-2 focus:ring-primary/30 focus:border-primary/50 transition-colors" />
                </div>
              </div>
            </div>

            {{-- Message --}}
            <div>
              <label class="block text-xs font-bold text-on-surface-variant mb-1.5">
                {{ $t('ຂໍ້ຄວາມ','Message','訊息') }} <span class="text-red-500">*</span>
              </label>
              <textarea name="message" rows="5" required
                        placeholder="{{ $t('ພິມຂໍ້ຄວາມຂອງທ່ານ...','Type your message here...','在此輸入您的訊息...') }}"
                        class="w-full px-3 py-2.5 text-sm bg-surface-container-low border border-surface-container-high rounded-lg
                               focus:ring-2 focus:ring-primary/30 focus:border-primary/50 transition-colors resize-y
                               @error('message') border-red-300 @enderror">{{ old('message') }}</textarea>
            </div>

            <div class="flex justify-end pt-2">
              <button type="submit"
                      class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-on-primary font-bold text-sm rounded-xl
                             hover:bg-secondary hover:text-on-secondary hover:shadow-lg
                             active:scale-[0.98] transition-all duration-200 group">
                <span>{{ $t('ສົ່ງຂໍ້ຄວາມ','Send Message','發送') }}</span>
                <i class="fas fa-paper-plane text-xs group-hover:-translate-y-0.5 group-hover:translate-x-0.5 transition-transform"></i>
              </button>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
</section>

@endsection
