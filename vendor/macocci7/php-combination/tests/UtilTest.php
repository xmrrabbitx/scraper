<?php

declare(strict_types=1);

namespace Macocci7\PhpCombination;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Macocci7\PhpCombination\Util;

final class UtilTest extends TestCase
{
    public static function provide_validateArray_can_throw_exception_with_invalid_param(): array
    {
        return [
            [ 'array' => [], 'message' => 'Empty array set.', ],
            [ 'array' => [ 'a' => 1, ], 'message' => 'Array keys must be integer.', ],
            [ 'array' => [ 0 => 1, 'b' => 2, ], 'message' => 'Array keys must be integer.', ],
            [ 'array' => [ null, ], 'message' => 'Array elements must be int, float or strings.', ],
            [ 'array' => [ true, ], 'message' => 'Array elements must be int, float or strings.', ],
            [ 'array' => [ false, ], 'message' => 'Array elements must be int, float or strings.', ],
            [ 'array' => [ [ 1, ], ], 'message' => 'Array elements must be int, float or strings.', ],
            [ 'array' => [ 1, null, ], 'message' => 'Array elements must be int, float or strings.', ],
            [ 'array' => [ 1, true, ], 'message' => 'Array elements must be int, float or strings.', ],
            [ 'array' => [ 1, false, ], 'message' => 'Array elements must be int, float or strings.', ],
            [ 'array' => [ 1, [ 1, ], ], 'message' => 'Array elements must be int, float or strings.', ],
        ];
    }

    #[DataProvider('provide_validateArray_can_throw_exception_with_invalid_param')]
    public function test_validateArray_can_throw_exception_with_invalid_param(array $array, string $message): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage($message);
        Util::validateArray($array);
    }

    public static function provide_validateArray_can_validate_array_correctly(): array
    {
        return [
            [ 'array' => [ 1, ], ],
            [ 'array' => [ 1.2, ], ],
            [ 'array' => [ 'A'], ],
            [ 'array' => [ 1, 1.2, ], ],
            [ 'array' => [ 1, 'A', ], ],
            [ 'array' => [ 1.2, 1, ], ],
            [ 'array' => [ 1.2, 'A', ], ],
            [ 'array' => [ 'A', 1, ], ],
            [ 'array' => [ 'A', 1.2, ], ],
            [ 'array' => [ 1, 1.2, 'A', ], ],
        ];
    }

    #[DataProvider('provide_validateArray_can_validate_array_correctly')]
    public function test_validateArray_can_validate_array_correctly(array $array): void
    {
        $this->assertTrue(Util::validateArray($array));
    }

    public static function provide_validateArrays_can_throw_exception_with_invalid_param(): array
    {
        return [
            "Empty" => ['arrays' => [], 'message' => "Empty array set.", ],
            "non-array included" => ['arrays' => [[1], [2], 3, ], 'message' => "index[2]: Array expected.", ],
            "over limit" => ['arrays' => [array_fill(0, 55109, null), array_fill(0, 55109, null), array_fill(0, 55109, null), array_fill(0, 55109, null), ], 'message' => " patterns found (over limit).", ],
        ];
    }

    #[DataProvider('provide_validateArrays_can_throw_exception_with_invalid_param')]
    public function test_validateArrays_can_throw_exception_with_invalid_param(array $arrays, string $message): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage($message);
        Util::validateArrays($arrays);
    }

    public static function provide_validateArrays_can_return_true_correctly(): array
    {
        return [
            "int index, 1 child array" => [ 'arrays' => [ [1], ], ],
            "int index, 2 child arrays" => [ 'arrays' => [ [1], [2], ], ],
            "int index, 2 child arrays, multiple grand child elements" => [ 'arrays' => [ [ 1, 2, ], [ 3, 4, 5, ], ], ],
            "string index, 1 child array" => [ 'arrays' => [ 'a' => [1], ], ],
            "string index, 2 child arrays" => [ 'arrays' => [ 'a' => [1], 'b' => [2], ], ],
        ];
    }

    #[DataProvider('provide_validateArrays_can_return_true_correctly')]
    public function test_validateArrays_can_return_true_correctly(array $arrays): void
    {
        $this->assertTrue(Util::validateArrays($arrays));
    }
}
