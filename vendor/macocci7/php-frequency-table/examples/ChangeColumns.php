<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Macocci7\PhpFrequencyTable\FrequencyTable;

$columns = [ 'Class', 'Frequency', ];
$ft = new FrequencyTable([
    'data' => [ 5, 10, 15, 20, 24, 27, 29, 30, 35, 40, ],
    'classRange' => 10,
    'columns2Show' => $columns,
]);

echo "# Changing Columns\n\n";

echo "## Case1:\n\n";
echo $ft->markdown() . "\n\n";

$columns = [ 'Class', 'Frequency', 'RelativeFrequency', ];
echo "## Case2:\n\n";
echo $ft->setColumns2Show($columns)->markdown() . "\n\n";

$columns = $ft->getValidColumns2Show();
echo "## Case3:\n\n";
echo $ft->setColumns2Show($columns)->meanOn()->markdown() . "\n\n";
