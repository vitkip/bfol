@extends('front.layouts.app')

@section('title', __('app.contact.title') . ' — ' . __('app.site_short'))

@section('content')
<section class="contact-section section">
  <div class="container">
    <div class="section-header">
      <h2>{{ __('app.contact.title') }}</h2>
    </div>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ url(app()->getLocale() . '/contact') }}" method="POST" class="contact-form">
      @csrf
      <input type="hidden" name="language" value="{{ app()->getLocale() }}">

      <div class="form-group">
        <label>{{ __('app.contact.name') }} *</label>
        <input type="text" name="name" value="{{ old('name') }}" required>
        @error('name')<span class="error">{{ $message }}</span>@enderror
      </div>

      <div class="form-group">
        <label>{{ __('app.contact.email') }} *</label>
        <input type="email" name="email" value="{{ old('email') }}" required>
        @error('email')<span class="error">{{ $message }}</span>@enderror
      </div>

      <div class="form-group">
        <label>{{ __('app.contact.phone') }}</label>
        <input type="text" name="phone" value="{{ old('phone') }}">
      </div>

      <div class="form-group">
        <label>{{ __('app.contact.subject') }}</label>
        <input type="text" name="subject" value="{{ old('subject') }}">
      </div>

      <div class="form-group">
        <label>{{ __('app.contact.message') }} *</label>
        <textarea name="message" rows="6" required>{{ old('message') }}</textarea>
        @error('message')<span class="error">{{ $message }}</span>@enderror
      </div>

      <button type="submit" class="btn btn-primary">{{ __('app.btn.submit') }}</button>
    </form>
  </div>
</section>
@endsection
