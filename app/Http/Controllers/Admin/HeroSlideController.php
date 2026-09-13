<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HeroSlideController extends Controller
{
    public function index()
    {
        $slides = HeroSlide::ordered()->paginate(15);

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

    public function moveUp(HeroSlide $heroSlide)
    {
        $this->swapOrder($heroSlide, 'up');

        return back()->with('success', 'Slide moved up.');
    }

    public function moveDown(HeroSlide $heroSlide)
    {
        $this->swapOrder($heroSlide, 'down');

        return back()->with('success', 'Slide moved down.');
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

    protected function swapOrder(HeroSlide $heroSlide, string $direction): void
    {
        $neighbour = HeroSlide::ordered()
            ->where('sort_order', $direction === 'up' ? '<' : '>', $heroSlide->sort_order)
            ->when($direction === 'up', fn ($q) => $q->orderByDesc('sort_order')->orderByDesc('id'))
            ->when($direction === 'down', fn ($q) => $q->orderBy('sort_order')->orderBy('id'))
            ->first();

        if (! $neighbour) {
            return;
        }

        [$heroSlide->sort_order, $neighbour->sort_order] = [$neighbour->sort_order, $heroSlide->sort_order];
        $heroSlide->save();
        $neighbour->save();

        $this->flushHeroCache();
    }

    protected function flushHeroCache(): void
    {
        Cache::forget('home_hero_slides');
    }
}
