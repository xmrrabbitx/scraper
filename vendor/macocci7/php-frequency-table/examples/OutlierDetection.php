<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/class/CsvUtil.php';
require_once __DIR__ . '/class/Outlier.php';

use Macocci7\PhpFrequencyTable\FrequencyTable;
use Macocci7\CsvUtil;
use Macocci7\Outlier;

$csvUtil = new CsvUtil();
$csvFileName = 'csv/672282_data.csv';

$ft = new FrequencyTable();
$ft->setClassRange(10);

$ol = new Outlier();

$propertyKeys = [
    'classRange',
    'Max',
    'Min',
    'DataRange',
    'Mode',
    'Mean',
    'Median',
    'FirstQuartile',
    'ThirdQuartile',
    'InterQuartileRange',
    'QuartileDeviation',
];

echo "# Pitching speed (MPH)\n\n";
echo "## Data Source\n\n[savant](https://baseballsavant.mlb.com/)\n\n";
echo "## Pitcher:\n\n";

echo " Detmers, Reid (672282)\n\n";

$dailyData = $csvUtil->getDailyData($csvFileName);
if (!$dailyData) {
    echo "Failed to load CSV data.\n\n";
}

echo "## Dates\n\n";

foreach (array_keys($dailyData) as $key) {
    echo "- [" . $key . "](#" . $key . ")\n";
}
foreach ($dailyData as $date => $data) {
    echo "\n## " . $date . "\n\n";
    $ft->setData($data);
    $parsed = $ft->parse();
    echo "<details><summary>Data</summary>\n\n";
    echo "|#|release_speed|\n";
    echo "|---|---|\n";
    foreach ($parsed['data'] as $index => $value) {
        echo "|" . ($index + 1) . "|" . $value . "|\n";
    }
    echo "</details>\n\n";
    echo "<details><summary>Properties</summary>\n\n";
    echo "|Property|Value|\n";
    echo "|---|---|\n";
    foreach ($propertyKeys as $key) {
        echo "|" . $key . "|" . $parsed[$key] . "|\n";
    }
    echo "|UCL|" . $ol->getUcl($parsed) . "|\n";
    echo "|LCL|" . $ol->getLcl($parsed) . "|\n";
    echo "</details>\n\n";
    $outliers = $ol->getOutliers($data);
    if ($outliers) {
        echo "<details><summary>Outliers</summary>\n\n";
        echo "|#|Value|\n";
        echo "|---|---|\n";
        foreach ($outliers as $index => $value) {
            echo "|" . ($index + 1) . "|" . $value . "|\n";
        }
        echo "</details>\n\n";
    } else {
        echo "no outliers.\n\n";
    }
}
echo "\n\n";
