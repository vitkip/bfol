<section class="relative z-30 -mt-16 sm:-mt-20 mb-12">
    <div class="container">
        <div class="bg-white rounded-2xl shadow-xl shadow-midnight-900/5 border border-slate-100 p-6 sm:p-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 divide-x divide-slate-100">
                @foreach($statistics as $stat)
                    <div class="flex flex-col items-center text-center px-4 group">
                        <div class="w-12 h-12 rounded-full bg-midnight-50 text-midnight-600 flex items-center justify-center text-xl mb-4 group-hover:-translate-y-1 group-hover:bg-midnight-900 group-hover:text-gold-400 transition-all duration-300">
                            <i class="{{ $stat->icon ?? 'fas fa-chart-line' }}"></i>
                        </div>
                        <div class="flex items-baseline gap-1 mb-1">
                            <h3 class="text-3xl md:text-4xl font-bold font-serif text-midnight-950">{{ $stat->value }}</h3>
                            @if($stat->suffix)
                                <span class="text-lg font-bold text-gold-500">{{ $stat->suffix }}</span>
                            @endif
                        </div>
                        <p class="text-sm font-medium text-slate-500 uppercase tracking-wide">{{ $stat->trans('label') }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
