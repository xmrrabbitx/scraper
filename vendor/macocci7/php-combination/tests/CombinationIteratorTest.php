<?php

declare(strict_types=1);

namespace Macocci7\PhpCombination;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Macocci7\PhpCombination\Util;
use Macocci7\PhpCombination\CombinationIterator;

final class CombinationIteratorTest extends TestCase
{
    public function test_constructor_returns_correct_instance(): void
    {
        $arrays = [
            [ 1, 2, ],
            [ 3, 4, ],
            [ 5, 6, ],
        ];
        $this->assertTrue(
            new CombinationIterator($arrays) instanceof \Iterator
        );
    }

    public static function provide_works_correctly(): array
    {
        return [
            'pattern 0' => [
                'arrays' => [
                    [],
                ],
                'expected' => [
                    [ null, ],
                ],
            ],
            'pattern 1' => [
                'arrays' => [
                    [ 1, ],
                ],
                'expected' => [
                    [ 1, ],
                ],
            ],
            'pattern 2' => [
                'arrays' => [
                    [ 1, 2, ],
                ],
                'expected' => [
                    [ 1, ],
                    [ 2, ],
                ],
            ],
            'pattern 3' => [
                'arrays' => [
                    [ 1, ],
                    [ 2, ],
                ],
                'expected' => [
                    [ 1, 2, ],
                ],
            ],
            'pattern 4' => [
                'arrays' => [
                    [ 1, 2, ],
                    [ 3, ],
                ],
                'expected' => [
                    [ 1, 3, ],
                    [ 2, 3, ],
                ],
            ],
            'pattern 5' => [
                'arrays' => [
                    [ 1, ],
                    [ 2, 3, ],
                ],
                'expected' => [
                    [ 1, 2, ],
                    [ 1, 3, ],
                ],
            ],
            'pattern 6' => [
                'arrays' => [
                    [ 1, 2, ],
                    [ 3, 4, ],
                    [ 5, 6, ],
                ],
                'expected' => [
                    [ 1, 3, 5, ],
                    [ 1, 3, 6, ],
                    [ 1, 4, 5, ],
                    [ 1, 4, 6, ],
                    [ 2, 3, 5, ],
                    [ 2, 3, 6, ],
                    [ 2, 4, 5, ],
                    [ 2, 4, 6, ],
                ],
            ],
        ];
    }

    #[DataProvider('provide_works_correctly')]
    public function test_works_correctly(array $arrays, array $expected): void
    {
        $c = new CombinationIterator($arrays);
        foreach ($c as $key => $current) {
            $this->assertSame($expected[$key], $current);
        }
    }
}
