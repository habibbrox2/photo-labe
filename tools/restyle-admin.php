<?php

/**
 * One-shot restyle of admin CRUD views onto the warm-paper design system.
 * Safe to re-run: every rule is idempotent (already-transformed strings no longer match).
 */

$dir = __DIR__ . '/../resources/views/admin';
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
$files = [];
foreach ($rii as $f) {
    if ($f->isFile() && $f->getExtension() === 'php') {
        $files[] = $f->getPathname();
    }
}
sort($files);

// Ordered exact-string rules: [old, new]
$rules = [
    // ---- Inputs: full-width text/textarea ----
    ['class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 focus:ring-1 focus:ring-primary-500 outline-none"', 'class="form-control-modern"'],
    ['class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none font-mono"', 'class="form-control-modern font-mono"'],
    ['class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none"', 'class="form-control-modern"'],

    // ---- Inputs: inline selects (filter bars) — customer-page standard ----
    ['class="px-4 py-2 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none"', 'class="form-control-modern w-auto !py-2.5 pr-10"'],

    // ---- Inputs: flex search fields ----
    ['class="flex-1 px-4 py-2 rounded-lg border border-gray-200 text-sm focus:border-accent-500 focus:ring-1 focus:ring-primary-500 outline-none"', 'class="form-control-modern flex-1 min-w-[180px] !py-2.5"'],
    ['class="flex-1 px-4 py-2 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none"', 'class="form-control-modern flex-1 min-w-[180px] !py-2.5"'],

    // ---- File inputs ----
    ['class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-600"',
     'class="form-control-modern !py-2 file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-primary-50 file:text-primary-700"'],
    ['class="w-full text-sm text-gray-600 file:mr-3 file:px-4 file:py-2 file:rounded-lg file:border-0 file:bg-primary-50 file:text-primary-700 file:text-sm file:font-semibold hover:file:bg-primary-100"',
     'class="form-control-modern text-gray-600 file:mr-3 file:px-4 file:py-2 file:rounded-full file:border-0 file:bg-primary-50 file:text-primary-700 file:text-xs file:font-semibold hover:file:bg-primary-100"'],
    ['class="flex-1 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-600"',
     'class="form-control-modern flex-1 !py-2 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-primary-50 file:text-primary-700"'],

    // ---- Buttons: primary (sizes) ----
    ['class="w-full px-4 py-2.5 bg-primary-600 text-white text-sm font-semibold rounded-lg hover:bg-primary-700"', 'class="btn btn-primary w-full"'],
    ['class="w-full px-4 py-2.5 bg-primary-600 text-white text-sm font-semibold rounded-lg hover:bg-primary-700 transition-colors"', 'class="btn btn-primary w-full"'],
    ['class="px-6 py-2.5 bg-primary-600 text-white text-sm font-semibold rounded-lg hover:bg-primary-700 transition-colors"', 'class="btn btn-primary btn-md"'],
    ['class="px-6 py-2.5 bg-primary-600 text-white text-sm font-semibold rounded-lg hover:bg-primary-700"', 'class="btn btn-primary btn-md"'],
    ['class="px-4 py-2.5 bg-primary-600 text-white text-sm font-semibold rounded-lg hover:bg-primary-700"', 'class="btn btn-primary btn-md"'],
    ['class="px-4 py-2 bg-primary-600 text-white text-sm font-semibold rounded-lg hover:bg-primary-700 transition-colors"', 'class="btn btn-primary btn-sm"'],
    ['class="px-4 py-2 bg-primary-600 text-white text-sm font-semibold rounded-lg hover:bg-primary-700"', 'class="btn btn-primary btn-sm"'],

    // ---- Buttons: secondary filter + misc ----
    ['class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200"', 'class="btn btn-secondary btn-sm"'],
    ['class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-800 transition-colors"', 'class="btn btn-primary w-full"'],
    ['class="w-full px-4 py-2.5 bg-emerald-600 text-white text-sm font-semibold rounded-lg hover:bg-emerald-700"', 'class="btn btn-primary w-full"'],
    ['class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition-colors"', 'class="btn btn-primary btn-md"'],
    ['class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-gray-800 hover:bg-gray-700 transition-colors"', 'class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-gray-900 hover:bg-gray-800 transition-colors"'],

    // ---- Filter pills (notifications) — customer espresso-pill standard ----
    ['class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ request(\'filter\') === \'unread\' ? \'bg-primary-600 text-white\' : \'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50\' }}"',
     'class="px-4 py-2 rounded-full text-sm font-semibold border transition-colors {{ request(\'filter\') === \'unread\' ? \'bg-gray-900 text-white border-gray-900\' : \'bg-white text-gray-600 border-surface-200 hover:border-gray-300 hover:text-gray-900\' }}"'],
    ['class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ !request(\'filter\') ? \'bg-primary-600 text-white\' : \'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50\' }}"',
     'class="px-4 py-2 rounded-full text-sm font-semibold border transition-colors {{ !request(\'filter\') ? \'bg-gray-900 text-white border-gray-900\' : \'bg-white text-gray-600 border-surface-200 hover:border-gray-300 hover:text-gray-900\' }}"'],

    // ---- Cards: specific then generic catch-all ----
    ['class="bg-white rounded-xl border border-gray-200 p-6 space-y-5"', 'class="surface-card p-6 space-y-5"'],
    ['class="bg-white rounded-xl border border-gray-200 p-6 mt-4 space-y-5"', 'class="surface-card p-6 mt-4 space-y-5"'],
    ['class="bg-white rounded-xl border border-gray-200 p-6"', 'class="surface-card p-6"'],
    ['class="bg-white rounded-xl border border-gray-200 p-5 mb-6"', 'class="surface-card p-5 mb-6"'],
    ['class="bg-white rounded-xl border border-gray-200 divide-y divide-gray-100 overflow-hidden"', 'class="surface-card divide-y divide-surface-200/70 overflow-hidden"'],
    ['bg-white rounded-xl border border-gray-200', 'surface-card'],

    // ---- Tables ----
    ['class="bg-gray-50 border-b border-gray-200"', 'class="bg-surface-50 border-b border-surface-200"'],
    ['class="divide-y divide-gray-100"', 'class="divide-y divide-surface-200/70"'],
    ['class="hover:bg-gray-50"', 'class="hover:bg-surface-50"'],

    // ---- Orders show: file-type chip → ring style (kept inline; not a status) ----
    ['class="px-2 py-0.5 text-xs rounded-full {{ $file->type === \'input\' ? \'bg-blue-100 text-blue-700\' : \'bg-green-100 text-green-700\' }}"',
     'class="px-2.5 py-1 rounded-full text-xs font-medium ring-1 ring-inset {{ $file->type === \'input\' ? \'bg-blue-50 text-blue-700 ring-blue-600/10\' : \'bg-emerald-50 text-emerald-700 ring-emerald-600/10\' }}"'],

    // ---- Media page: indigo → brand ----
    ['text-xs font-semibold text-indigo-600 hover:text-indigo-700', 'text-xs font-semibold text-primary-600 hover:text-primary-700'],
    [':class="dragover ? \'border-indigo-500 bg-indigo-50/60\' : \'border-gray-300 hover:border-indigo-400\'"', ':class="dragover ? \'border-accent-500 bg-accent-50/60\' : \'border-gray-300 hover:border-accent-400\'"'],
    ['class="w-14 h-14 mx-auto mb-4 bg-indigo-50 rounded-2xl flex items-center justify-center"', 'class="w-14 h-14 mx-auto mb-4 bg-accent-500 rounded-2xl flex items-center justify-center shadow-sm shadow-accent-500/25"'],
    ['class="w-7 h-7 text-indigo-500"', 'class="w-7 h-7 text-gray-900"'],
    ['<span class="text-sm font-bold text-indigo-600"', '<span class="text-sm font-bold text-accent-600"'],
    [':class="totalStatus === \'error\' ? \'bg-red-500\' : \'bg-gradient-to-r from-indigo-500 to-purple-500\'"', ':class="totalStatus === \'error\' ? \'bg-red-500\' : \'bg-gradient-to-r from-accent-500 to-accent-400\'"'],
    ['(u.status === \'done\' ? \'text-emerald-500\' : \'text-indigo-500\')', '(u.status === \'done\' ? \'text-emerald-500\' : \'text-accent-600\')'],
    [':class="isSelected(item.id) ? \'bg-indigo-600 border-indigo-600\' : \'bg-white/90 border-gray-300 hover:border-indigo-400\'"', ':class="isSelected(item.id) ? \'bg-primary-600 border-primary-600\' : \'bg-white/90 border-gray-300 hover:border-primary-400\'"'],
    [':class="copied ? \'bg-emerald-100 text-emerald-700\' : \'bg-indigo-50 text-indigo-600 hover:bg-indigo-100\'"', ':class="copied ? \'bg-emerald-100 text-emerald-700\' : \'bg-accent-50 text-accent-700 hover:bg-accent-100\'"'],
    ['focus:border-indigo-500', 'focus:border-primary-500'],

    // ---- Inline status badges → shared component (exact, incl. custom slot labels) ----
    ['<span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $slide->is_active ? \'bg-green-100 text-green-700\' : \'bg-gray-100 text-gray-600\' }}">{{ $slide->is_active ? \'Active\' : \'Hidden\' }}</span>',
     '<x-status-badge :status="$slide->is_active ? \'active\' : \'inactive\'">{{ $slide->is_active ? \'Active\' : \'Hidden\' }}</x-status-badge>'],
    ['<span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $testimonial->is_active ? \'bg-green-100 text-green-700\' : \'bg-gray-100 text-gray-600\' }}">{{ $testimonial->is_active ? \'Active\' : \'Inactive\' }}</span>',
     '<x-status-badge :status="$testimonial->is_active ? \'active\' : \'inactive\'" />'],
];

// Regex rules: [pattern, replacement]
$regexRules = [
    // remaining green/gray status spans → shared badge
    '/<span class="px-2 py-0\.5 text-(?:xs )?font-medium rounded-full \{\{ \$(\w+)->status === \'(\w+)\' \? \'bg-green-100 text-green-700\' : \'bg-gray-100 text-gray-600\' \}\}">\s*\{\{[^}]+\}\}\s*<\/span>/s',
    '<x-status-badge :status="\$$1->status" />',

    // index header: "N total X" plain text → eyebrow + count
    '/<p class="text-sm text-gray-500">\s*\{\{ \$(\w+)->total\(\) \}\} total ([a-z ]+?)\s*<\/p>/',
    '<span class="eyebrow">{{ ucwords(\'$2\') }}</span>' . "\n" . '                <p class="text-xs text-gray-400 mt-1">{{ \$$1->total() }} total</p>',
];

$changed = [];
foreach ($files as $file) {
    $src = $orig = file_get_contents($file);
    $count = 0;

    foreach ($rules as [$old, $new]) {
        $n = substr_count($src, $old);
        if ($n > 0) {
            $src = str_replace($old, $new, $src);
            $count += $n;
        }
    }
    foreach ($regexRules as $i => [$pat, $rep]) {
        $src = preg_replace($pat, $rep, $src, -1, $n);
        $count += $n;
    }

    if ($src !== $orig) {
        file_put_contents($file, $src);
        $changed[basename(dirname($file)) . '/' . basename($file)] = $count;
    }
}

echo "Changed " . count($changed) . " files, total replacements:\n";
foreach ($changed as $f => $n) {
    printf("  %-40s %d\n", $f, $n);
}

// Residual legacy markers sweep
echo "\n--- residual markers (should be empty or explained) ---\n";
$markers = ['bg-white rounded-xl border', 'bg-primary-600 text-white', 'bg-gray-50 border-b', 'indigo', 'bg-green-100', 'px-6 py-2.5 bg-primary'];
foreach ($files as $file) {
    $src = file_get_contents($file);
    foreach ($markers as $m) {
        if (stripos($src, $m) !== false) {
            $rel = str_replace($dir . DIRECTORY_SEPARATOR, '', $file);
            echo "  [$m] $rel\n";
        }
    }
}
echo "done\n";
