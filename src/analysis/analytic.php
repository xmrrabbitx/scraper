<?php

namespace Scraper\Trader\analysis;

use Scraper\Trader\core\General;

/**
 * analysis the categories
 * descriptions are 2nd column in Xls file
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
            $listPrices[] = $prices[5];
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
            $listPrices[] = $prices[5];
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
            $listPrices[] = $prices[5];
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
            $listPrices[] = $prices[5];
        }

        return (int)(array_sum($listPrices)/count($listPrices));
    }

    /**
     * calculate median between max and min prices in each category
     * @return int
     */
    public function median():int
    {
        $listPrices = [];
        foreach ($this->activeSheet as $index=>$prices){
            if ($index === 0) {
                continue;
            }
            $listPrices[] = $prices[5];
        }
        sort($listPrices);
        $length = count($listPrices);
        $middle_index = floor(($length - 1) / 2);
        if ($length % 2) {
            return $listPrices[$middle_index];
        } else {
            $low = $listPrices[$middle_index];
            $high = $listPrices[$middle_index + 1];
            var_dump($high);
            var_dump($low);
            return ($low + $high) / 2;
        }
    }

    /**
     * return frequency type product
     * @param string $type
     * @return float percentage of frequency each type
     */
    public function frequencyType(string $type):float
    {
        $listTypes = [];
        foreach ($this->activeSheet as $info){
            $description = $info[1];
            if(preg_match("/.$type./", $description)){
                $listTypes[] = $description;
            }
        }
        return number_format((float)((count($listTypes) * 100) / count($this->activeSheet)), 5);
    }
}