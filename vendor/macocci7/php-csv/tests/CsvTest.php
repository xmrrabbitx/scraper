<?php

declare(strict_types=1);

namespace Macocci7\PhpCsv;

require_once __DIR__ . '/../vendor/autoload.php';

use Macocci7\PhpCsv\Csv;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class CsvTest extends TestCase
{
    public function test_load_can_load_csv_correctly(): void
    {
        $csv = new Csv();
        $csv->load(__DIR__ . '/csv/test.csv');
        $this->assertSame(11, $csv->countRows());
        $this->assertSame(5, $csv->countColumns());

        $csv = new Csv(__DIR__ . '/csv/test.csv');
        $this->assertSame(11, $csv->countRows());
        $this->assertSame(5, $csv->countColumns());
    }

    public function test_load_can_throw_exception_when_csv_doesnt_exist(): void
    {
        $path = __DIR__ . 'csv/notfound.csv';
        $csv = new Csv();
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('File does not exist:[' . $path . ']');
        $csv->load($path);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('File does not exist:[' . $path . ']');
        $csv = new Csv($path);
    }

    public function test_save_can_save_csv_correctly(): void
    {
        $csv = new Csv(__DIR__ . '/csv/test.csv');
        $csv->save();
        $this->assertTrue(file_exists('new.csv'));

        $csv->save();
        $this->assertTrue(file_exists('new_1.csv'));

        unlink('new.csv');
        unlink('new_1.csv');

        $path = __DIR__ . '/csv/saved.csv';
        $csv->save($path);
        $this->assertTrue(file_exists($path));
        unlink($path);
    }

    public static function provide_encode_can_encode_correctly(): array
    {
        return [
            ['from' => 'utf8', 'to' => 'sjis'],
            ['from' => 'utf8', 'to' => 'jis'],
        ];
    }

    #[DataProvider('provide_encode_can_encode_correctly')]
    public function test_encode_can_encode_correctly(string $from, string $to): void
    {
        $csv = new Csv(__DIR__ . "/csv/test_{$from}.csv");
        $path2Save = __DIR__ . "/csv/saved_{$to}.csv";
        $csv->encode($from, $to)->save($path2Save);
        $path2Compare = __DIR__ . "/csv/test_{$to}.csv";
        $this->assertSame(
            file_get_contents($path2Compare),
            file_get_contents($path2Save)
        );
        unlink($path2Save);
    }

    public function test_countRows_can_count_rows_correctly(): void
    {
        $csv = new Csv();
        $this->assertSame(0, $csv->countRows());
        $csv = new Csv(__DIR__ . '/csv/test.csv');
        $this->assertSame(11, $csv->countRows());
    }

    public function test_countColumns_can_count_columns_correctly(): void
    {
        $csv = new Csv();
        $this->assertSame(0, $csv->countColumns());
        $csv = new Csv(__DIR__ . '/csv/test.csv');
        $this->assertSame(5, $csv->countcolumns());
    }

    public static function provide_methods_can_set_cast_types_correctly(): array
    {
        return [
            ['method' => 'bool', 'castType' => 'bool'],
            ['method' => 'int', 'castType' => 'int'],
            ['method' => 'float', 'castType' => 'float'],
            ['method' => 'string', 'castType' => 'string'],
            ['method' => 'raw', 'castType' => null],
        ];
    }

    #[DataProvider('provide_methods_can_set_cast_types_correctly')]
    public function test_methods_can_set_cast_types_correctly(string $method, string|null $castType): void
    {
        $csv = new Csv();
        $this->assertSame($castType, $csv->{$method}()->castType());
    }

    public function test_offsetRow_can_work_correctly(): void
    {
        $csv = new Csv();
        $this->assertSame(0, $csv->offsetRow());
        $this->assertSame(0, $csv->offsetRow(0)->offsetRow());
        $this->assertSame(1, $csv->offsetRow(1)->offsetRow());
        $this->assertSame(5, $csv->offsetRow(5)->offsetRow());
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Offset must be a natural number or zero.');
        $csv->offsetRow(-1);
    }

    public static function provide_cast_can_cast_correctly(): array
    {
        return [
            ['castTypeMethod' => 'bool', 'checkMethod' => 'is_bool'],
            ['castTypeMethod' => 'int', 'checkMethod' => 'is_int'],
            ['castTypeMethod' => 'float', 'checkMethod' => 'is_float'],
            ['castTypeMethod' => 'string', 'checkMethod' => 'is_string'],
            ['castTypeMethod' => 'raw', 'checkMethod' => 'is_string'],
        ];
    }

    #[DataProvider('provide_cast_can_cast_correctly')]
    public function test_cast_can_cast_correctly(string $castTypeMethod, string $checkMethod): void
    {
        $csv = new Csv();
        $this->assertTrue(
            $checkMethod($csv->{$castTypeMethod}()->cast('hoge'))
        );
    }

    public static function provide_castRow_can_cast_row_correctly(): array
    {
        return [
            [
                'castTypeMethod' => 'bool',
                'input' => ["-2", "-1", "0", "1", "2"],
                'expected' => [true, true, false, true, true],
            ],
            [
                'castTypeMethod' => 'int',
                'input' => ["-2.5", "-1.8", "0.0", "1.5", "2.5"],
                'expected' => [-2, -1, 0, 1, 2],
            ],
            [
                'castTypeMethod' => 'float',
                'input' => ["-2.5", "-1.8", "0.0", "1.5", "2.5"],
                'expected' => [-2.5, -1.8, 0.0, 1.5, 2.5],
            ],
            [
                'castTypeMethod' => 'string',
                'input' => [-2.5, -1.8, 0.0, 1.5, 2.5],
                'expected' => ["-2.5", "-1.8", "0", "1.5", "2.5"],
            ],
            [
                'castTypeMethod' => 'raw',
                'input' => ["-2.5", "-1.8", "0", "1.5", "2.5"],
                'expected' => ["-2.5", "-1.8", "0", "1.5", "2.5"],
            ],
            [
                'castTypeMethod' => 'raw',
                'input' => [-2.5, -1.8, 0.0, 1.5, 2.5],
                'expected' => [-2.5, -1.8, 0.0, 1.5, 2.5],
            ],
        ];
    }

    #[DataProvider('provide_castRow_can_cast_row_correctly')]
    public function test_castRow_can_cast_row_correctly(string $castTypeMethod, array $input, array $expected): void
    {
        $csv = new Csv();
        $this->assertSame(
            $expected,
            $csv->{$castTypeMethod}()->castRow($input)
        );
    }

    public static function provide_row_can_return_row_correctly(): array
    {
        return [
            [
                'row' => -1,
                'expected' => null,
            ],
            [
                'row' => 0,
                'expected' => null,
            ],
            [
                'row' => 1,
                'expected' => ["Name", "M/F", "Age", "Country", "email"],
            ],
            [
                'row' => 2,
                'expected' => ["user1", "F", "27", "Japan", "user1@example.com"],
            ],
            [
                'row' => 11,
                'expected' => ["user10", "M", "36", "Australia", "user10@example.com"],
            ],
            [
                'row' => 12,
                'expected' => null,
            ],
        ];
    }

    #[DataProvider('provide_row_can_return_row_correctly')]
    public function test_row_can_return_row_correctly(int $row, array|null $expected): void
    {
        $csv = new Csv(__DIR__ . '/csv/test.csv');
        $this->assertSame($expected, $csv->row($row));
    }

    public function test_row_can_return_null_with_no_data(): void
    {
        $csv = new Csv();
        $this->assertNull($csv->row(1));
    }

    public static function provide_column_can_retrun_data_correctly(): array
    {
        return [
            [
                'column' => -1,
                'expected' => null,
            ],
            [
                'column' => 0,
                'expected' => null,
            ],
            [
                'column' => 1,
                'expected' => [
                    "Name",
                    "user1",
                    "user2",
                    "user3",
                    "user4",
                    "user5",
                    "user6",
                    "user7",
                    "user8",
                    "user9",
                    "user10",
                ],
            ],
            [
                'column' => 2,
                'expected' => [
                    "M/F",
                    "F",
                    "M",
                    "F",
                    "M",
                    "F",
                    "M",
                    "F",
                    "M",
                    "F",
                    "M",
                ],
            ],
            [
                'column' => 3,
                'expected' => [
                    "Age",
                    "27",
                    "28",
                    "29",
                    "30",
                    "31",
                    "32",
                    "33",
                    "34",
                    "35",
                    "36",
                ],
            ],
            [
                'column' => 4,
                'expected' => [
                    "Country",
                    "Japan",
                    "U.S.",
                    "Italy",
                    "U.K.",
                    "China",
                    "Brasil",
                    "Russia",
                    "India",
                    "Egypt",
                    "Australia",
                ],
            ],
            [
                'column' => 5,
                'expected' => [
                    "email",
                    "user1@example.com",
                    "user2@example.com",
                    "user3@example.com",
                    "user4@example.com",
                    "user5@example.com",
                    "user6@example.com",
                    "user7@example.com",
                    "user8@example.com",
                    "user9@example.com",
                    "user10@example.com",
                ],
            ],
            [
                'column' => 6,
                'expected' => null,
            ],
        ];
    }

    #[DataProvider('provide_column_can_retrun_data_correctly')]
    public function test_column_can_return_data_correctly(int $column, array|null $expected): void
    {
        $csv = new Csv(__DIR__ . '/csv/test.csv');
        $this->assertSame($expected, $csv->column($column));
    }

    public function test_column_can_return_null_with_no_data(): void
    {
        $csv = new Csv();
        $this->assertNull($csv->column(1));
    }

    public function test_dump_can_dump_csv_correctly(): void
    {
        $csv = new Csv();
        $this->assertNull($csv->dump());
        $expected = '"Name","M/F","Age","Country","email"
"user1","F","27","Japan","user1@example.com"
"user2","M","28","U.S.","user2@example.com"
"user3","F","29","Italy","user3@example.com"
"user4","M","30","U.K.","user4@example.com"
"user5","F","31","China","user5@example.com"
"user6","M","32","Brasil","user6@example.com"
"user7","F","33","Russia","user7@example.com"
"user8","M","34","India","user8@example.com"
"user9","F","35","Egypt","user9@example.com"
"user10","M","36","Australia","user10@example.com"';
        $csv = new Csv(__DIR__ . '/csv/test.csv');
        $this->assertSame($expected, $csv->dump());
    }

    public function test_dumpArray_can_dump_array_correctly(): void
    {
        $csv = new Csv();
        $this->assertNull($csv->dumpArray());
        $expected = [
            ["Name", "M/F", "Age", "Country", "email",],
            ["user1", "F", "27", "Japan", "user1@example.com",],
            ["user2", "M", "28", "U.S.", "user2@example.com",],
            ["user3", "F", "29", "Italy", "user3@example.com",],
            ["user4", "M", "30", "U.K.", "user4@example.com",],
            ["user5", "F", "31", "China", "user5@example.com",],
            ["user6", "M", "32", "Brasil", "user6@example.com",],
            ["user7", "F", "33", "Russia", "user7@example.com",],
            ["user8", "M", "34", "India", "user8@example.com",],
            ["user9", "F", "35", "Egypt", "user9@example.com",],
            ["user10", "M", "36", "Australia", "user10@example.com",],
        ];
        $csv = new Csv(__DIR__ . '/csv/test.csv');
        $this->assertSame($expected, $csv->dumpArray());
    }

    public function test_clear_can_clear_csv_correctly(): void
    {
        $csv = new Csv(__DIR__ . '/csv/test.csv');
        $this->assertTrue(!is_null($csv->dumpArray()));
        $this->assertNull($csv->clear()->dumpArray());
    }

    public static function provide_cell_can_return_value_in_cell_correctly(): array
    {
        return [
            ['offsetRow' => 0, 'row' => -1, 'column' => -1, 'expected' => null],
            ['offsetRow' => 0, 'row' => -1, 'column' => 0, 'expected' => null],
            ['offsetRow' => 0, 'row' => -1, 'column' => 1, 'expected' => null],
            ['offsetRow' => 0, 'row' => 0, 'column' => -1, 'expected' => null],
            ['offsetRow' => 0, 'row' => 1, 'column' => -1, 'expected' => null],
            ['offsetRow' => 0, 'row' => 0, 'column' => 0, 'expected' => null],
            ['offsetRow' => 0, 'row' => 0, 'column' => 1, 'expected' => null],
            ['offsetRow' => 0, 'row' => 1, 'column' => 0, 'expected' => null],
            ['offsetRow' => 0, 'row' => 1, 'column' => 1, 'expected' => "Name"],
            ['offsetRow' => 0, 'row' => 1, 'column' => 2, 'expected' => "M/F"],
            ['offsetRow' => 0, 'row' => 1, 'column' => 3, 'expected' => "Age"],
            ['offsetRow' => 0, 'row' => 1, 'column' => 4, 'expected' => "Country"],
            ['offsetRow' => 0, 'row' => 1, 'column' => 5, 'expected' => "email"],
            ['offsetRow' => 0, 'row' => 1, 'column' => 6, 'expected' => null],
            ['offsetRow' => 0, 'row' => 2, 'column' => 0, 'expected' => null],
            ['offsetRow' => 0, 'row' => 2, 'column' => 1, 'expected' => "user1"],
            ['offsetRow' => 0, 'row' => 2, 'column' => 2, 'expected' => "F"],
            ['offsetRow' => 0, 'row' => 2, 'column' => 3, 'expected' => "27"],
            ['offsetRow' => 0, 'row' => 2, 'column' => 4, 'expected' => "Japan"],
            ['offsetRow' => 0, 'row' => 2, 'column' => 5, 'expected' => "user1@example.com"],
            ['offsetRow' => 0, 'row' => 2, 'column' => 6, 'expected' => null],
            ['offsetRow' => 0, 'row' => 11, 'column' => 0, 'expected' => null],
            ['offsetRow' => 0, 'row' => 11, 'column' => 1, 'expected' => "user10"],
            ['offsetRow' => 0, 'row' => 11, 'column' => 2, 'expected' => "M"],
            ['offsetRow' => 0, 'row' => 11, 'column' => 3, 'expected' => "36"],
            ['offsetRow' => 0, 'row' => 11, 'column' => 4, 'expected' => "Australia"],
            ['offsetRow' => 0, 'row' => 11, 'column' => 5, 'expected' => "user10@example.com"],
            ['offsetRow' => 0, 'row' => 11, 'column' => 6, 'expected' => null],
            ['offsetRow' => 0, 'row' => 12, 'column' => 0, 'expected' => null],
            ['offsetRow' => 0, 'row' => 12, 'column' => 1, 'expected' => null],

            ['offsetRow' => 1, 'row' => -1, 'column' => -1, 'expected' => null],
            ['offsetRow' => 1, 'row' => -1, 'column' => 0, 'expected' => null],
            ['offsetRow' => 1, 'row' => -1, 'column' => 1, 'expected' => null],
            ['offsetRow' => 1, 'row' => 0, 'column' => -1, 'expected' => null],
            ['offsetRow' => 1, 'row' => 1, 'column' => -1, 'expected' => null],
            ['offsetRow' => 1, 'row' => 0, 'column' => 0, 'expected' => null],
            ['offsetRow' => 1, 'row' => 0, 'column' => 1, 'expected' => "Name"],
            ['offsetRow' => 1, 'row' => 0, 'column' => 2, 'expected' => "M/F"],
            ['offsetRow' => 1, 'row' => 0, 'column' => 3, 'expected' => "Age"],
            ['offsetRow' => 1, 'row' => 0, 'column' => 4, 'expected' => "Country"],
            ['offsetRow' => 1, 'row' => 0, 'column' => 5, 'expected' => "email"],
            ['offsetRow' => 1, 'row' => 0, 'column' => 6, 'expected' => null],
            ['offsetRow' => 1, 'row' => 1, 'column' => 0, 'expected' => null],
            ['offsetRow' => 1, 'row' => 1, 'column' => 1, 'expected' => "user1"],
            ['offsetRow' => 1, 'row' => 1, 'column' => 2, 'expected' => "F"],
            ['offsetRow' => 1, 'row' => 1, 'column' => 3, 'expected' => "27"],
            ['offsetRow' => 1, 'row' => 1, 'column' => 4, 'expected' => "Japan"],
            ['offsetRow' => 1, 'row' => 1, 'column' => 5, 'expected' => "user1@example.com"],
            ['offsetRow' => 1, 'row' => 1, 'column' => 6, 'expected' => null],
            ['offsetRow' => 1, 'row' => 10, 'column' => 0, 'expected' => null],
            ['offsetRow' => 1, 'row' => 10, 'column' => 1, 'expected' => "user10"],
            ['offsetRow' => 1, 'row' => 10, 'column' => 2, 'expected' => "M"],
            ['offsetRow' => 1, 'row' => 10, 'column' => 3, 'expected' => "36"],
            ['offsetRow' => 1, 'row' => 10, 'column' => 4, 'expected' => "Australia"],
            ['offsetRow' => 1, 'row' => 10, 'column' => 5, 'expected' => "user10@example.com"],
            ['offsetRow' => 1, 'row' => 10, 'column' => 6, 'expected' => null],
            ['offsetRow' => 1, 'row' => 11, 'column' => 0, 'expected' => null],
            ['offsetRow' => 1, 'row' => 11, 'column' => 1, 'expected' => null],
        ];
    }

    #[DataProvider('provide_cell_can_return_value_in_cell_correctly')]
    public function test_cell_can_return_value_in_cell_correctly(int $offsetRow, int $row, int $column, string|null $expected): void
    {
        $csv = new Csv(__DIR__ . '/csv/test.csv');
        if ($offsetRow > 0) {
            $csv->offsetRow($offsetRow);
        }
        $this->assertSame($expected, $csv->cell($row, $column));
    }

    public static function provide_rowsBetween_can_return_rows_correctly(): array
    {
        return [
            ['offsetRow' => 0, 'start' => 1, 'end' => 0, 'expected' => null],
            ['offsetRow' => 0, 'start' => 0, 'end' => 0, 'expected' => []],
            ['offsetRow' => 0, 'start' => -1, 'end' => 0, 'expected' => []],
            ['offsetRow' => 0, 'start' => -1, 'end' => 1, 'expected' => [
                ["Name", "M/F", "Age", "Country", "email",],
            ]],
            ['offsetRow' => 0, 'start' => 1, 'end' => 1, 'expected' => [
                ["Name", "M/F", "Age", "Country", "email",],
            ]],
            ['offsetRow' => 0, 'start' => -1, 'end' => 2, 'expected' => [
                ["Name", "M/F", "Age", "Country", "email",],
                ["user1", "F", "27", "Japan", "user1@example.com",],
            ]],
            ['offsetRow' => 0, 'start' => 1, 'end' => 2, 'expected' => [
                ["Name", "M/F", "Age", "Country", "email",],
                ["user1", "F", "27", "Japan", "user1@example.com",],
            ]],
            ['offsetRow' => 0, 'start' => -1, 'end' => 3, 'expected' => [
                ["Name", "M/F", "Age", "Country", "email",],
                ["user1", "F", "27", "Japan", "user1@example.com",],
                ["user2", "M", "28", "U.S.", "user2@example.com",],
            ]],
            ['offsetRow' => 0, 'start' => 1, 'end' => 3, 'expected' => [
                ["Name", "M/F", "Age", "Country", "email",],
                ["user1", "F", "27", "Japan", "user1@example.com",],
                ["user2", "M", "28", "U.S.", "user2@example.com",],
            ]],
            ['offsetRow' => 0, 'start' => -1, 'end' => 11, 'expected' => [
                ["Name", "M/F", "Age", "Country", "email",],
                ["user1", "F", "27", "Japan", "user1@example.com",],
                ["user2", "M", "28", "U.S.", "user2@example.com",],
                ["user3", "F", "29", "Italy", "user3@example.com",],
                ["user4", "M", "30", "U.K.", "user4@example.com",],
                ["user5", "F", "31", "China", "user5@example.com",],
                ["user6", "M", "32", "Brasil", "user6@example.com",],
                ["user7", "F", "33", "Russia", "user7@example.com",],
                ["user8", "M", "34", "India", "user8@example.com",],
                ["user9", "F", "35", "Egypt", "user9@example.com",],
                ["user10", "M", "36", "Australia", "user10@example.com",],
            ]],
            ['offsetRow' => 0, 'start' => 1, 'end' => 11, 'expected' => [
                ["Name", "M/F", "Age", "Country", "email",],
                ["user1", "F", "27", "Japan", "user1@example.com",],
                ["user2", "M", "28", "U.S.", "user2@example.com",],
                ["user3", "F", "29", "Italy", "user3@example.com",],
                ["user4", "M", "30", "U.K.", "user4@example.com",],
                ["user5", "F", "31", "China", "user5@example.com",],
                ["user6", "M", "32", "Brasil", "user6@example.com",],
                ["user7", "F", "33", "Russia", "user7@example.com",],
                ["user8", "M", "34", "India", "user8@example.com",],
                ["user9", "F", "35", "Egypt", "user9@example.com",],
                ["user10", "M", "36", "Australia", "user10@example.com",],
            ]],
            ['offsetRow' => 0, 'start' => -1, 'end' => 12, 'expected' => [
                ["Name", "M/F", "Age", "Country", "email",],
                ["user1", "F", "27", "Japan", "user1@example.com",],
                ["user2", "M", "28", "U.S.", "user2@example.com",],
                ["user3", "F", "29", "Italy", "user3@example.com",],
                ["user4", "M", "30", "U.K.", "user4@example.com",],
                ["user5", "F", "31", "China", "user5@example.com",],
                ["user6", "M", "32", "Brasil", "user6@example.com",],
                ["user7", "F", "33", "Russia", "user7@example.com",],
                ["user8", "M", "34", "India", "user8@example.com",],
                ["user9", "F", "35", "Egypt", "user9@example.com",],
                ["user10", "M", "36", "Australia", "user10@example.com",],
            ]],
            ['offsetRow' => 0, 'start' => 1, 'end' => 12, 'expected' => [
                ["Name", "M/F", "Age", "Country", "email",],
                ["user1", "F", "27", "Japan", "user1@example.com",],
                ["user2", "M", "28", "U.S.", "user2@example.com",],
                ["user3", "F", "29", "Italy", "user3@example.com",],
                ["user4", "M", "30", "U.K.", "user4@example.com",],
                ["user5", "F", "31", "China", "user5@example.com",],
                ["user6", "M", "32", "Brasil", "user6@example.com",],
                ["user7", "F", "33", "Russia", "user7@example.com",],
                ["user8", "M", "34", "India", "user8@example.com",],
                ["user9", "F", "35", "Egypt", "user9@example.com",],
                ["user10", "M", "36", "Australia", "user10@example.com",],
            ]],
            ['offsetRow' => 0, 'start' => 2, 'end' => 2, 'expected' => [
                1 => ["user1", "F", "27", "Japan", "user1@example.com",],
            ]],
            ['offsetRow' => 0, 'start' => 2, 'end' => 3, 'expected' => [
                1 => ["user1", "F", "27", "Japan", "user1@example.com",],
                2 => ["user2", "M", "28", "U.S.", "user2@example.com",],
            ]],
            ['offsetRow' => 0, 'start' => 2, 'end' => 11, 'expected' => [
                1 => ["user1", "F", "27", "Japan", "user1@example.com",],
                2 => ["user2", "M", "28", "U.S.", "user2@example.com",],
                3 => ["user3", "F", "29", "Italy", "user3@example.com",],
                4 => ["user4", "M", "30", "U.K.", "user4@example.com",],
                5 => ["user5", "F", "31", "China", "user5@example.com",],
                6 => ["user6", "M", "32", "Brasil", "user6@example.com",],
                7 => ["user7", "F", "33", "Russia", "user7@example.com",],
                8 => ["user8", "M", "34", "India", "user8@example.com",],
                9 => ["user9", "F", "35", "Egypt", "user9@example.com",],
                10 => ["user10", "M", "36", "Australia", "user10@example.com",],
            ]],
            ['offsetRow' => 0, 'start' => 2, 'end' => 12, 'expected' => [
                1 => ["user1", "F", "27", "Japan", "user1@example.com",],
                2 => ["user2", "M", "28", "U.S.", "user2@example.com",],
                3 => ["user3", "F", "29", "Italy", "user3@example.com",],
                4 => ["user4", "M", "30", "U.K.", "user4@example.com",],
                5 => ["user5", "F", "31", "China", "user5@example.com",],
                6 => ["user6", "M", "32", "Brasil", "user6@example.com",],
                7 => ["user7", "F", "33", "Russia", "user7@example.com",],
                8 => ["user8", "M", "34", "India", "user8@example.com",],
                9 => ["user9", "F", "35", "Egypt", "user9@example.com",],
                10 => ["user10", "M", "36", "Australia", "user10@example.com",],
            ]],
            ['offsetRow' => 0, 'start' => 10, 'end' => 11, 'expected' => [
                9 => ["user9", "F", "35", "Egypt", "user9@example.com",],
                10 => ["user10", "M", "36", "Australia", "user10@example.com",],
            ]],
            ['offsetRow' => 0, 'start' => 10, 'end' => 12, 'expected' => [
                9 => ["user9", "F", "35", "Egypt", "user9@example.com",],
                10 => ["user10", "M", "36", "Australia", "user10@example.com",],
            ]],
            ['offsetRow' => 0, 'start' => 11, 'end' => 11, 'expected' => [
                10 => ["user10", "M", "36", "Australia", "user10@example.com",],
            ]],
            ['offsetRow' => 0, 'start' => 11, 'end' => 12, 'expected' => [
                10 => ["user10", "M", "36", "Australia", "user10@example.com",],
            ]],
            ['offsetRow' => 0, 'start' => 12, 'end' => 1, 'expected' => null],
            ['offsetRow' => 0, 'start' => 12, 'end' => 12, 'expected' => []],
            ['offsetRow' => 0, 'start' => 12, 'end' => 13, 'expected' => []],            ['offsetRow' => 0, 'start' => 1, 'end' => 0, 'expected' => null],

            ['offsetRow' => 1, 'start' => 1, 'end' => 0, 'expected' => null],
            ['offsetRow' => 1, 'start' => -1, 'end' => 0, 'expected' => [
                ["Name", "M/F", "Age", "Country", "email",],
            ]],
            ['offsetRow' => 1, 'start' => 0, 'end' => 0, 'expected' => [
                ["Name", "M/F", "Age", "Country", "email",],
            ]],
            ['offsetRow' => 1, 'start' => -1, 'end' => 1, 'expected' => [
                ["Name", "M/F", "Age", "Country", "email",],
                ["user1", "F", "27", "Japan", "user1@example.com",],
            ]],
            ['offsetRow' => 1, 'start' => 0, 'end' => 1, 'expected' => [
                ["Name", "M/F", "Age", "Country", "email",],
                ["user1", "F", "27", "Japan", "user1@example.com",],
            ]],
            ['offsetRow' => 1, 'start' => 1, 'end' => 1, 'expected' => [
                1 => ["user1", "F", "27", "Japan", "user1@example.com",],
            ]],
            ['offsetRow' => 1, 'start' => 1, 'end' => 2, 'expected' => [
                1 => ["user1", "F", "27", "Japan", "user1@example.com",],
                2 => ["user2", "M", "28", "U.S.", "user2@example.com",],
            ]],
        ];
    }

    #[DataProvider('provide_rowsBetween_can_return_rows_correctly')]
    public function test_rowsBetween_can_return_rows_correctly(int $offsetRow, int $start, int $end, array|null $expected): void
    {
        $csv = new Csv(__DIR__ . '/csv/test.csv');
        if ($offsetRow > 0) {
            $csv->offsetRow($offsetRow);
        }
        $this->assertSame($expected, $csv->rowsBetween($start, $end));
    }

    public function test_rowsBetween_can_return_null_with_no_data(): void
    {
        $csv = new Csv();
        $this->assertNull($csv->rowsBetween(1, 1));
    }

    public static function provide_rows_can_return_rows_correctly(): array
    {
        return [
            ['offsetRows' => 0, 'n' => -1, 'expected' => null],
            ['offsetRows' => 0, 'n' => 0, 'expected' => null],
            ['offsetRows' => 0, 'n' => 1, 'expected' => [
                ["Name", "M/F", "Age", "Country", "email",],
            ]],
            ['offsetRows' => 0, 'n' => 2, 'expected' => [
                ["Name", "M/F", "Age", "Country", "email",],
                ["user1", "F", "27", "Japan", "user1@example.com",],
            ]],
            ['offsetRows' => 0, 'n' => 3, 'expected' => [
                ["Name", "M/F", "Age", "Country", "email",],
                ["user1", "F", "27", "Japan", "user1@example.com",],
                ["user2", "M", "28", "U.S.", "user2@example.com",],
            ]],
            ['offsetRows' => 0, 'n' => 11, 'expected' => [
                ["Name", "M/F", "Age", "Country", "email",],
                ["user1", "F", "27", "Japan", "user1@example.com",],
                ["user2", "M", "28", "U.S.", "user2@example.com",],
                ["user3", "F", "29", "Italy", "user3@example.com",],
                ["user4", "M", "30", "U.K.", "user4@example.com",],
                ["user5", "F", "31", "China", "user5@example.com",],
                ["user6", "M", "32", "Brasil", "user6@example.com",],
                ["user7", "F", "33", "Russia", "user7@example.com",],
                ["user8", "M", "34", "India", "user8@example.com",],
                ["user9", "F", "35", "Egypt", "user9@example.com",],
                ["user10", "M", "36", "Australia", "user10@example.com",],
            ]],
            ['offsetRows' => 0, 'n' => 12, 'expected' => [
                ["Name", "M/F", "Age", "Country", "email",],
                ["user1", "F", "27", "Japan", "user1@example.com",],
                ["user2", "M", "28", "U.S.", "user2@example.com",],
                ["user3", "F", "29", "Italy", "user3@example.com",],
                ["user4", "M", "30", "U.K.", "user4@example.com",],
                ["user5", "F", "31", "China", "user5@example.com",],
                ["user6", "M", "32", "Brasil", "user6@example.com",],
                ["user7", "F", "33", "Russia", "user7@example.com",],
                ["user8", "M", "34", "India", "user8@example.com",],
                ["user9", "F", "35", "Egypt", "user9@example.com",],
                ["user10", "M", "36", "Australia", "user10@example.com",],
            ]],
            ['offsetRows' => 1, 'n' => -1, 'expected' => null],
            ['offsetRows' => 1, 'n' => 0, 'expected' => null],
            ['offsetRows' => 1, 'n' => 1, 'expected' => [
                1 => ["user1", "F", "27", "Japan", "user1@example.com",],
            ]],
            ['offsetRows' => 1, 'n' => 2, 'expected' => [
                1 => ["user1", "F", "27", "Japan", "user1@example.com",],
                2 => ["user2", "M", "28", "U.S.", "user2@example.com",],
            ]],
            ['offsetRows' => 1, 'n' => 10, 'expected' => [
                1 => ["user1", "F", "27", "Japan", "user1@example.com",],
                2 => ["user2", "M", "28", "U.S.", "user2@example.com",],
                3 => ["user3", "F", "29", "Italy", "user3@example.com",],
                4 => ["user4", "M", "30", "U.K.", "user4@example.com",],
                5 => ["user5", "F", "31", "China", "user5@example.com",],
                6 => ["user6", "M", "32", "Brasil", "user6@example.com",],
                7 => ["user7", "F", "33", "Russia", "user7@example.com",],
                8 => ["user8", "M", "34", "India", "user8@example.com",],
                9 => ["user9", "F", "35", "Egypt", "user9@example.com",],
                10 => ["user10", "M", "36", "Australia", "user10@example.com",],
            ]],
            ['offsetRows' => 1, 'n' => 11, 'expected' => [
                1 => ["user1", "F", "27", "Japan", "user1@example.com",],
                2 => ["user2", "M", "28", "U.S.", "user2@example.com",],
                3 => ["user3", "F", "29", "Italy", "user3@example.com",],
                4 => ["user4", "M", "30", "U.K.", "user4@example.com",],
                5 => ["user5", "F", "31", "China", "user5@example.com",],
                6 => ["user6", "M", "32", "Brasil", "user6@example.com",],
                7 => ["user7", "F", "33", "Russia", "user7@example.com",],
                8 => ["user8", "M", "34", "India", "user8@example.com",],
                9 => ["user9", "F", "35", "Egypt", "user9@example.com",],
                10 => ["user10", "M", "36", "Australia", "user10@example.com",],
            ]],
            ['offsetRows' => 2, 'n' => 9, 'expected' => [
                2 => ["user2", "M", "28", "U.S.", "user2@example.com",],
                3 => ["user3", "F", "29", "Italy", "user3@example.com",],
                4 => ["user4", "M", "30", "U.K.", "user4@example.com",],
                5 => ["user5", "F", "31", "China", "user5@example.com",],
                6 => ["user6", "M", "32", "Brasil", "user6@example.com",],
                7 => ["user7", "F", "33", "Russia", "user7@example.com",],
                8 => ["user8", "M", "34", "India", "user8@example.com",],
                9 => ["user9", "F", "35", "Egypt", "user9@example.com",],
                10 => ["user10", "M", "36", "Australia", "user10@example.com",],
            ]],
            ['offsetRows' => 2, 'n' => 10, 'expected' => [
                2 => ["user2", "M", "28", "U.S.", "user2@example.com",],
                3 => ["user3", "F", "29", "Italy", "user3@example.com",],
                4 => ["user4", "M", "30", "U.K.", "user4@example.com",],
                5 => ["user5", "F", "31", "China", "user5@example.com",],
                6 => ["user6", "M", "32", "Brasil", "user6@example.com",],
                7 => ["user7", "F", "33", "Russia", "user7@example.com",],
                8 => ["user8", "M", "34", "India", "user8@example.com",],
                9 => ["user9", "F", "35", "Egypt", "user9@example.com",],
                10 => ["user10", "M", "36", "Australia", "user10@example.com",],
            ]],
            ['offsetRows' => 10, 'n' => 1, 'expected' => [
                10 => ["user10", "M", "36", "Australia", "user10@example.com",],
            ]],
            ['offsetRows' => 10, 'n' => 2, 'expected' => [
                10 => ["user10", "M", "36", "Australia", "user10@example.com",],
            ]],
            ['offsetRows' => 11, 'n' => 1, 'expected' => []],
            ['offsetRows' => 11, 'n' => 2, 'expected' => []],
            ['offsetRows' => 12, 'n' => 1, 'expected' => []],
        ];
    }

    #[DataProvider('provide_rows_can_return_rows_correctly')]
    public function test_rows_can_return_rows_correctly(int $offsetRows, int $n, array|null $expected): void
    {
        $csv = new Csv(__DIR__ . '/csv/test.csv');
        if ($offsetRows > 0) {
            $csv->offsetRow($offsetRows);
        }
        $this->assertSame($expected, $csv->rows($n));
    }

    public static function provide_head_can_return_rows_correctly(): array
    {
        return [
            ['offsetRows' => 0, 'n' => -1, 'expected' => null],
            ['offsetRows' => 0, 'n' => 0, 'expected' => null],
            ['offsetRows' => 0, 'n' => 1, 'expected' => [
                ["Name", "M/F", "Age", "Country", "email",],
            ]],
            ['offsetRows' => 0, 'n' => 2, 'expected' => [
                ["Name", "M/F", "Age", "Country", "email",],
                ["user1", "F", "27", "Japan", "user1@example.com",],
            ]],
            ['offsetRows' => 0, 'n' => 3, 'expected' => [
                ["Name", "M/F", "Age", "Country", "email",],
                ["user1", "F", "27", "Japan", "user1@example.com",],
                ["user2", "M", "28", "U.S.", "user2@example.com",],
            ]],
            ['offsetRows' => 0, 'n' => 11, 'expected' => [
                ["Name", "M/F", "Age", "Country", "email",],
                ["user1", "F", "27", "Japan", "user1@example.com",],
                ["user2", "M", "28", "U.S.", "user2@example.com",],
                ["user3", "F", "29", "Italy", "user3@example.com",],
                ["user4", "M", "30", "U.K.", "user4@example.com",],
                ["user5", "F", "31", "China", "user5@example.com",],
                ["user6", "M", "32", "Brasil", "user6@example.com",],
                ["user7", "F", "33", "Russia", "user7@example.com",],
                ["user8", "M", "34", "India", "user8@example.com",],
                ["user9", "F", "35", "Egypt", "user9@example.com",],
                ["user10", "M", "36", "Australia", "user10@example.com",],
            ]],
            ['offsetRows' => 0, 'n' => 12, 'expected' => [
                ["Name", "M/F", "Age", "Country", "email",],
                ["user1", "F", "27", "Japan", "user1@example.com",],
                ["user2", "M", "28", "U.S.", "user2@example.com",],
                ["user3", "F", "29", "Italy", "user3@example.com",],
                ["user4", "M", "30", "U.K.", "user4@example.com",],
                ["user5", "F", "31", "China", "user5@example.com",],
                ["user6", "M", "32", "Brasil", "user6@example.com",],
                ["user7", "F", "33", "Russia", "user7@example.com",],
                ["user8", "M", "34", "India", "user8@example.com",],
                ["user9", "F", "35", "Egypt", "user9@example.com",],
                ["user10", "M", "36", "Australia", "user10@example.com",],
            ]],

            ['offsetRows' => 1, 'n' => -1, 'expected' => null],
            ['offsetRows' => 1, 'n' => 0, 'expected' => null],
            ['offsetRows' => 1, 'n' => 1, 'expected' => [
                ["Name", "M/F", "Age", "Country", "email",],
            ]],
            ['offsetRows' => 1, 'n' => 2, 'expected' => [
                ["Name", "M/F", "Age", "Country", "email",],
                ["user1", "F", "27", "Japan", "user1@example.com",],
            ]],
            ['offsetRows' => 1, 'n' => 3, 'expected' => [
                ["Name", "M/F", "Age", "Country", "email",],
                ["user1", "F", "27", "Japan", "user1@example.com",],
                ["user2", "M", "28", "U.S.", "user2@example.com",],
            ]],
            ['offsetRows' => 1, 'n' => 11, 'expected' => [
                ["Name", "M/F", "Age", "Country", "email",],
                ["user1", "F", "27", "Japan", "user1@example.com",],
                ["user2", "M", "28", "U.S.", "user2@example.com",],
                ["user3", "F", "29", "Italy", "user3@example.com",],
                ["user4", "M", "30", "U.K.", "user4@example.com",],
                ["user5", "F", "31", "China", "user5@example.com",],
                ["user6", "M", "32", "Brasil", "user6@example.com",],
                ["user7", "F", "33", "Russia", "user7@example.com",],
                ["user8", "M", "34", "India", "user8@example.com",],
                ["user9", "F", "35", "Egypt", "user9@example.com",],
                ["user10", "M", "36", "Australia", "user10@example.com",],
            ]],
            ['offsetRows' => 1, 'n' => 12, 'expected' => [
                ["Name", "M/F", "Age", "Country", "email",],
                ["user1", "F", "27", "Japan", "user1@example.com",],
                ["user2", "M", "28", "U.S.", "user2@example.com",],
                ["user3", "F", "29", "Italy", "user3@example.com",],
                ["user4", "M", "30", "U.K.", "user4@example.com",],
                ["user5", "F", "31", "China", "user5@example.com",],
                ["user6", "M", "32", "Brasil", "user6@example.com",],
                ["user7", "F", "33", "Russia", "user7@example.com",],
                ["user8", "M", "34", "India", "user8@example.com",],
                ["user9", "F", "35", "Egypt", "user9@example.com",],
                ["user10", "M", "36", "Australia", "user10@example.com",],
            ]],
        ];
    }

    #[DataProvider('provide_head_can_return_rows_correctly')]
    public function test_head_can_return_rows_correctly(int $offsetRows, int $n, array|null $expected): void
    {
        $csv = new Csv(__DIR__ . '/csv/test.csv');
        if ($offsetRows > 0) {
            $csv->offsetRow($offsetRows);
        }
        $this->assertSame($expected, $csv->head($n));
    }

    public function test_head_can_return_null_with_no_data(): void
    {
        $csv = new Csv();
        $this->assertNull($csv->head(11));
    }

    public function test_head_can_return_rows_with_default_value(): void
    {
        $csv = new Csv(__DIR__ . '/csv/test.csv');
        $this->assertSame($csv->head(5), $csv->head());
    }

    public static function provide_tail_can_return_rows_correctly(): array
    {
        return [
            ['offsetRows' => 0, 'n' => -1, 'expected' => null],
            ['offsetRows' => 0, 'n' => 0, 'expected' => null],
            ['offsetRows' => 0, 'n' => 1, 'expected' => [
                10 => ["user10", "M", "36", "Australia", "user10@example.com",],
            ]],
            ['offsetRows' => 0, 'n' => 2, 'expected' => [
                9 => ["user9", "F", "35", "Egypt", "user9@example.com",],
                10 => ["user10", "M", "36", "Australia", "user10@example.com",],
            ]],
            ['offsetRows' => 0, 'n' => 3, 'expected' => [
                8 => ["user8", "M", "34", "India", "user8@example.com",],
                9 => ["user9", "F", "35", "Egypt", "user9@example.com",],
                10 => ["user10", "M", "36", "Australia", "user10@example.com",],
            ]],
            ['offsetRows' => 0, 'n' => 11, 'expected' => [
                ["Name", "M/F", "Age", "Country", "email",],
                ["user1", "F", "27", "Japan", "user1@example.com",],
                ["user2", "M", "28", "U.S.", "user2@example.com",],
                ["user3", "F", "29", "Italy", "user3@example.com",],
                ["user4", "M", "30", "U.K.", "user4@example.com",],
                ["user5", "F", "31", "China", "user5@example.com",],
                ["user6", "M", "32", "Brasil", "user6@example.com",],
                ["user7", "F", "33", "Russia", "user7@example.com",],
                ["user8", "M", "34", "India", "user8@example.com",],
                ["user9", "F", "35", "Egypt", "user9@example.com",],
                ["user10", "M", "36", "Australia", "user10@example.com",],
            ]],
            ['offsetRows' => 0, 'n' => 12, 'expected' => [
                ["Name", "M/F", "Age", "Country", "email",],
                ["user1", "F", "27", "Japan", "user1@example.com",],
                ["user2", "M", "28", "U.S.", "user2@example.com",],
                ["user3", "F", "29", "Italy", "user3@example.com",],
                ["user4", "M", "30", "U.K.", "user4@example.com",],
                ["user5", "F", "31", "China", "user5@example.com",],
                ["user6", "M", "32", "Brasil", "user6@example.com",],
                ["user7", "F", "33", "Russia", "user7@example.com",],
                ["user8", "M", "34", "India", "user8@example.com",],
                ["user9", "F", "35", "Egypt", "user9@example.com",],
                ["user10", "M", "36", "Australia", "user10@example.com",],
            ]],

            ['offsetRows' => 1, 'n' => -1, 'expected' => null],
            ['offsetRows' => 1, 'n' => 0, 'expected' => null],
            ['offsetRows' => 1, 'n' => 1, 'expected' => [
                10 => ["user10", "M", "36", "Australia", "user10@example.com",],
            ]],
            ['offsetRows' => 1, 'n' => 2, 'expected' => [
                9 => ["user9", "F", "35", "Egypt", "user9@example.com",],
                10 => ["user10", "M", "36", "Australia", "user10@example.com",],
            ]],
            ['offsetRows' => 1, 'n' => 3, 'expected' => [
                8 => ["user8", "M", "34", "India", "user8@example.com",],
                9 => ["user9", "F", "35", "Egypt", "user9@example.com",],
                10 => ["user10", "M", "36", "Australia", "user10@example.com",],
            ]],
            ['offsetRows' => 1, 'n' => 11, 'expected' => [
                ["Name", "M/F", "Age", "Country", "email",],
                ["user1", "F", "27", "Japan", "user1@example.com",],
                ["user2", "M", "28", "U.S.", "user2@example.com",],
                ["user3", "F", "29", "Italy", "user3@example.com",],
                ["user4", "M", "30", "U.K.", "user4@example.com",],
                ["user5", "F", "31", "China", "user5@example.com",],
                ["user6", "M", "32", "Brasil", "user6@example.com",],
                ["user7", "F", "33", "Russia", "user7@example.com",],
                ["user8", "M", "34", "India", "user8@example.com",],
                ["user9", "F", "35", "Egypt", "user9@example.com",],
                ["user10", "M", "36", "Australia", "user10@example.com",],
            ]],
            ['offsetRows' => 1, 'n' => 12, 'expected' => [
                ["Name", "M/F", "Age", "Country", "email",],
                ["user1", "F", "27", "Japan", "user1@example.com",],
                ["user2", "M", "28", "U.S.", "user2@example.com",],
                ["user3", "F", "29", "Italy", "user3@example.com",],
                ["user4", "M", "30", "U.K.", "user4@example.com",],
                ["user5", "F", "31", "China", "user5@example.com",],
                ["user6", "M", "32", "Brasil", "user6@example.com",],
                ["user7", "F", "33", "Russia", "user7@example.com",],
                ["user8", "M", "34", "India", "user8@example.com",],
                ["user9", "F", "35", "Egypt", "user9@example.com",],
                ["user10", "M", "36", "Australia", "user10@example.com",],
            ]],
        ];
    }

    #[DataProvider('provide_tail_can_return_rows_correctly')]
    public function test_tail_can_return_rows_correctly(int $offsetRows, int $n, array|null $expected): void
    {
        $csv = new Csv(__DIR__ . '/csv/test.csv');
        if ($offsetRows > 0) {
            $csv->offsetRow($offsetRows);
        }
        $this->assertSame($expected, $csv->tail($n));
    }
}
