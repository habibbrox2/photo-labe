<header class="sticky top-0 z-50 bg-white/95 backdrop-blur-sm border-b border-gray-100" x-data="{ mobileOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 lg:h-20">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <div class="w-9 h-9 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-lg">P</span>
                </div>
                <span class="text-xl font-bold text-gray-900">PicLab</span>
            </a>

            {{-- Desktop Nav --}}
            <nav class="hidden lg:flex items-center gap-8">
                <a href="{{ route('services.index') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600 transition-colors">Services</a>
                <a href="{{ route('portfolio.index') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600 transition-colors">Portfolio</a>
                <a href="{{ route('products.index') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600 transition-colors">Products</a>
                <a href="{{ route('blog.index') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600 transition-colors">Blog</a>
                <a href="{{ route('about') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600 transition-colors">About</a>
                <a href="{{ route('contact') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600 transition-colors">Contact</a>
            </nav>

            {{-- CTA --}}
            <div class="hidden lg:flex items-center gap-4">
                @auth
                    <a href="{{ route('account.dashboard') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600">Dashboard</a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600">Admin</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600">Login</a>
                @endauth
                <a href="{{ route('quote.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-full hover:bg-indigo-700 transition-colors shadow-sm">
                    Get a Free Quote
                </a>
            </div>

            {{-- Mobile Toggle --}}
            <button @click="mobileOpen = !mobileOpen" class="lg:hidden p-2 text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    <path x-show="mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-200" x-transition:leave="transition ease-in duration-150" class="lg:hidden pb-4 border-t border-gray-100">
            <nav class="flex flex-col gap-1 pt-4">
                <a href="{{ route('services.index') }}" class="px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-lg">Services</a>
                <a href="{{ route('portfolio.index') }}" class="px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-lg">Portfolio</a>
                <a href="{{ route('products.index') }}" class="px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-lg">Products</a>
                <a href="{{ route('blog.index') }}" class="px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-lg">Blog</a>
                <a href="{{ route('about') }}" class="px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-lg">About</a>
                <a href="{{ route('contact') }}" class="px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-lg">Contact</a>
                <div class="border-t border-gray-100 mt-2 pt-2">
                    @auth
                        <a href="{{ route('account.dashboard') }}" class="px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-lg block">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-lg block">Login</a>
                        <a href="{{ route('register') }}" class="px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-lg block">Register</a>
                    @endauth
                    <a href="{{ route('quote.create') }}" class="mt-2 block text-center px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-full">Get a Free Quote</a>
                </div>
            </nav>
        </div>
    </div>
</header>
