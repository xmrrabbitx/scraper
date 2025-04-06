<?php

namespace Scraper\Trader\api;

include "../../vendor/autoload.php";

use Scraper\Trader\analysis\analyser;
use function Scraper\Trader\core\utilities\currentDate;
use function Scraper\Trader\core\utilities\gregorian_to_jalali;

header('Content-Type: application/json');

$json = file_get_contents('php://input');
$data = json_decode($json, true);
$dateFrom = $data['dateFrom'] ?? null;
$dateTo = $data['dateTo'] ?? null;
$category = $data['category'] ?? null;
$analyser = new analyser();
$productCategories = $analyser->getCategoryProducts("Divar");

if (!empty($productCategories)) {

    if ($dateFrom === null) {
        $cdate = explode("-", currentDate());
        $currentDate = gregorian_to_jalali($cdate[0], $cdate[1], $cdate[2]);
        $date = $currentDate[0] . "/" . $currentDate[1] . "/" . $currentDate[2];
    }elseif ($dateFrom === $dateTo){
        $date = $dateFrom;
    }else{

    }

    $filePath = 'Divar/' . $category . "/" . 'simple/' . $date . '.xls';
    $analyser = new analyser($filePath);

    $listPrices = $analyser->listPrices();

}

echo json_encode($listPrices);
exit;
