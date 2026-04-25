@extends('front.layouts.app')

@section('title', $item->trans('title') . ' - ' . __('app.nav.news'))

@section('content')

@php
    $thumbUrl = $item->thumbnail;
    if ($thumbUrl && !\Illuminate\Support\Str::startsWith($thumbUrl, ['http://', 'https://'])) {
        $thumbUrl = \Illuminate\Support\Str::startsWith($thumbUrl, '/storage/') 
            ? $thumbUrl 
            : Storage::url($thumbUrl);
    }
@endphp

{{-- Header Section --}}
<section class="bg-midnight-950 py-16 md:py-24 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-midnight-900 to-midnight-950"></div>
    @if($thumbUrl)
        <div class="absolute inset-0 opacity-20">
            <img src="{{ $thumbUrl }}" class="w-full h-full object-cover blur-sm" alt="Background">
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-midnight-950 via-midnight-950/80 to-transparent"></div>
    @endif
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-gold-500/10 blur-[100px] rounded-full pointer-events-none"></div>
    
    <div class="container relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            @if($item->category)
                <span class="inline-block px-4 py-1 bg-gold-500 text-midnight-950 text-xs font-bold uppercase tracking-widest rounded-full mb-6 shadow-lg shadow-gold-500/20">
                    {{ $item->category->trans('name') }}
                </span>
            @endif
            
            <h1 class="text-3xl md:text-5xl font-serif font-bold text-white mb-6 leading-tight">{{ $item->trans('title') }}</h1>
            
            <div class="flex flex-wrap items-center justify-center gap-6 text-sm text-slate-300 font-medium">
                <span class="flex items-center gap-2"><i class="far fa-calendar text-gold-500"></i> {{ \Carbon\Carbon::parse($item->published_at)->format('d M Y') }}</span>
                <span class="flex items-center gap-2"><i class="far fa-eye text-gold-500"></i> {{ $item->view_count ?? 0 }} Views</span>
            </div>
        </div>
    </div>
</section>

{{-- Content Section --}}
<section class="py-16 bg-slate-50 relative -mt-10 z-20">
    <div class="container">
        <div class="max-w-4xl mx-auto">
            
            <div class="bg-white rounded-2xl shadow-xl shadow-midnight-900/5 border border-slate-100 overflow-hidden">
                @if($thumbUrl)
                    <div class="w-full h-[400px] md:h-[500px]">
                        <img src="{{ $thumbUrl }}" alt="{{ $item->trans('title') }}" class="w-full h-full object-cover">
                    </div>
                @endif
                
                <div class="p-8 md:p-12 prose prose-slate max-w-none prose-headings:font-serif prose-headings:text-midnight-950 prose-a:text-gold-600 hover:prose-a:text-gold-500 prose-img:rounded-xl">
                    {!! $item->trans('content') !!}
                </div>
                
                <div class="p-8 md:p-12 border-t border-slate-100 bg-slate-50 flex items-center justify-between">
                    <a href="{{ route('front.news.index') }}" class="btn btn-outline">
                        <i class="fas fa-arrow-left mr-2"></i> Back to News
                    </a>
                    
                    <div class="flex items-center gap-4">
                        <span class="text-sm font-bold text-slate-400 uppercase tracking-widest">Share:</span>
                        <a href="#" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-midnight-900 hover:border-midnight-300 transition-colors shadow-sm"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-midnight-900 hover:border-midnight-300 transition-colors shadow-sm"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>

{{-- Related News Section --}}
@if($related->isNotEmpty())
<section class="py-16 bg-white border-t border-slate-200">
    <div class="container">
        <div class="max-w-4xl mx-auto">
            <h3 class="text-2xl font-serif font-bold text-midnight-950 mb-8 flex items-center gap-3">
                <i class="fas fa-newspaper text-gold-500"></i> Related News
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($related as $rel)
                    @php
                        $relThumbUrl = $rel->thumbnail;
                        if ($relThumbUrl && !\Illuminate\Support\Str::startsWith($relThumbUrl, ['http://', 'https://'])) {
                            $relThumbUrl = \Illuminate\Support\Str::startsWith($relThumbUrl, '/storage/') 
                                ? $relThumbUrl 
                                : Storage::url($relThumbUrl);
                        }
                    @endphp
                    <a href="{{ route('front.news.show', $rel->slug) }}" class="group flex gap-4 bg-slate-50 rounded-xl p-4 border border-slate-100 hover:shadow-md hover:border-midnight-200 transition-all">
                        <div class="w-24 h-24 rounded-lg bg-slate-200 overflow-hidden shrink-0">
                            @if($relThumbUrl)
                                <img src="{{ $relThumbUrl }}" alt="{{ $rel->trans('title') }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-400"><i class="fas fa-image"></i></div>
                            @endif
                        </div>
                        <div class="flex flex-col justify-center">
                            <h4 class="text-sm font-bold text-midnight-950 group-hover:text-gold-600 transition-colors line-clamp-2 mb-2">{{ $rel->trans('title') }}</h4>
                            <span class="text-xs text-slate-400 font-medium"><i class="far fa-calendar mr-1"></i> {{ \Carbon\Carbon::parse($rel->published_at)->format('d M Y') }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

@endsection
