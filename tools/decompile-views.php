<?php

/**
 * Recover Blade sources from compiled views - NO PCRE, NO backslash literals.
 * Pure strpos/substr scanner. Needles are single-quoted (dollar stays literal);
 * real newlines built with chr(10). Writes output only after residual-PHP check.
 */

$viewDir = __DIR__ . '/../storage/framework/views';
$outRoot = __DIR__ . '/../resources/views';

$targets = [
    'admin/layouts/app.blade.php',
    'admin/dashboard.blade.php',
    'admin/styleguide.blade.php',
];

$NL = chr(10);
$Q  = chr(39); // single quote

// needles (all backslash-free)
$N_SAVE1   = '<?php if (isset($component)) { $__componentOriginal';
$N_ATTRSAVE = '<?php if (isset($attributes)) { $__attributesOriginal';
$N_UNSET   = '<?php unset($__componentOriginal';
$N_ENDIFNL = '<?php endif; ?>' . $NL;
$N_WA_OPEN = '<?php $component->withAttributes([';
$N_WA_CLOSE = ']); ?>' . $NL;
$N_RC      = '<?php echo $__env->renderComponent(); ?>' . $NL;
$N_VIEWKEY = "'view' => 'components.";

$TOKENS = [
    'F1'  => '$__currentLoopData = ',
    'F2'  => '$__pol = ',
    'FE1' => '<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>',
    'FE'  => '<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>',
    'FEP' => '<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>',
    'E1F' => '<?php $__empty_1 = true; if ($__empty_1): ?>',
    'IF'  => '<?php if (',
    'EI'  => '<?php elseif (',
    'EL'  => '<?php else: ?>',
    'EN'  => '<?php endif; ?>',
    'EF'  => '<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>',
    'EE'  => '<?php echo e(',
    'PHP' => '<?php ',
    'YC'  => '<?php echo $__env->yieldContent(',
    'YP'  => '<?php echo $__env->yieldPushContent(',
    'SS'  => '<?php $__env->startSection(',
    'SE'  => '<?php $__env->stopSection(); ?>',
    'SP'  => '<?php $__env->startPush(',
    'EP'  => '<?php $__env->stopPush(); ?>',
    'MK'  => '<?php echo $__env->make(',
    'CS'  => '<?php echo csrf_field(); ?>',
    'JS'  => '<?php echo json_encode(',
    'RC'  => $N_SAVE1,
];

function fail(string $msg)
{
    global $CTX;
    fwrite(STDERR, "FATAL: " . ($CTX ?? '') . " $msg" . PHP_EOL);
    exit(1);
}
$CTX = '';

/** earliest token at/after $pos: returns [label, index] or [null, false]; longest needle wins ties */
function nextToken(string $s, int $pos, array $T): array
{
    $best = false;
    $bestLabel = null;
    $bestLen = 0;
    foreach ($T as $label => $needle) {
        $i = strpos($s, $needle, $pos);
        if ($i !== false && ($best === false || $i < $best || ($i === $best && strlen($needle) > $bestLen))) {
            $best = $i;
            $bestLabel = $label;
            $bestLen = strlen($needle);
        }
    }
    return [$bestLabel, $best];
}

/** consume a 4-line save/restore guard starting at $i (prefix), through its endif line */
function cutGuard(string $s, int $i, string $needle): int
{
    global $N_ENDIFNL;
    $e = strpos($s, $N_ENDIFNL, $i);
    if ($e === false) {
        fail("guard endif not found near offset $i");
    }
    return $e + strlen($N_ENDIFNL);
}

/** parse quoted name at $i (must start with a quote), return [name, nextOffset] */
function readQuoted(string $s, int $i): array
{
    global $Q;
    if ($s[$i] !== $Q) {
        fail("expected quote at offset $i");
    }
    $e = strpos($s, $Q, $i + 1);
    if ($e === false) {
        fail("unterminated quoted name at $i");
    }
    return [substr($s, $i + 1, $e - $i - 1), $e + 1];
}

/** paren matcher, quote-aware: $open at '(' index; returns offset AFTER matching ')' */
function matchParen(string $s, int $open): int
{
    $depth = 0;
    $n = strlen($s);
    for ($j = $open; $j < $n; $j++) {
        $ch = $s[$j];
        if ($ch === chr(39) || $ch === '"') {
            // skip quoted string (with backslash escapes)
            $q = $ch;
            $j++;
            while ($j < $n) {
                if ($s[$j] === chr(92)) {
                    $j += 2;
                    continue;
                }
                if ($s[$j] === $q) {
                    break;
                }
                $j++;
            }
            continue;
        }
        if ($ch === '(') {
            $depth++;
        } elseif ($ch === ')') {
            $depth--;
            if ($depth === 0) {
                return $j + 1;
            }
        }
    }
    fail("unbalanced parens from offset $open");
}

/** consume one restore guard (if/set/unset/endif, no braces) starting at $i; return next offset */
function cutRestoreGuard(string $seg, int $i): int
{
    $NL = chr(10);
    strpos($seg, '<?php if (isset($__', $i) === $i or fail('guard anchor mismatch at ' . $i);
    $e1 = strpos($seg, ')): ?>' . $NL, $i);
    $e1 !== false or fail('guard if tail not found at ' . $i);
    $e1 += strlen(')): ?>' . $NL);
    strpos($seg, '<?php $', $e1) === $e1 or fail('guard assign line not found at ' . $e1);
    $e2 = strpos($seg, '; ?>' . $NL, $e1);
    $e2 !== false or fail('guard assign tail not found at ' . $e1);
    $e2 += strlen('; ?>' . $NL);
    strpos($seg, '<?php unset(', $e2) === $e2 or fail('guard unset line not found at ' . $e2);
    $e3 = strpos($seg, '); ?>' . $NL, $e2);
    $e3 !== false or fail('guard unset tail not found at ' . $e2);
    return $e3 + strlen('); ?>' . $NL) + strlen('<?php endif; ?>' . $NL);
}

/** clean a component slot segment: strip restore guards, RC line, trailing endif */
function cleanSlot(string $seg): string
{
    global $N_RC, $N_ENDIFNL;
    foreach (['isset($__attributesOriginal', 'isset($__componentOriginal'] as $anchor) {
        $i = strpos($seg, '<?php if (' . $anchor);
        if ($i !== false) {
            $next = cutRestoreGuard($seg, $i);
            $seg = substr($seg, 0, $i) . substr($seg, $next);
        }
    }
    $n = substr_count($seg, $N_RC);
    $n === 1 or fail("expected exactly 1 renderComponent in slot, got $n");
    $seg = str_replace($N_RC, '', $seg);
    $t = rtrim($seg);
    $bare = '<?php endif; ?>';
    substr($t, -strlen($bare)) === $bare or fail('slot does not end with endif; got: ' . substr($t, -40));
    $seg = substr($t, 0, strlen($t) - strlen($bare));
    strpos($seg, '<?php') === false or fail('php left in cleaned slot: ' . substr($seg, 0, 160));
    return $seg;
}

/** parse withAttributes array literal into [name => [value, isQuoted]] pairs */
function parseAttrs(string $s): array
{
    $pairs = [];
    $i = 0;
    $n = strlen($s);
    while ($i < $n) {
        $q1 = strpos($s, chr(39), $i);
        if ($q1 === false) {
            break;
        }
        $q2 = strpos($s, chr(39), $q1 + 1);
        $key = substr($s, $q1 + 1, $q2 - $q1 - 1);
        $arrow = strpos($s, '=>', $q2);
        if ($arrow === false) {
            fail('attr arrow not found');
        }
        $vStart = $arrow + 2;
        // value ends at next comma (at depth 0) or end of string
        $depth = 0;
        $vEnd = $n;
        for ($j = $vStart; $j < $n; $j++) {
            $ch = $s[$j];
            if ($ch === '(' || $ch === '[') {
                $depth++;
            } elseif ($ch === ')' || $ch === ']') {
                $depth--;
            } elseif ($ch === ',' && $depth === 0) {
                $vEnd = $j;
                break;
            }
        }
        $raw = trim(substr($s, $vStart, $vEnd - $vStart));
        $quoted = ($raw !== '' && $raw[0] === chr(39));
        $val = $quoted ? substr($raw, 1, -1) : $raw;
        $pairs[] = [$key, $val, $quoted];
        $i = $vEnd + 1;
    }
    return $pairs;
}

/* ------------------------------------------------------------------ */
/* load + map (prefer /tmp salvage backup over possibly-rewritten live) */
/* ------------------------------------------------------------------ */
$found = [];
$dirs = [
    getenv('LOCALAPPDATA') . '/Temp/view-salvage', // real Windows temp (Git Bash rewrites TEMP=/tmp)
    getenv('TEMP') . '/view-salvage',
    $viewDir,
];
$dbgFiles = 0;
foreach ($dirs as $dir) {
    $list = glob("$dir/*.php");
    $list = is_array($list) ? $list : [];
    fwrite(STDERR, 'dir [' . $dir . '] -> ' . count($list) . ' files' . chr(10));
    foreach ($list as $compiled) {
        $dbgFiles++;
        $src = file_get_contents($compiled);
        $p = strpos($src, '/**PATH ');
        if ($p === false) {
            continue;
        }
        $start = $p + 8;
        $end = strpos($src, 'ENDPATH', $start);
        if ($end === false) {
            continue;
        }
        $path = str_replace(chr(92), '/', trim(substr($src, $start, $end - $start)));
        $anchor = '/resources/views/';
        $k = strrpos($path, $anchor);
        if ($k !== false) {
            $rel = substr($path, $k + strlen($anchor));
        } elseif (strpos($path, '/src/Illuminate/Pagination/') !== false) {
            $rel = 'vendor/pagination/tailwind.blade.php';
        } else {
            continue;
        }
        if (in_array($rel, $targets) && (!isset($found[$rel]) || strlen($src) > strlen($found[$rel]))) {
            $found[$rel] = $src;
        }
    }
}
if (count($found) !== count($targets)) {
    fail('expected ' . count($targets) . ' snapshots, found ' . count($found) . ': ' . implode(', ', array_keys($found)));
}

/* ------------------------------------------------------------------ */
/* decompile                                                           */
/* ------------------------------------------------------------------ */
foreach ($found as $rel => $src) {
    $src = str_replace(chr(13) . chr(10), chr(10), $src);
    // normalize both compiled if-forms: "<?php if(" -> "<?php if ("
    $src = str_replace('<?php if(', '<?php if (', $src);
    $src = str_replace('<?php elseif(', '<?php elseif (', $src);
    $p = strpos($src, '/**PATH ');
    $src = substr($src, 0, $p);
    $tail = '<?php ';
    substr($src, -strlen($tail)) === $tail and $src = substr($src, 0, strlen($src) - strlen($tail));

    $out = '';
    $pos = 0;
    $nComp = 0;

    while (true) {
        [$label, $i] = nextToken($src, $pos, $TOKENS);
        if ($label === null) {
            $out .= substr($src, $pos);
            break;
        }
        $out .= substr($src, $pos, $i - $pos);
        $pos = $i;

        switch ($label) {
            case 'F1':
            case 'F2':
                $pre = $label === 'F1' ? $TOKENS['F1'] : $TOKENS['F2'];
                $semi = strpos($src, '; $__env->addLoop(', $i);
                $expr = substr($src, $i + strlen($pre), $semi - ($i + strlen($pre)));
                $asNeedle = $label === 'F1' ? 'foreach($__currentLoopData as ' : 'foreach($__pol as ';
                $asPos = strpos($src, $asNeedle, $semi);
                if ($asPos === false) {
                    $asNeedle = $label === 'F1' ? 'foreach ($__currentLoopData as ' : 'foreach ($__pol as ';
                    $asPos = strpos($src, $asNeedle, $semi);
                }
                $colon = strpos($src, '):', $asPos);
                $asExpr = substr($src, $asPos + strlen($asNeedle), $colon - ($asPos + strlen($asNeedle)));
                $isForelse = $i >= 25 && substr($src, $i - 25, 25) === '<?php ' . '$__empty_1 = true; ';
                if ($isForelse) {
                    $prefix = '<?php ' . '$__empty_1 = true; ';
                    substr($out, -strlen($prefix)) === $prefix or fail('forelse prefix not at end of out');
                    $out = substr($out, 0, strlen($out) - strlen($prefix));
                }
                $out .= ($isForelse ? '@forelse(' : '@foreach(') . $expr . ' as ' . $asExpr . ')';
                $end4 = strpos($src, ';' . ' ?>', $colon);
                $end3 = strpos($src, ' ?>', $colon);
                if ($end3 !== false && ($end4 === false || $end3 < $end4)) {
                    $pos = $end3 + 3;
                } else {
                    $end4 !== false or fail('foreach tail not found after ' . $colon);
                    $pos = $end4 + 4;
                }
                break;

            case 'FE1':
                $out .= '@empty';
                $pos = $i + strlen($TOKENS['FE1']);
                break;

            case 'E1F':
                $out .= '@endforelse';
                $pos = $i + strlen($TOKENS['E1F']);
                break;

            case 'FEP':
                // placeholder-empty flag set inside loop body; drop entirely
                $pos = $i + strlen($TOKENS['FEP']);
                break;

            case 'FE':
                $out .= '@endforeach';
                $pos = $i + strlen($TOKENS['FE']);
                break;

            case 'IF':
            case 'EI':
                $pre = $label === 'IF' ? $TOKENS['IF'] : $TOKENS['EI'];
                $close = strpos($src, '): ?>', $i + strlen($pre));
                $cond = substr($src, $i + strlen($pre), $close - ($i + strlen($pre)));
                $out .= ($label === 'IF' ? '@if(' : '@elseif(') . $cond . ')';
                $pos = $close + 5;
                break;

            case 'EL':
                $out .= '@else';
                $pos = $i + strlen($TOKENS['EL']);
                break;

            case 'EN':
                $out .= '@endif';
                $pos = $i + strlen($TOKENS['EN']);
                break;

            case 'EF':
                $out .= '@endforeach';
                $pos = $i + strlen($TOKENS['EF']);
                break;

            case 'PHP':
                // raw php block (@php ... @endphp compiled as-is)
                $end = strpos($src, '?>', $i);
                if ($end === false) {
                    fail('php block tail not found');
                }
                $out .= '@php' . substr($src, $i + strlen($TOKENS['PHP']), $end - ($i + strlen($TOKENS['PHP']))) . '@endphp';
                $pos = $end + 2;
                break;

            case 'EE':
                $open = $i + strlen($TOKENS['EE']) - 1; // at the '('
                $after = matchParen($src, $open);
                $expr = substr($src, $open + 1, $after - 1 - ($open + 1));
                $out .= '{{ ' . $expr . ' }}';
                $pos = $after;
                // consume the closing tag chars that follow the expression
                if (substr($src, $after, 4) === ';' . ' ?>') {
                    $pos = $after + 4;
                } else {
                    fail('e() tail mismatch at ' . $after . ': ' . substr($src, $after, 40));
                }
                break;

            case 'YC':
                [$name, $j] = readQuoted($src, $i + strlen($TOKENS['YC']));
                if (substr($src, $j, 2) === ', ') {
                    $close = strpos($src, '); ?>', $j);
                    $arg = substr($src, $j + 2, $close - ($j + 2));
                    $out .= "@yield('$name', $arg)";
                    $pos = $close + 5;
                } else {                    $out .= "@yield('$name')";
                    $pos = $j + 5; // closing tag chars follow
                    if (substr($src, $j, 5) !== ');' . ' ?>') {
                        fail('yieldContent tail mismatch');
                    }
                }
                break;

            case 'YP':
                [$name, $j] = readQuoted($src, $i + strlen($TOKENS['YP']));
                if (substr($src, $j, 5) !== '); ?>') {
                    fail('yieldPushContent tail mismatch');
                }
                $out .= "@stack('$name')";
                $pos = $j + 5;
                break;

            case 'SS':
                [$name, $j] = readQuoted($src, $i + strlen($TOKENS['SS']));
                if (substr($src, $j, 2) === ', ') {
                    $close = strpos($src, '); ?>', $j);
                    $arg = substr($src, $j + 2, $close - ($j + 2));
                    $out .= "@section('$name', $arg)";
                    $pos = $close + 5;
                } else {
                    if (substr($src, $j, 5) !== '); ?>') {
                        fail('startSection tail mismatch');
                    }
                    $out .= "@section('$name')";
                    $pos = $j + 5;
                }
                break;

            case 'SE':
                $out .= '@endsection';
                $pos = $i + strlen($TOKENS['SE']);
                break;

            case 'SP':
                [$name, $j] = readQuoted($src, $i + strlen($TOKENS['SP']));
                if (substr($src, $j, 5) !== '); ?>') {
                    fail('startPush tail mismatch');
                }
                $out .= "@push('$name')";
                $pos = $j + 5;
                break;

            case 'EP':
                $out .= '@endpush';
                $pos = $i + strlen($TOKENS['EP']);
                break;

            case 'MK':
                $j = $i + strlen($TOKENS['MK']);
                [$view, $j] = readQuoted($src, $j);
                if (substr($src, $j, 2) === ', ') {
                    $j += 2;   // modern form: make('view', array_diff_key(...))
                }
                $close = strpos($src, ')->render(); ?>', $j);
                $inner = trim(substr($src, $j, $close - $j));
                $isExtends = $inner === ''
                    || strpos($inner, 'get_defined_vars') === 0
                    || strpos($inner, 'array_diff_key(get_defined_vars()') === 0;
                if ($isExtends) {
                    // modern form carries only the default data-exclusion expression
                    $ext = "@extends('$view')";
                    if (strpos($out, $ext) === false) {
                        $out = $ext . chr(10) . $out;   // hoist to top of the blade file
                    }
                } else {
                    $data = $inner;
                    $gv = strpos($data, ', ' . chr(92) . 'Illuminate' . chr(92) . 'Support' . chr(92) . 'Arr::except');
                    if ($gv !== false) {
                        $data = substr($data, 0, $gv);
                    }
                    $data = str_replace(chr(92) . chr(92), chr(92), $data);
                    $out .= "@include('$view', $data)";
                }
                $pos = $close + strlen(')->render(); ?>');
                break;

            case 'CS':
                $out .= '@csrf';
                $pos = $i + strlen($TOKENS['CS']);
                break;

            case 'JS':
                $open = $i + strlen($TOKENS['JS']) - 1;
                // find ", 15)" at depth 0 after the arg start
                $depth = 0;
                $j = $open;
                $n = strlen($src);
                $argEnd = false;
                while ($j < $n) {
                    $ch = $src[$j];
                    if ($ch === '(') {
                        $depth++;
                    } elseif ($ch === ')') {
                        $depth--;
                        if ($depth === 0) {
                            // check for ", 15)" form
                            if (substr($src, $j - 4, 5) === ', 15)') {
                                $argEnd = $j - 4;
                            }
                            break;
                        }
                    }
                    $j++;
                }
                if ($argEnd === false) {
                    fail('json_encode tail not found');
                }
                $arg = substr($src, $open + 1, $argEnd - ($open + 1));
                $out .= '@json(' . $arg . ')';
                $pos = $j + 1;
                if (substr($src, $pos, 1) === ' ') {
                    $pos++;
                }
                break;

            case 'RC':
                // component block: save1 .. restore-end
                $unsetPos = strpos($src, $N_UNSET, $i);
                if ($unsetPos === false) {
                    fail('component unset not found');
                }
                $endIf = strpos($src, '<?php endif; ?>', $unsetPos);
                if ($endIf === false) {
                    fail('component restore endif not found');
                }
                $regionEnd = $endIf + strlen('<?php endif; ?>');
                $region = substr($src, $i, $regionEnd - $i);

                // component name
                $vk = strpos($region, $N_VIEWKEY);
                if ($vk === false) {
                    fail('component view key not found');
                }
                $vEnd = strpos($region, chr(39), $vk + strlen($N_VIEWKEY));
                $compName = substr($region, $vk + strlen($N_VIEWKEY), $vEnd - ($vk + strlen($N_VIEWKEY)));

                // withAttributes
                $k = strpos($region, $N_WA_OPEN);
                if ($k === false) {
                    fail('withAttributes not found');
                }
                $kc = strpos($region, $N_WA_CLOSE, $k);
                if ($kc === false) {
                    fail('withAttributes close not found');
                }
                $attrsPhp = substr($region, $k + strlen($N_WA_OPEN), $kc - ($k + strlen($N_WA_OPEN)));
                $CTX = "[$rel @" . ($i + $kc + strlen($N_WA_CLOSE)) . ']';
                $slot = cleanSlot(substr($region, $kc + strlen($N_WA_CLOSE)));

                // rebuild tag
                $attrStr = '';
                foreach (parseAttrs($attrsPhp) as [$ak, $av, $aq]) {
                    if ($aq) {
                        $attrStr .= ' ' . $ak . '="' . $av . '"';
                    } else {
                        $attrStr .= ' :' . $ak . '="{{ ' . $av . ' }}"';
                    }
                }
                if (trim($slot) === '') {
                    $out .= '<x-' . $compName . $attrStr . ' />';
                } else {
                    $out .= '<x-' . $compName . $attrStr . '>' . $NL . $slot . $NL . '</x-' . $compName . '>';
                }
                $nComp++;
                $pos = $regionEnd;
                break;
        }
    }

    // tidy (PCRE-free)
    $out = str_replace(chr(13) . chr(10), $NL, $out);
    while (strpos($out, $NL . $NL . $NL) !== false) {
        $out = str_replace($NL . $NL . $NL, $NL . $NL, $out);
    }
    if (strpos($out, '<?php') !== false) {
        $k = strpos($out, '<?php');
        $ctx = substr($out, max(0, $k - 80), 240);
        $ctx = str_replace(chr(13), '<CR>', str_replace(chr(10), '<LF>', $ctx));
        fail("residual PHP in $rel at offset $k: " . $ctx);
    }

    $outPath = $outRoot . '/' . $rel;
    file_put_contents($outPath, $out);
    printf("%-38s components=%-3d bytes=%d%s", $rel, $nComp, strlen($out), PHP_EOL);
}
echo 'recovered ok' . PHP_EOL;
