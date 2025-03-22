<?php

namespace Macocci7\PhpScatterplot;

use Macocci7\PhpFrequencyTable\FrequencyTable;
use Macocci7\PhpScatterplot\Traits\JudgeTrait;

define("LIMIT_LAYERS", 8);

/**
 * Class for analysis
 * @author  macocci7 <macocci7@yahoo.co.jp>
 * @license MIT
 */
class Analyzer
{
    use JudgeTrait;

    /**
     * Frequency Table
     */
    public FrequencyTable $ft;

    /**
     * @var mixed[] $parsed
     */
    public array $parsed;

    /**
     * constructor
     */
    public function __construct()
    {
        $this->ft = new FrequencyTable();
    }

    /**
     * calculates mean of $data
     * @param   array<int|string, int|float>    $data
     * @return  float|null
     */
    public function mean(array $data)
    {
        if (!self::isValidData($data)) {
            return null;
        }
        return array_sum($data) / count($data);
    }

    /**
     * calculates variance of $data
     * @param   array<int|string, int|float>    $data
     * @return  float|null
     */
    public function variance(array $data)
    {
        if (!self::isValidData($data)) {
            return null;
        }
        $mean = $this->mean($data);
        $deviation2 = 0;
        foreach ($data as $value) {
            $deviation2 += ($value - $mean) ** 2;
        }
        return $deviation2 / count($data);
    }

    /**
     * calculates covariance of $dataX and $dataY
     * @param   array<int|string, int|float>    $dataX
     * @param   array<int|string, int|float>    $dataY
     * @return  float|null
     */
    public function covariance(array $dataX, array $dataY)
    {
        if (!self::isValidData($dataX) || !self::isValidData($dataY)) {
            return null;
        }
        if (count($dataX) !== count($dataY)) {
            return null;
        }
        $count = count($dataX);
        $meanX = $this->mean($dataX);
        $meanY = $this->mean($dataY);
        $deviationXY = 0;
        for ($i = 0; $i < $count; $i++) {
            $deviationXY += ($dataX[$i] - $meanX) * ($dataY[$i] - $meanY);
        }
        return $deviationXY / $count;
    }

    /**
     * calculates standard deviation of $data
     * @param   array<int|string, int|float>    $data
     * @return  float|null
     */
    public function standardDeviation(array $data)
    {
        if (!self::isValidData($data)) {
            return null;
        }
        return sqrt($this->variance($data));
    }

    /**
     * calculates correlation coefficient of $dataX and $dataY
     * @param   array<int|string, int|float> $dataX
     * @param   array<int|string, int|float> $dataY
     * @return  float|null
     */
    public function correlationCoefficient(array $dataX, array $dataY)
    {
        if (!self::isValidData($dataX) || !self::isValidData($dataY)) {
            return null;
        }
        if (count($dataX) !== count($dataY)) {
            return null;
        }
        $sx = $this->standardDeviation($dataX);
        $sy = $this->standardDeviation($dataY);
        if (!($sx * $sy)) {
            return null;
        }
        return $this->covariance($dataX, $dataY) / ($sx * $sy);
    }

    /**
     * derives the regression line formula
     * @param   array<int|string, int|float>    $dataX
     * @param   array<int|string, int|float>    $dataY
     * @return  array<string, int|float>|null
     */
    public function regressionLineFormula(array $dataX, array $dataY)
    {
        if (!self::isValidData($dataX) || !self::isValidData($dataY)) {
            return null;
        }
        if (count($dataX) !== count($dataY)) {
            return null;
        }
        $varianceX = $this->variance($dataX);
        if (!$varianceX) {
            return null;
        }
        $a = $this->covariance($dataX, $dataY) / $varianceX;
        $b = $this->mean($dataY) - $a * $this->mean($dataX);
        return [
            'a' => $a,
            'b' => $b,
        ];
    }

    /* future version
    public function regressionCurveFormula($dataX, $dataY)
    {
        if (!self::isValid($dataX || !self::isValid($dataY))) return;
        if (count($dataX) !== count($dataY)) return;
    }
    */

    /**
     * calculates the upper control limit of $data
     * @param   array<int|string, int|float>    $data
     * @return  float|null
     */
    public function getUcl(array $data)
    {
        if (!self::isValidData($data)) {
            return null;
        }
        $this->ft->setClassRange(1);
        $this->ft->setData($data);
        $parsed = $this->ft->parse();
        if (!array_key_exists('ThirdQuartile', $parsed)) {
            return null;
        }
        if (!array_key_exists('InterQuartileRange', $parsed)) {
            return null;
        }
        return $parsed['ThirdQuartile'] + 1.5 * $parsed['InterQuartileRange'];
    }

    /**
     * calculates the lower control limit of $data
     * @param   array<int|string, int|float>    $data
     * @return  float|null
     */
    public function getLcl(array $data)
    {
        if (!self::isValidData($data)) {
            return null;
        }
        $this->ft->setClassRange(1);
        $this->ft->setData($data);
        $parsed = $this->ft->parse();
        if (!array_key_exists('FirstQuartile', $parsed)) {
            return null;
        }
        if (!array_key_exists('InterQuartileRange', $parsed)) {
            return null;
        }
        return $parsed['FirstQuartile'] - 1.5 * $parsed['InterQuartileRange'];
    }

    /**
     * detects outliers and returns them
     * @param   array<int|string, int|float>    $data
     * @return  array<int, int|float>|null
     */
    public function outliers(array $data)
    {
        if (!self::isValidData($data)) {
            return null;
        }
        $this->ft->setClassRange(1);
        $this->ft->setData($data);
        $this->parsed = $this->ft->parse();
        $ucl = $this->getUcl($data);
        $lcl = $this->getLcl($data);
        if (null === $ucl || null === $lcl) {
            return null;
        }
        $outliers = [];
        foreach ($data as $value) {
            if ($value > $ucl || $value < $lcl) {
                $outliers[] = $value;
            }
        }
        return $outliers;
    }

    /**
     * returns x-max and y-max of $layers
     * @param   array<int|string, array<string, array<int|float>>>  $layers
     * @return  array<int, int|float>|null
     */
    public function layerMax(array $layers)
    {
        if (!self::isValidLayers($layers)) {
            return null;
        }
        $xMax = [];
        $yMax = [];
        foreach ($layers as $layer) {
            $xMax[] = max($layer['x']);
            $yMax[] = max($layer['y']);
        }
        return [max($xMax), max($yMax)];
    }

    /**
     * return x-min and y-min of $layers
     * @param   array<int|string, array<string, array<int|string>>> $layers
     * @return  array<int, int|float>|null
     */
    public function layerMin(array $layers)
    {
        if (!self::isValidLayers($layers)) {
            return null;
        }
        $xMin = [];
        $yMin = [];
        foreach ($layers as $layer) {
            $xMin[] = min($layer['x']);
            $yMin[] = min($layer['y']);
        }
        return [min($xMin), min($yMin)];
    }

    /**
     * returns parsed data of layers
     * @param   array<int|string, array<string, array<int|float>>>  $layers
     * @return  array<int|string, array<string, mixed>>|null
     */
    public function parse(array $layers)
    {
        if (!self::isValidLayers($layers)) {
            return null;
        }
        $parsed = [];
        foreach ($layers as $name => $layer) {
            $parsed[$name] = [
                'count' => count($layer['x']),
                'x' => [
                    'Mean' => $this->mean($layer['x']),
                    'Max' => max($layer['x']),
                    'Min' => min($layer['x']),
                    'Variance' => $this->variance($layer['x']),
                    'StandardDeviation'
                        => $this->standardDeviation($layer['x']),
                ],
                'y' => [
                    'Mean' => $this->mean($layer['y']),
                    'Max' => max($layer['y']),
                    'Min' => min($layer['y']),
                    'Variance' => $this->variance($layer['y']),
                    'StandardDeviation'
                        => $this->standardDeviation($layer['y']),
                ],
                'Covariance' => $this->covariance($layer['x'], $layer['y']),
                'CorrelationCoefficient'
                    => $this->correlationCoefficient($layer['x'], $layer['y']),
                'RegressionLineFormula'
                    => $this->regressionLineFormula($layer['x'], $layer['y']),
            ];
        }
        return $parsed;
    }
}
