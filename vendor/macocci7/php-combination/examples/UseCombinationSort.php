<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Macocci7\PhpCombination\Combination;

// Create an Instance
$c = new Combination();

// All Items
$items = [ 'A', 'B', 'C', 'D', 'E', ];
echo sprintf("All Items:\n\t(%s)\n\n", implode(", ", $items));

// Set a flag to sort
$sort = true;

// Call back
$f = function (array $array): string {
    return sprintf("(%s)", implode(', ', $array));
};

// All Combinations
echo sprintf(
    "All Combinations:\n\t%s\n\n",
    implode("\n\t", array_map($f, $c->all($items, $sort)))
);

// All Pairs: does not support sorting
echo sprintf(
    "All Pairs:\n\t%s\n\n",
    implode("\n\t", array_map($f, $c->pairs($items)))
);

// All Combinations of $n elements
$n = 3;
echo sprintf(
    "All Combinations of %d elements:\n\t%s\n\n",
    $n,
    implode("\n\t", array_map($f, $c->ofN($items, $n, $sort)))
);

// All Combinations of $a to $b elements
$a = 3;
$b = 4;
echo sprintf(
    "All Combinations of %d to %d elements:\n\t%s\n\n",
    $a,
    $b,
    implode("\n\t", array_map($f, $c->ofA2B($items, $a, $b, $sort)))
);

// All Combinations of $a1, $a2 and $a3: does not support sorting
$a1 = [ 'A1', 'A2', ];
$a2 = [ 'B1', 'B2', 'B3', ];
$a3 = [ 'C1', 'C2', 'C3', 'C4', ];

echo "All Combinations of multiple arrays:\n";
echo sprintf("\tArray1: (%s)\n", implode(', ', $a1));
echo sprintf("\tArray2: (%s)\n", implode(', ', $a2));
echo sprintf("\tArray3: (%s)\n", implode(', ', $a3));

$r = $c->fromArrays([$a1, $a2, $a3, ]);
$count = count($a1) * count($a2) * count($a3);
$n = strlen((string) $count);
echo sprintf("\tThere're %d patterns:\n", $count);
foreach ($r as $i => $e) {
    echo sprintf("\t%" . $n . "d: (%s)\n", $i + 1, implode(', ', $e));
}
