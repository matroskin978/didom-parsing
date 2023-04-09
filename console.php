<?php

set_time_limit(0);
ini_set('memory_limit', -1);

if (empty($argv[1])) {
    die('Empty argument!');
}
$max = $argv[2] ?? 3;

for ($i = 1; $i <= $argv[1]; $i++) {
    sleep(rand(1, $max));
    echo $i . PHP_EOL;
}

echo "DONE!";
