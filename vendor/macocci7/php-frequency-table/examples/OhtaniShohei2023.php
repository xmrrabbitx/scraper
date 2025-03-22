<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/class/CsvUtil.php';

use Macocci7\PhpFrequencyTable\FrequencyTable;
use Macocci7\CsvUtil;

$ft = new FrequencyTable();
$ft->setClassRange(5);

$csvUtil = new CsvUtil();
$dailyData = $csvUtil->getDailyData('csv/660271_data.csv');

echo "# Pitching speed (MPH)\n\n";
echo "## Pitcher:\n\n";
echo "[Ohtani Shohei](https://www.mlb.com/player/shohei-ohtani-660271)\n\n";
echo "## Data Source\n\n[savant](https://baseballsavant.mlb.com/)\n\n";
echo "## Dates\n\n";

foreach (array_keys($dailyData) as $key) {
    echo "- [" . $key . "](#" . $key . ")\n";
}

foreach ($dailyData as $key => $data) {
    echo "\n## " . $key . "\n\n";
    $ft->setData($data);
    echo "<details><summary>Properties</summary>\n\n";
    echo "|Property|Value|\n";
    echo "|:---|---:|\n";
    echo "|ClassRange|" . $ft->getClassRange() . "|\n";
    echo "|Max|" . $ft->getMax($data) . "|\n";
    echo "|Min|" . $ft->getMin($data) . "|\n";
    echo "|DataRange|" . $ft->getDataRange($data) . "|\n";
    echo "|Mode|" . $ft->getMode() . "|\n";
    echo "|Mean|" . number_format($ft->getMean(), 1, '.', ',') . "|\n";
    echo "|Median|" . $ft->getMedian($data) . "|\n";
    echo "|FirstQuartile|" . $ft->getFirstQuartile($data) . "|\n";
    echo "|ThirdQuartile|" . $ft->getThirdQuartile($data) . "|\n";
    echo "|InterQuartileRange|" . $ft->getInterQuartileRange($data) . "|\n";
    echo "|QuartileDeviation|" . $ft->getQuartileDeviation($data) . "|\n";
    echo "</details>\n\n";
    echo $ft->meanOn()->markdown();
    echo "\n\n";
}
