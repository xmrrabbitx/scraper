<?php

namespace Macocci7;

use Macocci7\PhpFrequencyTable\FrequencyTable;

/**
 * class for treating outliers
 */
class Outlier
{
    /**
     * returns Upper Control Limit
     * @param   array<string, mixed>
     * @return  int|float|null
     */
    public function getUcl(array $parsed)
    {
        if (!array_key_exists('ThirdQuartile', $parsed)) {
            return null;
        }
        if (!array_key_exists('InterQuartileRange', $parsed)) {
            return null;
        }
        return $parsed['ThirdQuartile'] + 1.5 * $parsed['InterQuartileRange'];
    }

    /**
     * returns Lower Control Limit
     * @param   array<string, mixed>
     * @return  int|float|null
     */
    public function getLcl(array $parsed)
    {
        if (!array_key_exists('FirstQuartile', $parsed)) {
            return null;
        }
        if (!array_key_exists('InterQuartileRange', $parsed)) {
            return null;
        }
        return $parsed['FirstQuartile'] - 1.5 * $parsed['InterQuartileRange'];
    }

    /**
     * returns outliers
     * @param   list<mixed> $data
     * @return  list<int|float>|null
     */
    public function getOutliers($data)
    {
        $ft = new FrequencyTable();
        if (!$ft->isSettableData($data)) {
            return null;
        }

        $ft->setClassRange(10);
        $ft->setData($data);
        $parsed = $ft->parse();

        $ucl = $this->getUcl($parsed);
        $lcl = $this->getLcl($parsed);
        if (!$ucl || !$lcl) {
            return null;
        }

        $outliers = [];
        foreach ($data as $value) {
            if ($value > $ucl || $value < $lcl) {
                $outliers[] = $value;
            }
        }
        unset($ft);
        return $outliers;
    }
}
