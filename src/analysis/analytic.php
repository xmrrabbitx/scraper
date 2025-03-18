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
    protected array|null $activeSheet;

    /**
     * @param string $filePath
     */
    public function __construct(string $filePath)
    {

        $this->activeSheet = General::getSheetArray($filePath, "Xls");

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
            return ($low + $high) / 2;
        }
    }

    /**
     * return frequency type product
     * @param string $type
     * @return array sum of frequency each type
     */
    public function fT(string $type):array
    {
        $listTypes = [];
        foreach ($this->activeSheet as $info){
            $description = $info[1];
            if(preg_match("/$type/", $description)){
                $listTypes[] = $description;
            }
        }
        return [
            "sumTypes"=>count($listTypes) ,
            "sumProducts"=>count($this->activeSheet)
        ];
    }

    /**
     * return frequency type percentage
     * @param array $listTypes
     * @return float percentage of frequency each type
     */
    public function fTP(int $sumTypes, int $sumProducts):float
    {
        return number_format((float)(($sumTypes * 100) / $sumProducts), 5);
    }
}