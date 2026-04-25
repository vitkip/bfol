@extends('front.layouts.app')

@section('title', __('app.nav.media') . ' - ' . __('app.site_name'))

@section('content')

{{-- Header Section --}}
<section class="bg-midnight-950 py-16 md:py-24 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-midnight-900 to-midnight-950"></div>
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-gold-500/10 blur-[100px] rounded-full pointer-events-none"></div>
    
    <div class="container relative z-10 text-center">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-serif font-bold text-white mb-6">{{ __('app.nav.media') }}</h1>
        <p class="text-lg text-slate-300 max-w-2xl mx-auto">Explore our gallery of photos, videos, and official documents from various events and activities.</p>
    </div>
</section>

<section class="py-16 bg-slate-50">
    <div class="container">
        
        {{-- Filters (Categories) --}}
        <div class="flex flex-wrap items-center justify-center gap-3 mb-12">
            <a href="{{ route('front.media.index') }}" 
               class="px-5 py-2 rounded-full text-sm font-medium transition-all duration-300 {{ !request('category') ? 'bg-midnight-900 text-white shadow-md shadow-midnight-900/20' : 'bg-white text-slate-600 border border-slate-200 hover:border-midnight-300 hover:text-midnight-900' }}">
                All Media
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('front.media.index', ['category' => $cat->slug]) }}" 
                   class="px-5 py-2 rounded-full text-sm font-medium transition-all duration-300 {{ request('category') === $cat->slug ? 'bg-midnight-900 text-white shadow-md shadow-midnight-900/20' : 'bg-white text-slate-600 border border-slate-200 hover:border-midnight-300 hover:text-midnight-900' }}">
                    {{ $cat->trans('name') }}
                </a>
            @endforeach
        </div>

        {{-- Media Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @forelse($items as $item)
                <div class="card group flex flex-col h-full bg-white rounded-2xl shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-midnight-900/5 transition-all duration-300 overflow-hidden">
                    
                    {{-- Thumbnail --}}
                    <div class="relative h-56 bg-slate-100 overflow-hidden shrink-0">
                        @if($item->thumbnail_url)
                            <img src="{{ Storage::url($item->thumbnail_url) }}" alt="{{ $item->trans('title') }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-slate-200 text-slate-400">
                                <i class="fas fa-image text-5xl"></i>
                            </div>
                        @endif

                        {{-- Type Icon Overlay --}}
                        <div class="absolute top-4 right-4 w-10 h-10 rounded-full bg-white/90 backdrop-blur-md shadow-sm flex items-center justify-center text-midnight-900">
                            @if($item->type === 'video')
                                <i class="fas fa-play ml-1"></i>
                            @elseif($item->type === 'document')
                                <i class="fas fa-file-pdf"></i>
                            @else
                                <i class="fas fa-image"></i>
                            @endif
                        </div>
                        
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
                        <h3 class="text-lg font-serif font-bold text-midnight-950 mb-3 line-clamp-2">{{ $item->trans('title') }}</h3>
                        
                        @if($item->trans('description'))
                            <p class="text-slate-500 text-sm mb-6 line-clamp-2 flex-grow">{{ $item->trans('description') }}</p>
                        @endif
                        
                        {{-- Actions --}}
                        <div class="mt-auto pt-4 border-t border-slate-100 flex items-center gap-3">
                            @if($item->external_url)
                                <a href="{{ $item->external_url }}" target="_blank" class="btn btn-outline text-xs py-2 w-full">
                                    <i class="fas fa-external-link-alt mr-2"></i> View Link
                                </a>
                            @endif
                            @if($item->file_url)
                                <a href="{{ Storage::url($item->file_url) }}" download class="btn btn-primary text-xs py-2 w-full">
                                    <i class="fas fa-download mr-2"></i> Download
                                </a>
                            @endif
                        </div>
                    </div>

                </div>
            @empty
                <div class="col-span-full py-20 text-center">
                    <div class="w-24 h-24 mx-auto bg-slate-100 rounded-full flex items-center justify-center mb-6 text-slate-300">
                        <i class="fas fa-folder-open text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-midnight-950 mb-2">No Media Found</h3>
                    <p class="text-slate-500">There are currently no media items in this category.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($items->hasPages())
            <div class="mt-16 flex justify-center">
                {{ $items->links() }}
            </div>
        @endif

    </div>
</section>

@endsection
