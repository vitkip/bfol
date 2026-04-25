@extends('front.layouts.app')

@section('title', $item->trans('title') . ' - ' . __('app.nav.events'))

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
                <span class="flex items-center gap-2"><i class="far fa-clock text-gold-500"></i> {{ \Carbon\Carbon::parse($item->start_date)->format('d M Y') }}</span>
                @if($item->location)
                    <span class="flex items-center gap-2"><i class="fas fa-map-marker-alt text-gold-500"></i> {{ $item->location }}</span>
                @endif
                <span class="flex items-center gap-2">
                    <i class="fas fa-circle text-[8px] {{ $item->status === 'published' ? 'text-green-500' : 'text-slate-500' }}"></i> 
                    {{ ucfirst($item->status) }}
                </span>
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
                
                {{-- Event Details Meta Grid --}}
                <div class="p-8 md:p-12 border-b border-slate-100 bg-slate-50/50">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">When</h4>
                            <p class="text-midnight-950 font-medium">
                                {{ \Carbon\Carbon::parse($item->start_date)->format('D, d M Y') }} 
                                @if($item->end_date && $item->end_date !== $item->start_date)
                                    - {{ \Carbon\Carbon::parse($item->end_date)->format('D, d M Y') }}
                                @endif
                                <br>
                                <span class="text-slate-500">{{ $item->start_time ?? 'TBA' }} @if($item->end_time) - {{ $item->end_time }} @endif</span>
                            </p>
                        </div>
                        
                        @if($item->location)
                        <div>
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Where</h4>
                            <p class="text-midnight-950 font-medium">
                                {{ $item->location }}<br>
                                @if($item->country) <span class="text-slate-500">{{ $item->country }}</span> @endif
                            </p>
                        </div>
                        @endif

                        @if($item->organizer)
                        <div>
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Organizer</h4>
                            <p class="text-midnight-950 font-medium">{{ $item->trans('organizer') }}</p>
                        </div>
                        @endif

                        @if($item->registration_deadline || $item->max_participants)
                        <div>
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Registration</h4>
                            <p class="text-midnight-950 font-medium">
                                @if($item->registration_deadline)
                                    Deadline: <span class="text-red-500">{{ \Carbon\Carbon::parse($item->registration_deadline)->format('d M Y') }}</span><br>
                                @endif
                                @if($item->max_participants)
                                    Capacity: <span class="text-slate-500">{{ $item->max_participants }} participants</span>
                                @endif
                            </p>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="p-8 md:p-12 prose prose-slate max-w-none prose-headings:font-serif prose-headings:text-midnight-950 prose-a:text-gold-600 hover:prose-a:text-gold-500 prose-img:rounded-xl">
                    {!! $item->trans('description') !!}
                </div>
                
                <div class="p-8 md:p-12 border-t border-slate-100 bg-slate-50 flex flex-col md:flex-row items-center justify-between gap-6">
                    <a href="{{ route('front.events.index') }}" class="btn btn-outline">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Events
                    </a>
                    
                    <div class="flex items-center gap-4">
                        @if($item->registration_url)
                            <a href="{{ $item->registration_url }}" target="_blank" class="btn btn-primary">
                                Register Now <i class="fas fa-external-link-alt ml-2"></i>
                            </a>
                        @endif
                        <span class="text-sm font-bold text-slate-400 uppercase tracking-widest ml-4 hidden md:block">Share:</span>
                        <a href="#" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-midnight-900 hover:border-midnight-300 transition-colors shadow-sm hidden md:flex"><i class="fab fa-facebook-f"></i></a>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>

@endsection
