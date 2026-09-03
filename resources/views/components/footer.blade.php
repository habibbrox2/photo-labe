<footer class="relative bg-surface-900 text-white overflow-hidden">
    {{-- Gradient Mesh Background --}}
    <div class="absolute inset-0 gradient-mesh opacity-30"></div>
    <div class="absolute inset-0 noise-overlay"></div>

    {{-- CTA Banner --}}
    <div class="relative border-b border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="relative bg-white/[0.04] ring-1 ring-inset ring-white/10 rounded-3xl p-12 md:p-16 text-center overflow-hidden">
                <div class="absolute top-0 left-0 w-64 h-64 bg-accent-500/10 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
                <div class="absolute bottom-0 right-0 w-64 h-64 bg-accent-500/10 rounded-full blur-3xl translate-x-1/2 translate-y-1/2"></div>
                
                <div class="relative z-10">
                    <h2 class="text-3xl md:text-5xl font-bold mb-4">Ready to Transform Your Images?</h2>
                    <p class="text-white/80 text-lg mb-8 max-w-2xl mx-auto">Get professional photo editing and creative design services tailored to your needs.</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('quote.create') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-accent-500 hover:bg-accent-400 text-primary-950 font-bold rounded-xl transition-colors shadow-lg shadow-accent-500/20">
                            Get a Free Quote
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                        <a href="{{ route('portfolio.index') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 border border-white/20 text-white font-semibold rounded-xl hover:bg-white/5 transition-colors">
                            View Our Work
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer Content --}}
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-12">
            {{-- Brand --}}
            <div class="lg:col-span-2">
                <a href="{{ route('home') }}" class="flex items-center gap-3 mb-6">
                    <div class="w-11 h-11 bg-gradient-to-br from-primary-500 to-accent-500 rounded-2xl flex items-center justify-center">
                        <span class="text-white font-bold text-xl">P</span>
                    </div>
                    <span class="text-2xl font-bold">PhotoLabe</span>
                </a>
                <p class="text-gray-400 leading-relaxed mb-6 max-w-sm">Professional photo editing and creative design services. Transform your images into stunning visuals that captivate and convert.</p>
                
                {{-- Social Links --}}
                <div class="flex gap-3">
                    <a href="#" class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center text-gray-400 hover:text-accent-200 hover:bg-accent-500/15 transition-colors" aria-label="Follow us on social media">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                    </a>
                    <a href="#" class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center text-gray-400 hover:text-accent-200 hover:bg-accent-500/15 transition-colors" aria-label="Follow us on social media">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    <a href="#" class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center text-gray-400 hover:text-accent-200 hover:bg-accent-500/15 transition-colors" aria-label="Follow us on social media">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                    </a>
                    <a href="#" class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center text-gray-400 hover:text-accent-200 hover:bg-accent-500/15 transition-colors" aria-label="Follow us on social media">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm3 8h-1.35c-.538 0-.65.221-.65.778v1.222h2l-.209 2h-1.791v7h-3v-7h-2v-2h2v-2.308c0-1.769.931-2.692 3.029-2.692h1.971v3z"/></svg>
                    </a>
                </div>
            </div>

            {{-- Services --}}
            <div>
                <h3 class="text-white font-bold mb-6 text-sm uppercase tracking-wider">Services</h3>
                <ul class="space-y-4 text-sm">
                    <li><a href="{{ route('services.index') }}" class="text-gray-400 hover:text-accent-200 transition-colors flex items-center gap-2"><span class="w-1 h-1 bg-accent-400 rounded-full"></span>All Services</a></li>
                    <li><a href="{{ route('services.index') }}#retouching" class="text-gray-400 hover:text-accent-200 transition-colors flex items-center gap-2"><span class="w-1 h-1 bg-accent-400 rounded-full"></span>Photo Retouching</a></li>
                    <li><a href="{{ route('services.index') }}#clipping-path" class="text-gray-400 hover:text-accent-200 transition-colors flex items-center gap-2"><span class="w-1 h-1 bg-accent-400 rounded-full"></span>Clipping Path</a></li>
                    <li><a href="{{ route('services.index') }}#color-correction" class="text-gray-400 hover:text-accent-200 transition-colors flex items-center gap-2"><span class="w-1 h-1 bg-accent-400 rounded-full"></span>Color Correction</a></li>
                    <li><a href="{{ route('services.index') }}#background-removal" class="text-gray-400 hover:text-accent-200 transition-colors flex items-center gap-2"><span class="w-1 h-1 bg-accent-400 rounded-full"></span>Background Removal</a></li>
                </ul>
            </div>

            {{-- Company --}}
            <div>
                <h3 class="text-white font-bold mb-6 text-sm uppercase tracking-wider">Company</h3>
                <ul class="space-y-4 text-sm">
                    <li><a href="{{ route('about') }}" class="text-gray-400 hover:text-accent-200 transition-colors flex items-center gap-2"><span class="w-1 h-1 bg-accent-400 rounded-full"></span>About Us</a></li>
                    <li><a href="{{ route('portfolio.index') }}" class="text-gray-400 hover:text-accent-200 transition-colors flex items-center gap-2"><span class="w-1 h-1 bg-accent-400 rounded-full"></span>Portfolio</a></li>
                    <li><a href="{{ route('blog.index') }}" class="text-gray-400 hover:text-accent-200 transition-colors flex items-center gap-2"><span class="w-1 h-1 bg-accent-400 rounded-full"></span>Blog</a></li>
                    <li><a href="{{ route('contact') }}" class="text-gray-400 hover:text-accent-200 transition-colors flex items-center gap-2"><span class="w-1 h-1 bg-accent-400 rounded-full"></span>Contact</a></li>
                    <li><a href="{{ route('faq') }}" class="text-gray-400 hover:text-accent-200 transition-colors flex items-center gap-2"><span class="w-1 h-1 bg-accent-400 rounded-full"></span>FAQ</a></li>
                </ul>
            </div>

            {{-- Contact --}}
            <div>
                <h3 class="text-white font-bold mb-6 text-sm uppercase tracking-wider">Contact</h3>
                <ul class="space-y-4 text-sm">
                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-accent-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <span class="text-gray-400">hello@photolabe.com</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-accent-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <span class="text-gray-400">+1 (555) 123-4567</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-accent-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <span class="text-gray-400">123 Creative Street<br>Design City, DC 10001</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Bottom Bar --}}
    <div class="relative border-t border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col sm:flex-row justify-between items-center gap-4">
            <p class="text-sm text-gray-500">&copy; {{ date('Y') }} {{ config('app.name', 'PhotoLabe') }}. All rights reserved.</p>
            <div class="flex gap-6 text-sm">
                <a href="#" class="text-gray-500 hover:text-accent-200 transition-colors">Privacy Policy</a>
                <a href="#" class="text-gray-500 hover:text-accent-200 transition-colors">Terms of Service</a>
                <a href="#" class="text-gray-500 hover:text-accent-200 transition-colors">Cookie Policy</a>
            </div>
        </div>
    </div>
</footer>
