<?php
$code = file_get_contents($argv[1]);
$toks = token_get_all($code);
$line = 1;
foreach ($toks as $idx => $tok) {
    if (is_array($tok)) {
        $text = strlen($tok[1]) > 60 ? substr($tok[1], 0, 60) . '...' : $tok[1];
        echo "tok#$idx L$line {$tok[0]} " . str_replace("\n", '\n', $text) . "\n";
        $line += substr_count($tok[1], "\n");
    } else {
        echo "tok#$idx L$line SYM '$tok'\n";
    }
}
echo "total tokens: " . count($toks) . " (file ends at line " . (substr_count($code, "\n") + 1) . ")\n";
