@extends('front.layouts.app')

@php
  $L = app()->getLocale();
  $siteName = match($L) {
    'zh'    => $settings->site_name_zh,
    'en'    => $settings->site_name_en,
    default => $settings->site_name_lo,
  };
@endphp

@section('title', $siteName ?: 'BFOL')

@section('content')

  @include('front.home.hero',          ['slides'      => $slides])
  @include('front.home.stats',         ['statistics'  => $statistics])
  @include('front.home.work-areas')
  @include('front.home.news-section',  ['latest_news' => $latest_news])
  @include('front.home.media-section')
  @include('front.home.partners',      ['partners'    => $partners])

@endsection
