<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Macocci7\PhpCsv\Csv;

// load a csv
$filename = 'csv/weather_tokyo_2023nov_sjis.csv';
$csv = new Csv($filename);

// encode data and save it
$csv->encode('SJIS', 'UTF-8')->save('csv/weather_tokyo_2023nov_utf8.csv');

// properties
echo sprintf(
    "Filename:[%s], %d rows, %d columns.\n\n",
    $filename,
    $csv->countRows(),
    $csv->countColumns()
);

// retrieve a row
echo sprintf("%s\n\n", implode(', ', $csv->row(5)));

// offset of rows for column()
$csv->offsetRow(7);

// retrieve columns: Date & Max temprature & Min temprature
echo sprintf("%s\n\n", implode(', ', $csv->string()->column(1)));
echo sprintf("%s\n\n", implode(', ', $csv->float()->column(2)));
echo sprintf("%s\n\n", implode(', ', $csv->int()->column(2)));
echo sprintf("%s\n\n", implode(', ', $csv->bool()->column(2)));
echo sprintf("%s\n\n", implode(', ', $csv->raw()->column(2)));
