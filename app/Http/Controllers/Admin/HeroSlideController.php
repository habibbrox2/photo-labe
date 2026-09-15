<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HeroSlideController extends Controller
{
    public function index()
    {
        // Deliberately unpaginated: drag & drop ordering only makes sense against the
        // whole list, and a homepage slider never holds more than a handful of slides.
        $slides = HeroSlide::ordered()->get();

        return view('admin.hero-slides.index', compact('slides'));
    }

    public function create()
    {
        return view('admin.hero-slides.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateSlide($request);

        $validated['sort_order'] = (int) $validated['sort_order'];
        $validated['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('hero', 'public');
        }

        HeroSlide::create($validated);

        $this->flushHeroCache();

        return redirect()->route('admin.hero-slides.index')->with('success', 'Hero slide created successfully.');
    }

    public function edit(HeroSlide $heroSlide)
    {
        return view('admin.hero-slides.edit', ['slide' => $heroSlide]);
    }

    public function update(Request $request, HeroSlide $heroSlide)
    {
        $validated = $this->validateSlide($request);

        $validated['sort_order'] = (int) $validated['sort_order'];
        $validated['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            \Storage::disk('public')->delete($heroSlide->image);
            $validated['image'] = $request->file('image')->store('hero', 'public');
        }

        $heroSlide->update($validated);

        $this->flushHeroCache();

        return redirect()->route('admin.hero-slides.index')->with('success', 'Hero slide updated successfully.');
    }

    /**
     * Persist the order produced by dragging the list. The client sends the complete,
     * ordered list of slide ids; sort_order is then renumbered from 1 across every
     * slide, so gaps and duplicates can never accumulate the way pair-swapping did.
     */
    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'order' => ['required', 'array', 'min:1'],
            'order.*' => ['integer', 'distinct'],
        ]);

        $submitted = collect($validated['order'])->unique()->values();

        // Ignore ids that no longer exist (a tab left open while a slide was deleted)
        // rather than rejecting the whole reorder.
        $known = HeroSlide::whereIn('id', $submitted)->pluck('id')->all();
        $ordered = $submitted->intersect($known)->values();

        // Slides the client did not mention keep their relative order, but are pushed
        // after the reordered ones so the whole table stays on a clean 1..N sequence.
        $rest = HeroSlide::whereNotIn('id', $ordered)->ordered()->pluck('id');
        $final = $ordered->concat($rest)->values();

        DB::transaction(function () use ($final) {
            foreach ($final as $index => $id) {
                HeroSlide::whereKey($id)->update(['sort_order' => $index + 1]);
            }
        });

        $this->flushHeroCache();

        if ($request->expectsJson()) {
            return response()->json([
                'order' => $final,
                'message' => $final->count().' slides reordered.',
            ]);
        }

        return back()->with('success', 'Slide order updated.');
    }

    public function destroy(HeroSlide $heroSlide)
    {
        \Storage::disk('public')->delete($heroSlide->image);
        $heroSlide->delete();

        $this->flushHeroCache();

        return redirect()->route('admin.hero-slides.index')->with('success', 'Hero slide deleted.');
    }

    protected function validateSlide(Request $request): array
    {
        $imageRule = $request->routeIs('admin.hero-slides.store')
            ? 'required'
            : 'nullable';

        return $request->validate([
            'image' => [$imageRule, 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'headline' => ['nullable', 'string', 'max:255'],
            'caption_label' => ['nullable', 'string', 'max:120'],
            'caption_text' => ['nullable', 'string', 'max:255'],
            'link_url' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['boolean'],
        ]);
    }

    protected function flushHeroCache(): void
    {
        Cache::forget('home_hero_slides');
    }
}
