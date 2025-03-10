<?php

include "./vendor/autoload.php";

use Scraper\Trader\divar\divarApi;

$divar= new divarApi();
$divar->cloth();
