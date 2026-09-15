@extends('admin.layouts.app')
@section('page-title', 'Edit Hero Slide')

@section('content')
<div class="max-w-3xl" x-data="heroSlidePreview({
        currentImageUrl: @js($slide->image ? asset('storage/' . $slide->image) : ''),
        defaultHeadline: @js(\App\Models\HeroSlide::DEFAULT_HEADLINE),
        headline: @js($slide->headline ?? ''),
        captionLabel: @js($slide->caption_label ?? ''),
        captionText: @js($slide->caption_text ?? ''),
    })">
    <form method="POST" action="{{ route('admin.hero-slides.update', $slide) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Image</label>
                @if($slide->image)
                    <div class="mb-2"><img loading="lazy" decoding="async" src="{{ asset('storage/' . $slide->image) }}" alt="{{ $slide->headline ?? 'Slide' }}" class="h-32 rounded-lg object-cover border border-gray-200"></div>
                @endif
                <input type="file" name="image" accept="image/*" x-on:change="previewUrl = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : ''" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-600">
                <p class="text-xs text-gray-400 mt-1">Leave empty to keep the current image (JPG, PNG, or WebP, max 4 MB).</p>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Headline</label>
                <input type="text" name="headline" value="{{ old('headline', $slide->headline) }}" maxlength="255" x-model="headline" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
                <p class="text-xs text-gray-400 mt-1">Shown as the big hero text while this slide is active. Leave empty to keep the default homepage headline.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Caption label</label>
                    <input type="text" name="caption_label" value="{{ old('caption_label', $slide->caption_label) }}" maxlength="120" placeholder="e.g. Jewelry" x-model="captionLabel" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Caption text</label>
                    <input type="text" name="caption_text" value="{{ old('caption_text', $slide->caption_text) }}" maxlength="255" placeholder="e.g. Diamond Collection" x-model="captionText" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Link URL</label>
                    <input type="text" name="link_url" value="{{ old('link_url', $slide->link_url) }}" maxlength="255" placeholder="e.g. /portfolio/diamonds" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Sort order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $slide->sort_order) }}" min="0" max="9999" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
                </div>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $slide->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600">
                <label class="text-sm text-gray-700">Active (visible on the homepage)</label>
            </div>
        </div>

        {{--
            Live preview: a real 1280px-wide homepage hero, scaled down to fit the form.
            The homepage hero's height is driven by its copy overlay, so the stage keeps the
            same padding (pt-40 pb-56 at >=1024px) and grows/shrinks exactly as the homepage does.
            Metrics that on the homepage come from xl: variants are inlined, because media queries
            resolve against the browser viewport rather than this fixed-width stage.
        --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 mt-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Live preview</h3>
                    <p class="text-xs text-gray-400 mt-0.5">How this slide renders on the homepage on a 1280px-wide screen, scaled down. Updates as you type.</p>
                </div>
                <span class="px-2 py-1 text-[11px] font-semibold rounded-full bg-accent-50 text-accent-700 shrink-0" x-show="previewUrl" x-cloak>New image</span>
            </div>

            <div class="mt-4 overflow-hidden" x-ref="viewport"
                 x-effect="syncHeight()"
                 :style="'height:' + scaledHeight + 'px'">
                <div x-ref="stage"
                     class="relative overflow-hidden bg-gray-950 text-white select-none"
                     :style="'width:1280px;transform-origin:top left;transform:scale(' + scale + ')'"
                     data-preview="hero-slide">
                    <img :key="previewUrl || currentImageUrl"
                         :src="previewUrl || currentImageUrl"
                         alt=""
                         class="absolute inset-0 w-full h-full object-cover pointer-events-none kenburns"
                         draggable="false">
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-gray-950/60 to-gray-950/30" aria-hidden="true"></div>
                    <div class="absolute inset-0 bg-gradient-to-r from-gray-950/85 via-gray-950/40 to-transparent" aria-hidden="true"></div>

                    {{-- Copy overlay: in flow, exactly like the homepage, so it sets the hero height --}}
                    <div class="relative z-10 max-w-7xl mx-auto" style="padding: 10rem 2rem 14rem;">
                        <div class="max-w-2xl">
                            <div class="inline-flex items-center gap-2.5 bg-white/10 border border-white/15 backdrop-blur pl-1.5 pr-4 py-1.5 rounded-full">
                                <span class="inline-flex items-center gap-1.5 text-[11px] font-bold uppercase tracking-wider text-gray-900 bg-accent-500 rounded-full px-2.5 py-1">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                    4.9
                                </span>
                                <span class="text-sm font-medium text-white/80">Rated excellent by 500+ studios &amp; brands</span>
                            </div>

                            <h1 class="mt-7 font-extrabold tracking-tight text-white drop-shadow-lg" style="font-size: 4.2rem; line-height: 1.02;">
                                <span x-show="!headline.trim()" x-html="defaultHeadline"></span>
                                <span x-show="headline.trim()" x-cloak x-text="headline"></span>
                            </h1>

                            <p class="mt-6 text-lg text-white/70 leading-relaxed max-w-xl">
                                Retouching, background removal, color grading and creative design — delivered by specialists in as little as 12 hours, with unlimited free revisions.
                            </p>

                            <div class="mt-9 flex items-center gap-4">
                                <span class="btn btn-lg btn-gradient shadow-xl shadow-accent-500/25">
                                    Get a Free Quote
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                </span>
                                <span class="btn btn-lg bg-white/10 border border-white/20 text-white backdrop-blur">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/></svg>
                                    View Our Work
                                </span>
                            </div>

                            <div class="mt-10 flex items-center gap-8">
                                <div>
                                    <div class="text-2xl font-extrabold text-white leading-none">24h</div>
                                    <div class="mt-1 text-sm text-white/60">avg. turnaround</div>
                                </div>
                                <div class="w-px h-10 bg-white/20" aria-hidden="true"></div>
                                <div>
                                    <div class="text-2xl font-extrabold text-white leading-none">10k+</div>
                                    <div class="mt-1 text-sm text-white/60">projects delivered</div>
                                </div>
                                <div class="w-px h-10 bg-white/20" aria-hidden="true"></div>
                                <div>
                                    <div class="text-2xl font-extrabold text-white leading-none">100%</div>
                                    <div class="mt-1 text-sm text-white/60">revision guarantee</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Slide caption (homepage position: bottom-24, container px-8 on lg+) --}}
                    <div class="absolute bottom-24 left-0 right-0 z-10 pointer-events-none" aria-hidden="true">
                        <div class="max-w-7xl mx-auto" style="padding-left: 2rem; padding-right: 2rem;">
                            <div x-show="captionLabel || captionText">
                                <div class="text-[11px] font-semibold uppercase tracking-wider text-accent-400" x-show="captionLabel" x-text="captionLabel"></div>
                                <div class="text-lg font-bold text-white" x-show="captionText" x-text="captionText"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="px-6 py-2.5 bg-primary-600 text-white text-sm font-semibold rounded-lg hover:bg-primary-700">Save changes</button>
            <a href="{{ route('admin.hero-slides.index') }}" class="px-6 py-2.5 text-gray-600 text-sm font-medium hover:bg-gray-100 rounded-lg">Cancel</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('heroSlidePreview', (config = {}) => ({
            currentImageUrl: config.currentImageUrl || '',
            previewUrl: '',
            defaultHeadline: config.defaultHeadline || '',
            headline: config.headline || '',
            captionLabel: config.captionLabel || '',
            captionText: config.captionText || '',

            scale: 1,
            // Height of the unscaled 1280px stage — driven by the copy overlay, like the homepage.
            stageHeight: 0,
            // Height the surrounding viewport box needs so the scaled stage fits exactly.
            scaledHeight: 0,

            init() {
                this.updateScale();

                window.addEventListener('resize', () => this.updateScale());

                // The stage grows and shrinks as the copy wraps differently, so the wrapper
                // height has to follow it. ResizeObserver covers it in browsers that deliver
                // callbacks, and these two catch late layout shifts (webfont swap above all)
                // in the environments that do not.
                if (window.ResizeObserver) {
                    this._observer = new ResizeObserver(() => this.measure());
                    this._observer.observe(this.$refs.stage);
                }
                if (document.fonts && document.fonts.ready) {
                    document.fonts.ready.then(() => this.measure());
                }
                window.addEventListener('load', () => this.measure());
            },

            destroy() {
                this._observer?.disconnect();
            },

            // Called from x-effect, so it re-runs whenever a previewed field (or the
            // scale) changes. Touching them here is what registers them as dependencies.
            syncHeight() {
                void this.headline;
                void this.captionLabel;
                void this.captionText;
                void this.previewUrl;
                void this.scale;

                // Wait for the bindings to write the new values before measuring.
                this.$nextTick(() => this.measure());
            },

            // The stage is a pixel-exact 1280px homepage hero; scale it to the column width.
            updateScale() {
                const width = this.$refs.viewport?.clientWidth || 0;
                this.scale = width > 0 ? Math.min(1, width / 1280) : 1;
                this.measure();
            },

            measure() {
                this.stageHeight = this.$refs.stage?.offsetHeight || 0;
                this.scaledHeight = Math.round(this.stageHeight * this.scale);
            },
        }));
    });
</script>
@endpush
