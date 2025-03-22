<?php

namespace Macocci7\PhpFrequencyTable\Traits;

trait JudgeTrait
{
    /**
     * judges if the param is number or not
     * @param   mixed   $value
     * @return  bool
     */
    public function isNumber(mixed $value)
    {
        return is_int($value) || is_float($value);
    }

    /**
     * judges if the param is valid or not
     * @param   mixed  $data
     * @return  bool
     */
    public function isSettableData(mixed $data)
    {
        if (!is_array($data)) {
            return false;
        }
        if (empty($data)) {
            return false;
        }
        foreach ($data as $value) {
            if (!$this->isNumber($value)) {
                return false;
            }
        }
        return true;
    }

    /**
     * judges if the param is valid class range or not
     * @param   mixed   $classRange
     * @return  bool
     */
    public function isSettableClassRange(mixed $classRange)
    {
        if (!$this->isNumber($classRange)) {
            return false;
        }
        return $classRange > 0;
    }

    /**
     * judges if the param is valid class or not
     * @param   mixed   $class
     * @return  bool
     */
    public function isSettableClass(mixed $class)
    {
        if (!is_array($class)) {
            return false;
        }
        if (empty($class)) {
            return false;
        }
        if (
            !array_key_exists('bottom', $class)
            || !array_key_exists('top', $class)
        ) {
            return false;
        }
        if (
            !$this->isNumber($class['bottom'])
            || !$this->isNumber($class['top'])
        ) {
            return false;
        }
        if (!($class['bottom'] < $class['top'])) {
            return false;
        }
        return true;
    }

    /**
     * judges if the param is valid for columns to show or not
     * @param   mixed   $columns
     * @return  bool
     */
    public function isSettableColumns2Show(mixed $columns)
    {
        if (!is_array($columns)) {
            return false;
        }
        if (empty($columns)) {
            return false;
        }
        foreach ($columns as $c) {
            if (!in_array($c, $this->getValidColumns2Show())) {
                return false;
            }
        }
        return true;
    }
}
