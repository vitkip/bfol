@extends('front.layouts.app')

@section('title', __('app.site_name'))

@section('content')

  {{-- Hero Slider --}}
  @include('front._partials.hero', ['slides' => $slides])

  {{-- Stats Bar --}}
  @include('front._partials.stats', ['statistics' => $statistics])

  {{-- News Section --}}
  <section class="news-section section">
    <div class="container">
      <div class="section-header">
        <h2>{{ __('app.nav.news') }} &amp; {{ __('app.nav.events') }}</h2>
      </div>
      <div class="news-grid">
        @foreach($latest_news as $item)
          <div class="news-card">
            @if($item->thumbnail)
              <img src="{{ Storage::url($item->thumbnail) }}" alt="{{ $item->trans('title') }}">
            @endif
            <div class="news-body">
              <span class="news-cat">{{ $item->category?->trans('name') }}</span>
              <h3><a href="{{ route('news.show', $item->slug) }}">{{ $item->trans('title') }}</a></h3>
              <p>{{ Str::limit($item->trans('excerpt'), 120) }}</p>
              <div class="news-meta">
                <span>{{ $item->published_at?->translatedFormat('d M Y') }}</span>
                <a href="{{ route('news.show', $item->slug) }}">{{ __('app.btn.read_more') }}</a>
              </div>
            </div>
          </div>
        @endforeach
      </div>
      <div class="view-all-wrap">
        <a href="{{ route('news.index') }}" class="btn btn-outline-primary">
          {{ __('app.btn.view_all') }}
        </a>
      </div>
    </div>
  </section>

  {{-- Partners --}}
  @include('front._partials.partners', ['partners' => $partners])

@endsection
