<?php   // phpcs:ignore

declare(strict_types=1);

namespace Macocci7\PhpFrequencyTable;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Macocci7\PhpCombination\Combination;
use Macocci7\PhpFrequencyTable\FrequencyTable;
use Nette\Neon\Neon;

/**
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
final class FrequencyTableTest extends TestCase
{
    private static $validColumns2Show = [
        'Class',
        'Frequency',
        'CumulativeFrequency',
        'RelativeFrequency',
        'CumulativeRelativeFrequency',
        'ClassValue',
        'ClassValue * Frequency',
        'Subtotal',
        'RelativeSubtotal',
        'CumulativeRelativeSubtotal',
    ];
    private $defaultColumns2Show = [
        'Class',
        'Frequency',
        'RelativeFrequency',
        'ClassValue',
        'ClassValue * Frequency',
    ];
    private static $defaultTableSeparator = '|';

    public static function setUpBeforeClass(): void
    {
        if (!file_exists('storage')) {
            mkdir('storage');
        }
    }

    public static function tearDownAfterClass(): void
    {
        rmdir('storage');
    }

    private function clearStorage(): void
    {
        array_map('unlink', glob("storage/test*"));
    }

    public function test_constructor_can_create_instance_without_params(): void
    {
        $ft = new FrequencyTable();
        $this->assertNull($ft->getData());
        $this->assertNull($ft->getClassRange());
        $this->assertNull($ft->getTotal());
        $this->assertSame($this->defaultColumns2Show, $ft->getColumns2Show());
    }

    public function test_constructor_can_set_only_data(): void
    {
        $data = [ 10, 30, 40, 45, 50, 52, ];
        $ft = new FrequencyTable(['data' => $data]);
        $this->assertSame($data, $ft->getData());
        $this->assertNull($ft->getClassRange());
        $this->assertEquals(0, $ft->getTotal());
        $this->assertSame($this->defaultColumns2Show, $ft->getColumns2Show());
    }

    public function test_constructor_can_set_data(): void
    {
        $data = [ 10, 30, 40, 45, 50, 52, ];
        $classRange = 20;
        $ft = new FrequencyTable([ 'data' => $data, 'classRange' => $classRange, ]);

        $this->assertSame($data, $ft->getData());
        $this->assertSame($classRange, $ft->getClassRange());
        $this->assertIsNumeric($ft->getTotal());
        $this->assertSame($this->defaultColumns2Show, $ft->getColumns2Show());
    }

    public static function provide_constructor_can_set_columns2Show(): array
    {
        return [
            ['columns2Show' => self::$validColumns2Show],
        ];
    }

    #[DataProvider('provide_constructor_can_set_columns2Show')]
    public function test_constructor_can_set_columns2Show(array $columns2Show): void
    {
        $pop = array_pop($columns2Show);
        $ft = new FrequencyTable(['columns2Show' => $columns2Show]);
        $this->assertSame($columns2Show, $ft->getColumns2Show());
        $this->assertFalse(in_array($pop, $ft->getColumns2Show()));
    }

    public function test_langs_can_return_supported_langs_correctly(): void
    {
        $ft = new FrequencyTable();
        $confPath = __DIR__ . '/../conf/FrequencyTable.neon';
        $confLangs = array_keys(Neon::decodeFile($confPath)['supportedLangs']);
        $this->assertSame($confLangs, $ft->langs());
    }

    public function test_lang_can_work_correctly(): void
    {
        $ft = new FrequencyTable();
        $confPath = __DIR__ . '/../conf/FrequencyTable.neon';
        $conf = Neon::decodeFile($confPath);
        $this->assertSame($conf['lang'], $ft->lang());
        foreach (array_keys($conf['supportedLangs']) as $lang) {
            $this->assertSame($lang, $ft->lang($lang)->lang());
        }
    }

    public static function provide_isNumber_can_judge_correctly(): array
    {
        return [
            [ 'param' => null, 'expect' => false, ],
            [ 'param' => true, 'expect' => false, ],
            [ 'param' => false, 'expect' => false, ],
            [ 'param' => 1, 'expect' => true, ],
            [ 'param' => 1.2, 'expect' => true, ],
            [ 'param' => '1', 'expect' => false, ],
            [ 'param' => [1], 'expect' => false, ],
        ];
    }

    #[DataProvider('provide_isNumber_can_judge_correctly')]
    public function test_isNumber_can_judge_correctly(mixed $param, bool $expect): void
    {
        $ft = new FrequencyTable();
        $this->assertSame($expect, $ft->isNumber($param));
    }

    public static function provide_isSettableData_can_judge_correctly(): array
    {
        return [
            [ 'data' => null, 'expected' => false, ],
            [ 'data' => true, 'expected' => false, ],
            [ 'data' => false, 'expected' => false, ],
            [ 'data' => 0, 'expected' => false, ],
            [ 'data' => 1.2, 'expected' => false, ],
            [ 'data' => 'hoge', 'expected' => false, ],
            [ 'data' => "1", 'expected' => false, ],
            [ 'data' => [], 'expected' => false, ],
            [ 'data' => [null], 'expected' => false, ],
            [ 'data' => [true], 'expected' => false, ],
            [ 'data' => [false], 'expected' => false, ],
            [ 'data' => ['hoge'], 'expected' => false, ],
            [ 'data' => ["1"], 'expected' => false, ],
            [ 'data' => [[]], 'expected' => false, ],
            [ 'data' => [ 0, null, ], 'expected' => false, ],
            [ 'data' => [ 0, true, ], 'expected' => false, ],
            [ 'data' => [ 0, false, ], 'expected' => false, ],
            [ 'data' => [ 0, 'hoge', ], 'expected' => false, ],
            [ 'data' => [ 0, "1", ], 'expected' => false, ],
            [ 'data' => [ 0, [], ], 'expected' => false, ],
            [ 'data' => [ 0, [1], ], 'expected' => false, ],
            [ 'data' => [ 0, 1, ], 'expected' => true, ],
            [ 'data' => [ 0, 1.2, ], 'expected' => true, ],
            [ 'data' => [ 0, -1, ], 'expected' => true, ],
            [ 'data' => [ 0, -1.2, ], 'expected' => true, ],
            [ 'data' => [ -1, 0, ], 'expected' => true, ],
            [ 'data' => [ -1, 0.5, ], 'expected' => true, ],
            [ 'data' => [ -1.2, 0, ], 'expected' => true, ],
            [ 'data' => [ -1.2, 0.5, ], 'expected' => true, ],
            [ 'data' => [ 0.1, 1, 2.3, 3, 4.5, 5, ], 'expected' => true, ],
        ];
    }

    #[DataProvider('provide_isSettableData_can_judge_correctly')]
    public function test_isSettableData_can_judge_correctly(mixed $data, bool $expected): void
    {
        $ft = new FrequencyTable();
        $this->assertSame($expected, $ft->isSettableData($data));
    }

    public static function provide_setData_can_set_correct_data(): array
    {
        return [
            [ 'data' => [0], ],
            [ 'data' => [1.2], ],
            [ 'data' => [-1], ],
            [ 'data' => [-1.5], ],
            [ 'data' => [ 0, 1, 2, ], ],
            [ 'data' => [ 0, 1.5, 2, ], ],
            [ 'data' => [ -1, -1.5, -1, -2, ], ],
            [ 'data' => [ -1.9, -1.5, -1.2, -2.5, ], ],
            [ 'data' => ['Donald' => 1], ],
            [ 'data' => [ 'Donald' => 1, 'Joe' => 2, ], ],
            [ 'data' => ['Donald' => 1.5], ],
            [ 'data' => [ 'Donald' => 1.5, 'Joe' => 2.5, ], ],
        ];
    }

    #[DataProvider('provide_setData_can_set_correct_data')]
    public function test_setData_can_set_correct_data(array $data): void
    {
        $ft = new FrequencyTable();
        $ft->setData($data);
        $this->assertSame($data, $ft->getData());
    }

    public static function provide_setData_can_set_null_by_invalid_data(): array
    {
        return [
            [ 'data' => null, ],
            [ 'data' => true, ],
            [ 'data' => false, ],
            [ 'data' => 'hoge', ],
            [ 'data' => 0, ],
            [ 'data' => 1.2, ],
            [ 'data' => [], ],
            [ 'data' => [null], ],
            [ 'data' => [true], ],
            [ 'data' => [false], ],
            [ 'data' => ['hoge'], ],
            [ 'data' => [[]], ],
            [ 'data' => [ 0, 1, 2, 'hoge', ], ],
        ];
    }

    #[DataProvider('provide_setData_can_set_null_by_invalid_data')]
    public function test_setData_can_set_null_by_invalid_data(mixed $data): void
    {
        $ft = new FrequencyTable();

        $ft->setData([ 0, 1, 2, ]);
        $this->assertFalse(null === $ft->getData());
        $ft->setData($data);
        $this->assertNull($ft->getData());
        $this->assertNull($ft->getTotal());
    }

    public function test_getData_can_get_correct_data(): void
    {
        $dataNumericIndex = [ 10, 20, 30, ];
        $dataHashArray = [
            'hoge' => 1,
            'huga' => 2,
            'hugo' => 3,
        ];

        $ft = new FrequencyTable();

        $ft->setData(null);
        $this->assertNull($ft->getData());

        $ft->setData([]);
        $this->assertEmpty($ft->getData());

        $ft->setData($dataNumericIndex);
        $this->assertSame($dataNumericIndex, $ft->getData());
        foreach ($dataNumericIndex as $index => $value) {
            $this->assertEquals($value, $ft->getData($index));
        }
        $this->assertNull($ft->getData(1234567890));    // This index does not exist in the array.

        $ft->setData($dataHashArray);
        $this->assertSame($dataHashArray, $ft->getData());
        foreach ($dataHashArray as $key => $value) {
            $this->assertSame($value, $ft->getData($key));
        }
        $this->assertNull($ft->getData(''));
        $this->assertNull($ft->getData('keyWhichDoesNotExist'));    // This key does not exist in the array.
    }

    public static function provide_getDataRange_can_get_data_range_correctly(): array
    {
        return [
            [ 'data' => null, 'expect' => null, ],
            [ 'data' => true, 'expect' => null, ],
            [ 'data' => false, 'expect' => null, ],
            [ 'data' => 0, 'expect' => null, ],
            [ 'data' => 1.2, 'expect' => null, ],
            [ 'data' => 'hoge', 'expect' => null, ],
            [ 'data' => [], 'expect' => null, ],
            [ 'data' => [null], 'expect' => null, ],
            [ 'data' => [true], 'expect' => null, ],
            [ 'data' => [false], 'expect' => null, ],
            [ 'data' => ['hoge'], 'expect' => null, ],
            [ 'data' => [[]], 'expect' => null, ],
            [ 'data' => [[5]], 'expect' => null, ],
            [ 'data' => [5], 'expect' => 0, ],
            [ 'data' => [ 0, 5, ], 'expect' => 5, ],
            [ 'data' => [ 0, 5, 9, ], 'expect' => 9, ],
            [ 'data' => [ -5, -2, 0, 3, ], 'expect' => 8, ],
            [ 'data' => [ 1.5, 10.5, 90, ], 'expect' => 88.5, ],
            [ 'data' => [ -9.5, -1.2, 0.8, ], 'expect' => 10.3, ],
        ];
    }

    #[DataProvider('provide_getDataRange_can_get_data_range_correctly')]
    public function test_getDataRange_can_get_data_range_correctly(mixed $data, null|int|float $expect): void
    {
        $ft = new FrequencyTable();
        $this->assertSame($expect, $ft->getDataRange($data));
    }

    public static function provide_isSettableClassRange_can_judge_correctly(): array
    {
        return [
            [ 'classRange' => null, 'expect' => false, ],
            [ 'classRange' => 'hoge', 'expect' => false, ],
            [ 'classRange' => [], 'expect' => false, ],
            [ 'classRange' => [1], 'expect' => false, ],
            [ 'classRange' => '1', 'expect' => false, ],
            [ 'classRange' => '1.5', 'expect' => false, ],
            [ 'classRange' => false, 'expect' => false, ],
            [ 'classRange' => true, 'expect' => false, ],
            [ 'classRange' => 0, 'expect' => false, ],
            [ 'classRange' => 0.0, 'expect' => false, ],
            [ 'classRange' => -1, 'expect' => false, ],
            [ 'classRange' => -1.5, 'expect' => false, ],
            [ 'classRange' => 0.1, 'expect' => true, ],
            [ 'classRange' => 1, 'expect' => true, ],
            [ 'classRange' => 1.5, 'expect' => true, ],
            [ 'classRange' => PHP_INT_MAX, 'expect' => true, ],
            [ 'classRange' => PHP_FLOAT_MAX, 'expect' => true, ],
            [ 'classRange' => PHP_INT_MAX + 1, 'expect' => true, ],
            [ 'classRange' => PHP_FLOAT_MAX + 1, 'expect' => true, ],
        ];
    }

    #[DataProvider('provide_isSettableClassRange_can_judge_correctly')]
    public function test_isSettableClassRange_can_judge_correctly(mixed $classRange, bool $expect): void
    {
        $ft = new FrequencyTable();
        $this->assertEquals($expect, $ft->isSettableClassRange($classRange));
    }

    public static function provide_setClassRange_can_set_valid_classRange(): array
    {
        // Only positive integer or positive float can be excepted.
        // Null is set when parameter in other types is specified.
        return [
            [ 'classRange' => null, 'expect' => [ 'return' => false, 'get' => null, ], ],
            [ 'classRange' => -1, 'expect' => [ 'return' => false, 'get' => null, ], ],
            [ 'classRange' => 0, 'expect' => [ 'return' => false, 'get' => null, ], ],
            [ 'classRange' => 0.1, 'expect' => [ 'return' => true, 'get' => 0.1, ], ],
            [ 'classRange' => 1, 'expect' => [ 'return' => true, 'get' => 1, ], ],
            [ 'classRange' => 0x539, 'expect' => [ 'return' => true, 'get' => 1337, ], ],
            [ 'classRange' => 0b10100111001, 'expect' => [ 'return' => true, 'get' => 1337, ], ],
            [ 'classRange' => 1337e0, 'expect' => [ 'return' => true, 'get' => 1337.0, ], ],
            [ 'classRange' => '0.1', 'expect' => [ 'return' => false, 'get' => null, ], ],
            [ 'classRange' => '1', 'expect' => [ 'return' => false, 'get' => null, ], ],
            [ 'classRange' => '0x539', 'expect' => [ 'return' => false, 'get' => null, ], ],
            [ 'classRange' => '0b10100111001', 'expect' => [ 'return' => false, 'get' => null, ], ],
            [ 'classRange' => '1337e0', 'expect' => [ 'return' => false, 'get' => null, ], ],
            [ 'classRange' => 'hoge', 'expect' => [ 'return' => false, 'get' => null, ], ],
            [ 'classRange' => [], 'expect' => [ 'return' => false, 'get' => null, ], ],
            [ 'classRange' => [1], 'expect' => [ 'return' => false, 'get' => null, ], ],
            [ 'classRange' => PHP_INT_MAX, 'expect' => [ 'return' => true, 'get' => PHP_INT_MAX, ], ],
            [ 'classRange' => PHP_FLOAT_MAX, 'expect' => [ 'return' => true, 'get' => PHP_FLOAT_MAX, ], ],
            [ 'classRange' => PHP_INT_MAX + 1, 'expect' => [ 'return' => true, 'get' => PHP_INT_MAX + 1, ], ],
            [ 'classRange' => PHP_FLOAT_MAX + 1, 'expect' => [ 'return' => true, 'get' => PHP_FLOAT_MAX + 1, ], ],
        ];
    }

    #[DataProvider('provide_setClassRange_can_set_valid_classRange')]
    public function test_setClassRange_can_set_valid_classRange(mixed $classRange, array $expect): void
    {
        $ft = new FrequencyTable();
        $ft->setClassRange($classRange);
        $this->assertSame($expect['get'], $ft->getClassRange());
    }

    public static function provide_getFrequencies_can_get_frequencies_correctly(): array
    {
        return [
            [ 'classRange' => null, 'data' => null, 'expect' => [], ],
            [ 'classRange' => null, 'data' => [], 'expect' => [], ],
            [ 'classRange' => null, 'data' => [ 10, 15, 20, 25, 30, ], 'expect' => [], ],
            [ 'classRange' => 20, 'data' => null, 'expect' => [], ],
            [ 'classRange' => 20, 'data' => [], 'expect' => [], ],
            [ 'classRange' => 20, 'data' => [10], 'expect' => [1], ],
            [ 'classRange' => 20, 'data' => [-20], 'expect' => [1], ],
            [ 'classRange' => 20, 'data' => [ 10, 15, 20, 45, 51, 55, 58, 74, 78, 93, ], 'expect' => [ 2, 1, 4, 2, 1, ], ],
            [ 'classRange' => 20, 'data' => [ 10, 10.02, 15, 20, 20.01, 25, 30, 39.99, 40, 100, ], 'expect' => [ 3, 5, 1, 0, 0, 1, ], ],
            [ 'classRange' => 20, 'data' => [ -100, -99, -20, 0, 10, 15, 20, 25, 30, ], 'expect' => [ 2, 0, 0, 0, 1, 3, 3, ], ],
        ];
    }

    #[DataProvider('provide_getFrequencies_can_get_frequencies_correctly')]
    public function test_getFrequencies_can_get_frequencies_correctly(int|null $classRange, array|null $data, array $expect): void
    {
        $ft = new FrequencyTable();
        $ft->setClassRange($classRange);
        $ft->setData($data);
        $this->assertSame($expect, $ft->getFrequencies());
    }

    public static function provide_reverseClasses_can_work_correctly(): array
    {
        return [
            [
                'data' => [0],
                'classRange' => 5,
                'reverse' => false,
                'expected' => [['bottom' => 0, 'top' => 5, ]],
            ],
            [
                'data' => [0],
                'classRange' => 5,
                'reverse' => true,
                'expected' => [['bottom' => 0, 'top' => 5, ]],
            ],
            [
                'data' => [0, 5],
                'classRange' => 5,
                'reverse' => false,
                'expected' => [['bottom' => 0, 'top' => 5, ], ['bottom' => 5, 'top' => 10, ]],
            ],
            [
                'data' => [0, 5],
                'classRange' => 5,
                'reverse' => true,
                'expected' => [['bottom' => 5, 'top' => 10, ], ['bottom' => 0, 'top' => 5, ]],
            ],
            [
                'data' => [0, 5, 10],
                'classRange' => 5,
                'reverse' => false,
                'expected' => [
                    ['bottom' => 0, 'top' => 5, ],
                    ['bottom' => 5, 'top' => 10, ],
                    ['bottom' => 10, 'top' => 15, ],
                ],
            ],
            [
                'data' => [0, 5, 10],
                'classRange' => 5,
                'reverse' => true,
                'expected' => [
                    ['bottom' => 10, 'top' => 15, ],
                    ['bottom' => 5, 'top' => 10, ],
                    ['bottom' => 0, 'top' => 5, ],
                ],
            ],
        ];
    }

    #[DataProvider('provide_reverseClasses_can_work_correctly')]
    public function test_reverseClasses_can_work_correctly(array $data, int|float $classRange, bool $reverse, array $expected): void
    {
        $ft = new FrequencyTable(['data' => $data, 'classRange' => $classRange]);
        if ($reverse) {
            $ft->reverseClasses();
        }
        $this->assertSame($expected, $ft->getClasses());
    }

    public static function provide_getClasses_can_get_classes_correctly(): array
    {
        return [
            [ 'classRange' => null, 'data' => null, 'expect' => [], ],
            [ 'classRange' => null, 'data' => [], 'expect' => [], ],
            [ 'classRange' => null, 'data' => [10], 'expect' => [], ],
            [ 'classRange' => null, 'data' => [ 10, 15, 20, 25, 30, ], 'expect' => [], ],
            [ 'classRange' => 20, 'data' => null, 'expect' => [], ],
            [ 'classRange' => 20, 'data' => [], 'expect' => [], ],
            [ 'classRange' => 20, 'data' => [10], 'expect' => [[ 'bottom' => 0, 'top' => 20, ], ], ],
            [
                'classRange' => 20,
                'data' => [-20,],
                'expect' => [
                    [ 'bottom' => -20, 'top' => 0, ],
                ],
            ],
            [
                'classRange' => 20,
                'data' => [20,],
                'expect' => [
                    [ 'bottom' => 20, 'top' => 40, ],
                ],
            ],
            [
                'classRange' => 20,
                'data' => [ 10, 15, 20, 45, 51, 55, 58, 74, 78, 93, ],
                'expect' => [
                    [ 'bottom' => 0, 'top' => 20, ],
                    [ 'bottom' => 20, 'top' => 40, ],
                    [ 'bottom' => 40, 'top' => 60, ],
                    [ 'bottom' => 60, 'top' => 80, ],
                    [ 'bottom' => 80, 'top' => 100, ],
                ],
            ],
            [
                'classRange' => 20,
                'data' => [ 10, 10.02, 15, 20, 20.01, 25, 30, 39.99, 40, 100, ],
                'expect' => [
                    [ 'bottom' => 0, 'top' => 20, ],
                    [ 'bottom' => 20, 'top' => 40, ],
                    [ 'bottom' => 40, 'top' => 60, ],
                    [ 'bottom' => 60, 'top' => 80, ],
                    [ 'bottom' => 80, 'top' => 100, ],
                    [ 'bottom' => 100, 'top' => 120, ],
                ],
            ],
            [
                'classRange' => 20,
                'data' => [ -100, -99, -20, 0, 10, 15, 20, 25, 30, ],
                'expect' => [
                    [ 'bottom' => -100, 'top' => -80, ],
                    [ 'bottom' => -80, 'top' => -60, ],
                    [ 'bottom' => -60, 'top' => -40, ],
                    [ 'bottom' => -40, 'top' => -20, ],
                    [ 'bottom' => -20, 'top' => 0, ],
                    [ 'bottom' => 0, 'top' => 20, ],
                    [ 'bottom' => 20, 'top' => 40, ],
                ],
            ],
        ];
    }

    #[DataProvider('provide_getClasses_can_get_classes_correctly')]
    public function test_getClasses_can_get_classes_correctly(int|null $classRange, array|null $data, array $expect): void
    {
        $ft = new FrequencyTable();
        $ft->setClassRange($classRange);
        $ft->setData($data);
        $this->assertSame($expect, $ft->getClasses());
    }

    public static function provide_isSettableClass_can_judge_correctly(): array
    {
        return [
            [ 'class' => null, 'expect' => false, ],
            [ 'class' => true, 'expect' => false, ],
            [ 'class' => false, 'expect' => false, ],
            [ 'class' => 0, 'expect' => false, ],
            [ 'class' => 1, 'expect' => false, ],
            [ 'class' => 1.5, 'expect' => false, ],
            [ 'class' => 'hoge', 'expect' => false, ],
            [ 'class' => [], 'expect' => false, ],
            [ 'class' => [null], 'expect' => false, ],
            [ 'class' => [true], 'expect' => false, ],
            [ 'class' => [false], 'expect' => false, ],
            [ 'class' => [0], 'expect' => false, ],
            [ 'class' => [1], 'expect' => false, ],
            [ 'class' => [1.5], 'expect' => false, ],
            [ 'class' => ['bottom' => 0], 'expect' => false, ],
            [ 'class' => ['top' => 20], 'expect' => false, ],
            [ 'class' => [[ 'bottom' => 20, 'top' => 40, ]], 'expect' => false, ],
            [ 'class' => [ 'bottom' => null, 'top' => null, ], 'expect' => false, ],
            [ 'class' => [ 'bottom' => 20, 'top' => null, ], 'expect' => false, ],
            [ 'class' => [ 'bottom' => null, 'top' => 40, ], 'expect' => false, ],
            [ 'class' => [ 'bottom' => true, 'top' => true, ], 'expect' => false, ],
            [ 'class' => [ 'bottom' => 20, 'top' => true, ], 'expect' => false, ],
            [ 'class' => [ 'bottom' => true, 'top' => 40, ], 'expect' => false, ],
            [ 'class' => [ 'bottom' => false, 'top' => false, ], 'expect' => false, ],
            [ 'class' => [ 'bottom' => 20, 'top' => false, ], 'expect' => false, ],
            [ 'class' => [ 'bottom' => false, 'top' => 40, ], 'expect' => false, ],
            [ 'class' => [ 'bottom' => [], 'top' => [], ], 'expect' => false, ],
            [ 'class' => [ 'bottom' => 20, 'top' => [], ], 'expect' => false, ],
            [ 'class' => [ 'bottom' => [], 'top' => 40, ], 'expect' => false, ],
            [ 'class' => [ 'bottom' => ['bottom' => 20, 'top' => 40, ], 'top' => [ 'bottom' => 20, 'top' => 40, ], ], 'expect' => false, ],
            [ 'class' => [ 'bottom' => 20, 'top' => ['bottom' => 20, 'top' => 40, ], ], 'expect' => false, ],
            [ 'class' => [ 'bottom' => ['bottom' => 20, 'top' => 40, ], 'top' => 40, ], 'expect' => false, ],
            [ 'class' => [ 'bottom' => 20, 'top' => "40", ], 'expect' => false, ],
            [ 'class' => [ 'bottom' => "20", 'top' => 40, ], 'expect' => false, ],
            [ 'class' => [ 'bottom' => "20", 'top' => "40", ], 'expect' => false, ],
            [ 'class' => [ 'bottom' => 40, 'top' => 20, ], 'expect' => false, ],
            [ 'class' => [ 'bottom' => 20, 'top' => 20, ], 'expect' => false, ],
            [ 'class' => [ 'bottom' => 0, 'top' => 20, ], 'expect' => true, ],
            [ 'class' => [ 'bottom' => 20, 'top' => 40, ], 'expect' => true, ],
            [ 'class' => [ 'bottom' => -40, 'top' => 20, ], 'expect' => true, ],
            [ 'class' => [ 'bottom' => -40, 'top' => -20, ], 'expect' => true, ],
            [ 'class' => [ 'bottom' => 40.5, 'top' => 20.1, ], 'expect' => false, ],
            [ 'class' => [ 'bottom' => 20.5, 'top' => 20.5, ], 'expect' => false, ],
            [ 'class' => [ 'bottom' => 0.5, 'top' => 20.2, ], 'expect' => true, ],
            [ 'class' => [ 'bottom' => 20.8, 'top' => 40.9, ], 'expect' => true, ],
            [ 'class' => [ 'bottom' => -40.2, 'top' => 20.5, ], 'expect' => true, ],
            [ 'class' => [ 'bottom' => -40.2, 'top' => -20.5, ], 'expect' => true, ],
        ];
    }

    #[DataProvider('provide_isSettableClass_can_judge_correctly')]
    public function test_isSettableClass_can_judge_correctly(mixed $class, bool $expect): void
    {
        $ft = new FrequencyTable();
        $this->assertSame($expect, $ft->isSettableClass($class));
    }

    public static function provide_getFrequency_can_get_frequency_correctly(): array
    {
        return [
            [ 'data' => null, 'class' => null, 'expect' => null, ],
            [ 'data' => null, 'class' => [], 'expect' => null, ],
            [ 'data' => [], 'class' => [ 'bottom' => 0, 'top' => 20, ], 'expect' => null, ],
            [ 'data' => 'hoge', 'class' => [ 'bottom' => 0, 'top' => 20, ], 'expect' => null, ],
            [ 'data' => [ 10, 20, 30, ], 'class' => 'hoge', 'expect' => null, ],
            [ 'data' => [], 'class' => null, 'expect' => null, ],
            [ 'data' => [], 'class' => [], 'expect' => null, ],
            [ 'data' => [], 'class' => ['bottom' => 0], 'expect' => null, ],
            [ 'data' => [], 'class' => [ 'bottom' => 0, 'top' => 20, ], 'expect' => null, ],
            [ 'data' => [], 'class' => [1], 'expect' => null, ],
            [ 'data' => [10], 'class' => null, 'expect' => null, ],
            [ 'data' => [10], 'class' => [], 'expect' => null, ],
            [ 'data' => [10], 'class' => [10], 'expect' => null, ],
            [ 'data' => [10], 'class' => ['bottom' => 0,], 'expect' => null, ],
            [ 'data' => [10], 'class' => [ 'bottom' => 0, 'top' => 20, ], 'expect' => 1, ],
            [ 'data' => [10], 'class' => ['top' => 20], 'expect' => null, ],
            [ 'data' => [ 5, 9, 10, 15, 20, 25, 30, 35, 40, ], 'class' => [ 'bottom' => 10, 'top' => 30, ], 'expect' => 4, ],
        ];
    }

    #[DataProvider('provide_getFrequency_can_get_frequency_correctly')]
    public function test_getFrequency_can_get_frequency_correctly(mixed $data, mixed $class, int|null $expect): void
    {
        $ft = new FrequencyTable();
        $this->assertSame($expect, $ft->getFrequency($data, $class));
    }

    public static function provide_getCumulativeFrequency_can_get_cumulative_frequency_correctly(): array
    {
        return [
            [ 'frequencies' => null, 'index' => null, 'expect' => null, ],
            [ 'frequencies' => true, 'index' => null, 'expect' => null, ],
            [ 'frequencies' => false, 'index' => null, 'expect' => null, ],
            [ 'frequencies' => 0, 'index' => null, 'expect' => null, ],
            [ 'frequencies' => 1.2, 'index' => null, 'expect' => null, ],
            [ 'frequencies' => "0", 'index' => null, 'expect' => null, ],
            [ 'frequencies' => "1.2", 'index' => null, 'expect' => null, ],
            [ 'frequencies' => [], 'index' => null, 'expect' => null, ],
            [ 'frequencies' => [null], 'index' => null, 'expect' => null, ],
            [ 'frequencies' => [true], 'index' => null, 'expect' => null, ],
            [ 'frequencies' => [false], 'index' => null, 'expect' => null, ],
            [ 'frequencies' => [0], 'index' => null, 'expect' => null, ],
            [ 'frequencies' => [1.2], 'index' => null, 'expect' => null, ],
            [ 'frequencies' => ["0"], 'index' => null, 'expect' => null, ],
            [ 'frequencies' => ["1.2"], 'index' => null, 'expect' => null, ],
            [ 'frequencies' => [[]], 'index' => null, 'expect' => null, ],
            [ 'frequencies' => [ 0, 1, 2, 3, 4, ], 'index' => null, 'expect' => null, ],
            [ 'frequencies' => [ 0, 1, 2, 3, 4, ], 'index' => true, 'expect' => null, ],
            [ 'frequencies' => [ 0, 1, 2, 3, 4, ], 'index' => false, 'expect' => null, ],
            [ 'frequencies' => [ 0, 1, 2, 3, 4, ], 'index' => 1.0, 'expect' => null, ],
            [ 'frequencies' => [ 0, 1, 2, 3, 4, ], 'index' => "1", 'expect' => null, ],
            [ 'frequencies' => [ 0, 1, 2, 3, 4, ], 'index' => [], 'expect' => null, ],
            [ 'frequencies' => [ 0, 1, 2, 3, 4, ], 'index' => [1], 'expect' => null, ],
            [ 'frequencies' => [ 0, 1, 2, 3, 4, ], 'index' => -1, 'expect' => null, ],
            [ 'frequencies' => [ 0, 1, 2, 3, 4, ], 'index' => 5, 'expect' => null, ],
            [ 'frequencies' => [ 0, 1, 2, 3, 4, ], 'index' => 0, 'expect' => 0, ],
            [ 'frequencies' => [ 0, 1, 2, 3, 4, ], 'index' => 1, 'expect' => 1, ],
            [ 'frequencies' => [ 0, 1, 2, 3, 4, ], 'index' => 2, 'expect' => 3, ],
            [ 'frequencies' => [ 0, 1, 2, 3, 4, ], 'index' => 3, 'expect' => 6, ],
            [ 'frequencies' => [ 0, 1, 2, 3, 4, ], 'index' => 4, 'expect' => 10, ],
            [ 'frequencies' => [ 0 => 0, 1 => 1, 3 => 3, 4 => 4, ], 'index' => 0, 'expect' => 0, ],
            [ 'frequencies' => [ 0 => 0, 1 => 1, 3 => 3, 4 => 4, ], 'index' => 1, 'expect' => 1, ],
            [ 'frequencies' => [ 0 => 0, 1 => 1, 3 => 3, 4 => 4, ], 'index' => 2, 'expect' => 4, ],

            // array index is renumbered, [ 0 => 0, 1 => 1, 2 => 3, 3 => 4, ]
            [ 'frequencies' => [ 0 => 0, 1 => 1, 3 => 3, 4 => 4, ], 'index' => 3, 'expect' => 8, ],

            // array index is renumbered. [ 0 => 0, 1 => 1, 2 => 3, 3 => 4, ], index of 4 does not exist.
            [ 'frequencies' => [ 0 => 0, 1 => 1, 3 => 3, 4 => 4, ], 'index' => 4, 'expect' => null, ],
        ];
    }

    #[DataProvider('provide_getCumulativeFrequency_can_get_cumulative_frequency_correctly')]
    public function test_getCumulativeFrequency_can_get_cumulative_frequency_correctly(mixed $frequencies, mixed $index, int|null $expect): void
    {
        $ft = new FrequencyTable();
        $this->assertSame($expect, $ft->getCumulativeFrequency($frequencies, $index));
    }

    public static function provide_getMin_can_get_correctly(): array
    {
        return [
            [ 'data' => null, 'expect' => null, ],
            [ 'data' => 'hoge', 'expect' => null, ],
            [ 'data' => 10, 'expect' => null, ],
            [ 'data' => [], 'expect' => null, ],
            [ 'data' => [ 'hoge', 'huga', ], 'expect' => null, ],
            [ 'data' => [10], 'expect' => 10, ],
            [ 'data' => [-10], 'expect' => -10, ],
            [ 'data' => [ -10, 0, 5, ], 'expect' => -10, ],
        ];
    }

    #[DataProvider('provide_getMin_can_get_correctly')]
    public function test_getMin_can_get_correctly(mixed $data, int|null $expect): void
    {
        $ft = new FrequencyTable();
        $this->assertSame($expect, $ft->getMin($data));
    }

    public static function provide_getMax_can_get_correctly(): array
    {
        return [
            [ 'data' => null, 'expect' => null, ],
            [ 'data' => 'hoge', 'expect' => null, ],
            [ 'data' => 10, 'expect' => null, ],
            [ 'data' => [], 'expect' => null, ],
            [ 'data' => [ 'hoge', 'huga', ], 'expect' => null, ],
            [ 'data' => [10], 'expect' => 10, ],
            [ 'data' => [-10], 'expect' => -10, ],
            [ 'data' => [ -10, 0, 5, ], 'expect' => 5, ],
            [ 'data' => [ 100, -15, 30, -21, 148, 45, ], 'expect' => 148, ],
        ];
    }

    #[DataProvider('provide_getMax_can_get_correctly')]
    public function test_getMax_can_get_correctly(mixed $data, int|null $expect): void
    {
        $ft = new FrequencyTable();
        $this->assertSame($expect, $ft->getMax($data));
    }

    public static function provide_setTotal_and_getTotal_can_work_correctly(): array
    {
        return [
            [ 'classRange' => 2, 'data' => null, 'setTotal' => null, 'expect' => [ 'setTotal' => false, 'getTotal' => null, ], ],
            [ 'classRange' => 2, 'data' => null, 'setTotal' => [], 'expect' => [ 'setTotal' => false, 'getTotal' => null, ], ],
            [ 'classRange' => 2, 'data' => null, 'setTotal' => 0, 'expect' => [ 'setTotal' => false, 'getTotal' => null, ], ],
            [ 'classRange' => 2, 'data' => null, 'setTotal' => true, 'expect' => [ 'setTotal' => false, 'getTotal' => null, ], ],
            [ 'classRange' => 2, 'data' => null, 'setTotal' => false, 'expect' => [ 'setTotal' => false, 'getTotal' => null, ], ],

            // This returns the total of array values.
            [ 'classRange' => 2, 'data' => null, 'setTotal' => [ 0, 1, 2, 3, 4, ], 'expect' => [ 'setTotal' => true, 'getTotal' => 10, ], ],

            // This returns the total of Frequencies, ie the number of elements.
            [ 'classRange' => 2, 'data' => [ 0, 1, 2, 3, 4, ], 'setTotal' => null, 'expect' => [ 'setTotal' => false, 'getTotal' => 5, ], ],
        ];
    }

    #[DataProvider('provide_setTotal_and_getTotal_can_work_correctly')]
    public function test_setTotal_and_getTotal_can_work_correctly(int $classRange, array|null $data, mixed $setTotal, array $expect): void
    {
        $ft = new FrequencyTable();
        $ft->setClassRange($classRange);
        $ft->setData($data);
        $ft->setTotal($setTotal);
        $this->assertSame($expect['getTotal'], $ft->getTotal());
    }

    public static function provide_getClassValue_can_get_class_value_correctly(): array
    {
        return [
            [ 'class' => null, 'expect' => null, ],
            [ 'class' => [], 'expect' => null, ],
            [ 'class' => '', 'expect' => null, ],
            [ 'class' => true, 'expect' => null, ],
            [ 'class' => false, 'expect' => null, ],
            [ 'class' => 0, 'expect' => null, ],
            [ 'class' => 'hoge', 'expect' => null, ],
            [ 'class' => 12.3, 'expect' => null, ],
            [ 'class' => ['hoge'], 'expect' => null, ],
            [ 'class' => [ 'bottom', 'top', ], 'expect' => null, ],
            [ 'class' => [ 'bottom' => 20, ], 'expect' => null, ],
            [ 'class' => [ 'top' => 20, ], 'expect' => null, ],
            [ 'class' => [ 'bottom' => 40, 'top' => 30, ], 'expect' => null, ],
            [ 'class' => [ 'bottom' => 30, 'top' => 30, ], 'expect' => null, ],
            [ 'class' => [ 'bottom' => 20, 'top' => 30, ], 'expect' => 25, ],
            [ 'class' => [ 'bottom' => "20", 'top' => 30, ], 'expect' => null, ],
            [ 'class' => [ 'bottom' => 20, 'top' => "30", ], 'expect' => null, ],
            [ 'class' => [ 'bottom' => "20", 'top' => "30", ], 'expect' => null, ],
            [ 'class' => [ 'bottom' => 20.5, 'top' => 30.6, ], 'expect' => 25.55, ],
            [ 'class' => [ 'bottom' => -20, 'top' => -30, ], 'expect' => null, ],
            [ 'class' => [ 'bottom' => -20, 'top' => 30, ], 'expect' => 5, ],
            [ 'class' => [ 'bottom' => -20.5, 'top' => -10.4, ], 'expect' => -15.45, ],
            [ 'class' => [ 'bottom' => 20, 'top' => 30, 'middle' => 28, ], 'expect' => 25, ],
        ];
    }

    #[DataProvider('provide_getClassValue_can_get_class_value_correctly')]
    public function test_getClassValue_can_get_class_value_correctly(mixed $class, int|float|null $expect): void
    {
        $ft = new FrequencyTable();
        $this->assertSame($expect, $ft->getClassValue($class));
    }

    public static function provide_getRelativeFrequency_can_get_relative_frequency_correctly(): array
    {
        return [
            [ 'frequencies' => null, 'frequency' => null, 'expect' => null, ],
            [ 'frequencies' => [], 'frequency' => null, 'expect' => null, ],
            [ 'frequencies' => [ 1, 2, 3, 4, ], 'frequency' => null, 'expect' => null, ],
            [ 'frequencies' => [ 1, 2, 3, 4, ], 'frequency' => true, 'expect' => null, ],
            [ 'frequencies' => [ 1, 2, 3, 4, ], 'frequency' => false, 'expect' => null, ],
            [ 'frequencies' => [ 1, 2, 3, 4, ], 'frequency' => [], 'expect' => null, ],
            [ 'frequencies' => [ 1, 2, 3, 4, ], 'frequency' => [0], 'expect' => null, ],
            [ 'frequencies' => [ 1, 2, 3, 4, ], 'frequency' => [2], 'expect' => null, ],
            [ 'frequencies' => [ 1, 2, 3, 4, ], 'frequency' => 0, 'expect' => 0, ],
            [ 'frequencies' => [ 1, 2, 3, 4, ], 'frequency' => 2, 'expect' => 0.2, ],
            [ 'frequencies' => [ 1, 2, 3, 4, ], 'frequency' => -2, 'expect' => null, ],   // Frequency must be a positive integer or zero.
            [ 'frequencies' => [ 1, 2, 3, 4, ], 'frequency' => 0.0, 'expect' => null, ],  // Frequency must be a positive integer or zero.
            [ 'frequencies' => [ 1, 2, 3, 4, ], 'frequency' => 2.0, 'expect' => null, ],  // Frequency must be a positive integer or zero.
            [ 'frequencies' => [ 1, 2, 3, 4, ], 'frequency' => 10, 'expect' => 1, ],
            [ 'frequencies' => [ 1, 2, 3, 4, ], 'frequency' => 11, 'expect' => null, ],   // Frequency must be less than or equal to total.
            [ 'frequencies' => null, 'frequency' => 0, 'expect' => null, ],
            [ 'frequencies' => [], 'frequency' => 0, 'expect' => null, ],
            [ 'frequencies' => [], 'frequency' => 2, 'expect' => null, ],
            [ 'frequencies' => [0], 'frequency' => 0, 'expect' => null, ], // Total of frequencies must be a positive integer.
        ];
    }

    #[DataProvider('provide_getRelativeFrequency_can_get_relative_frequency_correctly')]
    public function test_getRelativeFrequency_can_get_relative_frequency_correctly(array|null $frequencies, mixed $frequency, int|float|null $expect): void
    {
        $ft = new FrequencyTable();
        $ft->setTotal($frequencies);
        $this->assertSame($expect, $ft->getRelativeFrequency($frequency));
    }

    public static function provide_getCumulativeRelativeFrequency_can_get_cumulative_relative_frequency_correctly(): array
    {
        return [
            [ 'frequencies' => null, 'index' => null, 'expect' => null, ],
            [ 'frequencies' => true, 'index' => null, 'expect' => null, ],
            [ 'frequencies' => false, 'index' => null, 'expect' => null, ],
            [ 'frequencies' => 0, 'index' => null, 'expect' => null, ],
            [ 'frequencies' => 1.2, 'index' => null, 'expect' => null, ],
            [ 'frequencies' => "0", 'index' => null, 'expect' => null, ],
            [ 'frequencies' => "1.2", 'index' => null, 'expect' => null, ],
            [ 'frequencies' => [], 'index' => null, 'expect' => null, ],
            [ 'frequencies' => [null], 'index' => null, 'expect' => null, ],
            [ 'frequencies' => [true], 'index' => null, 'expect' => null, ],
            [ 'frequencies' => [false], 'index' => null, 'expect' => null, ],
            [ 'frequencies' => [0], 'index' => null, 'expect' => null, ],
            [ 'frequencies' => [1.2], 'index' => null, 'expect' => null, ],
            [ 'frequencies' => ["0"], 'index' => null, 'expect' => null, ],
            [ 'frequencies' => ["1.2"], 'index' => null, 'expect' => null, ],
            [ 'frequencies' => [[]], 'index' => null, 'expect' => null, ],
            [ 'frequencies' => [[0]], 'index' => null, 'expect' => null, ],
            [ 'frequencies' => [ 0, 1, 2, 3, 4, ], 'index' => null, 'expect' => null, ],
            [ 'frequencies' => [ 0, 1, 2, 3, 4, ], 'index' => true, 'expect' => null, ],
            [ 'frequencies' => [ 0, 1, 2, 3, 4, ], 'index' => false, 'expect' => null, ],
            [ 'frequencies' => [ 0, 1, 2, 3, 4, ], 'index' => 1.2, 'expect' => null, ],
            [ 'frequencies' => [ 0, 1, 2, 3, 4, ], 'index' => "0", 'expect' => null, ],
            [ 'frequencies' => [ 0, 1, 2, 3, 4, ], 'index' => "1.2", 'expect' => null, ],
            [ 'frequencies' => [ 0, 1, 2, 3, 4, ], 'index' => [], 'expect' => null, ],
            [ 'frequencies' => [ 0, 1, 2, 3, 4, ], 'index' => [0], 'expect' => null, ],
            [ 'frequencies' => [ 0, 1, 2, 3, 4, ], 'index' => [1.2], 'expect' => null, ],
            [ 'frequencies' => [ 0, 1, 2, 3, 4, ], 'index' => 0, 'expect' => 0 / 10, ], // total=10, rf=[0, 0.1, 0.2, 0.3, 0.4, ]
            [ 'frequencies' => [ 0, 1, 2, 3, 4, ], 'index' => 1, 'expect' => 0 / 10 + 1 / 10, ],
            [ 'frequencies' => [ 0, 1, 2, 3, 4, ], 'index' => 2, 'expect' => 0 / 10 + 1 / 10 + 2 / 10, ],
            [ 'frequencies' => [ 0, 1, 2, 3, 4, ], 'index' => 3, 'expect' => 0 / 10 + 1 / 10 + 2 / 10 + 3 / 10, ],
            [ 'frequencies' => [ 0, 1, 2, 3, 4, ], 'index' => 4, 'expect' => 0 / 10 + 1 / 10 + 2 / 10 + 3 / 10 + 4 / 10, ],
            [ 'frequencies' => [ 0, 1, 2, 3, 4, ], 'index' => -1, 'expect' => null, ],
            [ 'frequencies' => [ 0, 1, 2, 3, 4, ], 'index' => 5, 'expect' => null, ],
            [ 'frequencies' => [ 0 => 0, 1 => 1, 3 => 3, 4 => 4, ], 'index' => 0, 'expect' => 0 / 8, ], // array index is renumbered. [ 0 => 0, 1 => 1, 2 => 3, 3 => 4, ]
            [ 'frequencies' => [ 0 => 0, 1 => 1, 3 => 3, 4 => 4, ], 'index' => 1, 'expect' => 0 / 8 + 1 / 8, ], // total=8, rf=[ 0, 0.125, 0.375, 0.5, ]
            [ 'frequencies' => [ 0 => 0, 1 => 1, 3 => 3, 4 => 4, ], 'index' => 2, 'expect' => 0 / 8 + 1 / 8 + 3 / 8, ],
            [ 'frequencies' => [ 0 => 0, 1 => 1, 3 => 3, 4 => 4, ], 'index' => 3, 'expect' => 0 / 8 + 1 / 8 + 3 / 8 + 4 / 8, ],
            [ 'frequencies' => [ 0 => 0, 1 => 1, 3 => 3, 4 => 4, ], 'index' => 4, 'expect' => null, ],
        ];
    }

    #[DataProvider('provide_getCumulativeRelativeFrequency_can_get_cumulative_relative_frequency_correctly')]
    public function test_getCumulativeRelativeFrequency_can_get_cumulative_relative_frequency_correctly(mixed $frequencies, mixed $index, int|float|null $expect): void
    {
        $ft = new FrequencyTable();
        $ft->setTotal($frequencies);
        $this->assertSame($expect, $ft->getCumulativeRelativeFrequency($frequencies, $index));
    }

    public static function provide_getMean_can_get_mean_correctly(): array
    {
        return [
            [ 'classRange' => 10, 'data' => null, 'expect' => null, ],
            [ 'classRange' => 10, 'data' => [], 'expect' => null, ],
            [ 'classRange' => 10, 'data' => [0], 'expect' => 5, ], // Frequencies=[1], ClassValue=5, Mean=5
            [ 'classRange' => 10, 'data' => [10], 'expect' => 15, ], // Frequencies=[1], ClassValue=15, Mean=15
            [ 'classRange' => 10, 'data' => [ 0, 5, 10, 15, 20, ], 'expect' => 13, ], // Frequencies=[ 2, 2, 1, ], ClassValues=[ 5, 15, 25, ], Mean=13
        ];
    }

    #[DataProvider('provide_getMean_can_get_mean_correctly')]
    public function test_getMean_can_get_mean_correctly(int $classRange, array|null $data, int|null $expect): void
    {
        $ft = new FrequencyTable();
        $ft->setClassRange($classRange);
        $ft->setData($data);
        $this->assertSame($expect, $ft->getMean());
    }

    public static function provide_getMode_can_get_mode_correctly(): array
    {
        return [
            [ 'classRange' => null, 'data' => null, 'expect' => null, ],
            [ 'classRange' => true, 'data' => null, 'expect' => null, ],
            [ 'classRange' => false, 'data' => null, 'expect' => null, ],
            [ 'classRange' => [], 'data' => null, 'expect' => null, ],
            [ 'classRange' => 0, 'data' => null, 'expect' => null, ],
            [ 'classRange' => 10, 'data' => null, 'expect' => null, ],
            [ 'classRange' => 'hoge', 'data' => null, 'expect' => null, ],
            [ 'classRange' => null, 'data' => true, 'expect' => null, ],
            [ 'classRange' => null, 'data' => false, 'expect' => null, ],
            [ 'classRange' => null, 'data' => [], 'expect' => null, ],
            [ 'classRange' => null, 'data' => 0, 'expect' => null, ],
            [ 'classRange' => null, 'data' => 10, 'expect' => null, ],
            [ 'classRange' => null, 'data' => 'hoge', 'expect' => null, ],
            [ 'classRange' => 10, 'data' => 0, 'expect' => null, ],
            [ 'classRange' => 10, 'data' => [], 'expect' => null, ],
            [ 'classRange' => 10, 'data' => 'hoge', 'expect' => null, ],
            [ 'classRange' => 10, 'data' => [0], 'expect' => 5, ], // Frequencies=[1], ClassValues=[5], Mode=5
            [ 'classRange' => 10, 'data' => [ 0, 10, 12, 20, 24, 28, ], 'expect' => 25, ], // Frequencise=[ 1, 2, 3, ], ClassValues=[ 5, 15, 25, ], Mode=25
            [ 'classRange' => -10, 'data' => [ 0, 10, 12, 20, 24, 28, ], 'expect' => null, ], // ClassRange must be a positive number.
        ];
    }

    #[DataProvider('provide_getMode_can_get_mode_correctly')]
    public function test_getMode_can_get_mode_correctly(mixed $classRange, mixed $data, int|float|null $expect): void
    {
        $ft = new FrequencyTable();
        $ft->setClassRange($classRange);
        $ft->setData($data);
        $this->assertSame($expect, $ft->getMode());
    }

    public static function provide_getMedian_can_get_median_correctly(): array
    {
        return [
            [ 'data' => null, 'expect' => null, ],
            [ 'data' => true, 'expect' => null, ],
            [ 'data' => false, 'expect' => null, ],
            [ 'data' => 0, 'expect' => null, ],
            [ 'data' => 'hoge', 'expect' => null, ],
            [ 'data' => [], 'expect' => null, ],
            [ 'data' => [[0]], 'expect' => null, ],
            [ 'data' => [null], 'expect' => null, ],
            [ 'data' => [0], 'expect' => 0, ],
            [ 'data' => [ 0, 1, ], 'expect' => 0.5, ],
            [ 'data' => [ 0, 1, 2, ], 'expect' => 1, ],
            [ 'data' => [ 0, 1, 2, 3, ], 'expect' => 1.5, ],
            [ 'data' => [ 0, 1, 2, 3, 4, ], 'expect' => 2, ],
            [ 'data' => [ 3, 0, 4, 2, 1, ], 'expect' => 2, ],
            [ 'data' => [ 1, 4, 0, 2, ], 'expect' => 1.5, ],
        ];
    }

    #[DataProvider('provide_getMedian_can_get_median_correctly')]
    public function test_getMedian_can_get_median_correctly(mixed $data, int|float|null $expect): void
    {
        $ft = new FrequencyTable();
        $this->assertSame($expect, $ft->getMedian($data));
    }

    public static function provide_getMedianClass_can_get_median_class(): array
    {
        return [
            [ 'classRange' => null, 'data' => null, 'expect' => null, ],
            [ 'classRange' => 10, 'data' => null, 'expect' => null, ],
            [ 'classRange' => null, 'data' => [ 10, 20, 30, ], 'expect' => null, ],
            [ 'classRange' => 10, 'data' => [ 10, 20, 30, ], 'expect' => [ 'index' => 1, 'bottom' => 20, 'top' => 30, ], ],
            [ 'classRange' => 10, 'data' => [10], 'expect' => [ 'index' => 0, 'bottom' => 10, 'top' => 20], ],
        ];
    }

    #[DataProvider('provide_getMedianClass_can_get_median_class')]
    public function test_getMedianClass_can_get_median_class(int|null $classRange, array|null $data, array|null $expect): void
    {
        $ft = new FrequencyTable();
        $ft->setClassRange($classRange);
        $ft->setData($data);
        $this->assertSame($expect, $ft->getMedianClass());
    }

    public static function provide_getSubtotals_can_return_subtotals_correctly(): array
    {
        return [
            [ 'data' => [], 'classRange' => 10, 'expect' => [], ],
            [ 'data' => [10], 'classRange' => 10, 'expect' => [10], ],
            [ 'data' => [5, 6, ], 'classRange' => 10, 'expect' => [11], ],
            [ 'data' => [5, 10, ], 'classRange' => 10, 'expect' => [5, 10, ], ],
            [ 'data' => [5, 6, 10, 15, ], 'classRange' => 10, 'expect' => [11, 25, ], ],
            [ 'data' => [5, 9, 13, 15, 17, 23, 29, ], 'classRange' => 10, 'expect' => [14, 45, 52, ], ],
            [ 'data' => [null], 'classRange' => 10, 'expect' => [], ],
            [ 'data' => [0], 'classRange' => 10, 'expect' => [0], ],
            [ 'data' => [1, null, true, false, '0', []], 'classRange' => 10, 'expect' => [], ],
        ];
    }

    #[DataProvider('provide_getSubtotals_can_return_subtotals_correctly')]
    public function test_getSubtotals_can_return_subtotals_correctly(array $data, int|float $classRange, array $expect): void
    {
        $ft = new FrequencyTable(['data' => $data, 'classRange' => $classRange]);
        $this->assertSame($expect, $ft->getSubtotals());
    }

    public static function provide_getFirstQuartile_can_get_first_quartile_correctly(): array
    {
        return [
            [ 'data' => null, 'expect' => null, ],
            [ 'data' => true, 'expect' => null, ],
            [ 'data' => false, 'expect' => null, ],
            [ 'data' => 0, 'expect' => null, ],
            [ 'data' => 'hoge', 'expect' => null, ],
            [ 'data' => [], 'expect' => null, ],
            [ 'data' => [[0]], 'expect' => null, ],
            [ 'data' => ['hoge'], 'expect' => null, ],
            [ 'data' => [ 1, 2, 'hoge', ], 'expect' => null, ],
            [ 'data' => [ 1, 2, ], 'expect' => 1, ],
            [ 'data' => [ 1, 2, 3, ], 'expect' => 1, ],
            [ 'data' => [ 1, 2, 3, 4, ], 'expect' => 1.5, ],
            [ 'data' => [ 1, 2, 3, 4, 5, ], 'expect' => 1.5, ],
            [ 'data' => [ 3, 1, 5, 2, 4, ], 'expect' => 1.5, ],
        ];
    }

    #[DataProvider('provide_getFirstQuartile_can_get_first_quartile_correctly')]
    public function test_getFirstQuartile_can_get_first_quartile_correctly(mixed $data, int|float|null $expect): void
    {
        $ft = new FrequencyTable();
        $this->assertSame($expect, $ft->getFirstQuartile($data));
    }

    public static function provide_getThirdQuartile_can_get_third_quartile_correctly(): array
    {
        return [
            [ 'data' => null, 'expect' => null, ],
            [ 'data' => true, 'expect' => null, ],
            [ 'data' => false, 'expect' => null, ],
            [ 'data' => 0, 'expect' => null, ],
            [ 'data' => 'hoge', 'expect' => null, ],
            [ 'data' => [], 'expect' => null, ],
            [ 'data' => [[0]], 'expect' => null, ],
            [ 'data' => ['hoge'], 'expect' => null, ],
            [ 'data' => [ 1, 2, 'hoge', ], 'expect' => null, ],
            [ 'data' => [ 1, 2, ], 'expect' => 2, ],
            [ 'data' => [ 1, 2, 3, ], 'expect' => 3, ],
            [ 'data' => [ 1, 2, 3, 4, ], 'expect' => 3.5, ],
            [ 'data' => [ 1, 2, 3, 4, 5, ], 'expect' => 4.5, ],
            [ 'data' => [ 3, 1, 5, 2, 4, ], 'expect' => 4.5, ],
        ];
    }

    #[DataProvider('provide_getThirdQuartile_can_get_third_quartile_correctly')]
    public function test_getThirdQuartile_can_get_third_quartile_correctly(mixed $data, int|float|null $expect): void
    {
        $ft = new FrequencyTable();
        $this->assertSame($expect, $ft->getThirdQuartile($data));
    }

    public static function provide_getInterQuartileRange_can_get_inter_quartile_range_correctly(): array
    {
        return [
            [ 'data' => null, 'expect' => null, ],
            [ 'data' => true, 'expect' => null, ],
            [ 'data' => false, 'expect' => null, ],
            [ 'data' => 0, 'expect' => null, ],
            [ 'data' => 1.2, 'expect' => null, ],
            [ 'data' => 'hoge', 'expect' => null, ],
            [ 'data' => [], 'expect' => null, ],
            [ 'data' => [null], 'expect' => null, ],
            [ 'data' => [true], 'expect' => null, ],
            [ 'data' => [false], 'expect' => null, ],
            [ 'data' => ['hoge'], 'expect' => null, ],
            [ 'data' => [[]], 'expect' => null, ],
            [ 'data' => [[ 0, 5, 10, ]], 'expect' => null, ],
            [ 'data' => [0], 'expect' => 0, ],
            [ 'data' => [ 0, 1, ], 'expect' => 1, ],
            [ 'data' => [ 1, 3, 5, ], 'expect' => 4, ],
            [ 'data' => [ 0.5, 2.5, 3.5, 4.5, ], 'expect' => 2.5, ],
        ];
    }

    #[DataProvider('provide_getInterQuartileRange_can_get_inter_quartile_range_correctly')]
    public function test_getInterQuartileRange_can_get_inter_quartile_range_correctly(mixed $data, int|float|null $expect): void
    {
        $ft = new FrequencyTable();
        $this->assertSame($expect, $ft->getInterQuartileRange($data));
    }

    public static function provide_getQuartileDeviation_can_get_quartile_deviation_correctly(): array
    {
        return [
            [ 'data' => null, 'expect' => null, ],
            [ 'data' => true, 'expect' => null, ],
            [ 'data' => false, 'expect' => null, ],
            [ 'data' => 0, 'expect' => null, ],
            [ 'data' => 1.2, 'expect' => null, ],
            [ 'data' => '0', 'expect' => null, ],
            [ 'data' => [], 'expect' => null, ],
            [ 'data' => [null, ], 'expect' => null, ],
            [ 'data' => [true], 'expect' => null, ],
            [ 'data' => [false], 'expect' => null, ],
            [ 'data' => ['0'], 'expect' => null, ],
            [ 'data' => ['1.2'], 'expect' => null, ],
            [ 'data' => [[]], 'expect' => null, ],
            [ 'data' => [[0]], 'expect' => null, ],
            [ 'data' => [[0,1]], 'expect' => null, ],
            [ 'data' => [0], 'expect' => 0, ],
            [ 'data' => [1], 'expect' => 0, ],
            [ 'data' => [ 0, 10, ], 'expect' => 5, ],
            [ 'data' => [ 0, 10, 30, ], 'expect' => 15, ],
            [ 'data' => [ 0.5, 10.2, 30.5, ], 'expect' => 15.0, ],
            [ 'data' => [ 0, 10, 20, 30, ], 'expect' => 10, ],
            [ 'data' => [ 0.5, 10.5, 20.5, 30.5, ], 'expect' => 10.0, ], // Q1=5.5, Q3=25.5, IQR=20.0, QD=10.0
        ];
    }

    #[DataProvider('provide_getQuartileDeviation_can_get_quartile_deviation_correctly')]
    public function test_getQuartileDeviation_can_get_quartile_deviation_correctly(mixed $data, int|float|null $expect): void
    {
        $ft = new FrequencyTable();
        $this->assertSame($expect, $ft->getQuartileDeviation($data));
    }

    public static function provide_setTableSeparator_and_getTableSeparator_can_work_correctly(): array
    {
        $defaultSeparator = self::$defaultTableSeparator;
        return [
            [ 'separator' => null, 'expect' => [ 'return' => false, 'separator' => $defaultSeparator, ], ],
            [ 'separator' => true, 'expect' => [ 'return' => false, 'separator' => $defaultSeparator, ], ],
            [ 'separator' => false, 'expect' => [ 'return' => false, 'separator' => $defaultSeparator, ], ],
            [ 'separator' => 0, 'expect' => [ 'return' => false, 'separator' => $defaultSeparator, ], ],
            [ 'separator' => 1.5, 'expect' => [ 'return' => false, 'separator' => $defaultSeparator, ], ],
            [ 'separator' => [], 'expect' => [ 'return' => false, 'separator' => $defaultSeparator, ], ],
            [ 'separator' => '', 'expect' => [ 'return' => true, 'separator' => '', ], ],
            [ 'separator' => 'hoge', 'expect' => [ 'return' => true, 'separator' => 'hoge', ], ],
        ];
    }

    #[DataProvider('provide_setTableSeparator_and_getTableSeparator_can_work_correctly')]
    public function test_setTableSeparator_and_getTableSeparator_can_work_correctly(mixed $separator, array $expect): void
    {
        $ft = new FrequencyTable();
        $ft->setTableSeparator($separator);
        $this->assertSame($expect['separator'], $ft->getTableSeparator());
    }

    public static function provide_setDefaultTableSeparator_can_set_default_table_separator(): array
    {
        return [
            ['defaultTableSeparator' => self::$defaultTableSeparator],
        ];
    }

    #[DataProvider('provide_setDefaultTableSeparator_can_set_default_table_separator')]
    public function test_setDefaultTableSeparator_can_set_default_table_separator(string $defaultTableSeparator): void
    {
        $ft = new FrequencyTable();
        $ft->setTableSeparator('');
        $ft->setDefaultTableSeparator();
        $this->assertSame($defaultTableSeparator, $ft->getTableSeparator());
    }

    public static function provide_isSettableColumns2Show_can_judge_columns_2_show_correctly(): array
    {
        return [
            [ 'columns' => null, 'expect' => false, ],
            [ 'columns' => true, 'expect' => false, ],
            [ 'columns' => false, 'expect' => false, ],
            [ 'columns' => '', 'expect' => false, ],
            [ 'columns' => 'hoge', 'expect' => false, ],
            [ 'columns' => 0, 'expect' => false, ],
            [ 'columns' => 1.2, 'expect' => false, ],
            [ 'columns' => [], 'expect' => false, ],
            [ 'columns' => [0], 'expect' => false, ],
            [ 'columns' => ['hoge'], 'expect' => false, ],
            [ 'columns' => [ ...self::$validColumns2Show, 'hoge', ], 'expect' => false, ],
            [ 'columns' => [...self::$validColumns2Show], 'expect' => true, ],
            [ 'columns' => ['Class'], 'expect' => true, ],
            [ 'columns' => [ 'Class', 'Class', 'Class', 'Class', 'Class', ], 'expect' => true, ],
            [ 'columns' => ['Frequency'], 'expect' => true, ],
            [ 'columns' => ['RelativeFrequency'], 'expect' => true, ],
            [ 'columns' => ['ClassValue'], 'expect' => true, ],
            [ 'columns' => ['ClassValue * Frequency'], 'expect' => true, ],
            [ 'columns' => [ 'Class', 'Frequency', ], 'expect' => true, ],
            [ 'columns' => [ 'Class', 'Frequency', 'RelativeFrequency', ], 'expect' => true, ],

            [ 'columns' => [ 'Class', 'Frequency', 'RelativeFrequency', 'ClassValue', ], 'expect' => true, ],
            [ 'columns' => [ 'Class', 'Frequency', 'RelativeFrequency', 'ClassValue * Frequency', ], 'expect' => true, ],
        ];
    }

    #[DataProvider('provide_isSettableColumns2Show_can_judge_columns_2_show_correctly')]
    public function test_isSettableColumns2Show_can_judge_columns_2_show_correctly(mixed $columns, bool $expect): void
    {
        $ft = new FrequencyTable();
        $this->assertSame($expect, $ft->isSettableColumns2Show($columns));
    }

    public static function provide_getValidColumns2Show_can_get_valid_columns_2_show(): array
    {
        return [
            ['columns2Show' => self::$validColumns2Show],
        ];
    }

    #[DataProvider('provide_getValidColumns2Show_can_get_valid_columns_2_show')]
    public function test_getValidColumns2Show_can_get_valid_columns_2_show(array $columns2Show): void
    {
        $ft = new FrequencyTable();
        $this->assertSame($columns2Show, $ft->getValidColumns2Show());
    }

    public static function provide_setColumns2Show_can_set_columns_2_show_correctly(): array
    {
        return [
            [ 'columns' => null, 'expect' => [ 'columns' => self::$validColumns2Show, ], ],
            [ 'columns' => true, 'expect' => [ 'columns' => self::$validColumns2Show, ], ],
            [ 'columns' => false, 'expect' => [ 'columns' => self::$validColumns2Show, ], ],
            [ 'columns' => '', 'expect' => [ 'columns' => self::$validColumns2Show, ], ],
            [ 'columns' => 'hoge', 'expect' => [ 'columns' => self::$validColumns2Show, ], ],
            [ 'columns' => 0, 'expect' => [ 'columns' => self::$validColumns2Show, ], ],
            [ 'columns' => 1.2, 'expect' => [ 'columns' => self::$validColumns2Show, ], ],
            [ 'columns' => [], 'expect' => [ 'columns' => self::$validColumns2Show, ], ],
            [ 'columns' => [0], 'expect' => [ 'columns' => self::$validColumns2Show, ], ],
            [ 'columns' => ['hoge'], 'expect' => [ 'columns' => self::$validColumns2Show, ], ],
            [ 'columns' => [ ...self::$validColumns2Show, 'hoge', ], 'expect' => [ 'columns' => self::$validColumns2Show, ], ],
            [ 'columns' => self::$validColumns2Show, 'expect' => [ 'columns' => self::$validColumns2Show, ], ],
            [ 'columns' => ['Class'], 'expect' => [ 'columns' => ['Class'], ], ],
            [ 'columns' => ['Frequency'], 'expect' => [ 'columns' => ['Frequency'], ], ],
            [ 'columns' => ['RelativeFrequency'], 'expect' => [ 'columns' => ['RelativeFrequency'], ], ],
            [ 'columns' => ['ClassValue'], 'expect' => [ 'columns' => ['ClassValue'], ], ],
            [ 'columns' => ['ClassValue * Frequency'], 'expect' => [ 'columns' => ['ClassValue * Frequency'], ], ],
        ];
    }

    #[DataProvider('provide_setColumns2Show_can_set_columns_2_show_correctly')]
    public function test_setColumns2Show_can_set_columns_2_show_correctly(mixed $columns, array $expect): void
    {
        $ft = new FrequencyTable();
        $ft->setColumns2Show($ft->getValidColumns2Show());
        $ft->setColumns2Show($columns);
        $this->assertSame($expect['columns'], $ft->getColumns2Show());
    }

    public static function provide_getDataOfEachClass_can_get_data_4_each_class_correctly(): array
    {
        return [
            [ 'classRange' => null, 'data' => null, 'expect' => [], ],
            [ 'classRange' => 10, 'data' => null, 'expect' => [], ],
            [ 'classRange' => null, 'data' => [0], 'expect' => [], ],
            [ 'classRange' => 10, 'data' => [0], 'expect' => [
                    [
                        'Class' => '0 ~ 10',
                        'Frequency' => 1,
                        'CumulativeFrequency' => 1,
                        'RelativeFrequency' => 1,
                        'CumulativeRelativeFrequency' => 1,
                        'ClassValue' => 5,
                        'ClassValue * Frequency' => 5,
                        'Subtotal' => 0,
                        'RelativeSubtotal' => 0,
                        'CumulativeRelativeSubtotal' => 0,
                    ],
                ],
            ],
            [ 'classRange' => 10, 'data' => [ 0, 5, 10, 15, 20, ], 'expect' => [
                    [
                        'Class' => '0 ~ 10',
                        'Frequency' => 2,
                        'CumulativeFrequency' => 2,
                        'RelativeFrequency' => 0.4,
                        'CumulativeRelativeFrequency' => 0.4,
                        'ClassValue' => 5,
                        'ClassValue * Frequency' => 10,
                        'Subtotal' => 5,
                        'RelativeSubtotal' => 0.1,
                        'CumulativeRelativeSubtotal' => 0.1,
                    ],
                    [
                        'Class' => '10 ~ 20',
                        'Frequency' => 2,
                        'CumulativeFrequency' => 4,
                        'RelativeFrequency' => 0.4,
                        'CumulativeRelativeFrequency' => 0.8,
                        'ClassValue' => 15,
                        'ClassValue * Frequency' => 30,
                        'Subtotal' => 25,
                        'RelativeSubtotal' => 0.5,
                        'CumulativeRelativeSubtotal' => 0.6,
                    ],
                    [
                        'Class' => '20 ~ 30',
                        'Frequency' => 1,
                        'CumulativeFrequency' => 5,
                        'RelativeFrequency' => 0.2,
                        'CumulativeRelativeFrequency' => 1.0,
                        'ClassValue' => 25,
                        'ClassValue * Frequency' => 25,
                        'Subtotal' => 20,
                        'RelativeSubtotal' => 0.4,
                        'CumulativeRelativeSubtotal' => 1.0,
                    ],
                ],
            ],
        ];
    }

    #[DataProvider('provide_getDataOfEachClass_can_get_data_4_each_class_correctly')]
    public function test_getDataOfEachClass_can_get_data_4_each_class_correctly(int|null $classRange, array|null $data, array $expect): void
    {
        $ft = new FrequencyTable();
        $ft->setColumns2Show(['Class', 'Frequency']);
        $ft->setClassRange($classRange);
        $ft->setData($data);
        $this->assertSame($expect, $ft->getDataOfEachClass());
    }

    public static function provide_filterData2Show_can_filter_data_2_show_correctly(): array
    {
        return [
            ['columns2Show' => self::$validColumns2Show],
        ];
    }

    #[DataProvider('provide_filterData2Show_can_filter_data_2_show_correctly')]
    public function test_filterData2Show_can_filter_data_2_show_correctly(array $columns2Show): void
    {
        $ft = new FrequencyTable();
        $classRange = 10;
        $ft->setClassRange($classRange);
        $data = [ 0, 5, 10, 15, 20, ];
        $ft->setData($data);
        $c = new Combination();
        foreach ($c->all($columns2Show) as $combination) {
            if (empty($combination)) {
                continue;
            }
            $ft->setColumns2Show($combination);
            $filtered = $ft->filterData2Show($ft->getDataOfEachClass());
            foreach ($columns2Show as $key) {
                if (in_array($key, $combination)) {
                    $this->assertTrue(array_key_exists($key, $filtered[0]));
                } else {
                    $this->assertFalse(array_key_exists($key, $filtered[0]));
                }
            }
        }
    }

    public static function provide_getTableData_can_return_table_data_correctly(): array
    {
        return [
            "case1" => [
                'classRange' => 10,
                'data' => [ 5, 10, 15, 20, 24, 28, 30,],
                'expect' => [
                    'tableHead' => [
                        'Class',
                        'Frequency',
                        'RelativeFrequency',
                        'ClassValue',
                        'ClassValue * Frequency',
                    ],
                    'classData' => [
                        [
                            'Class' => '0 ~ 10',
                            'Frequency' => '1',
                            'RelativeFrequency' => '0.14',
                            'ClassValue' => '5.0',
                            'ClassValue * Frequency' => '5.0',
                        ],
                        [
                            'Class' => '10 ~ 20',
                            'Frequency' => '2',
                            'RelativeFrequency' => '0.29',
                            'ClassValue' => '15.0',
                            'ClassValue * Frequency' => '30.0',
                        ],
                        [
                            'Class' => '20 ~ 30',
                            'Frequency' => '3',
                            'RelativeFrequency' => '0.43',
                            'ClassValue' => '25.0',
                            'ClassValue * Frequency' => '75.0',
                        ],
                        [
                            'Class' => '30 ~ 40',
                            'Frequency' => '1',
                            'RelativeFrequency' => '0.14',
                            'ClassValue' => '35.0',
                            'ClassValue * Frequency' => '35.0',
                        ],
                    ],
                    'total' => [
                        'Class' => 'Total',
                        'Frequency' => '7',
                        'RelativeFrequency' => '1.00',
                        'ClassValue' => '---',
                        'ClassValue * Frequency' => '145.0',
                    ],
                    'mean' => [
                        'Class' => 'Mean',
                        'Frequency' => '---',
                        'RelativeFrequency' => '---',
                        'ClassValue' => '---',
                        'ClassValue * Frequency' => '20.7',
                    ],
                ],
            ],
            "case2" => [
                'classRange' => 10,
                'data' => [ 5, 15, 20, 24, 28, 30, 35, 40, ],
                'expect' => [
                    'tableHead' => [
                        'Class',
                        'Frequency',
                        'RelativeFrequency',
                        'ClassValue',
                        'ClassValue * Frequency',
                    ],
                    'classData' => [
                        [
                            'Class' => '0 ~ 10',
                            'Frequency' => '1',
                            'RelativeFrequency' => '0.13',
                            'ClassValue' => '5.0',
                            'ClassValue * Frequency' => '5.0',
                        ],
                        [
                            'Class' => '10 ~ 20',
                            'Frequency' => '1',
                            'RelativeFrequency' => '0.13',
                            'ClassValue' => '15.0',
                            'ClassValue * Frequency' => '15.0',
                        ],
                        [
                            'Class' => '20 ~ 30',
                            'Frequency' => '3',
                            'RelativeFrequency' => '0.38',
                            'ClassValue' => '25.0',
                            'ClassValue * Frequency' => '75.0',
                        ],
                        [
                            'Class' => '30 ~ 40',
                            'Frequency' => '2',
                            'RelativeFrequency' => '0.25',
                            'ClassValue' => '35.0',
                            'ClassValue * Frequency' => '70.0',
                        ],
                        [
                            'Class' => '40 ~ 50',
                            'Frequency' => '1',
                            'RelativeFrequency' => '0.13',
                            'ClassValue' => '45.0',
                            'ClassValue * Frequency' => '45.0',
                        ],
                    ],
                    'total' => [
                        'Class' => 'Total',
                        'Frequency' => '8',
                        'RelativeFrequency' => '1.00',
                        'ClassValue' => '---',
                        'ClassValue * Frequency' => '210.0',
                    ],
                    'mean' => [
                        'Class' => 'Mean',
                        'Frequency' => '---',
                        'RelativeFrequency' => '---',
                        'ClassValue' => '---',
                        'ClassValue * Frequency' => '26.3',
                    ],
                ],
            ],
        ];
    }

    #[DataProvider('provide_getTableData_can_return_table_data_correctly')]
    public function test_getTableData_can_return_table_data_correctly(int $classRange, array $data, array $expect): void
    {
        $ft = new FrequencyTable([
            'classRange' => $classRange,
            'data' => $data,
        ]);
        $this->assertSame($expect, $ft->getTableData());
    }

    public static function provide_parse_return_null_under_invalid_condition(): array
    {
        return [
            [ 'classRange' => null, 'data' => null, ],
            [ 'classRange' => true, 'data' => null, ],
            [ 'classRange' => false, 'data' => null, ],
            [ 'classRange' => 0, 'data' => null, ],
            [ 'classRange' => 1.2, 'data' => null, ],
            [ 'classRange' => -1, 'data' => null, ],
            [ 'classRange' => "10", 'data' => null, ],
            [ 'classRange' => 10, 'data' => null, ],
            [ 'classRange' => [], 'data' => null, ],
            [ 'classRange' => 10, 'data' => true, ],
            [ 'classRange' => 10, 'data' => false, ],
            [ 'classRange' => 10, 'data' => 0, ],
            [ 'classRange' => 10, 'data' => 1.2, ],
            [ 'classRange' => 10, 'data' => "10", ],
            [ 'classRange' => 10, 'data' => [], ],
            [ 'classRange' => 10, 'data' => [[10]], ],
            [ 'classRange' => 10, 'data' => [null], ],
            [ 'classRange' => 10, 'data' => [true], ],
            [ 'classRange' => 10, 'data' => [false], ],
            [ 'classRange' => 10, 'data' => ["10"], ],
            [ 'classRange' => 10, 'data' => [ 10, 20, "30", ], ],
            [ 'classRange' => 10, 'data' => [ 1.2, 3.4, "4.6", ], ],
        ];
    }

    #[DataProvider('provide_parse_return_null_under_invalid_condition')]
    public function test_parse_return_null_under_invalid_condition(mixed $classRange, mixed $data): void
    {
        $ft = new FrequencyTable();
        $ft->setClassRange($classRange);
        $ft->setData($data);
        $this->assertNull($ft->parse());
    }

    public function test_parse_can_return_valid_data(): void
    {
        $ft = new FrequencyTable();
        $ft->setClassRange(10);
        $data = [ 0, 5, 10, 15, 20, ];
        $ft->setData($data);
        $expect = [
            'classRange' => 10,
            'data' => $data,
            'Max' => 20,
            'Min' => 0,
            'DataRange' => 20,
            'Mode' => 5,
            'Total' => 5,
            'Mean' => 13,
            'Median' => 10,
            'MedianClass' => [ 'index' => 1, 'bottom' => 10, 'top' => 20, ],
            'FirstQuartile' => 2.5,
            'ThirdQuartile' => 17.5,
            'InterQuartileRange' => 15.0,
            'QuartileDeviation' => 7.5,
            'Classes' => [
                [ 'bottom' => 0, 'top' => 10, ],
                [ 'bottom' => 10, 'top' => 20, ],
                [ 'bottom' => 20, 'top' => 30, ],
            ],
            'Frequencies' => [ 2, 2, 1, ],
            'Subtotals' => [5, 25, 20, ],
            'FrequencyTable' => [
                'tableHead' => [
                    'Class',
                    'Frequency',
                    'RelativeFrequency',
                    'ClassValue',
                    'ClassValue * Frequency',
                ],
                'classData' => [
                    [
                        'Class' => '0 ~ 10',
                        'Frequency' => '2',
                        'RelativeFrequency' => '0.40',
                        'ClassValue' => '5.0',
                        'ClassValue * Frequency' => '10.0',
                    ],
                    [
                        'Class' => '10 ~ 20',
                        'Frequency' => '2',
                        'RelativeFrequency' => '0.40',
                        'ClassValue' => '15.0',
                        'ClassValue * Frequency' => '30.0',
                    ],
                    [
                        'Class' => '20 ~ 30',
                        'Frequency' => '1',
                        'RelativeFrequency' => '0.20',
                        'ClassValue' => '25.0',
                        'ClassValue * Frequency' => '25.0',
                    ],
                ],
                'total' => [
                    'Class' => 'Total',
                    'Frequency' => '5',
                    'RelativeFrequency' => '1.00',
                    'ClassValue' => '---',
                    'ClassValue * Frequency' => '65.0',
                ],
                'mean' => [
                    'Class' => 'Mean',
                    'Frequency' => '---',
                    'RelativeFrequency' => '---',
                    'ClassValue' => '---',
                    'ClassValue * Frequency' => '13.0',
                ],
            ],
        ];
        $this->assertSame($expect, $ft->parse());
    }

    public static function provide_xsv_can_return_null_with_invalid_parameters(): array
    {
        return [
            [ 'path' => '', 'separator' => '', 'quatation' => '"', ],
            [ 'path' => '', 'separator' => '', 'quatation' => "'", ],
            [ 'path' => 'path', 'separator' => '', 'quatation' => '"', ],
            [ 'path' => 'path', 'separator' => '', 'quatation' => "'", ],
        ];
    }

    #[DataProvider('provide_xsv_can_return_null_with_invalid_parameters')]
    public function test_xsv_can_return_null_with_invalid_parameters(mixed $path, string|null $separator, string $quatation): void
    {
        $ft = new FrequencyTable();
        $this->assertNull($ft->xsv($path, $separator, $quatation));
    }

    public function test_csv_can_save_csv(): void
    {
        $data = [0, 5, 10, 15, 20, ];
        $columns2Show = [ 'Class', 'Frequency', ];
        $expect = [
            [ 'Class', 'Frequency', ],
            [ '0 ~ 10', '2', ],
            [ '10 ~ 20', '2', ],
            [ '20 ~ 30', '1', ],
            [ 'Total', '5',],
        ];
        $path = 'storage/test.csv';
        $splitter = ',';
        $ft = new FrequencyTable();
        $ft->setClassRange(10);
        $ft->setData($data);
        $ft->setColumns2Show($columns2Show);
        $this->clearStorage();
        $this->assertIsInt($ft->csv($path));
        $this->assertTrue(file_exists($path));
        $csv = array_map(
            fn($value): array => str_getcsv($value, $splitter, "\"", "\\"),
            file($path, FILE_IGNORE_NEW_LINES)
        );
        $this->assertSame($expect, $csv);
        $this->clearStorage();
    }


    public function test_csv_can_return_csv(): void
    {
        $data = [ 0, 5, 10, 15, 20, ];
        $columns2Show = [ 'Class', 'Frequency', ];
        $expect = [
            [ 'Class', 'Frequency', ],
            [ '0 ~ 10', '2', ],
            [ '10 ~ 20', '2', ],
            [ '20 ~ 30', '1', ],
            [ 'Total', '5',],
        ];
        $cases = [
            [ 'path' => null, ],
            [ 'path' => '', ],
            [ 'path' => '0', ],
        ];
        $splitter = ',';
        $eol = "\n";
        $ft = new FrequencyTable();
        $ft->setClassRange(10);
        $ft->setData($data);
        $ft->setColumns2Show($columns2Show);
        foreach ($cases as $case) {
            $return = $ft->csv($case['path']);
            $this->assertIsString($return);
            $csv = array_map(
                fn($value): array => str_getcsv($value, $splitter, "\"", "\\"),
                explode($eol, $return)
            );
            array_pop($csv);
            $this->assertSame($expect, $csv);
        }
    }

    public function test_tsv_can_save_tsv(): void
    {
        $data = [ 0, 5, 10, 15, 20, ];
        $columns2Show = [ 'Class', 'Frequency', ];
        $expect = [
            [ 'Class', 'Frequency', ],
            [ '0 ~ 10', '2', ],
            [ '10 ~ 20', '2', ],
            [ '20 ~ 30', '1', ],
            [ 'Total', '5',],
        ];
        $path = 'storage/test.tsv';
        $splitter = "\t";
        $ft = new FrequencyTable();
        $ft->setClassRange(10);
        $ft->setData($data);
        $ft->setColumns2Show($columns2Show);
        $this->clearStorage();
        $this->assertIsInt($ft->tsv($path));
        $this->assertTrue(file_exists($path));
        $csv = array_map(
            fn($value): array => str_getcsv($value, $splitter, "\"", "\\"),
            file($path, FILE_IGNORE_NEW_LINES)
        );
        $this->assertSame($expect, $csv);
        $this->clearStorage();
    }

    public function test_csv_can_return_tsv(): void
    {
        $data = [ 0, 5, 10, 15, 20, ];
        $columns2Show = [ 'Class', 'Frequency', ];
        $expect = [
            [ 'Class', 'Frequency', ],
            [ '0 ~ 10', '2', ],
            [ '10 ~ 20', '2', ],
            [ '20 ~ 30', '1', ],
            [ 'Total', '5',],
        ];
        $cases = [
            [ 'path' => null, ],
            [ 'path' => '', ],
            [ 'path' => '0', ],
        ];
        $splitter = "\t";
        $eol = "\n";
        $ft = new FrequencyTable();
        $ft->setClassRange(10);
        $ft->setData($data);
        $ft->setColumns2Show($columns2Show);
        foreach ($cases as $case) {
            $return = $ft->tsv($case['path']);
            $this->assertIsString($return);
            $csv = array_map(
                fn($value): array => str_getcsv($value, $splitter, "\"", "\\"),
                explode($eol, $return)
            );
            array_pop($csv);
            $this->assertSame($expect, $csv);
        }
    }

    public function test_html_can_save_html(): void
    {
        $data = [ 0, 5, 10, 15, 20, ];
        $columns2Show = ['Class', 'Frequency', ];
        $expect = "<table>
<tr><th>Class</th><th>Frequency</th></tr>
<tr><td>0 ~ 10</td><td>2</td></tr>
<tr><td>10 ~ 20</td><td>2</td></tr>
<tr><td>20 ~ 30</td><td>1</td></tr>
<tr><td>Total</td><td>5</td></tr>
</table>
";
        $path = 'storage/test.html';
        if (file_exists($path)) {
            unlink($path);
        }
        $ft = new FrequencyTable();
        $ft->setClassRange(10);
        $ft->setData($data);
        $ft->setColumns2Show($columns2Show);
        $this->clearStorage();
        $this->assertIsInt($ft->html($path));
        $this->assertTrue(file_exists($path));
        $this->assertSame($expect, file_get_contents($path));
        $this->clearStorage();
    }

    public function test_html_can_return_html(): void
    {
        $data = [ 0, 5, 10, 15, 20, ];
        $columns2Show = ['Class', 'Frequency', ];
        $expect = "<table>
<tr><th>Class</th><th>Frequency</th></tr>
<tr><td>0 ~ 10</td><td>2</td></tr>
<tr><td>10 ~ 20</td><td>2</td></tr>
<tr><td>20 ~ 30</td><td>1</td></tr>
<tr><td>Total</td><td>5</td></tr>
</table>
";
        $path = null;
        $ft = new FrequencyTable();
        $ft->setClassRange(10);
        $ft->setData($data);
        $ft->setColumns2Show($columns2Show);
        $this->clearStorage();
        $html = $ft->html($path);
        $this->assertSame($expect, $html);
        $this->clearStorage();
    }

    public function test_markdown_can_save_markdown(): void
    {
        $data = [ 0, 5, 10, 15, 20, ];
        $columns2Show = [ 'Class', 'Frequency', ];
        $expect = "|Class|Frequency|
|:---:|:---:|
|0 ~ 10|2|
|10 ~ 20|2|
|20 ~ 30|1|
|Total|5|
";
        $path = 'storage/test.md';
        if (file_exists($path)) {
            unlink($path);
        }
        $ft = new FrequencyTable();
        $ft->setClassRange(10);
        $ft->setData($data);
        $ft->setColumns2Show($columns2Show);
        $this->clearStorage();
        $this->assertIsInt($ft->markdown($path));
        $this->assertTrue(file_exists($path));
        $this->assertSame($expect, file_get_contents($path));
        $this->clearStorage();
    }

    public function test_markdown_can_return_markdown(): void
    {
        $data = [ 0, 5, 10, 15, 20, ];
        $columns2Show = ['Class', 'Frequency', ];
        $expect = "|Class|Frequency|
|:---:|:---:|
|0 ~ 10|2|
|10 ~ 20|2|
|20 ~ 30|1|
|Total|5|
";
        $path = null;
        $ft = new FrequencyTable();
        $ft->setClassRange(10);
        $ft->setData($data);
        $ft->setColumns2Show($columns2Show);
        $this->assertSame($expect, $ft->markdown($path));
    }

    public static function provide_save_can_return_false_with_invalid_parameter(): array
    {
        return [
            [ 'path' => '', ],
            [ 'path' => 'hoge.txt', ],
            [ 'path' => 'hoge.php', ],
            [ 'path' => 'hoge.png', ],
        ];
    }

    #[DataProvider('provide_save_can_return_false_with_invalid_parameter')]
    public function test_save_can_return_false_with_invalid_parameter(mixed $path): void
    {
        $ft = new FrequencyTable();
        $this->assertFalse($ft->save($path));
    }

    public static function provide_save_can_save_in_specified_format(): array
    {
        return [
            [ 'path' => 'storage/test.csv', ],
            [ 'path' => 'storage/test.CSV', ],
            [ 'path' => 'storage/test.Csv', ],
            [ 'path' => 'storage/test.CSv', ],
            [ 'path' => 'storage/test.CsV', ],
            [ 'path' => 'storage/test.cSv', ],
            [ 'path' => 'storage/test.cSV', ],
            [ 'path' => 'storage/test.csV', ],

            [ 'path' => 'storage/test.tsv', ],
            [ 'path' => 'storage/test.TSV', ],
            [ 'path' => 'storage/test.Tsv', ],
            [ 'path' => 'storage/test.Tsv', ],
            [ 'path' => 'storage/test.TSv', ],
            [ 'path' => 'storage/test.TsV', ],
            [ 'path' => 'storage/test.tSv', ],
            [ 'path' => 'storage/test.tSV', ],
            [ 'path' => 'storage/test.tsV', ],

            [ 'path' => 'storage/test.html', ],
            [ 'path' => 'storage/test.HTML', ],
            [ 'path' => 'storage/test.Html', ],
            [ 'path' => 'storage/test.hTml', ],
            [ 'path' => 'storage/test.htMl', ],
            [ 'path' => 'storage/test.htmL', ],
            [ 'path' => 'storage/test.HTml', ],
            [ 'path' => 'storage/test.HtMl', ],
            [ 'path' => 'storage/test.HtmL', ],
            [ 'path' => 'storage/test.hTMl', ],
            [ 'path' => 'storage/test.hTmL', ],
            [ 'path' => 'storage/test.htML', ],
            [ 'path' => 'storage/test.HTMl', ],
            [ 'path' => 'storage/test.HTmL', ],
            [ 'path' => 'storage/test.HtML', ],
            [ 'path' => 'storage/test.hTML', ],

            [ 'path' => 'storage/test.md', ],
            [ 'path' => 'storage/test.MD', ],
            [ 'path' => 'storage/test.Md', ],
            [ 'path' => 'storage/test.mD', ],
        ];
    }

    #[DataProvider('provide_save_can_save_in_specified_format')]
    public function test_save_can_save_in_specified_format(string $path): void
    {
        $ft = new FrequencyTable();
        $ft->setClassRange(10);
        $ft->setData([ 0, 5, 10, 15, 20, ]);
        $this->clearStorage();
        $this->assertIsInt($ft->save($path));
        $this->assertTrue(file_exists($path));
        $this->clearStorage();
    }
}
