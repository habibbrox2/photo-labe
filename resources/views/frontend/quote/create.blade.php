@extends('layouts.app')
@section('title', 'Get a Free Quote')
@section('content')

<section class="page-hero-light">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        <nav aria-label="Breadcrumb">
            <ol class="flex items-center gap-1.5 text-sm text-gray-500">
                <li><a href="{{ route('home') }}" class="hover:text-primary-600 transition-colors">Home</a></li>
                <li aria-hidden="true" class="text-gray-300">/</li>
                <li aria-current="page" class="text-gray-700 font-medium">Free Quote</li>
            </ol>
        </nav>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-14">
        <div class="max-w-3xl">
            <span class="eyebrow">Free, no-obligation</span>
            <h1 class="mt-5 text-4xl md:text-5xl font-extrabold tracking-tight text-gray-900 leading-[1.08]">Tell us about your project — <em class="italic text-accent-600">we'll quote it fast</em></h1>
            <p class="mt-5 text-lg text-gray-500">Send your requirements and a sample. A real retoucher replies with a fixed price within a few hours — usually on the same day.</p>
        </div>
    </div>
</section>

<section class="py-14 lg:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-12 gap-14">
            {{-- Trust / info column --}}
            <aside class="lg:col-span-4">
                <div class="lg:sticky lg:top-24 space-y-6">
                    <div class="rounded-3xl border border-surface-200 bg-surface-50/60 p-7">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-6">What happens next</h2>
                        <ol class="space-y-6">
                            <li class="flex gap-4">
                                <span class="w-8 h-8 shrink-0 rounded-full bg-accent-500 text-gray-900 font-extrabold text-sm flex items-center justify-center">1</span>
                                <div>
                                    <div class="text-sm font-bold text-gray-900">We review your request</div>
                                    <p class="mt-1 text-sm text-gray-500 leading-relaxed">A specialist checks your files and notes the exact work required.</p>
                                </div>
                            </li>
                            <li class="flex gap-4">
                                <span class="w-8 h-8 shrink-0 rounded-full bg-accent-500 text-gray-900 font-extrabold text-sm flex items-center justify-center">2</span>
                                <div>
                                    <div class="text-sm font-bold text-gray-900">You get a fixed quote</div>
                                    <p class="mt-1 text-sm text-gray-500 leading-relaxed">A clear price and delivery time — no surprises on the invoice.</p>
                                </div>
                            </li>
                            <li class="flex gap-4">
                                <span class="w-8 h-8 shrink-0 rounded-full bg-accent-500 text-gray-900 font-extrabold text-sm flex items-center justify-center">3</span>
                                <div>
                                    <div class="text-sm font-bold text-gray-900">Approve &amp; we edit</div>
                                    <p class="mt-1 text-sm text-gray-500 leading-relaxed">Pay only when you're happy. Unlimited free revisions included.</p>
                                </div>
                            </li>
                        </ol>
                    </div>

                    <div class="rounded-3xl border border-surface-200 bg-white p-7">
                        <ul class="space-y-4 text-sm text-gray-600">
                            <li class="flex items-center gap-3"><span class="w-6 h-6 rounded-full bg-accent-100 flex items-center justify-center shrink-0"><x-icon name="check" class="w-3.5 h-3.5 text-accent-700" /></span>Reply within a few hours, 7 days a week</li>
                            <li class="flex items-center gap-3"><span class="w-6 h-6 rounded-full bg-accent-100 flex items-center justify-center shrink-0"><x-icon name="check" class="w-3.5 h-3.5 text-accent-700" /></span>Volume discounts for 50+ images</li>
                            <li class="flex items-center gap-3"><span class="w-6 h-6 rounded-full bg-accent-100 flex items-center justify-center shrink-0"><x-icon name="check" class="w-3.5 h-3.5 text-accent-700" /></span>Your files stay private — never shared</li>
                            <li class="flex items-center gap-3"><span class="w-6 h-6 rounded-full bg-accent-100 flex items-center justify-center shrink-0"><x-icon name="check" class="w-3.5 h-3.5 text-accent-700" /></span>NDA available for enterprise clients</li>
                        </ul>
                    </div>

                    @if($testimonials->count())
                    <div class="rounded-3xl border border-surface-200 bg-white p-7">
                        <div class="flex items-center gap-2 mb-5">
                            <div class="flex gap-0.5" role="img" aria-label="5 out of 5 stars">
                                @for($i = 0; $i < 5; $i++)
                                <svg class="w-4 h-4 text-accent-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                            <span class="text-sm font-bold text-gray-900">4.9/5 from clients</span>
                        </div>
                        <div class="space-y-5">
                            @foreach($testimonials as $testimonial)
                            <div>
                                <p class="text-sm text-gray-600 leading-relaxed">“{{ mb_substr($testimonial->content, 0, 110) }}{{ mb_strlen($testimonial->content) > 110 ? '…' : '' }}”</p>
                                <div class="mt-2 flex items-center gap-2">
                                    @if($testimonial->avatar)
                                    <img loading="lazy" decoding="async" src="{{ asset('storage/' . $testimonial->avatar) }}" alt="{{ $testimonial->name }}" class="w-7 h-7 rounded-full object-cover">
                                    @else
                                    <span class="w-7 h-7 rounded-full bg-gray-900 text-white flex items-center justify-center text-[10px] font-bold">{{ mb_substr($testimonial->name, 0, 1) }}</span>
                                    @endif
                                    <span class="text-xs font-bold text-gray-900">{{ $testimonial->name }}</span>
                                    @if($testimonial->company)
                                    <span class="text-xs text-gray-400">· {{ $testimonial->company }}</span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="rounded-2xl p-6 bg-gray-900 text-white">
                        <div class="flex items-center gap-4">
                            <span class="w-12 h-12 rounded-2xl bg-accent-500 flex items-center justify-center">
                                <x-icon name="chat" class="w-6 h-6 text-gray-900" />
                            </span>
                            <div>
                                <div class="text-sm font-bold">Prefer to talk?</div>
                                <p class="text-white/60 text-xs mt-0.5">Email hello@photolabe.com or use the contact page.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            {{-- Form --}}
            <div class="lg:col-span-8">
                <form action="{{ route('quote.store') }}" method="POST" enctype="multipart/form-data" class="rounded-3xl border border-surface-200 bg-white p-7 md:p-10 shadow-[0_20px_50px_-30px_rgba(28,25,23,0.2)]">
                    @csrf

                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name *</label>
                            <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" required
                                class="form-control-modern">
                            @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email *</label>
                            <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required
                                class="form-control-modern">
                            @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone <span class="font-normal text-gray-400">(optional)</span></label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                                class="form-control-modern">
                            @error('phone') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="service_id" class="block text-sm font-semibold text-gray-700 mb-2">Service</label>
                            <select id="service_id" name="service_id" class="form-control-modern">
                                <option value="">Select a service (optional)</option>
                                @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>{{ $service->title }} — from ${{ number_format($service->starting_price ?? 0, 2) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="quantity" class="block text-sm font-semibold text-gray-700 mb-2">Image Quantity *</label>
                            <input type="number" id="quantity" name="quantity" value="{{ old('quantity', 1) }}" min="1" required
                                class="form-control-modern">
                            @error('quantity') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="deadline" class="block text-sm font-semibold text-gray-700 mb-2">Deadline <span class="font-normal text-gray-400">(optional)</span></label>
                            <input type="date" id="deadline" name="deadline" value="{{ old('deadline') }}"
                                class="form-control-modern">
                            @error('deadline') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="requirements" class="block text-sm font-semibold text-gray-700 mb-2">Project Requirements *</label>
                        <textarea id="requirements" name="requirements" rows="5" required placeholder="Tell us what you need — type of editing, style references, how the images will be used..."
                            class="form-control-modern resize-none">{{ old('requirements') }}</textarea>
                        @error('requirements') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mt-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Reference Files <span class="font-normal text-gray-400">(optional)</span></label>
                        <input type="file" name="files[]" multiple accept=".jpg,.jpeg,.png,.webp,.tiff,.zip,.psd"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-accent-500 file:text-gray-900 hover:file:bg-accent-600 file:cursor-pointer file:transition-colors cursor-pointer">
                        <p class="text-xs text-gray-400 mt-2">Max 5 files · JPG, PNG, WebP, TIFF, ZIP, PSD · up to 10MB each</p>
                        @error('files') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        @error('files.*') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Time-trap: encrypted form-opened timestamp --}}
                    <input type="hidden" name="{{ \App\Support\FormTimeTrap::FIELD }}" value="{{ \App\Support\FormTimeTrap::token() }}">

                    {{-- Honeypot: hidden from humans, bots autofill it --}}
                    <div class="absolute -left-[9999px] top-auto" aria-hidden="true">
                        <label for="website">Website</label>
                        <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                    </div>
                    <div class="mt-8 pt-8 border-t border-surface-200 flex flex-col sm:flex-row items-center gap-4">
                        <button type="submit" class="btn btn-lg btn-gradient w-full sm:w-auto">
                            Submit Quote Request
                            <x-icon name="send" class="w-4 h-4" />
                        </button>
                        <p class="text-xs text-gray-400">We'll never share your details. No spam, ever.</p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
