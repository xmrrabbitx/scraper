<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Macocci7\PhpCombination\Combination;

$c = new Combination();
$items = [ 'A', 'B', 'C', ];

foreach ($c->all($items) as $index => $item) {
    echo sprintf(
        "%d: (%s)\n",
        $index,
        implode(', ', $item)
    );
}
