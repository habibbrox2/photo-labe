<?php

/**
 * One-shot brand icon generator — PhotoLabe (warm ink + gold).
 *
 * Regenerates every icon referenced by layouts/app.blade.php in the current
 * design language: rounded dark-ink square, gold "P" monogram (matches the
 * header logo mark).
 *
 * Run: php tools/generate-icons.php
 */

$gold    = [245, 158, 11];   // accent-500
$goldLt  = [252, 211, 77];   // accent-300
$ink     = [26, 22, 19];     // primary-950
$inkSoft = [42, 36, 31];     // primary-900

$sizes = [
    'favicon-16x16.png'     => 16,
    'favicon-32x32.png'     => 32,
    'apple-touch-icon.png'  => 180,
    'favicon-192x192.png'   => 192,
    'favicon-512x512.png'   => 512,
    'mstile-144x144.png'    => 144,
];

// Render once at high resolution, then downscale — keeps small sizes crisp.
$master = 512;
$img = imagecreatetruecolor($master, $master);
imagesavealpha($img, true);
imagealphablending($img, false);
imagefilledrectangle($img, 0, 0, $master - 1, $master - 1, imagecolorallocatealpha($img, 0, 0, 0, 127));
imagealphablending($img, true);

// Rounded-square ink tile covering ~92% of the canvas.
$tile = (int) round($master * 0.92);
$off  = (int) (($master - $tile) / 2);
$radius = (int) round($tile * 0.22);

imagefilledrectangle($img, $off, $off, $off + $tile - 1, $off + $tile - 1, imagecolorallocate($img, ...$ink));
drawRoundedMask($img, $off, $off, $tile, $radius, $ink);

// Subtle warm gradient at the top of the tile.
for ($y = 0; $y < (int) ($tile * 0.45); $y++) {
    $t = 1 - $y / ($tile * 0.45);
    $alpha = (int) round(28 * $t);
    $line = imagecolorallocatealpha($img, ...[...$inkSoft, $alpha]);
    imageline($img, $off + 1, $off + $y + 1, $off + $tile - 2, $off + $y + 1, $line);
}

// Gold "P" — drawn as shapes so no font dependency, matching the header mark's weight.
$cx = $master / 2;
$cy = $master / 2;
$stroke = (int) round($master * 0.105);          // stem thickness
$pH = (int) round($master * 0.52);               // total P height
$pTop = $cy - (int) ($pH / 2);
$bowlR = (int) round($master * 0.155);           // bowl radius
$bowlCy = $pTop + $bowlR;
$bowlCx = $cx + (int) round($master * 0.045);

// Vertical stem, slightly left of center to balance the bowl.
$stemX = $cx - (int) round($master * 0.095);
imagefilledrectangle($img, $stemX, $pTop, $stemX + $stroke - 1, $pTop + $pH - 1, imagecolorallocate($img, ...$gold));

// Bowl: outer ring segment (right half-circle) + inner cutout.
$ring = (int) round($stroke * 0.78);
imagefilledellipse($img, $bowlCx, $bowlCy, $bowlR * 2, $bowlR * 2, imagecolorallocate($img, ...$gold));
imagefilledellipse($img, $bowlCx, $bowlCy, ($bowlR - $ring) * 2, ($bowlR - $ring) * 2, imagecolorallocate($img, ...$ink));
// Cover the left half of the bowl ring so only the right arc shows.
imagefilledrectangle($img, $off, $pTop, $bowlCx - 1, $bowlCy, imagecolorallocate($img, ...$ink));

// Tiny gold top-left accent dot, echoing the gold dot in the nav.
$dotR = (int) round($master * 0.028);
imagefilledellipse($img, $off + (int) round($tile * 0.16), $off + (int) round($tile * 0.16), $dotR * 2, $dotR * 2, imagecolorallocate($img, ...$goldLt));

foreach ($sizes as $name => $size) {
    $out = imagecreatetruecolor($size, $size);
    imagesavealpha($out, true);
    imagealphablending($out, false);
    imagefilledrectangle($out, 0, 0, $size - 1, $size - 1, imagecolorallocatealpha($out, 0, 0, 0, 127));
    imagealphablending($out, true);
    imagecopyresampled($out, $img, 0, 0, 0, 0, $size, $size, $master, $master);
    imagepng($out, __DIR__ . '/../public/' . $name, 6);
    imagedestroy($out);
    echo "wrote public/{$name} ({$size}x{$size})\n";
}

// Multi-resolution .ico built from raw PNG chunks (no external libs needed).
writeIco(__DIR__ . '/../public/favicon.ico', [
    ['data' => pngData(16), 'size' => 16],
    ['data' => pngData(32), 'size' => 32],
    ['data' => pngData(48), 'size' => 48],
]);
echo "wrote public/favicon.ico (16/32/48)\n";

imagedestroy($img);

function drawRoundedMask($img, $x, $y, $size, $r, $color): void
{
    // Mask out the corners outside the rounded rect by redrawing them transparent.
    $corners = [
        [$x, $y, 1, 1],
        [$x + $size - 1, $y, -1, 1],
        [$x, $y + $size - 1, 1, -1],
        [$x + $size - 1, $y + $size - 1, -1, -1],
    ];
    $transparent = imagecolorallocatealpha($img, 0, 0, 0, 127);
    foreach ($corners as [$cx0, $cy0, $sx, $sy]) {
        for ($dy = 0; $dy < $r; $dy++) {
            for ($dx = 0; $dx < $r; $dx++) {
                $ox = $r - 1 - $dx;
                $oy = $r - 1 - $dy;
                if (($ox * $ox + $oy * $oy) > ($r * $r)) {
                    imagesetpixel($img, $cx0 + $sx * $dx, $cy0 + $sy * $dy, $transparent);
                }
            }
        }
    }
}

function pngData(int $size): string
{
    // Re-render at target size from a fresh copy of the master for max quality.
    static $master = null, $masterImg = null;
    if ($masterImg === null) {
        $masterImg = imagecreatetruecolor(512, 512);
        imagecopy($masterImg, $GLOBALS['img'], 0, 0, 0, 0, 512, 512);
    }
    $out = imagecreatetruecolor($size, $size);
    imagesavealpha($out, true);
    imagealphablending($out, false);
    imagefilledrectangle($out, 0, 0, $size - 1, $size - 1, imagecolorallocatealpha($out, 0, 0, 0, 127));
    imagealphablending($out, true);
    imagecopyresampled($out, $masterImg, 0, 0, 0, 0, $size, $size, 512, 512);
    ob_start();
    imagepng($out, null, 9);
    $data = ob_get_clean();
    imagedestroy($out);
    return $data;
}

function writeIco(string $path, array $entries): void
{
    $count = count($entries);
    $header = pack('vvv', 0, 1, $count);
    $offset = 6 + 16 * $count;
    $dir = '';
    $body = '';
    foreach ($entries as $e) {
        $s = $e['size'];
        $dir .= pack('CCCCvvVV', $s >= 256 ? 0 : $s, $s >= 256 ? 0 : $s, 0, 0, 1, 32, strlen($e['data']), $offset);
        $body .= $e['data'];
        $offset += strlen($e['data']);
    }
    file_put_contents($path, $header . $dir . $body);
}
