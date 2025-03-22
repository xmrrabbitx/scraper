<?php

namespace Macocci7\PhpFrequencyTable\Traits;

trait FrequencyTrait
{
    /**
     * returns frequencies
     * @return  array<int|null>
     */
    public function getFrequencies()
    {
        $frequencies = [];
        foreach ($this->getClasses() as $class) {
            $frequencies[] = $this->getFrequency($this->data, $class);
        }
        return $frequencies;
    }

    /**
     * returns frequency of the specified class
     * @param   mixed   $data
     * @param   mixed   $class
     * @return  int|null
     */
    public function getFrequency(mixed $data, mixed $class)
    {
        if (
            !$this->isSettableData($data)
            || !$this->isSettableClass($class)
        ) {
            return null;
        }
        $count = 0;
        foreach ($data as $d) { // @phpstan-ignore-line
            if ($d >= $class['bottom'] && $d < $class['top']) { // @phpstan-ignore-line
                $count++;
            }
        }
        return $count;
    }

    /**
     * returns cumulative frequency of the specified (array) index
     * @param   mixed   $frequencies
     * @param   mixed   $index
     * @return  int|null
     */
    public function getCumulativeFrequency(mixed $frequencies, mixed $index)
    {
        if (
            !$this->isSettableData($frequencies)
            || !is_int($index)
        ) {
            return null;
        }
        if (
            $index < 0
            || $index >= count($frequencies) // @phpstan-ignore-line
        ) {
            return null;
        }
        return array_sum(array_slice($frequencies, 0, $index + 1)); // @phpstan-ignore-line
    }

    /**
     * returns relative frequency
     * @param   mixed   $frequency
     * @return  int|float|null
     */
    public function getRelativeFrequency(mixed $frequency)
    {
        if (!$this->getTotal() || !(is_int($frequency))) {
            return null;
        }
        if ($frequency < 0 || $frequency > $this->getTotal()) {
            return null;
        }
        return $frequency / $this->getTotal();
    }

    /**
     * returns cumulative relative frequency
     * @param   mixed   $frequencies
     * @param   mixed   $index
     * @return  int|float|null
     */
    public function getCumulativeRelativeFrequency(mixed $frequencies, mixed $index)
    {
        if (!$this->isSettableData($frequencies) || !is_int($index)) {
            return null;
        }
        if ($index < 0 || $index >= count($frequencies)) { // @phpstan-ignore-line
            return null;
        }
        $rf = [];
        foreach (array_slice($frequencies, 0, $index + 1) as $frequency) { // @phpstan-ignore-line
            $rf[] = $this->getRelativeFrequency($frequency);
        }
        return array_sum($rf);
    }
}
