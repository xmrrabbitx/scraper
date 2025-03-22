<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Macocci7\PhpCombination\CombinationGenerator;

// Create an Instance
$c = new CombinationGenerator();

// All Items
$items = [ 'A', 'B', 'C', 'D', 'E', ];
echo sprintf("All Items:\n\t(%s)\n\n", implode(", ", $items));

// Common Format
$fmt = "\t(%s)\n";

// All Combinations
echo "All Combinations:\n";
foreach ($c->all($items) as $e) {
    echo sprintf($fmt, implode(', ', $e));
}
echo "\n";

// All Pairs
echo "All Pairs:\n";
foreach ($c->pairs($items) as $e) {
    echo sprintf($fmt, implode(', ', $e));
}
echo "\n";

// All Combinations of $n elements
$n = 3;
echo sprintf("All Combinations of %d elements:\n", $n);
foreach ($c->ofN($items, $n) as $e) {
    echo sprintf($fmt, implode(', ', $e));
}
echo "\n";

// All Combinations of $a to $b elements
$a = 3;
$b = 4;
echo sprintf(
    "All Combinations of %d to %d elements:\n",
    $a,
    $b,
);
foreach ($c->ofA2B($items, $a, $b) as $e) {
    echo sprintf($fmt, implode(', ', $e));
}
echo "\n";

// All Combinations of $a1, $a2 and $a3
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
