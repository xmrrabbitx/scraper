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

$sp = new Scatterplot();
$sp->layers($layers)
   ->config('AdjustDisplayByNeon.neon')
   ->config([
    // This results in transparent backgournd
    'canvasBackgroundColor' => null,
   ])
   ->create('img/TransparentBackground.png');
