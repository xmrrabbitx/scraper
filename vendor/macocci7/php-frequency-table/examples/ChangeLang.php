<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Macocci7\PhpFrequencyTable\FrequencyTable;

$ft = new FrequencyTable([
    'data' => [ 5, 10, 12, 15, 20, 25, 30, ],
    'classRange' => 10,
]);
$ft->meanOn()->setColumns2Show($ft->getValidColumns2Show());

echo "# Supported Languages\n\n";
foreach ($ft->langs() as $index => $lang) {
    echo "## Language:[" . $lang . "]\n\n";
    echo $ft->lang($lang)->markdown() . "\n\n";
}
