<?php

declare(strict_types=1);

namespace Macocci7\PhpCombination;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Macocci7\PhpCombination\Util;
use Macocci7\PhpCombination\Combination;

final class CombinationTest extends TestCase
{
    public function test_all_can_throw_exception_with_invalid_param(): void
    {
        $c = new Combination();
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Empty array set.");
        $c->all([]);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Too many elements.");
        $c->all(range(1, Util::systemBit() - 1));
    }

    public static function provide_all_can_return_all_combinations_correctly(): array
    {
        return [
            "1 element" => ['items' => [1], 'expect' => [[1]], ],
            "2 elements" => ['items' => [1, 2, ], 'expect' => [[1, 2, ], [1], [2], ], ],
            "3 elements" => ['items' => [1, 2, 3, ], 'expect' => [[1, 2, 3, ], [1, 2, ], [1, 3, ], [1], [2, 3, ], [2], [3], ], ],
        ];
    }

    #[DataProvider('provide_all_can_return_all_combinations_correctly')]
    public function test_all_can_return_all_combinations_correctly(array $items, array $expect): void
    {
        $c = new Combination();
        $this->assertSame($expect, $c->all($items));
    }

    public static function provide_all_can_sort_correctly(): array
    {
        return [
            "1 element" => ['items' => [1], 'expect' => [[1]], ],
            "2 elements" => ['items' => [1, 2, ], 'expect' => [[1], [1, 2, ], [2], ], ],
            "3 elements" => ['items' => [1, 2, 3, ], 'expect' => [[1], [1, 2, ], [1, 2, 3, ], [1, 3, ], [2], [2, 3, ], [3], ], ],
        ];
    }

    #[DataProvider('provide_all_can_sort_correctly')]
    public function test_all_can_sort_correctly(array $items, array $expect): void
    {
        $c = new Combination();
        $this->assertSame($expect, $c->all($items, true));
    }

    public static function provide_pairs_can_throw_exception_with_invalid_param(): array
    {
        return [
            "1 element" => ['items' => [1], ],
        ];
    }

    #[DataProvider('provide_pairs_can_throw_exception_with_invalid_param')]
    public function test_pairs_can_throw_exception_with_invalid_param(array $items): void
    {
        $c = new Combination();
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Too few elements.");
        $c->pairs($items);
    }

    public static function provide_pairs_can_return_all_pairs_correctly(): array
    {
        return [
            "2 elements" => ['items' => [1, 2, ], 'expect' => [[1, 2, ]], ],
            "3 elements" => ['items' => [1, 2, 3, ], 'expect' => [[1, 2, ], [1, 3, ], [2, 3, ], ], ],
            "4 elements" => ['items' => [1, 2, 3, 4, ], 'expect' => [[1, 2, ], [1, 3, ], [1, 4, ], [2, 3, ], [2, 4, ], [3, 4, ], ], ],
        ];
    }

    #[DataProvider('provide_pairs_can_return_all_pairs_correctly')]
    public function test_pairs_can_return_all_pairs_correctly(array $items, array $expect): void
    {
        $c = new Combination();
        $this->assertSame($expect, $c->pairs($items));
    }

    public static function provide_ofN_can_throw_exception_with_invalid_param(): array
    {
        return [
            "1 element, n = -1" => ['items' => [1], 'n' => -1, ],
            "1 element, n = 0" => ['items' => [1], 'n' => 0, ],

            "1 element, n = 2" => ['items' => [1], 'n' => 2, ],
            "2 elements, n = 0" => ['items' => [1, 2, ], 'n' => 0, ],

            "2 elements, n = 3" => ['items' => [1, 2, ], 'n' => 3, ],
            "3 elements, n = 0" => ['items' => [1, 2, 3, ], 'n' => 0, ],

            "3 elements, n = 4" => ['items' => [1, 2, 3, ], 'n' => 4, ],
        ];
    }

    #[DataProvider('provide_ofN_can_throw_exception_with_invalid_param')]
    public function test_ofN_can_throw_exception_with_invalid_param(array $items, int $n): void
    {
        $c = new Combination();
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid number specified.");
        $c->ofN($items, $n);
    }

    public static function provide_ofN_can_return_all_combinations_of_n_elements_correctly(): array
    {
        return [
            "1 element, n = 1" => ['items' => [1], 'n' => 1, 'expect' => [[1]], ],
            "2 elements, n = 1" => ['items' => [1, 2, ], 'n' => 1, 'expect' => [[1], [2], ], ],
            "2 elements, n = 2" => ['items' => [1, 2, ], 'n' => 2, 'expect' => [[1, 2, ]], ],
            "3 elements, n = 1" => ['items' => [1, 2, 3, ], 'n' => 1, 'expect' => [[1], [2], [3], ], ],
            "3 elements, n = 2" => ['items' => [1, 2, 3, ], 'n' => 2, 'expect' => [[1, 2, ], [1, 3, ], [2, 3, ], ], ],
            "3 elements, n = 3" => ['items' => [1, 2, 3, ], 'n' => 3, 'expect' => [[1, 2, 3, ]], ],
        ];
    }

    #[DataProvider('provide_ofN_can_return_all_combinations_of_n_elements_correctly')]
    public function test_ofN_can_return_all_combinations_of_n_elements_correctly(array $items, int $n, array $expect): void
    {
        $c = new Combination();
        $this->assertSame($expect, $c->ofN($items, $n));
    }

    public static function provide_ofA2B_can_throw_exception_with_invalid_param(): array
    {
        $m = [
            "Invalid number specified.",
            "A must be less than B.",
            "B exceeds the number of elements.",
        ];
        return [
            "3 elements, a = 0, b = 0" => ['items' => [1, 2, 3, ], 'a' => 0, 'b' => 0, 'message' => $m[0], ],
            "3 elements, a = 0, b = 1" => ['items' => [1, 2, 3, ], 'a' => 0, 'b' => 1, 'message' => $m[0], ],
            "3 elements, a = 2, b = 1" => ['items' => [1, 2, 3, ], 'a' => 2, 'b' => 1, 'message' => $m[1], ],
            "3 elements, a = 2, b = 2" => ['items' => [1, 2, 3, ], 'a' => 2, 'b' => 2, 'message' => $m[1], ],
            "3 elements, a = 2, b = 4" => ['items' => [1, 2, 3, ], 'a' => 2, 'b' => 4, 'message' => $m[2], ],
        ];
    }

    #[DataProvider('provide_ofA2B_can_throw_exception_with_invalid_param')]
    public function test_ofA2B_can_throw_exception_with_invalid_param(array $items, int $a, int $b, string $message): void
    {
        $c = new Combination();
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage($message);
        $c->ofA2B($items, $a, $b);
    }

    public static function provide_ofA2B_can_return_all_combinations_of_A_2_B_elements(): array
    {
        return [
            "2 elements, a = 1, b = 2" => ['items' => [1, 2, ], 'a' => 1, 'b' => 2, 'expect' => [[1, 2, ], [1], [2], ], ],
            "3 elements, a = 1, b = 2" => ['items' => [1, 2, 3, ], 'a' => 1, 'b' => 2, 'expect' => [[1, 2, ], [1, 3, ], [1], [2, 3, ], [2], [3], ], ],
            "3 elements, a = 2, b = 3" => ['items' => [1, 2, 3, ], 'a' => 2, 'b' => 3, 'expect' => [[1, 2, 3, ], [1, 2, ], [1, 3, ], [2, 3, ], ], ],
        ];
    }

    #[DataProvider('provide_ofA2B_can_return_all_combinations_of_A_2_B_elements')]
    public function test_ofA2B_can_return_all_combinations_of_A_2_B_elements(array $items, int $a, int $b, array $expect): void
    {
        $c = new Combination();
        $this->assertSame($expect, $c->ofA2B($items, $a, $b));
    }

    public static function provide_fromArrays_can_return_combinations_correctly(): array
    {
        return [
            "1 element" => ['arrays' => [[1]], 'expect' => [[1]], ],
            "1 element, 1 element" => ['arrays' => [[1], [2], ], 'expect' => [[1, 2, ]], ],
            "1 element, 2 elements" => ['arrays' => [[1], [2, 3, ], ], 'expect' => [[1, 2, ], [1, 3, ], ], ],
            "2 elements, 2 elements" => ['arrays' => [[1, 2, ], [3, 4, ], ], 'expect' => [[1, 3, ], [1, 4, ], [2, 3, ], [2, 4, ], ], ],
            "2 elements, 2 elements, 2 elements" => ['arrays' => [[1, 2, ], [3, 4, ], [5, 6, ], ], 'expect' => [[1, 3, 5, ], [1, 3, 6, ], [1, 4, 5, ], [1, 4, 6, ], [2, 3, 5, ], [2, 3, 6, ], [2, 4, 5, ], [2, 4, 6, ], ], ],
        ];
    }

    #[DataProvider('provide_fromArrays_can_return_combinations_correctly')]
    public function test_fromArrays_can_return_combinations_correctly(array $arrays, array $expect): void
    {
        $c = new Combination();
        $i = 0;
        foreach ($c->fromArrays($arrays) as $index => $combination) {
            $this->assertSame($expect[$index], $combination);
            $i++;
        }
        $this->assertSame(count($expect), $i);
    }
}
