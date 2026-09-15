<?php

namespace App\Support;

/**
 * Builds the data an admin list lightbox needs.
 *
 * The viewer keeps one flat list of every image on the page and pages through it,
 * so each thumbnail has to know its own position inside that flat list. Records
 * without images are skipped, which is exactly why the position cannot just be the
 * row number.
 */
final class PreviewGallery
{
    /**
     * @param  iterable<object>  $records  Rows in display order.
     * @param  callable(object): array<string, ?string>  $paths  Image paths per record, keyed by role.
     * @return array{0: list<string>, 1: array<int|string, array<string, int>>}
     *         The ordered image URLs, and a map of record key => role => index in the URL list.
     */
    public static function for(iterable $records, callable $paths): array
    {
        $urls = [];
        $index = [];

        foreach ($records as $record) {
            $roles = [];

            foreach ($paths($record) as $role => $path) {
                if (blank($path)) {
                    continue;
                }

                $roles[$role] = count($urls);
                $urls[] = asset('storage/'.$path);
            }

            if ($roles !== []) {
                $index[$record->getKey()] = $roles;
            }
        }

        return [$urls, $index];
    }
}
