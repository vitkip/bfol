@extends("front.layouts.app")
@section("content")
<div class="container section">
    <h1>[ Raw Data ] Page: {{ $page->trans('title') }}</h1>
    
    <div style="border: 1px solid #ccc; padding: 20px; background: #fafafa; margin-bottom: 30px;">
        <h2>{{ $page->trans('title') }}</h2>
        <p><strong>Meta Title:</strong> {{ $page->trans('meta_title') }} | <strong>Meta Desc:</strong> {{ $page->meta_description }}</p>
        
        @if($page->thumbnail) 
            <img src="{{ Storage::url($page->thumbnail) }}" style="max-width: 300px; display: block; margin-bottom: 20px;"> 
        @endif

        <div style="background: #fff; padding: 15px; border: 1px solid #eee;">
            {!! $page->trans('content') !!}
        </div>
    </div>
</div>
@endsection
