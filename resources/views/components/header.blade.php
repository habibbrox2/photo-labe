<header
    class="fixed top-0 left-0 right-0 z-50 bg-surface-50/85 backdrop-blur-xl border-b border-gray-200/70 transition-all duration-500"
    x-data="{ 
        mobileOpen: false, 
        scrolled: false, 
        activeDropdown: null,
        searchOpen: false 
    }"
    x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 8 }, { passive: true })"
    @keydown.escape.window="activeDropdown = null; mobileOpen = false; searchOpen = false"
    :class="scrolled ? 'shadow-[0_1px_0_0_rgba(0,0,0,0.02),0_10px_30px_-18px_rgba(28,25,23,0.25)]' : ''">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20 lg:h-[4.75rem]">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 group relative z-10" aria-label="PhotoLabe home">
                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-primary-800 via-primary-700 to-accent-500 flex items-center justify-center shadow-sm shadow-primary-800/20 transition-transform duration-300 group-hover:scale-105">
                    <span class="text-white font-black text-lg tracking-tight">P</span>
                </div>
                <span class="flex flex-col leading-none">
                    <span class="text-xl font-black tracking-tight text-gray-900">PhotoLabe</span>
                    <span class="text-[10px] font-semibold uppercase tracking-[0.22em] text-gray-400 mt-0.5">Creative Studio</span>
                </span>
            </a>

            {{-- Desktop Nav --}}
            <nav class="hidden lg:flex items-center gap-0.5" aria-label="Primary">
                @php
                $navItems = [
                ['route' => 'services.index', 'label' => 'Services', 'hasDropdown' => true],
                ['route' => 'portfolio.index', 'label' => 'Portfolio', 'hasDropdown' => false],
                ['route' => 'products.index', 'label' => 'Products', 'hasDropdown' => false],
                ['route' => 'about', 'label' => 'About', 'hasDropdown' => false],
                ['route' => 'contact', 'label' => 'Contact', 'hasDropdown' => false],
                ];
                @endphp

                @foreach($navItems as $item)
                <div
                    class="relative"
                    @mouseenter="{{ $item['hasDropdown'] ? "activeDropdown = '{$item['route']}'" : '' }}"
                    @mouseleave="activeDropdown = null"
                    @focusout="if (!$el.contains($event.relatedTarget)) activeDropdown = null">
                    <a
                        href="{{ route($item['route']) }}"
                        @if($item['hasDropdown'])
                        @focus="activeDropdown = '{{ $item['route'] }}'"
                        aria-haspopup="true"
                        :aria-expanded="activeDropdown === '{{ $item['route'] }}' ? 'true' : 'false'"
                        @endif
                        class="relative px-3 py-2 text-sm font-medium transition-colors duration-200 rounded-lg flex items-center gap-1 {{ request()->routeIs($item['route']) ? 'text-gray-900' : 'text-gray-600 hover:text-gray-900' }}">
                        {{ $item['label'] }}
                        @if($item['hasDropdown'])
                        <svg class="w-3.5 h-3.5 text-gray-400 transition-transform duration-300" :class="activeDropdown === '{{ $item['route'] }}' ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                        @endif
                        @if(request()->routeIs($item['route']))
                        <span class="absolute -bottom-[3px] left-1/2 -translate-x-1/2 w-4 h-0.5 rounded-full bg-accent-500"></span>
                        @endif
                    </a>

                    {{-- Services Mega Menu --}}
                    @if($item['hasDropdown'] && $item['route'] === 'services.index')
                    <div
                        x-show="activeDropdown === '{{ $item['route'] }}'"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-2 scale-[0.98]"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                        x-transition:leave-end="opacity-0 translate-y-2 scale-[0.98]"
                        class="absolute top-full left-1/2 -translate-x-1/2 pt-3"
                        @mouseenter="activeDropdown = '{{ $item['route'] }}'"
                        @mouseleave="activeDropdown = null"
                        x-cloak>
                        <div class="bg-white rounded-2xl p-5 shadow-[0_30px_60px_-25px_rgba(28,25,23,0.35)] border border-gray-200/80 w-[min(600px,90vw)]">
                            <div class="grid grid-cols-2 gap-1.5">
                                {{-- Main Services --}}
                                <div>
                                    <div class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-1 px-3 pt-1">Services</div>
                                    <a href="{{ route('services.show', 'professional-photo-retouching') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors group">
                                        <div class="w-9 h-9 bg-gray-100 rounded-lg flex items-center justify-center group-hover:bg-accent-100 transition-colors">
                                            <svg class="w-4.5 h-4.5 text-accent-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-800">Photo Retouching</div>
                                            <div class="text-xs text-gray-400">Professional editing</div>
                                        </div>
                                    </a>
                                    <a href="{{ route('services.show', 'background-removal') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors group">
                                        <div class="w-9 h-9 bg-gray-100 rounded-lg flex items-center justify-center group-hover:bg-accent-100 transition-colors">
                                            <svg class="w-4.5 h-4.5 text-accent-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-800">Background Removal</div>
                                            <div class="text-xs text-gray-400">Clean edges</div>
                                        </div>
                                    </a>
                                    <a href="{{ route('services.show', 'color-correction') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors group">
                                        <div class="w-9 h-9 bg-gray-100 rounded-lg flex items-center justify-center group-hover:bg-accent-100 transition-colors">
                                            <svg class="w-4.5 h-4.5 text-accent-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-800">Color Correction</div>
                                            <div class="text-xs text-gray-400">Professional grading</div>
                                        </div>
                                    </a>
                                </div>

                                {{-- More Services & CTA --}}
                                <div>
                                    <div class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-1 px-3 pt-1">More</div>
                                    <a href="{{ route('services.show', 'jewelry-retouching') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors group">
                                        <div class="w-9 h-9 bg-gray-100 rounded-lg flex items-center justify-center group-hover:bg-accent-100 transition-colors">
                                            <svg class="w-4.5 h-4.5 text-accent-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-800">Jewelry Retouching</div>
                                            <div class="text-xs text-gray-400">Specialized service</div>
                                        </div>
                                    </a>
                                    <a href="{{ route('services.show', 'clipping-path') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors group">
                                        <div class="w-9 h-9 bg-gray-100 rounded-lg flex items-center justify-center group-hover:bg-accent-100 transition-colors">
                                            <svg class="w-4.5 h-4.5 text-accent-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-800">Clipping Path</div>
                                            <div class="text-xs text-gray-400">Precise masking</div>
                                        </div>
                                    </a>

                                    {{-- CTA --}}
                                    <div class="mt-2 p-4 rounded-xl bg-surface-100 border border-gray-200/70">
                                        <div class="text-sm font-bold text-gray-900 mb-0.5">Get a Free Quote</div>
                                        <div class="text-xs text-gray-500 mb-3">Start your project today</div>
                                        <a href="{{ route('quote.create') }}" class="block text-center px-4 py-2 bg-accent-500 hover:bg-accent-400 text-primary-950 text-xs font-bold rounded-lg transition-colors">
                                            Get Started
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
            <div class="hidden lg:flex items-center gap-1.5">
                {{-- Search Button --}}
                <button
                    @click="searchOpen = !searchOpen"
                    @keydown.escape.window="searchOpen = false"
                    :aria-expanded="searchOpen.toString()"
                    aria-controls="site-search"
                    aria-label="Toggle site search"
                    class="w-9 h-9 rounded-lg flex items-center justify-center text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>

                {{-- Cart Button --}}
                <a href="{{ route('cart.index') }}" class="relative w-9 h-9 rounded-lg flex items-center justify-center text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition-colors" aria-label="Shopping cart">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    @if(($cartCount ?? 0) > 0)
                    <span class="absolute -top-0.5 -right-0.5 min-w-4.5 h-4.5 px-1 bg-accent-500 text-primary-950 text-[10px] font-bold rounded-full flex items-center justify-center" aria-label="{{ $cartCount }} items in cart">{{ $cartCount }}</span>
                    @endif
                </a>

                {{-- Auth Dropdown --}}
                @auth
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" @click.outside="open = false" class="flex items-center space-x-2 p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white text-sm font-semibold">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <svg class="w-4 h-4 text-gray-600 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-50">
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <a href="{{ route('account.dashboard') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM14 13a1 1 0 011-1h4a1 1 0 011 1v6a1 1 0 01-1 1h-4a1 1 0 01-1-1v-6zM4 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-3z" />
                            </svg>
                            Dashboard
                        </a>
                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 16v2m8-8h2M4 12H2m15.07-7.07l-1.41 1.41M6.34 17.66l-1.41 1.41M17.66 17.66l1.41-1.41M6.34 6.34l-1.41-1.41M12 18a6 6 0 100-12 6 6 0 000 12z" />
                            </svg>
                            Admin Panel
                        </a>
                        <a href="{{ route('admin.media.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4-4 4 4 4-4 4 4M4 6h16M4 6v12" />
                            </svg>
                            Media Library
                        </a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST" class="block">
                            @csrf
                            <button type="submit" class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4v4a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v4m-4 4l-4-4m0 0l4-4" />
                                </svg>
                                Sign out
                            </button>
                        </form>
                    </div>
                </div>
                @else
                <a href="{{ route('login') }}" class="px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                    Login
                </a>
                @endauth

                {{-- CTA Button --}}
                <a href="{{ route('quote.create') }}" class="ml-2 inline-flex items-center gap-2 px-5 py-2.5 bg-accent-500 hover:bg-accent-400 text-primary-950 text-sm font-bold rounded-xl transition-colors">
                    Get a Quote
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>

            {{-- Mobile Toggle --}}
            <button @click="mobileOpen = !mobileOpen" :aria-expanded="mobileOpen.toString()" aria-controls="mobile-navigation" aria-label="Toggle navigation menu" class="lg:hidden w-10 h-10 rounded-lg flex items-center justify-center bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors">
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
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="absolute top-full left-0 right-0 pt-3"
            @click.away="searchOpen = false"
            id="site-search"
            role="search"
            x-cloak>
            <div class="bg-white rounded-2xl p-5 shadow-[0_30px_60px_-25px_rgba(28,25,23,0.35)] border border-gray-200/80">
                <form action="{{ route('services.index') }}" method="GET" class="relative">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input
                        type="text"
                        name="search"
                        placeholder="Search services, products..."
                        class="w-full pl-12 pr-14 py-3 bg-gray-100 border border-transparent rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-accent-500 focus:border-transparent transition-all"
                        autofocus>
                    <kbd class="absolute right-4 top-1/2 -translate-y-1/2 px-1.5 py-0.5 bg-gray-200/70 rounded text-[11px] text-gray-500">ESC</kbd>
                </form>

                <div class="mt-3 flex items-center gap-3">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Popular:</span>
                    <a href="{{ route('services.show', 'professional-photo-retouching') }}" class="px-3 py-1.5 bg-gray-100 rounded-lg text-xs text-gray-600 hover:bg-accent-100 hover:text-primary-900 transition-colors">Photo Retouching</a>
                    <a href="{{ route('services.show', 'background-removal') }}" class="px-3 py-1.5 bg-gray-100 rounded-lg text-xs text-gray-600 hover:bg-accent-100 hover:text-primary-900 transition-colors">Background Removal</a>
                    <a href="{{ route('services.show', 'color-correction') }}" class="px-3 py-1.5 bg-gray-100 rounded-lg text-xs text-gray-600 hover:bg-accent-100 hover:text-primary-900 transition-colors">Color Correction</a>
                </div>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div
            x-show="mobileOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="lg:hidden absolute top-full left-0 right-0 pt-3 pb-6"
            id="mobile-navigation"
            x-cloak>
            <div class="bg-white rounded-2xl p-5 shadow-[0_30px_60px_-25px_rgba(28,25,23,0.35)] border border-gray-200/80 mx-4">
                <nav class="flex flex-col gap-0.5" aria-label="Mobile">
                    @foreach($navItems as $item)
                    <a href="{{ route($item['route']) }}" class="flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 rounded-xl transition-colors {{ request()->routeIs($item['route']) ? 'bg-gray-50 text-gray-900' : '' }}">
                        {{ $item['label'] }}
                        @if(request()->routeIs($item['route']))
                        <span class="w-4 h-0.5 rounded-full bg-accent-500"></span>
                        @endif
                    </a>
                    @endforeach

                    <div class="border-t border-gray-100 mt-2 pt-2">
                        @auth
                        <div class="flex items-center gap-3 px-4 py-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white text-sm font-semibold">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                        <a href="{{ route('account.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 rounded-xl transition-colors">
                            <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Dashboard
                        </a>
                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 rounded-xl transition-colors">
                            <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 16v2m8-8h2M4 12H2m15.07-7.07l-1.41 1.41M6.34 17.66l-1.41 1.41M17.66 17.66l1.41-1.41M6.34 6.34l-1.41-1.41M12 18a6 6 0 100-12 6 6 0 000 12z" />
                            </svg>
                            Admin Panel
                        </a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST" class="block">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-red-600 hover:bg-red-50 rounded-xl transition-colors">
                                <svg class="w-5 h-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4v4a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v4m-4 4l-4-4m0 0l4-4" />
                                </svg>
                                Sign out
                            </button>
                        </form>
                        @else
                        <a href="{{ route('login') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 rounded-xl transition-colors">
                            <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 rounded-xl transition-colors">
                            <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                            Register
                        </a>
                        @endauth

                        <a href="{{ route('cart.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 rounded-xl transition-colors">
                            <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            Cart
                            @if(($cartCount ?? 0) > 0)
                            <span class="ml-auto px-2 py-0.5 bg-accent-500 text-primary-950 text-xs font-bold rounded-full">{{ $cartCount }}</span>
                            @endif
                        </a>
                    </div>

                    <a href="{{ route('quote.create') }}" class="mt-3 block text-center px-6 py-3.5 bg-accent-500 hover:bg-accent-400 text-primary-950 text-sm font-bold rounded-xl transition-colors">
                        Get a Free Quote
                    </a>
                </nav>
            </div>
        </div>
    </div>
</header>

{{-- Spacer for fixed header --}}
<div class="h-20 lg:h-[4.75rem]"></div>
