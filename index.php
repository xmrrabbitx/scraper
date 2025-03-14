<?php

include "./vendor/autoload.php";

use Scraper\Trader\analysis\analytic;
use Scraper\Trader\divar\divarApi;

//$divar= new divarApi();
//$divar->cloth();

$analytitc = new analytic('./src/xls/1403/12/23.xls');
$max = $analytitc->max();
var_dump($max);
$min = $analytitc->min();
var_dump($min);
$sum = $analytitc->sum();
var_dump($sum);
$average = $analytitc->average();
var_dump($average);
$averagePrice = $analytitc->median();
var_dump($averagePrice);