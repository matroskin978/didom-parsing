<?php

$s = '<script>alert("Hi, &lt;b&gt;\'User\'&lt;/b&gt;!")</script>';

//echo htmlspecialchars($s, ENT_COMPAT, 'UTF-8', false);
//echo htmlspecialchars($s, double_encode: false);
//echo htmlspecialchars($s, encoding: 'UTF-8', double_encode: false);

function test(string $a = 'A', string $b = 'B', string $c = 'C'): string
{
    return "$a $b $c";
}

echo test('N', b: 'K', c: 'L');
