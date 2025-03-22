<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Macocci7\PhpCombination\Combination;
use Macocci7\PhpCsv\Csv;
use Macocci7\PhpScatterplot\Scatterplot;

$cu = new Csv('csv/weather_tokyo.csv');
$cb = new Combination();

$cu->encode('SJIS', 'UTF-8')
   ->offsetRow(7);
$heads = $cu->row(5);
$days = $cu->raw()->column(1);

$dictionary = [
    '平均気温(℃)' => 'Mean Temperature(℃)',
    '最高気温(℃)' => 'Maximum Temperature(℃)',
    '最低気温(℃)' => 'Minimum Temperature(℃)',
    '日照時間(時間)' => 'Sunshine Hours(h)',
    '降水量の合計(mm)' => 'Precipitation Amount(mm)',
    '平均現地気圧(hPa)' => 'Mean Local Air Pressure(hPa)',
];
$columns = [2, 5, 8, 11, 15, 22];
$parsed = [];
$pairs = $cb->pairs($columns);
foreach ($pairs as $index => $pair) {
    $x = $pair[0];
    $y = $pair[1];
    $layers = [
        'Tokyo, Japan' => [
            'x' => $cu->float()->column($x),
            'y' => $cu->float()->column($y),
        ],
    ];

    $sp = new Scatterplot();
    $sp->layers($layers)
       ->regressionLineOn()
       ->labelX($dictionary[$heads[$x]])
       ->labelY($dictionary[$heads[$y]])
       ->caption('Weather in Tokyo : ' . $days[0] . '～' . $days[count($days) - 1])
       ->create(sprintf("img/Matrix%02d.png", $index));
    $parsed[] = $sp->parse($layers);
}

// Markdown -------------------------------------

echo "# Scatterplot Matrix: Weather in Tokyo\n\n";
echo "## Data Source\n\n"
   . "<a href='https://www.data.jma.go.jp/gmd/risk/obsdl/' target='_blank'>"
   . "Japan Meteorological Agency</a>\n\n";
echo "## Period: " . $days[0] . '～' . $days[count($days) - 1] . "\n";
$count = count($columns);
echo "<table>\n";
echo "<tr>\n<th>＼</th>\n";
foreach ($columns as $column) {
    echo "<th>" . $dictionary[$heads[$column]] . "</th>\n";
}
echo "</tr>\n";
$index = 0;
for ($b = 0; $b < $count; $b++) {
    echo "<tr><td>" . $dictionary[$heads[$columns[$b]]] . "</td>\n";
    for ($a = 0; $a < $count; $a++) {
        echo "<td>";
        if ($a > $b) {
            $imgsrc = 'img/Matrix' . sprintf('%02d', $index) . '.png';
            echo '<a href="' . $imgsrc . '">';
            echo '<img src="' . $imgsrc . '" width="120">';
            echo "</a><br />\n";
            echo "<details><summary>Properties</summary>\n\n";
            foreach ($parsed[$index]['Tokyo, Japan'] as $key => $property) {
                if (is_array($property)) {
                    echo "- " . $key . ": \n";
                    foreach ($property as $k => $v) {
                        echo "\t- " . $k . ": " . $v . "\n";
                    }
                } else {
                    echo "- " . $key . ": " . $property . "\n";
                }
            }
            echo "</details>";
            $index++;
        } else {
            echo "-";
        }
        echo "</td>\n";
    }
    echo "</tr>\n";
}
echo "</table>\n";
