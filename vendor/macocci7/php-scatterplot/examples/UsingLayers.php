<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Macocci7\PhpScatterplot\Scatterplot;

$layers = [
    'John' => [
        'x' => [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, ],
        'y' => [ 1, 2, 3, 4, 5, 8, 4, 7, 11, 9, 1, ],
    ],
    'Jake' => [
        'x' => [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, ],
        'y' => [ 11, 8, 10, 7, 9, 6, 5, 3, 4, 2, 1, ],
    ],
    'Hugo' => [
        'x' => [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, ],
        'y' => [ 4, 8, 10, 1, 9, 6, 5, 3, 7, 1, 11, ],
    ],
    'Alex' => [
        'x' => [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, ],
        'y' => [ 3, 5, 11, 4, 8, 2, 9, 10, 1, 11, 7, ],
    ],
];

$legends = array_keys($layers);

$sp = new Scatterplot();
$sp->layers($layers)
   ->plotSize(6)
   ->legends($legends)
   ->labelX('Data X')
   ->labelY('Data Y')
   ->caption('Using Layers')
   ->create('img/UsingLayers.png');

// Markdown -------------------------------------

// Headline
echo "# PHP-Scatterplot : Using Layers\n\n";

// Layers
echo "<details><summary>Layers</summary>\n\n";
$dataCount = count($layers['Jake']['x']);
$columns = [];
foreach ($layers as $name => $layer) {
    $columns[] = $name . ": x";
    $columns[] = $name . ": y";
}
echo "|#|" . implode('|', $columns) . "|\n";
echo "|" . implode('|', array_fill(0, count($legends) * 2 + 1, ':---:')) . "|\n";
for ($i = 0; $i < $dataCount; $i++) {
    $columns = [];
    foreach ($layers as $layer) {
        $columns[] = $layer['x'][$i];
        $columns[] = $layer['y'][$i];
    }
    echo "|" . $i . "|" . implode('|', $columns) . "|\n";
}
echo "</details>\n\n";

// Regression Line Formula
echo "<details><summary>Regression Line Formula</summary>\n\n";
foreach ($layers as $name => $layer) {
    $formula = $sp->regressionLineFormula($layer['x'], $layer['y']);
    echo "- " . $name . ": y = " . $formula['a'] . " x + " . $formula['b'] . "\n";
}
echo "</details>\n\n";

// Scatter plot
echo "![UsingLayers.png](img/UsingLayers.png)\n";
