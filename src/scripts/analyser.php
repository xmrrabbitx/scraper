<?php

namespace Scraper\Trader\scripts;

use Scraper\Trader\analysis\analytic;
use function Scraper\Trader\core\utilities\currentDate;
use function Scraper\Trader\core\utilities\gregorian_to_jalali;

class analyser
{
    const BASE_PATH = "../src/xls/";

    public function getTypeProducts(string $dirPath): array
    {
        foreach (scandir(self::BASE_PATH) as $directories){
            if($directories === $dirPath){
                $path = scandir(self::BASE_PATH . "/" . $dirPath);
                $path = array_diff($path, ['.', '..']);
            }
        }

        return $path ?? [];
    }

    public function sumPrices(string $serviceName, string $productCategory, string $type, string $date=null)
    {
        if(!$date){
            $cdate = explode("-", currentDate());
            $currentDate = gregorian_to_jalali($cdate[0],$cdate[1], $cdate[2]);
            $date = $currentDate[0] . "/" . $currentDate[1] . "/" . $currentDate[2];
        }

        $analytic = new analytic(self::BASE_PATH . $serviceName . "/" . $productCategory . "/" . $type . "/" . $date . ".xls");
        return $analytic->sum();
    }
}
/*
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
$med = $analytic->medianType($sumTypesPrices);
//var_dump($med);
$plot = new plotAnalyser();
$plot->medianScatter($sumTypesPrices);

$result2 = $analytic->fTP($sumTypes2, $sumProducts2);
//var_dump($result2);
//var_dump($analytic2->medianType($sumTypesPrices2));
*/