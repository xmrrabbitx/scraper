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
   ->limitX(0, 12)
   ->limitY(0, 12)
   ->gridXPitch(2)
   ->gridYPitch(2)
   ->bgcolor('#ccccff')
   ->colors(['#ffffff'])
   ->plotSize(4)
   ->fontColor('#333333')
   ->grid(1, '#999999')
   ->gridXOn()
   ->gridYOn()
   ->regressionLine(3, [ '#666666', '#cc2222', '#2222cc', '#22cc22', ])
   ->referenceLineX(1.5, 1, '#00ccff')
   ->referenceLineY(1.5, 1, '#00ccff')
   ->specificationLimitX(0.5, 11.5, 1, '#ff00ff')
   ->specificationLimitY(0.5, 11.5, 1, '#ff00ff')
   ->labelX('DATA X')
   ->labelY('DATA Y')
   ->caption('SCATTER PLOT')
   ->legends($legends)
   ->create('img/AdjustDisplayByMethods.png');

// Markdown -------------------------------------

// Headline
echo "# Adjusting the Display By Methods\n\n";

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

// Properties
echo "<details><summary>Properties</summary>\n\n";
echo "|Key|Value|\n";
echo "|:---:|:---:|\n";
foreach ($sp->getConfig() as $key => $value) {
    echo "|" . $key . "|"
        . (is_array($value) ? "[" . implode(', ', $value) . "]"
            : (is_null($value) ? '`null`'
                : (true === $value ? '`true`'
                    : (false === $value ? '`false`' : $value)
                )
            )
        )
        . "|\n";
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
echo "![AdjustDisplayByMethods.png](img/AdjustDisplayByMethods.png)\n";
