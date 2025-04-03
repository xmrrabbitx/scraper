<?php

namespace Scraper\Trader\api;

include "../../vendor/autoload.php";

use Scraper\Trader\analysis\analyser;
use function Scraper\Trader\core\utilities\currentDate;
use function Scraper\Trader\core\utilities\gregorian_to_jalali;

header('Content-Type: application/json');

$analyser = new analyser();
$productCategories = $analyser->getCategoryProducts("Divar");
$currenetDate = currentDate();
$date = null;

if (!empty($productCategories)) {
    foreach ($productCategories as $category) {

        if($category === "clothing") {
            if (!$date) {
                $cdate = explode("-", currentDate());
                $currentDate = gregorian_to_jalali($cdate[0], $cdate[1], $cdate[2]);
                $date = $currentDate[0] . "/" . $currentDate[1] . "/" . $currentDate[2];
            }
            $filePath = 'Divar/' . $category . "/" . 'simple/' . $date . '.xls';
            $analyser = new analyser($filePath);

            $listPrices = $analyser->listPrices();

        }
    }
}

echo json_encode($listPrices);
exit;
