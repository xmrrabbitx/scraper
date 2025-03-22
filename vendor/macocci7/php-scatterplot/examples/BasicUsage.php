<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Macocci7\PhpScatterplot\Scatterplot;

$layers = [
    [
        'x' => [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, ],
        'y' => [ 1, 2, 3, 4, 5, 8, 4, 7, 11, 9, 1, ],
    ],
];

$sp = new Scatterplot();
$sp->layers($layers)
   ->create('img/BasicUsage.png');

// Markdown -------------------------------------

// Headline
echo "# PHP-Scatterplot : Basic Usage\n\n";

// Layers
echo "<details><summary>Layer</summary>\n\n";
$dataCount = count($layers[0]['x']);
echo "|#|x|y|\n";
echo "|:---:|:---:|:---:|\n";
$xs = $layers[0]['x'];
$ys = $layers[0]['y'];
for ($i = 0; $i < $dataCount; $i++) {
    echo "|" . $i . "|" . $xs[$i] . "|" . $ys[$i] . "|\n";
}
echo "\n</details>\n\n";

// Regression Line Formula
echo "<details><summary>Regression Line Formula</summary>\n\n";
$formula = $sp->regressionLineFormula($xs, $ys);
echo "- y = " . $formula['a'] . " x + " . $formula['b'] . "\n";
echo "</details>\n\n";

// Scatter plot
echo "![BasicUsage.png](img/BasicUsage.png)\n";
