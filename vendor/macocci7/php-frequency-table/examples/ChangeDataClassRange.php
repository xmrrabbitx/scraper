<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Macocci7\PhpFrequencyTable\FrequencyTable;

$ft = new FrequencyTable();

$dataset = [
    'Group A' => [
        'data' => [ 90, 20, 80, 30, 70, 40, 60, ],
        'classRange' => 20,
    ],
    'Group B' => [
        'data' => [ 100, 10, 65, 40, 55, 90, 72, 84, ],
        'classRange' => 25,
    ],
];

echo "# Frequency Tables\n\n";
foreach ($dataset as $key => $data) {
    $ft->setClassRange($data['classRange']);
    $ft->setData($data['data']);
    echo "## " . $key . "\n\n";
    echo $ft->meanOn()->markdown() . "\n\n";
}
