<?php

namespace App\Services;

use App\Models\BeforeAfterProject;

/**
 * Renders inline shortcodes inside rich-text content (pages, services,
 * products, portfolio descriptions).
 *
 * Supported shortcodes:
 *   [before_after id=3]   — embeds the before/after slider for project #3
 *
 * Unknown or unpublished ids render as an empty string so a typo can never
 * break the page — the surrounding content is preserved.
 */
class ShortcodeService
{
    /** Per-request memo of rendered shortcode output, keyed by project id. */
    protected array $rendered = [];

    public function render(?string $content): string
    {
        if ($content === null || $content === '') {
            return '';
        }

        return preg_replace_callback(
            '/\[before_after\s+id\s*=\s*(\d+)\s*\]/i',
            function (array $matches): string {
                return $this->renderBeforeAfter((int) $matches[1]);
            },
            $content
        );
    }

    protected function renderBeforeAfter(int $id): string
    {
        if (array_key_exists($id, $this->rendered)) {
            return $this->rendered[$id];
        }

        $project = BeforeAfterProject::query()
            ->where('id', $id)
            ->where('status', 'published')
            ->first();

        if (! $project) {
            return $this->rendered[$id] = '';
        }

        // Rendering a view while another view is already rendering can leave
        // a stray output buffer open on some nesting paths. Track the level
        // and always close anything opened beyond it, so the parent render
        // stays balanced.
        $bufferLevel = ob_get_level();

        try {
            $html = view('components.before-after', [
                'before' => asset('storage/' . $project->before_image),
                'after' => asset('storage/' . $project->after_image),
                'title' => $project->title,
            ])->render();
        } finally {
            while (ob_get_level() > $bufferLevel) {
                ob_end_clean();
            }
        }

        return $this->rendered[$id] = $html;
    }
}
