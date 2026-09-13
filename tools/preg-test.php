<?php
foreach (glob(getenv('TEMP') . '/view-salvage/*.php') as $f) {
    $src = file_get_contents($f);
    $p = strpos($src, '/**PATH ');
    if ($p === false) {
        echo basename($f) . ": no marker\n";
        continue;
    }
    $start = $p + 8;
    $end = strpos($src, 'ENDPATH', $start);
    $path = str_replace(chr(92), '/', substr($src, $start, $end - $start));
    echo basename($f) . " => [$path]\n";
}
