<section class="py-20 bg-slate-50 border-t border-slate-200 overflow-hidden">
    <div class="container text-center mb-10">
        <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Trusted By International Organizations</p>
    </div>
    
    <div class="relative w-full overflow-hidden flex flex-col gap-8">
        {{-- Fading Edges --}}
        <div class="absolute inset-y-0 left-0 w-24 bg-gradient-to-r from-slate-50 to-transparent z-10 pointer-events-none"></div>
        <div class="absolute inset-y-0 right-0 w-24 bg-gradient-to-l from-slate-50 to-transparent z-10 pointer-events-none"></div>

        <div class="flex w-max animate-scroll-x hover:[animation-play-state:paused]">
            {{-- We duplicate the list to make it scroll infinitely smoothly --}}
            @for($i = 0; $i < 2; $i++)
                <div class="flex gap-12 px-6 items-center justify-center">
                    @foreach($partners as $partner)
                        <a href="{{ $partner->website_url ?? '#' }}" 
                           target="_blank" 
                           title="{{ $partner->trans('name') }} ({{ $partner->trans('country_name') }})"
                           class="group relative flex flex-col items-center justify-center w-32 h-16 shrink-0 grayscale opacity-60 hover:grayscale-0 hover:opacity-100 transition-all duration-300">
                            @if($partner->logo_url)
                                @php
                                    $logo = Str::startsWith($partner->logo_url, ['http', '/storage']) 
                                        ? asset($partner->logo_url) 
                                        : Storage::url($partner->logo_url);
                                @endphp
                                <img src="{{ $logo }}" alt="{{ $partner->trans('name') }}" class="max-w-full max-h-full object-contain">
                            @else
                                <span class="font-bold text-base text-slate-500 group-hover:text-midnight-900 text-center leading-tight">{{ $partner->trans('name') }}</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            @endfor
        </div>
    </div>
</section>

<style>
@keyframes scroll-x {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}
.animate-scroll-x {
    animation: scroll-x 30s linear infinite;
}
</style>
