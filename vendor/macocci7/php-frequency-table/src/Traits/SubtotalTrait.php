<?php

namespace Macocci7\PhpFrequencyTable\Traits;

trait SubtotalTrait
{
    /**
     * returns all subtotals of each class
     *
     * @return  array<int, int|float|null>
     */
    public function getSubtotals()
    {
        $subtotals = [];
        foreach ($this->getClasses() as $class) {
            $subtotals[] = $this->getSubtotal($class);
        }
        return $subtotals;
    }

    /**
     * returns subtotal of the class
     *
     * @param   array<string, int|float>    $class
     * @return  int|float|null
     */
    protected function getSubtotal(array $class)
    {
        if (!$this->isSettableClass($class)) {
            return null;
        }
        $subtotal = 0;
        foreach ($this->getData() as $value) {
            if ($class['bottom'] <= $value and $value < $class['top']) {
                $subtotal += $value;
            }
        }
        return $subtotal;
    }

    /**
     * returns relative subtotals
     *
     * @return  array<int, int|float|null>
     */
    public function getRelativeSubtotals()
    {
        $relativeSubtotals = [];
        $total = array_sum($this->getData());
        foreach ($this->getSubtotals() as $subtotal) {
            $relativeSubtotals[] = $this->isNumber($total)
                ? ($total === 0 ? 0 : $subtotal / $total)
                : null;
        }
        return $relativeSubtotals;
    }

    /**
     * returns relative cululative subtotals
     *
     * @return  array<int, int|float|null>
     */
    public function getCumulativeRelativeSubtotals()
    {
        $relativeCumulativeSubtotals = [];
        $sum = 0;
        foreach ($this->getRelativeSubtotals() as $relativeSubtotal) {
            $sum += $relativeSubtotal;
            $relativeCumulativeSubtotals[] = $sum;
        }
        return $relativeCumulativeSubtotals;
    }
}
