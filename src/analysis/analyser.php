<?php

namespace Scraper\Trader\analysis;

use Scraper\Trader\core\General;
use Scraper\Trader\divar\divarApi;

/**
 * analysis the categories
 * descriptions are 2nd column in Xls file
 * prices are 4th column in Xls file
 */
class analyser
{
    protected array|null $activeSheet;

    const BASE_PATH = "../../src/xls/";
    const XLS_PATH_PLOT = "../src/xls/";

    /**
     * @param string $filePath
     */
    public function __construct(string $filePath=null)
    {
        $this->activeSheet = General::getSheetArray(self::BASE_PATH . $filePath, "Xls");
    }

    /**
     * @return array
     */
    public function listPrices():array
    {
        $listPrices = [];
        foreach ($this->activeSheet as $index=>$prices){
            if ($index === 0) {
                continue;
            }
            $listPrices[] = $prices[6];
        }

        return $listPrices;
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
            $listPrices[] = $prices[6];
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
            $listPrices[] = $prices[6];
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
            $listPrices[] = $prices[6];
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
            $listPrices[] = $prices[6];
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
        foreach ($this->activeSheet as $index => $prices){
            if ($index === 0) {
                continue;
            }
            $listPrices[] = $prices[6];
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
     * calculate median between max and min prices in each category
     * @return int
     */
    public function medianType(array $listPrices):int
    {
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
        $listPrices = [];
        foreach ($this->activeSheet as $info){
            $description = $info[1];
            if(preg_match("/$type/", $description)){
                $listTypes[] = $description;
                $listPrices[] = (int)$info[6];
            }
        }
        return [
            "sumTypes"=>count($listTypes) ,
            "sumProducts"=>count($this->activeSheet),
            "listPrices" => $listPrices
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

    /**
     * @param string $dirPath
     * @return array
     */
    public function getCategoryProducts(string $dirPath): array
    {
        foreach (scandir(self::BASE_PATH) as $directories){
            if($directories === $dirPath){
                $path = scandir(self::BASE_PATH . "/" . $dirPath);
                $path = array_diff($path, ['.', '..']);
            }
        }

        return $path ?? [];
    }

    /**
     * @param string $dirPath
     * @return array
     */
    public function getCategoryProductsPlot(string $dirPath): array
    {
        foreach (scandir(self::XLS_PATH_PLOT) as $directories){
            if($directories === $dirPath){
                $path = scandir(self::XLS_PATH_PLOT . "/" . $dirPath);
                $path = array_diff($path, ['.', '..']);
            }
        }

        return $path ?? [];
    }
}