# PHP-Csv

A simple PHP library for csv operation.

## Contents
- [Requirements](#requirements)
- [Installation](#installation)
- [Usage](#usage)
- [Examples](#examples)
- [LICENSE](#license)

## Requirements

- PHP 8.1 (CLI) or later
- Composer

## Installation

```bash
composer require macocci7/php-csv
```

## Usage

- PHP:

    ```php
    <?php

    require_once('../vendor/autoload.php');

    use Macocci7\PhpCsv\Csv;

    // load a csv
    $filename = 'csv/weather_tokyo_2023nov_sjis.csv';
    $csv = new Csv($filename);

    // encode data and save it
    $csv->encode('SJIS', 'UTF-8')->save('csv/weather_tokyo_2023nov_utf8.csv');

    // properties
    echo sprintf(
        "Filename:[%s], %d rows, %d columns.\n\n",
        $filename,
        $csv->countRows(),
        $csv->countColumns()
    );

    // retrieve a row
    echo sprintf("%s\n\n", implode(', ', $csv->row(5)));

    // offset of rows for column()
    $csv->offsetRow(7);

    // retrieve columns: Date & Max temprature & Min temprature
    echo sprintf("%s\n\n", implode(', ', $csv->string()->column(1)));
    echo sprintf("%s\n\n", implode(', ', $csv->float()->column(2)));
    echo sprintf("%s\n\n", implode(', ', $csv->int()->column(2)));
    echo sprintf("%s\n\n", implode(', ', $csv->bool()->column(2)));
    echo sprintf("%s\n\n", implode(', ', $csv->raw()->column(2)));
    ```

- Result:

    ```
    Filename:[csv/weather_tokyo_2023nov_sjis.csv], 38 rows, 27 columns.

    年月日, 平均気温(℃), 平均気温(℃), 平均気温(℃), 最高気温(℃), 最高気温(℃), 最高気温(℃), 最低気温(℃), 最低気温(℃), 最低気温(℃), 日照時間(時間), 日照時間(時間), 日照時間(時間), 日照時間(時間), 降水量の合計(mm), 降水量の合計(mm), 降水量の合計(mm), 降水量の合計(mm), 平均蒸気圧(hPa), 平均蒸気圧(hPa), 平均蒸気圧(hPa), 平均現地気圧(hPa), 平均現地気圧(hPa), 平均現地気圧(hPa), 平均雲量(10分比), 平均雲量(10分比), 平均雲量(10分比)

    2023/10/1, 2023/10/2, 2023/10/3, 2023/10/4, 2023/10/5, 2023/10/6, 2023/10/7, 2023/10/8, 2023/10/9, 2023/10/10, 2023/10/11, 2023/10/12, 2023/10/13, 2023/10/14, 2023/10/15, 2023/10/16, 2023/10/17, 2023/10/18, 2023/10/19, 2023/10/20, 2023/10/21, 2023/10/22, 2023/10/23, 2023/10/24, 2023/10/25, 2023/10/26, 2023/10/27, 2023/10/28, 2023/10/29, 2023/10/30, 2023/10/31

    25.6, 23.3, 22.8, 18.7, 20, 20.8, 20.4, 18.1, 15.4, 20.2, 19.8, 19.4, 18.5, 18.2, 14.9, 19, 19.5, 18.9, 20.3, 21.8, 19, 15.9, 16.7, 17.7, 17.7, 16.9, 17.8, 17.5, 16.3, 16.9, 17.2

    25, 23, 22, 18, 20, 20, 20, 18, 15, 20, 19, 19, 18, 18, 14, 19, 19, 18, 20, 21, 19, 15, 16, 17, 17, 16, 17, 17, 16, 16, 17

    1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1

    25.6, 23.3, 22.8, 18.7, 20.0, 20.8, 20.4, 18.1, 15.4, 20.2, 19.8, 19.4, 18.5, 18.2, 14.9, 19.0, 19.5, 18.9, 20.3, 21.8, 19.0, 15.9, 16.7, 17.7, 17.7, 16.9, 17.8, 17.5, 16.3, 16.9, 17.2

    ```

## Methods
- `load()`: loads csv specified by the param
- `save()`: saves data into a csv file specified by the param
- `encode()`: encodes loaded csv data
- `countRows()`: returns the count of rows of the csv
- `countColumns()`: returns the max count of columns of the csv
- `bool()`: specify the cast type as (`bool`)
- `int()`: specify the cast type as (`int`)
- `float()`: specify the cast type as (`float`)
- `string()`: specify the cast type as (`string`)
- `raw()`: unset the cast type
- `castType()`: returns current cast type
- `offsetRow()`: specify offset of rows
- `row()`: retrieve the specified row as an array
- `column()`: retrieve the specified column as an array
- `dump()`: returns all data as csv
- `dumpArray()`: returns all data as an array
- `clear()`: clears loaded csv data
- `cell()`: returns the value in the cell
- `rowsBetween()`: returns rows between specified row numbers
- `rows()`: returns first `$n` rows.
- `head()`: returns `$n` rows from the beginning of csv
- `tail()`: returns `$n` rows from the end of csv

## Examples

- [UseCsv.php](examples/UseCsv.php) results in >> [UseCsv.txt](examples/UseCsv.txt)

## LICENSE

[MIT](LICENSE)

***

*Document Created 2023/11/10*

*Document Updated 2025/01/03*

Copyright 2023-2025 macocci7.
