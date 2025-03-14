<?php

include "./vendor/autoload.php";

use Scraper\Trader\analysis\analytic;
use Scraper\Trader\divar\divarApi;

//$divar= new divarApi();
//$divar->cloth();

$analytitc = new analytic('./src/xls/1403/12/24.xls');
$max = $analytitc->max();
var_dump($max);
$min = $analytitc->min();
var_dump($min);
$sum = $analytitc->sum();
var_dump($sum);
$average = $analytitc->average();
var_dump($average);
$medianPrice = $analytitc->median();
var_dump($medianPrice);
$frequency = $analytitc->frequencyType('کت');
var_dump($frequency);
$frequency = $analytitc->frequencyType('شومیز');
var_dump($frequency);
$frequency = $analytitc->frequencyType('مانتو');
var_dump($frequency);
$frequency = $analytitc->frequencyType('شلوار');
var_dump($frequency);
$frequency = $analytitc->frequencyType('مردانه');
var_dump($frequency);
$frequency = $analytitc->frequencyType('زنانه');
var_dump($frequency);
$frequency = $analytitc->frequencyType('مجلسی');
var_dump($frequency);
$frequency = $analytitc->frequencyType('بچگانه');
var_dump($frequency);
$frequency = $analytitc->frequencyType('شال');
var_dump($frequency);
$frequency = $analytitc->frequencyType('روسری');
var_dump($frequency);
$frequency = $analytitc->frequencyType('دامن');
var_dump($frequency);
$frequency = $analytitc->frequencyType('جوراب');
var_dump($frequency);