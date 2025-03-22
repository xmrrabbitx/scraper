<?php

namespace Macocci7\PhpCombination;

use Macocci7\PhpCombination\Util;
use Macocci7\PhpCombination\CombinationTrait;

/**
 * Class for generating combinations.
 * @author  macocci7 <macocci7@yahoo.co.jp>
 * @license MIT
 */
class Combination
{
    use CombinationTrait;

    /**
     * returns all combinations from single array.
     * returns sorted array when the second param is set as true.
     * @param   array<int, int|float|string>    $items
     * @param   bool                            $sort = false
     * @return  array<int, array<int, int|float|string>>
     * @thrown  \Exception
     */
    public function all(array $items, bool $sort = false)
    {
        $count = count($items);
        Util::validateArray($items);
        if ($count >= Util::systemBit() - 1) {
            throw new \Exception("Too many elements.");
        }
        $numberOfAllPatterns = 2 ** $count;
        $format = '%0' . $count . 'b';
        $combinations = [];
        for ($i = $numberOfAllPatterns - 1; $i > 0; $i--) {
            $combination = [];
            foreach (str_split(sprintf($format, $i)) as $index => $bit) {
                if ((bool) $bit) {
                    $combination[] = $items[$index];
                }
            }
            $combinations[] = $combination;
        }
        if ($sort) {
            $strs = array_map(fn ($c): string => implode(',', $c), $combinations);
            array_multisort($strs, SORT_ASC, SORT_STRING, $combinations);
        }
        return $combinations;
    }

    /**
     * returns all pairs
     * @param   array<int, int|float|string>    $items
     * @return  array<int, array<int, int|float|string>>
     */
    public function pairs(array $items)
    {
        Util::validateArray($items);
        if (count($items) < 2) {
            throw new \Exception("Too few elements.");
        }
        $pairs = [];
        $lastIndex = count($items) - 1;
        for ($x = 0; $x < $lastIndex; $x++) {
            for ($y = $x + 1; $y <= $lastIndex; $y++) {
                $pairs[] = [$items[$x], $items[$y]];
            }
        }
        return $pairs;
    }

    /**
     * returns all combinations of $n elements
     * @param   array<int, int|float|string>    $items
     * @param   int                             $n
     * @param   bool                            $sort = false
     * @return  array<int, array<int, int|float|string>>
     * @thrown  \Exception
     */
    public function ofN(array $items, int $n, bool $sort = false)
    {
        /**
         * ex) $items = [1,2,3,4], $n = 3
         * ==> [1,2,3], [1,2,4], [1,3,4], [2,3,4]
         */
        Util::validateArray($items);
        if ($n < 1 || count($items) < $n) {
            throw new \Exception("Invalid number specified.");
        }
        $r = [];
        foreach ($this->all($items, $sort) as $c) {
            if (count($c) === $n) {
                $r[] = $c;
            }
        }
        return $r;
    }

    /**
     * returns all combinations of $a to $b elements
     * @param   array<int, int|float|string>    $items
     * @param   int                             $a
     * @param   int                             $b
     * @param   bool                            $sort = false
     * @return  array<int, array<int, int|float|string>>
     * @thrown  \Exception
     */
    public function ofA2B(array $items, int $a, int $b, bool $sort = false)
    {
        Util::validateArray($items);
        if ($a < 1 || $b < 1) {
            throw new \Exception("Invalid number specified.");
        }
        if ($a >= $b) {
            throw new \Exception("A must be less than B.");
        }
        $count = count($items);
        if ($b > $count) {
            throw new \Exception("B exceeds the number of elements.");
        }
        $combinations = [];
        foreach ($this->all($items, $sort) as $c) {
            $count = count($c);
            if ($a <= $count && $count <= $b) {
                $combinations[] = $c;
            }
        }
        return $combinations;
    }
}
