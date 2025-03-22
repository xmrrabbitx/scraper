<?php

namespace Macocci7\PhpCombination;

/**
 * Class for generating combinations with Iterator.
 *
 * @implements \Iterator<int, mixed>
 * @author  macocci7 <macocci7@yahoo.co.jp>
 * @license MIT
 */
class CombinationIterator implements \Iterator
{
    /**
     * @var mixed[] $arrays
     */
    private array $arrays;

    /**
     * @var int[]|null  $pointers
     */
    private array|null $pointers = null;

    /**
     * @var int $key
     */
    private int $key;

    /**
     * constructor
     *
     * @param   array<array<int, mixed[]>>  $arrays
     */
    public function __construct(array $arrays)
    {
        $this->arrays = $arrays;
        $this->pointers = array_fill(0, count($arrays), 0);
        $this->key = 0;
    }

    /**
     * returns current element
     *
     * @return  mixed
     */
    public function current(): mixed
    {
        $result = [];
        foreach ($this->pointers as $arrayIndex => $elementIndex) {
            $result[] = $this->arrays[$arrayIndex][$elementIndex] ?? null;
        }
        return $result;
    }

    /**
     * returns current key.
     *
     * @return  int
     */
    public function key(): int
    {
        return $this->key;
    }

    /**
     * advances the pointer one step.
     */
    public function next(): void
    {
        for ($i = count($this->pointers) - 1; $i >= 0; $i--) {
            if ($this->pointers[$i] < count($this->arrays[$i]) - 1) {
                $this->pointers[$i]++;
                // 繰り上げた位より下位の位をゼロにセット
                // 1199 + 1 = 1200
                //  ^           ^^
                $count = count($this->pointers);
                for ($j = $i + 1; $j < $count; $j++) {
                    $this->pointers[$j] = 0;
                }
                $this->key++;
                return;
            }
        }
        $this->pointers = null;
    }

    /**
     * rewinds the pointers and the key.
     *
     * @return  void
     */
    public function rewind(): void
    {
        $this->pointers = array_fill(0, count($this->arrays), 0);
        $this->key = 0;
    }

    /**
     * returns if the pointers are valid.
     *
     * @return  bool
     */
    public function valid(): bool
    {
        return $this->pointers !== null;
    }
}
