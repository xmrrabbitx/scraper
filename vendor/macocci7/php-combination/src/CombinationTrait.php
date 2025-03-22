<?php

namespace Macocci7\PhpCombination;

use Macocci7\PhpCombination\Util;
use Macocci7\PhpCombination\CombinationIterator;

trait CombinationTrait
{
    /**
     * returns all combinations from arrays
     * @param   list<list<mixed>>   $arrays each elements must be array.
     * @return  CombinationIterator
     * @thrown  \Exception
     */
    public function fromArrays(array $arrays): CombinationIterator
    {
        Util::validateArrays($arrays);
        return new CombinationIterator($arrays);
    }
}
