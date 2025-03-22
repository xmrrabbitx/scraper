<?php

namespace Macocci7\PhpScatterplot\Traits;

trait VisibilityAppendixTrait
{
    protected bool $referenceLineX = false;
    protected bool $referenceLineY = false;
    protected bool $specificationLimitX = false;
    protected bool $specificationLimitY = false;
    protected bool $regressionLine = false;
    protected bool $outlier = true;
    protected bool $mean = false;

    /**
     * sets reference line of x off
     * @return self
     */
    public function referenceLineXOff()
    {
        $this->referenceLineX = false;
        return $this;
    }

    /**
     * sets reference line of y off
     * @return self
     */
    public function referenceLineYOff()
    {
        $this->referenceLineY = false;
        return $this;
    }

    /**
     * sets reference lines off
     * @return self
     */
    public function referenceLinesOff()
    {
        $this->referenceLineXOff();
        $this->referenceLineYOff();
        return $this;
    }

    /**
     * sets specification limit of x off
     * @return self
     */
    public function specificationLimitXOff()
    {
        $this->specificationLimitX = false;
        return $this;
    }

    /**
     * sets specification limit of y off
     * @return self
     */
    public function specificationLimitYOff()
    {
        $this->specificationLimitY = false;
        return $this;
    }

    /**
     * sets specification limits off
     * @return self
     */
    public function specificationLimitsOff()
    {
        $this->specificationLimitXOff();
        $this->specificationLimitYOff();
        return $this;
    }

    /**
     * sets regression line on
     * @return  self
     */
    public function regressionLineOn()
    {
        $this->regressionLine = true;
        return $this;
    }

    /**
     * sets regression line off
     * @return self
     */
    public function regressionLineOff()
    {
        $this->regressionLine = false;
        return $this;
    }
}
