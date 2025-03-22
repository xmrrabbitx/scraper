<?php

namespace Macocci7\PhpScatterplot\Traits;

trait StyleAppendixTrait
{
    protected int|float $referenceLineXValue;
    protected int $referenceLineXWidth = 1;
    protected string|null $referenceLineXColor = '#0000ff';

    protected int|float $referenceLineYValue;
    protected int $referenceLineYWidth = 1;
    protected string|null $referenceLineYColor = '#0000ff';

    protected int|float $specificationLimitXLower;
    protected int|float $specificationLimitXUpper;
    protected int $specificationLimitXWidth = 1;
    protected string|null $specificationLimitXColor = '#ff0000';

    protected int|float $specificationLimitYLower;
    protected int|float $specificationLimitYUpper;
    protected int $specificationLimitYWidth = 1;
    protected string|null $specificationLimitYColor = '#ff0000';

    protected int $regressionLineWidth = 2;
    protected string|null $regressionLineColor = '#00cc00';

    protected int $outlierDiameter = 2;
    protected string|null $outlierColor = '#ff0000';
    protected string|null $meanColor = '#0000ff';
    protected int $legendWidth = 100;
    protected int|float $legendFontSize = 10;
    /**
     * @var string[]    $regressionLineColors
     */
    protected array $regressionLineColors = [
        '#ff0000',
        '#ff6666',
        '#ff9933',
        '#ff0066',
        '#ff00cc',
        '#ff6600',
        '#ffcc00',
        '#ff00ff',
    ];

    /**
     * sets x, width and color of the reference line of x
     * @param   int|float   $x
     * @param   int         $width
     * @param   string      $color = '#0000ff'
     * @return  self
     * @thrown  \Exception
     */
    public function referenceLineX(int|float $x, int $width = 1, string $color = '#0000ff')
    {
        if ($width < 1) {
            throw new \Exception("Width must be positive integer.");
        }
        if (!self::isColorCode($color)) {
            throw new \Exception("Color code must be in '#rgb' or '#rrggbb' format.");
        }
        $this->referenceLineX = true;
        $this->referenceLineXValue = $x;
        $this->referenceLineXWidth = $width;
        $this->referenceLineXColor = $color;
        return $this;
    }

    /**
     * sets y, width and color of the reference line of y
     * @param   int|float   $y
     * @param   int         $width
     * @param   string      $color = '#0000ff'
     * @return  self
     * @thrown  \Exception
     */
    public function referenceLineY(int|float $y, int $width = 1, string $color = '#0000ff')
    {
        if ($width < 1) {
            throw new \Exception("Width must be positive integer.");
        }
        if (!self::isColorCode($color)) {
            throw new \Exception("Color code must be in '#rgb' or '#rrggbb' format.");
        }
        $this->referenceLineY = true;
        $this->referenceLineYValue = $y;
        $this->referenceLineYWidth = $width;
        $this->referenceLineYColor = $color;
        return $this;
    }

    /**
     * sets the specification limits of x
     * @param   int|float   $lower
     * @param   int|float   $upper
     * @param   int         $width = 1
     * @param   string      $color = '#ff00ff'
     * @return  self
     * @thrown  \Exception
     */
    public function specificationLimitX(int|float $lower, int|float $upper, int $width = 1, string $color = '#ff00ff')
    {
        if ($lower >= $upper) {
            throw new \Exception("The lower and upper limits are opposite in size.");
        }
        if ($width < 1) {
            throw new \Exception("Width must be positive integer.");
        }
        if (!self::isColorCode($color)) {
            throw new \Exception("Color code msut be in '#rgb' or '#rrggbb' format.");
        }
        $this->specificationLimitX = true;
        $this->specificationLimitXLower = $lower;
        $this->specificationLimitXUpper = $upper;
        $this->specificationLimitXWidth = $width;
        $this->specificationLimitXColor = $color;
        return $this;
    }

    /**
     * sets the specification limits of y
     * @param   int|float   $lower
     * @param   int|float   $upper
     * @param   int         $width = 1
     * @param   string      $color = '#ff00ff'
     * @return  self
     * @thrown  \Exception
     */
    public function specificationLimitY(int|float $lower, int|float $upper, int $width = 1, string $color = '#ff00ff')
    {
        if ($lower >= $upper) {
            throw new \Exception("The lower and upper limits are opposite in size.");
        }
        if ($width < 1) {
            throw new \Exception("Width must be positive integer.");
        }
        if (!self::isColorCode($color)) {
            throw new \Exception("Color code msut be in '#rgb' or '#rrggbb' format.");
        }
        $this->specificationLimitY = true;
        $this->specificationLimitYLower = $lower;
        $this->specificationLimitYUpper = $upper;
        $this->specificationLimitYWidth = $width;
        $this->specificationLimitYColor = $color;
        return $this;
    }

    /**
     * sets the width and color of the regression line
     * @param   int         $width
     * @param   string[]    $colors
     * @return  self
     * @thrown  \Exception
     */
    public function regressionLine(int $width, array $colors)
    {
        if ($width < 1) {
            throw new \Exception("Width must be positive integer.");
        }
        if (!self::isColorCodesAll($colors)) {
            throw new \Exception("Color codes must be in '#rgb' or '#rrggbb' format.");
        }
        $this->regressionLineColors = array_slice(
            array_values($colors),
            0,
            LIMIT_LAYERS
        );
        $this->regressionLine = true;
        $this->regressionLineWidth = $width;
        return $this;
    }
}
