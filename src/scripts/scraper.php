<?php

namespace Scraper\Trader\scripts;

use Scraper\Trader\analysis\analytic;
use Scraper\Trader\divar\divarApi;
use GuzzleHttp\Pool;

class scraper
{
    /**
     * @param string $className
     * @return void
     */
    public function scrapeDivar(string $className)
    {
        $divar= new divarApi();

        $divar->$className();
        //$divar->cloth();
        //$divar->stationery();
        //$divar->shoesBeltBag("major", null, 0, "1403/11/01");
        //$divar->shoesBeltBag();
        //$divar->accessories();
        //$divar->healthBeauty();

        //$divar->childrensClothingShoe("major", null, 0, "1403/11/01");
        //$divar->childrensClothingShoe();

        //$divar->stationery("major", null, 0, "1403/11/01");

    }
}

