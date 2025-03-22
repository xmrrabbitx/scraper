<?php

namespace Macocci7\PhpFrequencyTable\Traits;

trait DataTrait
{
    /**
     * @var mixed   $data
     */
    protected mixed $data = null;

    /**
     * @var int|float|null  $total
     */
    protected mixed $total = null;

    /**
     * sets data
     * @param   mixed   $data   default = null
     * @return  self
     */
    public function setData(mixed $data)
    {
        if ($this->isSettableData($data)) {
            $this->data = $data;
            $this->setTotal($this->getFrequencies());
        } else {
            $this->data = null;
            $this->total = null;
        }
        return $this;
    }

    /**
     * returns data
     * @param   int|string|null $key = null
     * @return  array<int|string, int|float>
     */
    public function getData(int|string|null $key = null)
    {
        // @phpstan-ignore-next-line
        return is_null($key) ? $this->data : ($this->data[$key] ?? null);
    }

    /**
     * returns data range
     * @param   mixed   $data
     * @return  int|float|null
     */
    public function getDataRange(mixed $data)
    {
        if (!$this->isSettableData($data)) {
            return null;
        }
        return $this->getMax($data) - $this->getMin($data);
    }

    /**
     * returns min value of the specified array
     * @param   mixed   $data
     * @return  int|float|null
     */
    public function getMin(mixed $data)
    {
        return $this->isSettableData($data) ? min($data) : null; // @phpstan-ignore-line
    }

    /**
     * returns max value of the specified array
     * @param   mixed   $data
     * @return  int|float|null
     */
    public function getMax(mixed $data)
    {
        return $this->isSettableData($data) ? max($data) : null; // @phpstan-ignore-line
    }

    /**
     * sets total of the specified data
     * @param   mixed   $data
     * @return  self
     */
    public function setTotal(mixed $data)
    {
        if ($this->isSettableData($data)) {
            $this->total = array_sum($data); // @phpstan-ignore-line
        } else {
            $this->total = null;
        }
        return $this;
    }

    /**
     * returns total
     * @return  int|float|null
     */
    public function getTotal()
    {
        if (is_null($this->total) && $this->isSettableData($this->getData())) {
            $this->setTotal($this->getFrequencies());
        }
        return $this->total;
    }

    /**
     * returns mean value
     * @return  int|float|null
     */
    public function getMean()
    {
        if (!$this->isSettableData($this->getData()) || !$this->getTotal()) {
            return null;
        }
        $fc = [];
        $classes = $this->getClasses();
        foreach ($this->getFrequencies() as $index => $frequency) {
            $fc[] = $this->getClassValue($classes[$index]) * $frequency;
        }
        return array_sum($fc) / $this->getTotal();
    }

    /**
     * returns mode
     * @return  int|float|null
     */
    public function getMode()
    {
        $frequencies = $this->getFrequencies();
        if (0 === count($frequencies)) {
            return null;
        }
        $classes = $this->getClasses();
        $index = array_search(max($frequencies), $frequencies);
        return $this->getClassValue($classes[$index]);
    }

    /**
     * returns median
     * @param   mixed   $param
     * @return  int|float|null
     */
    public function getMedian(mixed $param)
    {
        if (!$this->isSettableData($param)) {
            return null;
        }
        $data = array_merge($param); // @phpstan-ignore-line
        $count = count($data);
        sort($data);
        if (1 === $count % 2) {
            return $data[(int) (($count + 1) / 2) - 1]; // @phpstan-ignore-line
        }
        return ($data[(int) ($count / 2) - 1] + $data[(int) ($count / 2)]) / 2;
    }
}
