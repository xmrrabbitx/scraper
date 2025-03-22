<?php

namespace Macocci7\PhpScatterplot\Traits;

trait VisibilityCoreTrait
{
    protected bool $gridX = false;
    protected bool $gridY = false;

    /**
     * sets grid of x on
     * @return  self
     */
    public function gridXOn()
    {
        $this->gridX = true;
        return $this;
    }

    /**
     * sets grid of x off
     * @return self
     */
    public function gridXOff()
    {
        $this->gridX = false;
        return $this;
    }

    /**
     * sets grid of y on
     * @return self
     */
    public function gridYOn()
    {
        $this->gridY = true;
        return $this;
    }

    /**
     * sets grid of y off
     * @return self
     */
    public function gridYOff()
    {
        $this->gridY = false;
        return $this;
    }

    /**
     * sets legend off
     * @return self
     */
    public function legendOff()
    {
        $this->legend = false;
        return $this;
    }
}
