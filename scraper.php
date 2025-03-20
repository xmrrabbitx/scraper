<?php

include "./vendor/autoload.php";

use Scraper\Trader\analysis\analytic;
use Scraper\Trader\divar\divarApi;

$divar= new divarApi();

//$divar->cloth();
//$divar->shoesBeltBag();
//$divar->accessories();
//$divar->healthBeauty();

//$divar->childrensClothingShoe("major", null, 0, "1403/11/01");
//$divar->childrensClothingShoe();

//$divar->stationery("major", null, 0, "1403/11/01");
$divar->stationery();
