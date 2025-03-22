<?php

namespace Macocci7\PhpCombination;

use Macocci7\PhpCombination\Util;
use Macocci7\PhpCombination\CombinationTrait;

/**
 * Class for generating combinations with Generator.
 * @author  macocci7 <macocci7@yahoo.co.jp>
 * @license MIT
 */
class CombinationGenerator
{
    use CombinationTrait;

    /**
     * returns all combinations
     * @param   array<int, int|float|string>    $items
     * @return  \Generator
     * @thrown  \Exception
     */
    public function all(array $items)
    {
        $count = count($items);
        Util::validateArray($items);
        if ($count >= Util::systemBit() - 1) {
            throw new \Exception("Too many elements.");
        }
        $numberOfAllPatterns = 2 ** $count;
        $format = '%0' . $count . 'b';
        for ($i = $numberOfAllPatterns - 1; $i > 0; $i--) {
            $combination = [];
            foreach (str_split(sprintf($format, $i)) as $index => $bit) {
                if ((bool) $bit) {
                    $combination[] = $items[$index];
                }
            }
            yield $combination;
        }
    }

    /**
     * returns all pairs
     * @param   array<int, int|float|string>    $items
     * @return  \Generator
     * @thrown  \Exception
     */
    public function pairs(array $items)
    {
        Util::validateArray($items);
        if (count($items) < 2) {
            throw new \Exception("Too few elements.");
        }
        $lastIndex = count($items) - 1;
        for ($x = 0; $x < $lastIndex; $x++) {
            for ($y = $x + 1; $y <= $lastIndex; $y++) {
                yield [$items[$x], $items[$y]];
            }
        }
    }

    /**
     * returns all combinations of $n elements
     * @param   array<int, int|float|string>    $items
     * @param   int                             $n
     * @return  \Generator
     * @thrown  \Exception
     */
    public function ofN(array $items, int $n)
    {
        /**
         * ex) $items = [1,2,3,4], $n = 3
         * ==> [1,2,3], [1,2,4], [1,3,4], [2,3,4]
         */
        Util::validateArray($items);
        if ($n < 1 || count($items) < $n) {
            throw new \Exception("Invalid number specified.");
        }
        foreach ($this->all($items) as $c) {
            if (count($c) === $n) {
                yield $c;
            }
        }
    }

    /**
     * returns all combinations of $a to $b elements
     * @param   array<int, int|float|string>    $items
     * @param   int                             $a
     * @param   int                             $b
     * @return  \Generator
     * @thrown  \Exception
     */
    public function ofA2B(array $items, int $a, int $b)
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
        foreach ($this->all($items) as $c) {
            $count = count($c);
            if ($a <= $count && $count <= $b) {
                yield $c;
            }
        }
    }
}
