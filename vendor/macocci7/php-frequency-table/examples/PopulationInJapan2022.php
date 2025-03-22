<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Macocci7\PhpFrequencyTable\FrequencyTable;

$population = [
    '北海道' => 5246170,
    '青森県' => 1249527,
    '岩手県' => 1227142,
    '宮城県' => 2321203,
    '秋田県' => 970724,
    '山形県' => 1070600,
    '福島県' => 1842608,
    '茨城県' => 2924544,
    '栃木県' => 1974255,
    '群馬県' => 1987570,
    '埼玉県' => 7328472,
    '千葉県' => 6296556,
    '東京都' => 14042127,
    '神奈川県' => 9271325,
    '新潟県' => 2150392,
    '富山県' => 1064722,
    '石川県' => 1157057,
    '福井県' => 778444,
    '山梨県' => 850270,
    '長野県' => 2094126,
    '岐阜県' => 1969582,
    '静岡県' => 3763570,
    '愛知県' => 7533419,
    '三重県' => 1815420,
    '滋賀県' => 1440474,
    '京都府' => 2609771,
    '大阪府' => 8833907,
    '兵庫県' => 5515383,
    '奈良県' => 1362000,
    '和歌山県' => 944407,
    '鳥取県' => 555491,
    '島根県' => 667646,
    '岡山県' => 1917334,
    '広島県' => 2808524,
    '山口県' => 1360744,
    '徳島県' => 727080,
    '香川県' => 974044,
    '愛媛県' => 1384016,
    '高知県' => 682466,
    '福岡県' => 5117171,
    '佐賀県' => 818112,
    '長崎県' => 1331605,
    '熊本県' => 1745952,
    '大分県' => 1162114,
    '宮崎県' => 1085258,
    '鹿児島県' => 1589361,
    '沖縄県' => 1435479,
];

$classRange = 1000000;  // 1 million

$ft = new FrequencyTable([
    'data' => $population,
    'classRange' => $classRange,
    'columns2Show' => [ // Only specified columns will be shown.
        'Class',
        'Frequency',
        'CumulativeFrequency',
        'RelativeFrequency',
        'CumulativeRelativeFrequency',
        'ClassValue',
        'ClassValue * Frequency',
    ],
]);

echo "# Population in Japan, in 2022\n";
echo "<details><summary>使用データ(INPUT DATA)：総務省(Ministry of Internal Affairs and Communications)</summary>\n\n<br />\n\n";
echo "**Population In Japan, in 2022**\n<br />\n\n";
echo "|Prefecture|Population|\n";
echo "|:---:|---:|\n";
foreach ($population as $key => $value) {
    echo sprintf("|%s|%s|\n", $key, number_format($value));
}
echo "</details>\n\n<br />\n\n";
echo "## Frequency Table\n\n";
echo $ft->meanOn()->markdown();
echo "\n";
