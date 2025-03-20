<?php


include "./vendor/autoload.php";

use Scraper\Trader\analysis\analytic;

$sumProducts = 0;
$sumProducts2 = 0;
$sumTypes = 0;
$sumTypesPrices = [];
$sumTypes2 = 0;
$sumTypesPrices2 = [];
for ($i=0;$i<=30;$i++) {
    if (is_file("./src/xls/Divar/shoesBeltBag/major/1403/12/".$i.".xls")) {
        $analytic = new analytic("./src/xls/Divar/shoesBeltBag/major/1403/12/".$i.".xls");

        $ft1 = $analytic->fT('دمپایی');
        $sumProducts = $sumProducts + $ft1['sumProducts'];
        $sumTypes = $sumTypes + $ft1['sumTypes'];
        $sumTypesPrices = $ft1['listPrices'];
        var_dump($sumTypesPrices);
    }
    if (is_file("./src/xls/Divar/shoesBeltBag/simple/1403/12/".$i.".xls")) {
        $analytic2 = new analytic("./src/xls/Divar/shoesBeltBag/simple/1403/12/".$i.".xls");

        $ft2 = $analytic2->fT('دمپایی');
        $sumProducts2 = $sumProducts2 + $ft2['sumProducts'];
        $sumTypes2 = $sumTypes2 + $ft2['sumTypes'];
        $sumTypesPrices2 = $ft2['listPrices'];

    }
}

$result = $analytic->fTP($sumTypes, $sumProducts);
//var_dump($result);
//var_dump($analytic->medianType($sumTypesPrices));

$result2 = $analytic->fTP($sumTypes2, $sumProducts2);
//var_dump($result2);
//var_dump($analytic2->medianType($sumTypesPrices2));