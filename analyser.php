<?php


include "./vendor/autoload.php";

use Scraper\Trader\analysis\analytic;

$sumProducts = 0;
$sumProducts2 = 0;
$sumTypes = 0;
$sumTypes2 = 0;
for ($i=0;$i<=30;$i++) {
    if (is_file("./src/xls/Divar/childrensClothingShoe/major/1403/12/".$i.".xls")) {
        $analytic = new analytic("./src/xls/Divar/childrensClothingShoe/major/1403/12/".$i.".xls");

        $ft1 = $analytic->fT('خودکار');
        $sumProducts = $sumProducts + $ft1['sumProducts'];
        $sumTypes = $sumTypes + $ft1['sumTypes'];

    }
    if (is_file("./src/xls/Divar/childrensClothingShoe/simple/1403/12/".$i.".xls")) {
        $analytic2 = new analytic("./src/xls/Divar/childrensClothingShoe/simple/1403/12/".$i.".xls");

        $ft2 = $analytic2->fT('خودکار');
        $sumProducts2 = $sumProducts2 + $ft2['sumProducts'];
        $sumTypes2 = $sumTypes2 + $ft2['sumTypes'];

    }
}

//$result = $analytic->fTP($sumTypes, $sumProducts);
//var_dump($result);
var_dump($analytic->median());

//$result2 = $analytic->fTP($sumTypes2, $sumProducts2);
//var_dump($result2);
var_dump($analytic2->median());