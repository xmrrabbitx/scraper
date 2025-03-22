<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Macocci7\PhpScatterplot\Analyzer;

$a = new Analyzer();

$layers = [
    'John' => [
        'x' => [1,2,3,4,5,6,7,8,9,10,11],
        'y' => [1,2,3,4,5,8,4,7,11,9,1],
    ],
    'Jake' => [
        'x' => [1,2,3,4,5,6,7,8,9,10,11],
        'y' => [11,8,10,7,9,6,5,3,4,2,1],
    ],
];

var_dump($a->parse($layers));
