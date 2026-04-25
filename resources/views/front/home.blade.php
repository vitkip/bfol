@extends('front.layouts.app')

@section('title', __('app.site_name'))

@section('content')

  {{-- Hero Slider --}}
  @include('front._partials.hero', ['slides' => $slides])

  {{-- Stats Bar --}}
  @include('front._partials.stats', ['statistics' => $statistics])

  {{-- News Section --}}
  <section class="py-20 bg-white">
    <div class="container">
      
      <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
        <div class="max-w-2xl">
          <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-midnight-50 text-midnight-800 text-sm font-semibold mb-4">
            <span class="w-2 h-2 rounded-full bg-midnight-600 animate-pulse"></span>
            Latest Updates
          </div>
          <h2 class="text-3xl md:text-4xl font-serif font-bold text-midnight-950">{{ __('app.nav.news') }} &amp; {{ __('app.nav.events') }}</h2>
        </div>
        <a href="{{ route('front.news.index') }}" class="btn btn-outline shrink-0 group">
          {{ __('app.btn.view_all') }}
          <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
        </a>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($latest_news as $item)
          @php
              $thumbUrl = $item->thumbnail;
              if ($thumbUrl && !\Illuminate\Support\Str::startsWith($thumbUrl, ['http://', 'https://'])) {
                  $thumbUrl = \Illuminate\Support\Str::startsWith($thumbUrl, '/storage/') 
                      ? $thumbUrl 
                      : Storage::url($thumbUrl);
              }
          @endphp
          <div class="card group flex flex-col h-full bg-white rounded-2xl shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-midnight-900/5 transition-all duration-300 overflow-hidden">
            
            {{-- Thumbnail --}}
            <div class="relative h-56 bg-slate-100 overflow-hidden shrink-0">
              @if($thumbUrl)
                <img src="{{ $thumbUrl }}" alt="{{ $item->trans('title') }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
              @else
                <div class="w-full h-full flex items-center justify-center bg-slate-200 text-slate-400">
                  <i class="fas fa-newspaper text-5xl"></i>
                </div>
              @endif
              
              {{-- Category Badge --}}
              @if($item->category)
                <div class="absolute bottom-4 left-4">
                  <span class="px-3 py-1 bg-midnight-900/80 backdrop-blur-md text-white text-xs font-bold rounded-lg shadow-sm">
                    {{ $item->category->trans('name') }}
                  </span>
                </div>
              @endif
            </div>
            
            {{-- Content --}}
            <div class="p-6 flex flex-col flex-grow">
              <div class="flex items-center gap-4 text-xs text-slate-400 font-medium uppercase tracking-wider mb-3">
                <span class="flex items-center gap-1"><i class="far fa-calendar text-gold-500"></i> {{ \Carbon\Carbon::parse($item->published_at)->format('d M Y') }}</span>
              </div>
              
              <h3 class="text-xl font-serif font-bold text-midnight-950 mb-3 line-clamp-2 group-hover:text-midnight-600 transition-colors">
                <a href="{{ route('front.news.show', $item->slug) }}" class="focus:outline-none">
                  <span class="absolute inset-0" aria-hidden="true"></span>
                  {{ $item->trans('title') }}
                </a>
              </h3>
              
              <p class="text-slate-500 text-sm mb-6 line-clamp-3 flex-grow">{{ $item->trans('excerpt') }}</p>
              
              <div class="mt-auto pt-4 border-t border-slate-100 flex items-center text-midnight-600 font-bold text-sm group-hover:text-gold-600 transition-colors">
                {{ __('app.btn.read_more') ?? 'Read More' }} <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
              </div>
            </div>

          </div>
        @endforeach
      </div>

    </div>
  </section>

  {{-- Partners --}}
  @include('front._partials.partners', ['partners' => $partners])

@endsection
