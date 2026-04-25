@extends('front.layouts.app')

@section('title', __('app.contact.title') . ' - ' . __('app.site_name'))

@section('content')

{{-- Header Section --}}
<section class="bg-midnight-950 py-16 md:py-24 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-midnight-900 to-midnight-950"></div>
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-gold-500/10 blur-[100px] rounded-full pointer-events-none"></div>
    
    <div class="container relative z-10 text-center max-w-3xl mx-auto">
        <h1 class="text-4xl md:text-5xl font-serif font-bold text-white mb-6">{{ __('app.contact.title') ?? 'Contact Us' }}</h1>
        <p class="text-lg text-slate-300">Get in touch with the Lao Buddhist Association Foreign Affairs Committee. We are here to answer your questions and hear your suggestions.</p>
    </div>
</section>

{{-- Content Section --}}
<section class="py-16 bg-slate-50 relative -mt-10 z-20">
    <div class="container max-w-6xl mx-auto">
        
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
            
            {{-- Contact Information Cards --}}
            <div class="lg:col-span-2 flex flex-col gap-6">
                
                <div class="bg-white rounded-2xl p-8 shadow-xl shadow-midnight-900/5 border border-slate-100 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-gold-500/10 rounded-bl-[100px] -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                    <div class="w-12 h-12 rounded-xl bg-midnight-50 text-midnight-600 flex items-center justify-center text-xl mb-6">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3 class="text-lg font-bold text-midnight-950 mb-2">Our Location</h3>
                    <p class="text-slate-500 leading-relaxed">
                        {{ \App\Models\SiteSetting::get('site_address_'.app()->getLocale()) ?? \App\Models\SiteSetting::get('site_address_lo') ?? 'Vientiane, Lao PDR' }}
                    </p>
                </div>
                
                <div class="bg-white rounded-2xl p-8 shadow-xl shadow-midnight-900/5 border border-slate-100 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-gold-500/10 rounded-bl-[100px] -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                    <div class="w-12 h-12 rounded-xl bg-midnight-50 text-midnight-600 flex items-center justify-center text-xl mb-6">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h3 class="text-lg font-bold text-midnight-950 mb-2">Email Address</h3>
                    <p class="text-slate-500 leading-relaxed">
                        <a href="mailto:{{ \App\Models\SiteSetting::get('site_email') }}" class="hover:text-gold-600 transition-colors">
                            {{ \App\Models\SiteSetting::get('site_email') }}
                        </a>
                    </p>
                </div>
                
                <div class="bg-white rounded-2xl p-8 shadow-xl shadow-midnight-900/5 border border-slate-100 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-gold-500/10 rounded-bl-[100px] -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                    <div class="w-12 h-12 rounded-xl bg-midnight-50 text-midnight-600 flex items-center justify-center text-xl mb-6">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <h3 class="text-lg font-bold text-midnight-950 mb-2">Phone Number</h3>
                    <p class="text-slate-500 leading-relaxed">
                        <a href="tel:{{ \App\Models\SiteSetting::get('site_phone') }}" class="hover:text-gold-600 transition-colors">
                            {{ \App\Models\SiteSetting::get('site_phone') }}
                        </a>
                    </p>
                </div>
                
            </div>
            
            {{-- Contact Form --}}
            <div class="lg:col-span-3">
                <div class="bg-white rounded-2xl p-8 md:p-12 shadow-xl shadow-midnight-900/5 border border-slate-100 h-full">
                    
                    <div class="mb-8">
                        <h2 class="text-2xl font-serif font-bold text-midnight-950 mb-2">Send us a message</h2>
                        <p class="text-slate-500">Fill out the form below and our team will get back to you as soon as possible.</p>
                    </div>

                    @if(session('success'))
                        <div class="mb-8 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 flex items-start gap-3">
                            <i class="fas fa-check-circle mt-1 text-green-500"></i>
                            <div>
                                <h4 class="font-bold">Message Sent!</h4>
                                <p class="text-sm mt-1">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('front.contact.submit') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="language" value="{{ app()->getLocale() }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Name --}}
                            <div>
                                <label class="block text-sm font-bold text-midnight-950 mb-2">{{ __('app.contact.name') ?? 'Full Name' }} <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <input type="text" name="name" value="{{ old('name') }}" required
                                        class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-midnight-500 focus:ring-2 focus:ring-midnight-500/20 transition-all outline-none @error('name') border-red-500 focus:border-red-500 focus:ring-red-500/20 @enderror"
                                        placeholder="John Doe">
                                </div>
                                @error('name')<p class="mt-2 text-xs text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>@enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label class="block text-sm font-bold text-midnight-950 mb-2">{{ __('app.contact.email') ?? 'Email Address' }} <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <input type="email" name="email" value="{{ old('email') }}" required
                                        class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-midnight-500 focus:ring-2 focus:ring-midnight-500/20 transition-all outline-none @error('email') border-red-500 focus:border-red-500 focus:ring-red-500/20 @enderror"
                                        placeholder="john@example.com">
                                </div>
                                @error('email')<p class="mt-2 text-xs text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Phone --}}
                            <div>
                                <label class="block text-sm font-bold text-midnight-950 mb-2">{{ __('app.contact.phone') ?? 'Phone Number' }}</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                        <i class="fas fa-phone-alt"></i>
                                    </div>
                                    <input type="text" name="phone" value="{{ old('phone') }}"
                                        class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-midnight-500 focus:ring-2 focus:ring-midnight-500/20 transition-all outline-none"
                                        placeholder="+856 20...">
                                </div>
                            </div>

                            {{-- Subject --}}
                            <div>
                                <label class="block text-sm font-bold text-midnight-950 mb-2">{{ __('app.contact.subject') ?? 'Subject' }}</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                        <i class="fas fa-tag"></i>
                                    </div>
                                    <input type="text" name="subject" value="{{ old('subject') }}"
                                        class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-midnight-500 focus:ring-2 focus:ring-midnight-500/20 transition-all outline-none"
                                        placeholder="How can we help?">
                                </div>
                            </div>
                        </div>

                        {{-- Message --}}
                        <div>
                            <label class="block text-sm font-bold text-midnight-950 mb-2">{{ __('app.contact.message') ?? 'Your Message' }} <span class="text-red-500">*</span></label>
                            <textarea name="message" rows="5" required
                                class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-midnight-500 focus:ring-2 focus:ring-midnight-500/20 transition-all outline-none resize-none @error('message') border-red-500 focus:border-red-500 focus:ring-red-500/20 @enderror"
                                placeholder="Type your message here...">{{ old('message') }}</textarea>
                            @error('message')<p class="mt-2 text-xs text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>@enderror
                        </div>

                        {{-- Submit Button --}}
                        <div class="pt-4 flex justify-end">
                            <button type="submit" class="btn btn-primary px-8 py-3 group">
                                <span>{{ __('app.btn.submit') ?? 'Send Message' }}</span>
                                <i class="fas fa-paper-plane ml-2 group-hover:-translate-y-1 group-hover:translate-x-1 transition-transform"></i>
                            </button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
        
    </div>
</section>

@endsection
