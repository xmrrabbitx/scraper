<?php

include "./vendor/autoload.php";

use Scraper\Trader\analysis\analytic;
use Scraper\Trader\divar\divarApi;

$divar= new divarApi();
//$divar->cloth();
//$divar->shoesBeltBag();
$divar->accessories();



/*
$sumProducts = 0;
$sumProducts2 = 0;
$sumTypes = 0;
$sumTypes2 = 0;
for ($i=0;$i<=30;$i++) {
    if (is_file("./src/xls/1403/12/".$i.".xls")) {
        $analytic = new analytic("./src/xls/1403/12/".$i.".xls");

        $ftshalvar = $analytic->fT('شلوار');
        $sumProducts = $sumProducts + $ftshalvar['sumProducts'];
        $sumTypes = $sumTypes + $ftshalvar['sumTypes'];

        $result = $analytic->fTP($sumTypes, $sumProducts);
        var_dump($result);

        $ftmajlesi = $analytic->fT('مجلسی');
        $sumProducts2 = $sumProducts2 + $ftmajlesi['sumProducts'];
        $sumTypes2 = $sumTypes2 + $ftmajlesi['sumTypes'];
        $result = $analytic->fTP($sumTypes2, $sumProducts2);
        var_dump($result);
    }
}
*/


