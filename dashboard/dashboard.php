<?php

include "vendor/autoload.php";

use Scraper\Trader\scripts\analyser;
use Scraper\Trader\Divar\divarApi;
use Scraper\Trader\scripts\scraper;

$analyser = new analyser();
$types = $analyser->getTypes("Divar");
if(!empty($types)){
    foreach ($types as $type){
        print("<div>$type</div>");
    }
}


//$scraper = new scraper();
//$scraper->scrape();


