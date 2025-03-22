<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Macocci7\PhpFrequencyTable\FrequencyTable;

$ft = new FrequencyTable([
    'data' => [ 0, 5, 10, 15, 20, ],
    'classRange' => 10,
]);
echo $ft->meanOn()->markdown();
