@extends('front.layouts.app')

@section('title', 'Search Results - ' . __('app.site_name'))

@section('content')

{{-- Header Section --}}
<section class="bg-midnight-950 py-16 md:py-24 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-midnight-900 to-midnight-950"></div>
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-gold-500/10 blur-[100px] rounded-full pointer-events-none"></div>
    
    <div class="container relative z-10 text-center max-w-3xl mx-auto">
        <h1 class="text-4xl md:text-5xl font-serif font-bold text-white mb-6">Search Results</h1>
        <p class="text-lg text-slate-300">Found {{ count($results) ?? 0 }} results for "<span class="text-gold-400 font-bold">{{ $q }}</span>"</p>
        
        <form action="{{ route('front.search') }}" method="GET" class="mt-8 relative max-w-xl mx-auto">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                <i class="fas fa-search"></i>
            </div>
            <input type="text" name="q" value="{{ $q }}" placeholder="Search again..." 
                   class="w-full pl-12 pr-4 py-4 bg-white/10 backdrop-blur-md border border-white/20 rounded-full text-white placeholder-slate-400 focus:bg-white focus:text-midnight-950 focus:placeholder-slate-500 transition-all shadow-xl outline-none">
            <button type="submit" class="absolute inset-y-2 right-2 px-6 bg-gold-500 hover:bg-gold-400 text-midnight-950 font-bold rounded-full transition-colors">
                Search
            </button>
        </form>
    </div>
</section>

{{-- Content Section --}}
<section class="py-16 bg-slate-50">
    <div class="container max-w-4xl mx-auto">
        
        @if(count($results) > 0)
            <div class="flex flex-col gap-6">
                @foreach($results as $result)
                    @php
                        $type = class_basename($result);
                        
                        $link = '#';
                        $icon = 'fas fa-file-alt';
                        $badgeColor = 'bg-slate-100 text-slate-600';
                        
                        if ($type === 'News') {
                            $link = route('front.news.show', $result->slug);
                            $icon = 'fas fa-newspaper';
                            $badgeColor = 'bg-blue-100 text-blue-700';
                        } elseif ($type === 'Event') {
                            $link = route('front.events.show', $result->slug);
                            $icon = 'far fa-calendar-alt';
                            $badgeColor = 'bg-purple-100 text-purple-700';
                        } elseif ($type === 'Page') {
                            $link = route('front.page.show', $result->slug);
                            $icon = 'fas fa-file-signature';
                            $badgeColor = 'bg-green-100 text-green-700';
                        }
                    @endphp
                    
                    <a href="{{ $link }}" class="group block bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-slate-100 hover:shadow-lg hover:border-midnight-200 transition-all duration-300">
                        <div class="flex items-start gap-4 md:gap-6">
                            
                            <div class="w-12 h-12 md:w-16 md:h-16 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 shrink-0 group-hover:bg-midnight-50 group-hover:text-midnight-600 transition-colors">
                                <i class="{{ $icon }} text-xl md:text-2xl"></i>
                            </div>
                            
                            <div class="flex-grow">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="px-2 py-1 text-[10px] font-bold uppercase tracking-wider rounded-md {{ $badgeColor }}">
                                        {{ $type }}
                                    </span>
                                </div>
                                <h3 class="text-xl font-serif font-bold text-midnight-950 mb-2 group-hover:text-gold-600 transition-colors">
                                    {{ $result->trans('title') ?? $result->trans('name') }}
                                </h3>
                                <p class="text-slate-500 text-sm line-clamp-2 mb-4">
                                    {{ Str::limit(strip_tags($result->trans('content') ?? $result->trans('description')), 200) }}
                                </p>
                                <span class="text-sm font-bold text-midnight-600 group-hover:text-gold-600 transition-colors inline-flex items-center">
                                    Read Full Result <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                                </span>
                            </div>
                            
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="py-20 text-center bg-white rounded-3xl border border-slate-100 shadow-sm">
                <div class="w-24 h-24 mx-auto bg-slate-50 rounded-full flex items-center justify-center mb-6 text-slate-300">
                    <i class="fas fa-search text-4xl"></i>
                </div>
                <h3 class="text-2xl font-serif font-bold text-midnight-950 mb-4">No results found</h3>
                <p class="text-slate-500 max-w-md mx-auto">
                    We couldn't find anything matching your search for "<span class="font-bold text-midnight-900">{{ $q }}</span>". 
                    Try adjusting your search terms or browse our latest news and events.
                </p>
                <div class="mt-8 flex justify-center gap-4">
                    <a href="{{ route('front.news.index') }}" class="btn btn-outline">Browse News</a>
                    <a href="{{ route('front.events.index') }}" class="btn btn-outline">Browse Events</a>
                </div>
            </div>
        @endif
        
    </div>
</section>

@endsection
