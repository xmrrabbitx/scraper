<?php

namespace Scraper\Trader\analysis;

use Scraper\Trader\core\General;

/**
 * analysis the categories
 * prices are 4th column in Xls file
 */
class analytic
{

    protected array $activeSheet;

    /**
     * @param string $filePath
     */
    public function __construct($filePath)
    {

        $this->activeSheet = General::getSheetArray($filePath, "Xls") ?? null;

    }

    /**
     * calculate max prices
     * @return int
     */
    public function max():int
    {
        $listPrices = [];
        foreach ($this->activeSheet as $index=>$prices){
            if ($index === 0) {
                continue;
            }
            $listPrices[] = $prices[4];
        }

       return (int)max($listPrices);
    }

    /**
     * calculate min prices
     * @return int
     */
    public function min():int
    {
        $listPrices = [];
        foreach ($this->activeSheet as $index=>$prices){
            if ($index === 0) {
                continue;
            }
            $listPrices[] = $prices[4];
        }

        return (int)min($listPrices);
    }

    /**
     * calculate sum prices
     * @return int
     */
    public function sum():int
    {
        $listPrices = [];
        foreach ($this->activeSheet as $index => $prices){
            if ($index === 0) {
                continue;
            }
            $listPrices[] = $prices[4];
        }

        return (int)array_sum($listPrices);
    }

    /**
     * calculate average
     * @return int
     */
    public function average():int
    {
        $listPrices = [];
        foreach ($this->activeSheet as $index=>$prices){
            if ($index === 0) {
                continue;
            }
            $listPrices[] = $prices[4];
        }

        return (int)(array_sum($listPrices)/count($listPrices));
    }

    /**
     * calculate average between max and min prices in each category
     * @return int
     */
    public function averagePrice():int
    {
        $listPrices = [];
        foreach ($this->activeSheet as $index=>$prices){
            if ($index === 0) {
                continue;
            }
            $listPrices[] = $prices[4];
        }

        return (int)((max($listPrices)-min($listPrices)) / 2);
    }

}