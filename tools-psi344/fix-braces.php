<?php
// PSI-344: convert PHP curly-brace string offsets ($s{$i}) to bracket syntax ($s[$i]).
// Tokenizer-based so "{$var}" interpolation inside strings is never touched.
// Usage: php fix-braces.php <file> [<file> ...]   (writes in place)
foreach (array_slice($argv, 1) as $path) {
    $src = file_get_contents($path);
    if ($src === false) { echo "SKIP $path (unreadable)\n"; continue; }
    $tokens = @token_get_all($src);
    if (!is_array($tokens)) { echo "SKIP $path (untokenizable)\n"; continue; }
    $out = '';
    $changed = 0;
    $n = count($tokens);
    $prevSig = '';
    for ($i = 0; $i < $n; $i++) {
        $tok = $tokens[$i];
        if (is_array($tok)) {
            if ($tok[0] === T_WHITESPACE || $tok[0] === T_COMMENT || $tok[0] === T_DOC_COMMENT) { $out .= $tok[1]; continue; }
            $id = $tok[0];
            $afterProp = ($prevSig === '->' || $prevSig === '::');
            if ($id === T_VARIABLE || $id === T_STRING_VARNAME || ($afterProp && $id === T_STRING)) {
                // Offset access candidate: variable followed by literal '{'
                $out .= $tok[1];
                $j = $i + 1;
                while ($j < $n && is_array($tokens[$j]) && in_array($tokens[$j][0], array(T_WHITESPACE, T_COMMENT), true)) $j++;
                if ($j < $n && $tokens[$j] === '{') {
                    // Find matching close brace
                    $depth = 0;
                    for ($k = $j; $k < $n; $k++) {
                        if ($tokens[$k] === '{') $depth++;
                        elseif ($tokens[$k] === '}') { $depth--; if ($depth === 0) break; }
                    }
                    if ($k < $n) {
                        for ($m = $j; $m <= $k; $m++) {
                            if ($tokens[$m] === '{') { $out .= '['; $changed++; }
                            elseif ($tokens[$m] === '}') { $out .= ']'; }
                            else { $out .= is_array($tokens[$m]) ? $tokens[$m][1] : $tokens[$m]; }
                        }
                        $i = $k;
                        $prevSig = ']';
                        continue;
                    }
                }
                $prevSig = $tok[1];
                continue;
            }
            $out .= $tok[1];
            $prevSig = ($id === T_STRING) ? strtolower($tok[1]) : $tok[1];
        } else {
            $out .= $tok;
            $prevSig = $tok;
        }
    }
    if ($changed > 0) {
        file_put_contents($path, $out);
        echo "FIXED $path ($changed offsets)\n";
    } else {
        echo "CLEAN $path\n";
    }
}
