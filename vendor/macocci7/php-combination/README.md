# PHP-Combination

## 1. Features

`PHP-Combination` is a simple PHP library to make combinations from array elements.

`PHP-Combination` can:

- create `all` combinations
- `sort` combinations
- create `pairs`
- create all combinations `of N` elements
- create all combinations `of A 2 B` elements
- create all combinations between multiple arrays
- be used in data provider of PHPUnit

## 2. Contents
- [1. Features](#1-features)
- 2\. Contents
- [3. Requirements](#3-requirements)
- [4. Installation](#4-installation)
- [5. Classes](#5-classes)
  - [5.1. Combination](#51-combination)
  - [5.2. CombinationGenerator](#52-combinationgenerator)
  - [5.3. How to choose the class to use](#53-how-to-choose-the-class-to-use)
- [6. Methods](#6-methods)
- [7. Limit on the Number of Array Elements](#7-limit-on-the-number-of-array-elements)
- [8. Usage](#8-usage)
  - [8.1. Basic Usage](#81-basic-usage)
    - [8.1.1. Combination](#811-combination)
    - [8.1.2. CombinationGenerator](#812-combinationgenerator)
  - [8.2. Using Combination](#82-using-combination)
  - [8.3. Using Combination with Sorting](#83-using-combination-with-sorting)
  - [8.4. Using CombinationGenerator](#84-using-combinationgenerator)
  - [8.5. Using In PHPUnit](#85-using-in-phpunit)
- [9. Examples](#9-examples)
- [10. LICENSE](#10-license)

## 3. Requirements

- PHP 8.1 or later
- Composer

## 4. Installation

```bash
composer require macocci7/php-combination
```

## 5. Classes

There're 2 types of classes for the same methods.

### 5.1. Combination

```php
Macocci7\PhpCombination\Combination
```

This class returns the result as type of `array`.

### 5.2. CombinationGenerator

```php
Macocci7\PhpCombination\CombinationGenerator
```

This class returns the result as type of `Generator` object.

### 5.3. How to choose the class to use

There might be 3 factors.

1. **The number of elements of the param and result of all()**

    If the array of the param has $n elements,
    
    the number of array elements of returned array will be:
    
    ```php
    2 ** $n - 1
    ```
    |$n|formula|elements|
    |---:|:---|---:|
    |1|2 ** 1 - 1|1|
    |5|2 ** 5 - 1|31|
    |10|2 ** 10 - 1|1,023|
    |20|2 ** 20 - 1|1,048,575|
    |30|2 ** 30 - 1|1,073,741,823|
    |40|2 ** 40 - 1|1,099,511,627,775|
    |50|2 ** 50 - 1|1,125,899,906,842,623|
    |60|2 ** 60 - 1|1,152,921,504,606,846,975|
    |62|2 ** 62 - 1|4,611,686,018,427,387,903|

2. **Memory limit on your environment**

    Memory usage depends on:
    - the number of array elements to return
    - the type of data

    `Combination` uses much more memory than `CombinationGenerator`.

3. **execution time**

    Looping Generator takes much longer time than looping array.

In some cases, `CombinationGenerator` takes several times longer than `Combination`.

However, using an array with many elements as an argument can cause `Combination` to exceed memory limits.

In that case, use `CombinationGenerator`.

It will never exceeds the memory limit, and certainly complete the task.

## 6. Methods

### 6.1. Macocci7\PhpCombination\Combination

- `all()`: returns all combinations of the param
- `pairs()`: returns all pairs of the param
- `ofN()`: returns all combinations of N elements of the param
- `ofA2B()`: returns all combinations of A to B elements of the param
- `fromArrays()`: returns all combinations of multiple arrays as an instance of `Iterator`.

### 6.2. Macocci7\PhpCombination\CombinationGenenrator

- `all()`: returns all combinations of the param
- `pairs()`: returns all pairs of the param
- `ofN()`: returns all combinations of N elements of the param
- `ofA2B()`: returns all combinations of A to B elements of the param
- `fromArrays()`: returns all combinations of multiple arrays as an instance of `Iterator`.

## 7. Limit on the Number of Array Elements

The number of array elements of the param:
- 32bit-system: 30 elements
- 64bit-system: 62 elements

This limit is set to ensure that the number of elements in the returned array does not exceed the PHP upper limit on the index number of array.

The max index number of array in PHP equals to `PHP_INT_MAX`.

`PHP_INT_MAX`:
- 32bit-system: 2147483647 === 2 ** 31 - 1
- 64bit-system: 9223372036854775807 === 2 ** 63 - 1

## 8. Usage

- [8.1. Basic Usage](#81-basic-usage)
    - [8.1.1. Combination](#811-combination)
    - [8.1.2. CombinationGenerator](#812-combinationgenerator)
- [8.2. Using Combination](#82-using-combination)
- [8.3. Using Combination with Sorting](#83-using-combination-with-sorting)
- [8.4. Using CombinationGenerator](#84-using-combinationgenerator)
- [8.5. Using In PHPUnit](#85-using-in-phpunit)

### 8.1. Basic Usage

#### 8.1.1 Combination

- PHP:

    ```php
    <?php

    require_once __DIR__ . '/../vendor/autoload.php';

    use Macocci7\PhpCombination\Combination;

    $c = new Combination();
    $items = [ 'A', 'B', 'C', ];

    foreach ($c->all($items) as $index => $item) {
        echo sprintf(
            "%d: (%s)\n",
            $index,
            implode(', ', $item)
        );
    }
    ```

- Result:

    ```
    0: (A, B, C)
    1: (A, B)
    2: (A, C)
    3: (A)
    4: (B, C)
    5: (B)
    6: (C)
    ```

#### 8.1.2. CombinationGenerator

- PHP:

    ```php
    <?php

    require_once __DIR__ . '/../vendor/autoload.php';

    use Macocci7\PhpCombination\CombinationGenerator;

    $c = new CombinationGenerator();
    $items = [ 'A', 'B', 'C', ];

    foreach ($c->all($items) as $index => $item) {
        echo sprintf(
            "%d: (%s)\n",
            $index,
            implode(', ', $item)
        );
    }
    ```

- Result:

    ```
    0: (A, B, C)
    1: (A, B)
    2: (A, C)
    3: (A)
    4: (B, C)
    5: (B)
    6: (C)
    ```

### 8.2. Using Combination

- PHP:

    ```php
    <?php

    require_once __DIR__ . '/../vendor/autoload.php';

    use Macocci7\PhpCombination\Combination;

    // Create an Instance
    $c = new Combination();

    // All Items
    $items = ['A', 'B', 'C', 'D', 'E', ];
    echo sprintf("All Items:\n\t(%s)\n\n", implode(", ", $items));

    // Call back
    $f = function (array $array): string {
        return sprintf("(%s)", implode(', ', $array));
    };

    // All Combinations
    echo sprintf(
        "All Combinations:\n\t%s\n\n",
        implode("\n\t", array_map($f, $c->all($items)))
    );

    // All Pairs
    echo sprintf(
        "All Pairs:\n\t%s\n\n",
        implode("\n\t", array_map($f, $c->pairs($items)))
    );

    // All Combinations of $n elements
    $n = 3;
    echo sprintf(
        "All Combinations of %d elements:\n\t%s\n\n",
        $n,
        implode("\n\t", array_map($f, $c->ofN($items, $n)))
    );

    // All Combinations of $a to $b elements
    $a = 3;
    $b = 4;
    echo sprintf(
        "All Combinations of %d to %d elements:\n\t%s\n\n",
        $a,
        $b,
        implode("\n\t", array_map($f, $c->ofA2B($items, $a, $b)))
    );

    // All Combinations of $a1, $a2 and $a3
    $a1 = ['A1', 'A2', ];
    $a2 = ['B1', 'B2', 'B3', ];
    $a3 = ['C1', 'C2', 'C3', 'C4', ];

    echo "All Combinations of multiple arrays:\n";
    echo sprintf("\tArray1: (%s)\n", implode(', ', $a1));
    echo sprintf("\tArray2: (%s)\n", implode(', ', $a2));
    echo sprintf("\tArray3: (%s)\n", implode(', ', $a3));

    $r = $c->fromArrays([$a1, $a2, $a3, ]);
    $count = count($a1) * count($a2) * count($a3);
    $n = strlen((string) $count);
    echo sprintf("\tThere're %d patterns:\n", $count);
    foreach ($r as $i => $e) {
        echo sprintf("\t%" . $n . "d: (%s)\n", $i + 1, implode(', ', $e));
    }
    ```

- Result:

    ```
    All Items:
        (A, B, C, D, E)

    All Combinations:
        (A, B, C, D, E)
        (A, B, C, D)
        (A, B, C, E)
        (A, B, C)
        (A, B, D, E)
        (A, B, D)
        (A, B, E)
        (A, B)
        (A, C, D, E)
        (A, C, D)
        (A, C, E)
        (A, C)
        (A, D, E)
        (A, D)
        (A, E)
        (A)
        (B, C, D, E)
        (B, C, D)
        (B, C, E)
        (B, C)
        (B, D, E)
        (B, D)
        (B, E)
        (B)
        (C, D, E)
        (C, D)
        (C, E)
        (C)
        (D, E)
        (D)
        (E)

    All Pairs:
        (A, B)
        (A, C)
        (A, D)
        (A, E)
        (B, C)
        (B, D)
        (B, E)
        (C, D)
        (C, E)
        (D, E)

    All Combinations of 3 elements:
        (A, B, C)
        (A, B, D)
        (A, B, E)
        (A, C, D)
        (A, C, E)
        (A, D, E)
        (B, C, D)
        (B, C, E)
        (B, D, E)
        (C, D, E)

    All Combinations of 3 to 4 elements:
        (A, B, C, D)
        (A, B, C, E)
        (A, B, C)
        (A, B, D, E)
        (A, B, D)
        (A, B, E)
        (A, C, D, E)
        (A, C, D)
        (A, C, E)
        (A, D, E)
        (B, C, D, E)
        (B, C, D)
        (B, C, E)
        (B, D, E)
        (C, D, E)

    All Combinations of multiple arrays:
        Array1: (A1, A2)
        Array2: (B1, B2, B3)
        Array3: (C1, C2, C3, C4)
        There're 24 patterns:
        1: (A1, B1, C1)
        2: (A1, B1, C2)
        3: (A1, B1, C3)
        4: (A1, B1, C4)
        5: (A1, B2, C1)
        6: (A1, B2, C2)
        7: (A1, B2, C3)
        8: (A1, B2, C4)
        9: (A1, B3, C1)
        10: (A1, B3, C2)
        11: (A1, B3, C3)
        12: (A1, B3, C4)
        13: (A2, B1, C1)
        14: (A2, B1, C2)
        15: (A2, B1, C3)
        16: (A2, B1, C4)
        17: (A2, B2, C1)
        18: (A2, B2, C2)
        19: (A2, B2, C3)
        20: (A2, B2, C4)
        21: (A2, B3, C1)
        22: (A2, B3, C2)
        23: (A2, B3, C3)
        24: (A2, B3, C4)
    ```

### 8.3. Using Combination with Sorting

- PHP:

    ```php
    <?php

    require_once __DIR__ . '/../vendor/autoload.php';

    use Macocci7\PhpCombination\Combination;

    // Create an Instance
    $c = new Combination();

    // All Items
    $items = ['A', 'B', 'C', 'D', 'E', ];
    echo sprintf("All Items:\n\t(%s)\n\n", implode(", ", $items));

    // Set a flag to sort
    $sort = true;

    // Call back
    $f = function (array $array): string {
        return sprintf("(%s)", implode(', ', $array));
    };

    // All Combinations
    echo sprintf(
        "All Combinations:\n\t%s\n\n",
        implode("\n\t", array_map($f, $c->all($items, $sort)))
    );

    // All Pairs: does not support sorting
    echo sprintf(
        "All Pairs:\n\t%s\n\n",
        implode("\n\t", array_map($f, $c->pairs($items)))
    );

    // All Combinations of $n elements
    $n = 3;
    echo sprintf(
        "All Combinations of %d elements:\n\t%s\n\n",
        $n,
        implode("\n\t", array_map($f, $c->ofN($items, $n, $sort)))
    );

    // All Combinations of $a to $b elements
    $a = 3;
    $b = 4;
    echo sprintf(
        "All Combinations of %d to %d elements:\n\t%s\n\n",
        $a,
        $b,
        implode("\n\t", array_map($f, $c->ofA2B($items, $a, $b, $sort)))
    );

    // All Combinations of $a1, $a2 and $a3: does not support sorting
    $a1 = ['A1', 'A2', ];
    $a2 = ['B1', 'B2', 'B3', ];
    $a3 = ['C1', 'C2', 'C3', 'C4', ];

    echo "All Combinations of multiple arrays:\n";
    echo sprintf("\tArray1: (%s)\n", implode(', ', $a1));
    echo sprintf("\tArray2: (%s)\n", implode(', ', $a2));
    echo sprintf("\tArray3: (%s)\n", implode(', ', $a3));

    $r = $c->fromArrays([$a1, $a2, $a3, ]);
    $count = count($a1) * count($a2) * count($a3);
    $n = strlen((string) $count);
    echo sprintf("\tThere're %d patterns:\n", $count);
    foreach ($r as $i => $e) {
        echo sprintf("\t%" . $n . "d: (%s)\n", $i + 1, implode(', ', $e));
    }
    ```

- Result:

    ```
    All Items:
        (A, B, C, D, E)

    All Combinations:
        (A)
        (A, B)
        (A, B, C)
        (A, B, C, D)
        (A, B, C, D, E)
        (A, B, C, E)
        (A, B, D)
        (A, B, D, E)
        (A, B, E)
        (A, C)
        (A, C, D)
        (A, C, D, E)
        (A, C, E)
        (A, D)
        (A, D, E)
        (A, E)
        (B)
        (B, C)
        (B, C, D)
        (B, C, D, E)
        (B, C, E)
        (B, D)
        (B, D, E)
        (B, E)
        (C)
        (C, D)
        (C, D, E)
        (C, E)
        (D)
        (D, E)
        (E)

    All Pairs:
        (A, B)
        (A, C)
        (A, D)
        (A, E)
        (B, C)
        (B, D)
        (B, E)
        (C, D)
        (C, E)
        (D, E)

    All Combinations of 3 elements:
        (A, B, C)
        (A, B, D)
        (A, B, E)
        (A, C, D)
        (A, C, E)
        (A, D, E)
        (B, C, D)
        (B, C, E)
        (B, D, E)
        (C, D, E)

    All Combinations of 3 to 4 elements:
        (A, B, C)
        (A, B, C, D)
        (A, B, C, E)
        (A, B, D)
        (A, B, D, E)
        (A, B, E)
        (A, C, D)
        (A, C, D, E)
        (A, C, E)
        (A, D, E)
        (B, C, D)
        (B, C, D, E)
        (B, C, E)
        (B, D, E)
        (C, D, E)

    All Combinations of multiple arrays:
        Array1: (A1, A2)
        Array2: (B1, B2, B3)
        Array3: (C1, C2, C3, C4)
        There're 24 patterns:
        1: (A1, B1, C1)
        2: (A1, B1, C2)
        3: (A1, B1, C3)
        4: (A1, B1, C4)
        5: (A1, B2, C1)
        6: (A1, B2, C2)
        7: (A1, B2, C3)
        8: (A1, B2, C4)
        9: (A1, B3, C1)
        10: (A1, B3, C2)
        11: (A1, B3, C3)
        12: (A1, B3, C4)
        13: (A2, B1, C1)
        14: (A2, B1, C2)
        15: (A2, B1, C3)
        16: (A2, B1, C4)
        17: (A2, B2, C1)
        18: (A2, B2, C2)
        19: (A2, B2, C3)
        20: (A2, B2, C4)
        21: (A2, B3, C1)
        22: (A2, B3, C2)
        23: (A2, B3, C3)
        24: (A2, B3, C4)
    ```

### 8.4. Using CombinationGenerator

- PHP:

    ```php
    <?php

    require_once __DIR__ . '/../vendor/autoload.php';

    use Macocci7\PhpCombination\CombinationGenerator;

    // Create an Instance
    $c = new CombinationGenerator();

    // All Items
    $items = ['A', 'B', 'C', 'D', 'E', ];
    echo sprintf("All Items:\n\t(%s)\n\n", implode(", ", $items));

    // Common Format
    $fmt = "\t(%s)\n";

    // All Combinations
    echo "All Combinations:\n";
    foreach ($c->all($items) as $e) {
        echo sprintf($fmt, implode(', ', $e));
    }
    echo "\n";

    // All Pairs
    echo "All Pairs:\n";
    foreach ($c->pairs($items) as $e) {
        echo sprintf($fmt, implode(', ', $e));
    }
    echo "\n";

    // All Combinations of $n elements
    $n = 3;
    echo sprintf("All Combinations of %d elements:\n", $n);
    foreach ($c->ofN($items, $n) as $e) {
        echo sprintf($fmt, implode(', ', $e));
    }
    echo "\n";

    // All Combinations of $a to $b elements
    $a = 3;
    $b = 4;
    echo sprintf(
        "All Combinations of %d to %d elements:\n",
        $a,
        $b,
    );
    foreach ($c->ofA2B($items, $a, $b) as $e) {
        echo sprintf($fmt, implode(', ', $e));
    }
    echo "\n";

    // All Combinations of $a1, $a2 and $a3
    $a1 = [ 'A1', 'A2', ];
    $a2 = [ 'B1', 'B2', 'B3', ];
    $a3 = [ 'C1', 'C2', 'C3', 'C4', ];

    echo "All Combinations of multiple arrays:\n";
    echo sprintf("\tArray1: (%s)\n", implode(', ', $a1));
    echo sprintf("\tArray2: (%s)\n", implode(', ', $a2));
    echo sprintf("\tArray3: (%s)\n", implode(', ', $a3));

    $r = $c->fromArrays([$a1, $a2, $a3, ]);
    $count = count($a1) * count($a2) * count($a3);
    $n = strlen((string) $count);
    echo sprintf("\tThere're %d patterns:\n", $count);
    foreach ($r as $i => $e) {
        echo sprintf("\t%" . $n . "d: (%s)\n", $i + 1, implode(', ', $e));
    }
    ```

- Result:

    ```
    All Items:
        (A, B, C, D, E)

    All Combinations:
        (A, B, C, D, E)
        (A, B, C, D)
        (A, B, C, E)
        (A, B, C)
        (A, B, D, E)
        (A, B, D)
        (A, B, E)
        (A, B)
        (A, C, D, E)
        (A, C, D)
        (A, C, E)
        (A, C)
        (A, D, E)
        (A, D)
        (A, E)
        (A)
        (B, C, D, E)
        (B, C, D)
        (B, C, E)
        (B, C)
        (B, D, E)
        (B, D)
        (B, E)
        (B)
        (C, D, E)
        (C, D)
        (C, E)
        (C)
        (D, E)
        (D)
        (E)

    All Pairs:
        (A, B)
        (A, C)
        (A, D)
        (A, E)
        (B, C)
        (B, D)
        (B, E)
        (C, D)
        (C, E)
        (D, E)

    All Combinations of 3 elements:
        (A, B, C)
        (A, B, D)
        (A, B, E)
        (A, C, D)
        (A, C, E)
        (A, D, E)
        (B, C, D)
        (B, C, E)
        (B, D, E)
        (C, D, E)

    All Combinations of 3 to 4 elements:
        (A, B, C, D)
        (A, B, C, E)
        (A, B, C)
        (A, B, D, E)
        (A, B, D)
        (A, B, E)
        (A, C, D, E)
        (A, C, D)
        (A, C, E)
        (A, D, E)
        (B, C, D, E)
        (B, C, D)
        (B, C, E)
        (B, D, E)
        (C, D, E)

    All Combinations of multiple arrays:
        Array1: (A1, A2)
        Array2: (B1, B2, B3)
        Array3: (C1, C2, C3, C4)
        There're 24 patterns:
        1: (A1, B1, C1)
        2: (A1, B1, C2)
        3: (A1, B1, C3)
        4: (A1, B1, C4)
        5: (A1, B2, C1)
        6: (A1, B2, C2)
        7: (A1, B2, C3)
        8: (A1, B2, C4)
        9: (A1, B3, C1)
        10: (A1, B3, C2)
        11: (A1, B3, C3)
        12: (A1, B3, C4)
        13: (A2, B1, C1)
        14: (A2, B1, C2)
        15: (A2, B1, C3)
        16: (A2, B1, C4)
        17: (A2, B2, C1)
        18: (A2, B2, C2)
        19: (A2, B2, C3)
        20: (A2, B2, C4)
        21: (A2, B3, C1)
        22: (A2, B3, C2)
        23: (A2, B3, C3)
        24: (A2, B3, C4)
    ```

### 8.5. Using In PHPUnit

For Example, `fromArray()` method can be quite useful for testing with data providers of PHPUnit.

Here's an example for testing the class for ordering products,

with patterns of `size`, `color` and `amount`.

- Install MONOLOG before testing: (Just for this example)

    ```bash
    composer require --dev monolog/monolog
    ```

- PHP: Class to be tested

    ```php
    <?php

    namespace Macocci7\PhpCombination\Examples;

    use Monolog\Level;
    use Monolog\Logger;
    use Monolog\Handler\StreamHandler;

    class UseInPhpUnit
    {
        private Logger $log;

        public function __construct()
        {
            $this->log = new Logger('UseInPhpUnit');
            $this->log->pushHandler(
                new StreamHandler(__DIR__ . '/UseInPhpUnit.log', Level::Debug)
            );
        }

        public function order(
            int $productId,
            string $size,
            string $color,
            int $amount
        ) {
            $this->log->info('Adding a new order', [
                'productId' => $productId,
                'size' => $size,
                'color' => $color,
                'amount' => $amount,
            ]);
            return true;
        }
    }
    ```

- PHP: Test Class

    ```php
    <?php

    declare(strict_types=1);

    namespace Macocci7\PhpCombination;

    require_once __DIR__ . '/../vendor/autoload.php';
    require_once __DIR__ . '/UseInPhpUnit.class.php';

    use PHPUnit\Framework\Attributes\DataProvider;
    use PHPUnit\Framework\TestCase;
    use Macocci7\PhpCombination\Examples\UseInPhpUnit;
    use Macocci7\PhpCombination\Combination;

    final class UseInPhpUnitTest extends TestCase
    {
        public static function provide_order_can_order_correctly(): array
        {
            $products = [ 1101, 1102, ];
            $sizes = [ 'S', 'M', 'L', ];
            $colors = [ 'White', 'Black', ];
            $amount = [ 1, 2, ];
            $c = new Combination();
            $data = [];
            foreach (
                $c->fromArrays([$products, $sizes, $colors, $amount]) as $e
            ) {
                $data[implode(', ', $e)] = $e;
            }
            return $data;
        }

        /**
        * PHPDoc for PHPUnit 9.x
        * @dataProvider provide_order_can_order_correctly
        */
        // Attribute for PHPUnit 10.x or later
        #[DataProvider('provide_order_can_order_correctly')]
        public function test_order_can_order_correctly(
            int $productId,
            string $size,
            string $color,
            int $amount
        ): void {
            $u = new UseInPhpUnit();
            $this->assertTrue($u->order(
                $productId,
                $size,
                $color,
                $amount
            ));
        }
    }
    ```

- Result: STDOUT

    ```bash
    $ vendor/bin/phpunit ./examples/UseInPhpUnitTest.php --color=auto --testdox
    PHPUnit 10.5.19 by Sebastian Bergmann and contributors.

    Runtime:       PHP 8.1.26

    ........................                                          24 / 24 (100%)

    Time: 00:00.053, Memory: 8.00 MB

    Use In Php Unit (Macocci7\PhpCombination\UseInPhpUnit)
    ✔ Order can order correctly with 1101,·S,·White,·1
    ✔ Order can order correctly with 1101,·S,·White,·2
    ✔ Order can order correctly with 1101,·S,·Black,·1
    ✔ Order can order correctly with 1101,·S,·Black,·2
    ✔ Order can order correctly with 1101,·M,·White,·1
    ✔ Order can order correctly with 1101,·M,·White,·2
    ✔ Order can order correctly with 1101,·M,·Black,·1
    ✔ Order can order correctly with 1101,·M,·Black,·2
    ✔ Order can order correctly with 1101,·L,·White,·1
    ✔ Order can order correctly with 1101,·L,·White,·2
    ✔ Order can order correctly with 1101,·L,·Black,·1
    ✔ Order can order correctly with 1101,·L,·Black,·2
    ✔ Order can order correctly with 1102,·S,·White,·1
    ✔ Order can order correctly with 1102,·S,·White,·2
    ✔ Order can order correctly with 1102,·S,·Black,·1
    ✔ Order can order correctly with 1102,·S,·Black,·2
    ✔ Order can order correctly with 1102,·M,·White,·1
    ✔ Order can order correctly with 1102,·M,·White,·2
    ✔ Order can order correctly with 1102,·M,·Black,·1
    ✔ Order can order correctly with 1102,·M,·Black,·2
    ✔ Order can order correctly with 1102,·L,·White,·1
    ✔ Order can order correctly with 1102,·L,·White,·2
    ✔ Order can order correctly with 1102,·L,·Black,·1
    ✔ Order can order correctly with 1102,·L,·Black,·2

    OK (24 tests, 24 assertions)
    ```

- Result: LOG

    ```log
    [2024-04-18T00:46:47.065595+00:00] UseInPhpUnit.INFO: Adding a new order {"productId":1101,"size":"S","color":"White","amount":1} []
    [2024-04-18T00:46:47.069266+00:00] UseInPhpUnit.INFO: Adding a new order {"productId":1101,"size":"S","color":"White","amount":2} []
    [2024-04-18T00:46:47.070687+00:00] UseInPhpUnit.INFO: Adding a new order {"productId":1101,"size":"S","color":"Black","amount":1} []
    [2024-04-18T00:46:47.072942+00:00] UseInPhpUnit.INFO: Adding a new order {"productId":1101,"size":"S","color":"Black","amount":2} []
    [2024-04-18T00:46:47.074656+00:00] UseInPhpUnit.INFO: Adding a new order {"productId":1101,"size":"M","color":"White","amount":1} []
    [2024-04-18T00:46:47.077154+00:00] UseInPhpUnit.INFO: Adding a new order {"productId":1101,"size":"M","color":"White","amount":2} []
    [2024-04-18T00:46:47.078569+00:00] UseInPhpUnit.INFO: Adding a new order {"productId":1101,"size":"M","color":"Black","amount":1} []
    [2024-04-18T00:46:47.079828+00:00] UseInPhpUnit.INFO: Adding a new order {"productId":1101,"size":"M","color":"Black","amount":2} []
    [2024-04-18T00:46:47.080988+00:00] UseInPhpUnit.INFO: Adding a new order {"productId":1101,"size":"L","color":"White","amount":1} []
    [2024-04-18T00:46:47.082439+00:00] UseInPhpUnit.INFO: Adding a new order {"productId":1101,"size":"L","color":"White","amount":2} []
    [2024-04-18T00:46:47.083968+00:00] UseInPhpUnit.INFO: Adding a new order {"productId":1101,"size":"L","color":"Black","amount":1} []
    [2024-04-18T00:46:47.084862+00:00] UseInPhpUnit.INFO: Adding a new order {"productId":1101,"size":"L","color":"Black","amount":2} []
    [2024-04-18T00:46:47.085751+00:00] UseInPhpUnit.INFO: Adding a new order {"productId":1102,"size":"S","color":"White","amount":1} []
    [2024-04-18T00:46:47.086692+00:00] UseInPhpUnit.INFO: Adding a new order {"productId":1102,"size":"S","color":"White","amount":2} []
    [2024-04-18T00:46:47.090893+00:00] UseInPhpUnit.INFO: Adding a new order {"productId":1102,"size":"S","color":"Black","amount":1} []
    [2024-04-18T00:46:47.092489+00:00] UseInPhpUnit.INFO: Adding a new order {"productId":1102,"size":"S","color":"Black","amount":2} []
    [2024-04-18T00:46:47.094058+00:00] UseInPhpUnit.INFO: Adding a new order {"productId":1102,"size":"M","color":"White","amount":1} []
    [2024-04-18T00:46:47.095176+00:00] UseInPhpUnit.INFO: Adding a new order {"productId":1102,"size":"M","color":"White","amount":2} []
    [2024-04-18T00:46:47.096440+00:00] UseInPhpUnit.INFO: Adding a new order {"productId":1102,"size":"M","color":"Black","amount":1} []
    [2024-04-18T00:46:47.099292+00:00] UseInPhpUnit.INFO: Adding a new order {"productId":1102,"size":"M","color":"Black","amount":2} []
    [2024-04-18T00:46:47.100918+00:00] UseInPhpUnit.INFO: Adding a new order {"productId":1102,"size":"L","color":"White","amount":1} []
    [2024-04-18T00:46:47.102176+00:00] UseInPhpUnit.INFO: Adding a new order {"productId":1102,"size":"L","color":"White","amount":2} []
    [2024-04-18T00:46:47.103248+00:00] UseInPhpUnit.INFO: Adding a new order {"productId":1102,"size":"L","color":"Black","amount":1} []
    [2024-04-18T00:46:47.106206+00:00] UseInPhpUnit.INFO: Adding a new order {"productId":1102,"size":"L","color":"Black","amount":2} []
    ```

## 9. Examples

- [BasicUsage.php](examples/BasiUsage.php) >> results in [BasicUsage.txt](examples/BasicUsage.txt)
- [BasicUsageGenerator.php](examples/BasicUsageGenerator.php) >> results in [BasicUsageGenerator.txt](examples/BasicUsageGenerator.txt)
- [UseCombination.php](examples/UseCombination.php) >> results in [UseCombination.txt](examples/UseCombination.txt)
- [UseCombinationSort.php](examples/UseCombinationSort.php) >> results in [UseCombinationSort.txt](examples/UseCombinationSort.txt)
- [UseCombinationGenerator.php](examples/UseCombinationGenerator.php) >> results in [UseCombinationGenerator.txt](examples/UseCombinationGenerator.txt)
- [UseInPhpUnit.class.php](examples/UseInPhpUnit.class.php) & [UseInPhpUnitTest.php](examples/UseInPhpUnitTest.php) >> results in [UseInPhpUnit.log](examples/UseInPhpUnit.log)

## 10. LICENSE

[MIT](LICENSE)

***

*Document Created: 2023/11/11*

*Document Updated: 2025/01/04*

Copyright 2023 - 2025 macocci7
