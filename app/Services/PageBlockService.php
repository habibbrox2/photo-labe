<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PageBlockService
{
    public const TYPES = ['hero', 'rich_text', 'image', 'cta', 'stats', 'faq', 'collection'];

    public function normalise(?array $blocks): array
    {
        $blocks = array_values($blocks ?? []);
        if (count($blocks) > 30) throw ValidationException::withMessages(['blocks' => 'A page may contain at most 30 blocks.']);

        return array_map(function ($block) {
            $type = $block['type'] ?? '';
            if (! in_array($type, self::TYPES, true)) throw ValidationException::withMessages(['blocks' => 'An unsupported content block was submitted.']);
            $data = is_array($block['data'] ?? null) ? $block['data'] : [];
            $clean = ['id' => Str::uuid()->toString(), 'type' => $type, 'data' => []];
            $text = fn ($key, $max = 500) => Str::limit(trim(strip_tags((string) ($data[$key] ?? ''))), $max, '');

            if ($type === 'hero') $clean['data'] = ['eyebrow' => $text('eyebrow', 120), 'title' => $text('title', 180), 'subtitle' => $text('subtitle', 500), 'image' => $text('image', 255)];
            if ($type === 'rich_text') $clean['data'] = ['heading' => $text('heading', 180), 'body' => $this->safeHtml((string) ($data['body'] ?? ''))];
            if ($type === 'image') $clean['data'] = ['path' => $text('path', 255), 'alt' => $text('alt', 255), 'caption' => $text('caption', 500)];
            if ($type === 'cta') {
                $url = trim((string) ($data['url'] ?? ''));
                if ($url !== '' && ! Str::startsWith($url, '/') && ! filter_var($url, FILTER_VALIDATE_URL)) throw ValidationException::withMessages(['blocks' => 'CTA URLs must be internal paths or valid URLs.']);
                if (Str::startsWith($url, 'http://')) throw ValidationException::withMessages(['blocks' => 'External CTA URLs must use HTTPS.']);
                $clean['data'] = ['heading' => $text('heading', 180), 'text' => $text('text', 500), 'label' => $text('label', 80), 'url' => $url];
            }
            if ($type === 'stats') $clean['data'] = ['items' => $this->items($data['items'] ?? [], ['value' => 40, 'label' => 100], 6)];
            if ($type === 'faq') $clean['data'] = ['items' => $this->items($data['items'] ?? [], ['question' => 255, 'answer' => 1000], 20)];
            if ($type === 'collection') {
                $source = $data['source'] ?? '';
                if (! in_array($source, ['services', 'portfolio', 'products', 'testimonials'], true)) throw ValidationException::withMessages(['blocks' => 'Choose a valid collection.']);
                $clean['data'] = ['source' => $source, 'heading' => $text('heading', 180), 'limit' => max(1, min(12, (int) ($data['limit'] ?? 3)))];
            }
            return $clean;
        }, $blocks);
    }

    private function items($items, array $fields, int $max): array
    {
        return collect(is_array($items) ? $items : [])->take($max)->map(function ($item) use ($fields) {
            return collect($fields)->mapWithKeys(fn ($max, $key) => [$key => Str::limit(trim(strip_tags((string) (($item[$key] ?? '')))), $max, '')])->all();
        })->filter(fn ($item) => collect($item)->filter()->isNotEmpty())->values()->all();
    }

    private function safeHtml(string $html): string
    {
        $html = strip_tags($html, '<p><h2><h3><h4><strong><em><ul><ol><li><br><blockquote>');
        return preg_replace('/<([a-z0-9]+)(?:\s[^>]*)?>/i', '<$1>', $html) ?? '';
    }
}
