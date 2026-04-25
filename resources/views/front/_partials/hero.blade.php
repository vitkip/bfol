<section class="relative h-[600px] md:h-[700px] bg-midnight-950 overflow-hidden" 
         x-data="heroSlider()" 
         x-init="start()"
         @mouseenter="pause()"
         @mouseleave="resume()">
    
    <template x-for="(slide, index) in slides" :key="index">
        <div class="absolute inset-0 transition-opacity duration-1000 ease-in-out"
             :class="active === index ? 'opacity-100 z-10' : 'opacity-0 z-0'">
             
            <!-- Background Image -->
            <div class="absolute inset-0 bg-midnight-900">
                <template x-if="slide.image_url">
                    <img :src="slide.image_url" :alt="slide.title" class="w-full h-full object-cover opacity-50 mix-blend-overlay">
                </template>
                <template x-if="!slide.image_url">
                    <div class="absolute inset-0 bg-gradient-to-br from-midnight-900 via-midnight-800 to-midnight-950">
                        <div class="absolute top-1/4 left-1/4 w-[500px] h-[500px] rounded-full bg-gold-500/10 blur-[120px]"></div>
                        <div class="absolute bottom-1/4 right-1/4 w-[400px] h-[400px] rounded-full bg-midnight-500/20 blur-[100px]"></div>
                    </div>
                </template>
            </div>

            <!-- Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-midnight-950 via-transparent to-transparent"></div>

            <!-- Content -->
            <div class="relative z-20 h-full container flex items-center">
                <div class="max-w-3xl pt-20"
                     x-show="active === index"
                     x-transition:enter="transition ease-out duration-1000 delay-300"
                     x-transition:enter-start="opacity-0 translate-y-8"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    
                    <template x-if="slide.tag">
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-lg bg-white/10 backdrop-blur-md border border-white/10 mb-6 shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-gold-400 animate-pulse"></span>
                            <span class="text-white text-sm font-semibold tracking-wide uppercase" x-text="slide.tag"></span>
                        </div>
                    </template>

                    <h2 class="text-4xl md:text-5xl lg:text-6xl font-serif font-bold text-white leading-tight mb-6 drop-shadow-xl" x-text="slide.title"></h2>
                    
                    <template x-if="slide.subtitle">
                        <p class="text-lg md:text-xl text-slate-300 mb-10 max-w-2xl leading-relaxed" x-text="slide.subtitle"></p>
                    </template>

                    <div class="flex flex-wrap gap-4">
                        <template x-if="slide.btn1_text">
                            <a :href="slide.btn1_url" class="btn btn-gold text-base px-8 py-3.5 shadow-xl shadow-gold-500/20 hover:-translate-y-0.5">
                                <span x-text="slide.btn1_text"></span>
                            </a>
                        </template>
                        <template x-if="slide.btn2_text">
                            <a :href="slide.btn2_url" class="btn bg-white/10 text-white border border-white/20 backdrop-blur-md hover:bg-white/20 text-base px-8 py-3.5 hover:-translate-y-0.5 transition-all">
                                <span x-text="slide.btn2_text"></span>
                            </a>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <!-- Controls -->
    <div class="absolute bottom-10 left-0 right-0 z-30 container flex justify-between items-end pointer-events-none">
        
        <!-- Dots -->
        <div class="flex gap-3 pointer-events-auto">
            <template x-for="(slide, index) in slides" :key="index">
                <button @click="goTo(index)" 
                        class="h-2 rounded-full transition-all duration-300 focus:outline-none"
                        :class="active === index ? 'w-10 bg-gold-400' : 'w-2 bg-white/40 hover:bg-white/60'"></button>
            </template>
        </div>

        <!-- Arrows & Counter -->
        <div class="flex items-center gap-6 pointer-events-auto bg-midnight-900/50 backdrop-blur-lg border border-white/10 rounded-2xl p-2 shadow-xl">
            <button @click="prev()" class="w-10 h-10 flex items-center justify-center rounded-xl text-white/70 hover:text-white hover:bg-white/10 transition-colors">
                <i class="fas fa-chevron-left"></i>
            </button>
            <div class="text-white font-medium font-serif">
                <span x-text="String(active + 1).padStart(2, '0')" class="text-gold-400"></span>
                <span class="text-white/30 mx-1">/</span>
                <span x-text="String(slides.length).padStart(2, '0')" class="text-white/50"></span>
            </div>
            <button @click="next()" class="w-10 h-10 flex items-center justify-center rounded-xl text-white/70 hover:text-white hover:bg-white/10 transition-colors">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
</section>

@php
$heroSlidesData = $slides->map(function($s) {
    return [
        'title' => $s->trans('title'),
        'subtitle' => $s->trans('subtitle'),
        'tag' => $s->trans('tag'),
        'image_url' => $s->image_url ? Storage::url($s->image_url) : null,
        'btn1_text' => $s->trans('btn1_text'),
        'btn1_url' => $s->btn1_url ?? '#',
        'btn2_text' => $s->trans('btn2_text'),
        'btn2_url' => $s->btn2_url ?? '#'
    ];
})->values();
@endphp
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('heroSlider', () => ({
        active: 0,
        interval: null,
        slides: @json($heroSlidesData),
        start() {
            this.resume();
        },
        pause() {
            clearInterval(this.interval);
        },
        resume() {
            this.pause();
            this.interval = setInterval(() => {
                this.next();
            }, 6000);
        },
        next() {
            this.active = (this.active + 1) % this.slides.length;
        },
        prev() {
            this.active = (this.active - 1 + this.slides.length) % this.slides.length;
        },
        goTo(index) {
            this.active = index;
            this.resume();
        }
    }));
});
</script>
