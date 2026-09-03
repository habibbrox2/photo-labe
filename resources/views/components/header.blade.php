<header 
    class="fixed top-0 left-0 right-0 z-50 transition-all duration-500" 
    x-data="{ 
        mobileOpen: false, 
        scrolled: false, 
        activeDropdown: null,
        searchOpen: false 
    }" 
    x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 50 })" 
    :class="scrolled ? 'glass-dark shadow-2xl shadow-black/10' : 'bg-transparent'"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20 lg:h-24">
            
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 group relative z-10">
                <div class="relative">
                    <div class="w-12 h-12 bg-gradient-to-br from-primary-500 via-primary-600 to-accent-500 rounded-2xl flex items-center justify-center transform group-hover:scale-110 group-hover:rotate-3 transition-all duration-500 shadow-xl shadow-primary-500/30">
                        <span class="text-white font-black text-2xl">P</span>
                    </div>
                    <div class="absolute -inset-2 bg-gradient-to-br from-primary-400 to-accent-400 rounded-2xl opacity-0 group-hover:opacity-30 blur-lg transition-opacity duration-500"></div>
                </div>
                <div class="flex flex-col">
                    <span class="text-2xl font-black tracking-tight leading-none" :class="scrolled ? 'text-white' : 'text-white'">PhotoLabe</span>
                    <span class="text-[10px] font-semibold uppercase tracking-[0.2em] text-white/60">Creative Studio</span>
                </div>
            </a>

            {{-- Desktop Nav --}}
            <nav class="hidden lg:flex items-center gap-1 relative">
                @php
                    $navItems = [
                        ['route' => 'services.index', 'label' => 'Services', 'hasDropdown' => true],
                        ['route' => 'portfolio.index', 'label' => 'Portfolio', 'hasDropdown' => false],
                        ['route' => 'products.index', 'label' => 'Products', 'hasDropdown' => false],
                        ['route' => 'blog.index', 'label' => 'Blog', 'hasDropdown' => false],
                        ['route' => 'about', 'label' => 'About', 'hasDropdown' => false],
                        ['route' => 'contact', 'label' => 'Contact', 'hasDropdown' => false],
                    ];
                @endphp
                
                @foreach($navItems as $item)
                    <div 
                        class="relative" 
                        @mouseenter="$item['hasDropdown'] ? activeDropdown = '{{ $item['route'] }}' : null"
                        @mouseleave="activeDropdown = null"
                    >
                        <a 
                            href="{{ route($item['route']) }}" 
                            class="relative px-4 py-2 text-sm font-semibold transition-all duration-300 rounded-xl flex items-center gap-1.5 hover:bg-white/10 {{ request()->routeIs($item['route']) ? 'text-white bg-white/10' : 'text-white/80 hover:text-white' }}"
                        >
                            {{ $item['label'] }}
                            @if($item['hasDropdown'])
                                <svg class="w-3.5 h-3.5 transition-transform duration-300" :class="activeDropdown === '{{ $item['route'] }}' ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            @endif
                            @if(request()->routeIs($item['route']))
                                <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1.5 h-1.5 bg-accent-400 rounded-full"></span>
                            @endif
                        </a>

                        {{-- Services Mega Menu --}}
                        @if($item['hasDropdown'] && $item['route'] === 'services.index')
                            <div 
                                x-show="activeDropdown === '{{ $item['route'] }}'"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                                class="absolute top-full left-1/2 -translate-x-1/2 pt-4"
                                @mouseenter="activeDropdown = '{{ $item['route'] }}'"
                                @mouseleave="activeDropdown = null"
                                x-cloak
                            >
                                <div class="glass-dark rounded-3xl p-6 shadow-2xl shadow-black/20 w-[600px] border border-white/10">
                                    <div class="grid grid-cols-2 gap-4">
                                        {{-- Main Services --}}
                                        <div>
                                            <div class="text-xs font-bold text-white/40 uppercase tracking-wider mb-3 px-2">Services</div>
                                            <a href="{{ route('services.show', 'professional-photo-retouching') }}" class="flex items-center gap-3 p-3 rounded-2xl hover:bg-white/10 transition-all group">
                                                <div class="w-10 h-10 bg-gradient-to-br from-primary-500/20 to-primary-600/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                                    <svg class="w-5 h-5 text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-semibold text-white group-hover:text-primary-400 transition-colors">Photo Retouching</div>
                                                    <div class="text-xs text-white/50">Professional editing</div>
                                                </div>
                                            </a>
                                            <a href="{{ route('services.show', 'background-removal') }}" class="flex items-center gap-3 p-3 rounded-2xl hover:bg-white/10 transition-all group">
                                                <div class="w-10 h-10 bg-gradient-to-br from-accent-500/20 to-accent-600/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                                    <svg class="w-5 h-5 text-accent-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-semibold text-white group-hover:text-accent-400 transition-colors">Background Removal</div>
                                                    <div class="text-xs text-white/50">Clean edges</div>
                                                </div>
                                            </a>
                                            <a href="{{ route('services.show', 'color-correction') }}" class="flex items-center gap-3 p-3 rounded-2xl hover:bg-white/10 transition-all group">
                                                <div class="w-10 h-10 bg-gradient-to-br from-emerald-500/20 to-emerald-600/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                                    <svg class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-semibold text-white group-hover:text-emerald-400 transition-colors">Color Correction</div>
                                                    <div class="text-xs text-white/50">Professional grading</div>
                                                </div>
                                            </a>
                                        </div>

                                        {{-- More Services & CTA --}}
                                        <div>
                                            <div class="text-xs font-bold text-white/40 uppercase tracking-wider mb-3 px-2">More</div>
                                            <a href="{{ route('services.show', 'jewelry-retouching') }}" class="flex items-center gap-3 p-3 rounded-2xl hover:bg-white/10 transition-all group">
                                                <div class="w-10 h-10 bg-gradient-to-br from-amber-500/20 to-amber-600/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                                    <svg class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-semibold text-white group-hover:text-amber-400 transition-colors">Jewelry Retouching</div>
                                                    <div class="text-xs text-white/50">Specialized service</div>
                                                </div>
                                            </a>
                                            <a href="{{ route('services.show', 'clipping-path') }}" class="flex items-center gap-3 p-3 rounded-2xl hover:bg-white/10 transition-all group">
                                                <div class="w-10 h-10 bg-gradient-to-br from-rose-500/20 to-rose-600/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                                    <svg class="w-5 h-5 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-semibold text-white group-hover:text-rose-400 transition-colors">Clipping Path</div>
                                                    <div class="text-xs text-white/50">Precise masking</div>
                                                </div>
                                            </a>
                                            
                                            {{-- CTA --}}
                                            <div class="mt-4 p-4 bg-gradient-to-br from-primary-600/20 to-accent-600/20 rounded-2xl border border-white/10">
                                                <div class="text-sm font-bold text-white mb-1">Get a Free Quote</div>
                                                <div class="text-xs text-white/60 mb-3">Start your project today</div>
                                                <a href="{{ route('quote.create') }}" class="block text-center px-4 py-2 bg-gradient-to-r from-primary-600 to-accent-600 text-white text-xs font-bold rounded-xl hover:from-primary-700 hover:to-accent-700 transition-all">
                                                    Get Started →
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </nav>

            {{-- Right Actions --}}
            <div class="hidden lg:flex items-center gap-3">
                {{-- Search Button --}}
                <button 
                    @click="searchOpen = !searchOpen" 
                    class="w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-300 hover:bg-white/10"
                    :class="scrolled ? 'text-white/80 hover:text-white' : 'text-white/80 hover:text-white'"
                >
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>

                {{-- Cart Button --}}
                <a href="{{ route('cart.index') }}" class="relative w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-300 hover:bg-white/10 text-white/80 hover:text-white">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <span class="absolute -top-1 -right-1 w-5 h-5 bg-accent-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">0</span>
                </a>

                {{-- Auth --}}
                @auth
                    <a href="{{ route('account.dashboard') }}" class="text-sm font-semibold transition-colors text-white/80 hover:text-white">
                        Dashboard
                    </a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold transition-colors text-white/80 hover:text-white">
                            Admin
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="text-sm font-semibold transition-colors text-white/80 hover:text-white">
                        Login
                    </a>
                @endauth

                {{-- CTA Button --}}
                <a href="{{ route('quote.create') }}" class="btn-modern group inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-primary-600 to-accent-600 text-white text-sm font-bold rounded-2xl hover:from-primary-700 hover:to-accent-700 transition-all shadow-lg shadow-primary-500/25 hover:shadow-xl hover:shadow-primary-500/30 hover:-translate-y-0.5">
                    Get a Quote
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>

            {{-- Mobile Toggle --}}
            <button @click="mobileOpen = !mobileOpen" class="lg:hidden w-11 h-11 rounded-xl flex items-center justify-center transition-all duration-300 bg-white/10 text-white">
                <div class="w-5 h-4 flex flex-col justify-between">
                    <span class="w-full h-0.5 bg-current rounded-full transition-all duration-300" :class="mobileOpen ? 'rotate-45 translate-y-[7px]' : ''"></span>
                    <span class="w-full h-0.5 bg-current rounded-full transition-all duration-300" :class="mobileOpen ? 'opacity-0 scale-0' : ''"></span>
                    <span class="w-full h-0.5 bg-current rounded-full transition-all duration-300" :class="mobileOpen ? '-rotate-45 -translate-y-[7px]' : ''"></span>
                </div>
            </button>
        </div>

        {{-- Search Overlay --}}
        <div 
            x-show="searchOpen" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-4"
            class="absolute top-full left-0 right-0 pt-4"
            @click.away="searchOpen = false"
            x-cloak
        >
            <div class="glass-dark rounded-3xl p-6 shadow-2xl shadow-black/20 border border-white/10">
                <form action="{{ route('services.index') }}" method="GET" class="relative">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-white/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Search services, products, blog..." 
                        class="w-full pl-12 pr-4 py-4 bg-white/10 border border-white/20 rounded-2xl text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                        autofocus
                    >
                    <kbd class="absolute right-4 top-1/2 -translate-y-1/2 px-2 py-1 bg-white/10 rounded-lg text-xs text-white/40">ESC</kbd>
                </form>
                
                <div class="mt-4 flex items-center gap-4">
                    <span class="text-xs font-semibold text-white/40 uppercase tracking-wider">Popular:</span>
                    <a href="{{ route('services.show', 'photo-retouching') }}" class="px-3 py-1.5 bg-white/10 rounded-xl text-xs text-white/70 hover:bg-white/20 hover:text-white transition-all">Photo Retouching</a>
                    <a href="{{ route('services.show', 'background-removal') }}" class="px-3 py-1.5 bg-white/10 rounded-xl text-xs text-white/70 hover:bg-white/20 hover:text-white transition-all">Background Removal</a>
                    <a href="{{ route('services.show', 'color-correction') }}" class="px-3 py-1.5 bg-white/10 rounded-xl text-xs text-white/70 hover:bg-white/20 hover:text-white transition-all">Color Correction</a>
                </div>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div 
            x-show="mobileOpen" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-4"
            class="lg:hidden absolute top-full left-0 right-0 pt-4 pb-6"
            x-cloak
        >
            <div class="glass-dark rounded-3xl p-6 shadow-2xl shadow-black/20 border border-white/10 mx-4">
                <nav class="flex flex-col gap-1">
                    @foreach($navItems as $item)
                        <a href="{{ route($item['route']) }}" class="flex items-center justify-between px-4 py-3.5 text-sm font-semibold text-white/80 hover:bg-white/10 hover:text-white rounded-2xl transition-all {{ request()->routeIs($item['route']) ? 'bg-white/10 text-white' : '' }}">
                            {{ $item['label'] }}
                            <svg class="w-4 h-4 text-white/40" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    @endforeach
                    
                    <div class="border-t border-white/10 mt-3 pt-3">
                        @auth
                            <a href="{{ route('account.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-white/80 hover:bg-white/10 hover:text-white rounded-2xl transition-all">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-white/80 hover:bg-white/10 hover:text-white rounded-2xl transition-all">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                                Login
                            </a>
                            <a href="{{ route('register') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-white/80 hover:bg-white/10 hover:text-white rounded-2xl transition-all">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                                Register
                            </a>
                        @endauth
                        
                        <a href="{{ route('cart.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-white/80 hover:bg-white/10 hover:text-white rounded-2xl transition-all">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            Cart
                            <span class="ml-auto px-2 py-0.5 bg-accent-500 text-white text-xs font-bold rounded-full">0</span>
                        </a>
                    </div>
                    
                    <a href="{{ route('quote.create') }}" class="mt-4 block text-center px-6 py-4 bg-gradient-to-r from-primary-600 to-accent-600 text-white text-sm font-bold rounded-2xl shadow-lg shadow-primary-500/25 hover:from-primary-700 hover:to-accent-700 transition-all">
                        Get a Free Quote
                    </a>
                </nav>
            </div>
        </div>
    </div>
</header>

{{-- Spacer for fixed header --}}
<div class="h-20 lg:h-24"></div>
