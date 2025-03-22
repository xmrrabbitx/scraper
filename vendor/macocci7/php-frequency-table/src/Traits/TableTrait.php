<?php

namespace Macocci7\PhpFrequencyTable\Traits;

use Macocci7\PhpFrequencyTable\Helper\Config;

trait TableTrait
{
    /**
     * @var string  $defaultTableSeparator
     */
    protected string $defaultTableSeparator = '|';

    /**
     * @var string  $classSeparator
     */
    protected string $classSeparator = ' ~ ';

    /**
     * @var mixed   $tableSeparator
     */
    protected mixed $tableSeparator = null;

    /**
     * @var string[]    $validColumns2Show
     */
    protected array $validColumns2Show;

    /**
     * @var string[]    $defaultColumns2Show
     */
    protected array $defaultColumns2Show;

    /**
     * @var string[]    $columns2Show
     */
    protected array $columns2Show = [];

    /**
     * @var string[]    $defaultTableColumnAligns
     */
    protected array $defaultTableColumnAligns;

    /**
     * returns table head
     * @return  string[]
     */
    public function getTableHead()
    {
        $conf = $this->supportedLangs[$this->lang()]; // @phpstan-ignore-line
        $heads = [];
        foreach ($this->getColumns2Show() as $c) {
            $heads[] = $conf[$c] ?? $c;
        }
        return $heads;
    }

    /**
     * returns totals in the table to show
     * @param   list<array<string, int|float|string|null>>  $data
     * @return  array<string, float|int|string|null>
     */
    public function getTableTotal2Show(array $data)
    {
        return [
            'Class' => $this->supportedLangs[$this->lang()]['Total'] ?? 'Total', // @phpstan-ignore-line
            'Frequency' => $this->getTotal(),
            'CumulativeFrequency' => $this->getTotal(),
            'RelativeFrequency' => array_sum(array_column($data, 'RelativeFrequency')), // @phpstan-ignore-line
            // @phpstan-ignore-next-line
            'CumulativeRelativeFrequency' => array_sum(array_column($data, 'RelativeFrequency')),
            'ClassValue' => '---',
            // @phpstan-ignore-next-line
            'ClassValue * Frequency' => array_sum(array_column($data, 'ClassValue * Frequency')),
            'Subtotal' => array_sum(array_column($data, 'Subtotal')),
            'RelativeSubtotal' => array_sum(array_column($data, 'RelativeSubtotal')),
            'CumulativeRelativeSubtotal' => array_sum(array_column($data, 'RelativeSubtotal')),
        ];
    }

    /**
     * returns mean row in the table to show
     * @return  array<string, string|int|float|null>
     */
    public function getMean2Show()
    {
        return [
            // @phpstan-ignore-next-line
            'Class' => $this->supportedLangs[$this->lang()]['Mean'] ?? 'Mean',
            'Frequency' => '---',
            'CumulativeFrequency' => '---',
            'RelativeFrequency' => '---',
            'CumulativeRelativeFrequency' => '---',
            'ClassValue' => '---',
            'ClassValue * Frequency' => $this->getMean(),
            'Subtotal' => array_sum($this->getData()) / count($this->getData()),
            'RelativeSubtotal' => '---',
            'CumulativeRelativeSubtotal' => '---',
        ];
    }

    /**
     * returns the data of each class
     * @return  list<array<string, int|float|string|null>>
     */
    public function getDataOfEachClass()
    {
        if (!$this->isSettableData($this->getData())) {
            return [];
        }
        $data = [];
        $classes = $this->getClasses();
        $frequencies = $this->getFrequencies();
        $subtotals = $this->getSubtotals();
        $relativeSubtotals = $this->getRelativeSubtotals();
        $cumulativeRelativeSubtotals = $this->getCumulativeRelativeSubtotals();
        $fc = [];
        $rf = [];
        foreach ($frequencies as $index => $frequency) {
            $fc[] = $frequency * $this->getClassValue($classes[$index]);
            $rf[] = $this->getRelativeFrequency($frequency);
            $data[] = [
                'Class' => number_format($classes[$index]['bottom'])
                           . $this->classSeparator
                           . number_format($classes[$index]['top']),
                'Frequency' => $frequency,
                'CumulativeFrequency' => $this->getCumulativeFrequency(
                    $frequencies,
                    $index
                ),
                'RelativeFrequency' => $rf[$index],
                'CumulativeRelativeFrequency' => $this->getCumulativeRelativeFrequency(
                    $frequencies,
                    $index
                ),
                'ClassValue' => $this->getClassValue($classes[$index]),
                'ClassValue * Frequency' => $fc[$index],
                'Subtotal' => $subtotals[$index],
                'RelativeSubtotal' => $relativeSubtotals[$index],
                'CumulativeRelativeSubtotal' => $cumulativeRelativeSubtotals[$index],
            ];
        }
        return $data;
    }

    /**
     * filters data to show
     * @param   list<array<string, int|float|string|null>>  $data
     * @return  list<array<string, int|float|string|null>>
     */
    public function filterData2Show(array $data)
    {
        $columns2Show = $this->getColumns2Show();
        $filtered = [];
        foreach ($data as $index => $row) {
            $filtered[$index] = [];
            foreach ($columns2Show as $c) {
                $filtered[$index][$c] = array_key_exists($c, $row)
                                      ? $row[$c]
                                      : null
                                      ;
            }
        }
        return $filtered;
    }

    /**
     * returns table data
     * @return mixed
     */
    public function getTableData()
    {
        return [
            'tableHead' => $this->getTableHead(),
            'classData' => $this->formatData2Show(
                // @phpstan-ignore-next-line
                $this->filterData2Show($this->getDataOfEachClass())
            ),
            'total' => $this->formatData2Show(
                // @phpstan-ignore-next-line
                $this->filterData2Show(
                    [$this->getTableTotal2Show($this->getDataOfEachClass())]
                )
            )[0],
            'mean' => $this->formatData2Show(
                // @phpstan-ignore-next-line
                $this->filterData2Show([$this->getMean2Show()])
            )[0],
        ];
    }

    /**
     * sets the table separator to show frequency table
     * @param   mixed   $separator
     * @return  self
     */
    public function setTableSeparator(mixed $separator)
    {
        if (is_string($separator)) {
            $this->tableSeparator = $separator;
        }
        return $this;
    }

    /**
     * returns the current table separator
     * @return  mixed
     */
    public function getTableSeparator()
    {
        return $this->tableSeparator;
    }

    /**
     * sets the default table separator
     * @return  self
     */
    public function setDefaultTableSeparator()
    {
        $this->setTableSeparator($this->defaultTableSeparator);
        return $this;
    }

    /**
     * returns table column aligns to show
     * @return  array<int|string, string>
     */
    public function getTableColumnAligns2Show()
    {
        $aligns = [];
        foreach ($this->getColumns2Show() as $column) {
            $aligns[$column] = $this->defaultTableColumnAligns[$column];
        }
        return $aligns;
    }

    /**
     * returns columns to show
     * @return  string[]
     */
    public function getColumns2Show()
    {
        return $this->columns2Show;
    }

    /**
     * returns valid columns to show
     * @return  string[]
     */
    public function getValidColumns2Show()
    {
        return $this->validColumns2Show;
    }

    /**
     * sets columns to show
     * @param   mixed   $columns
     * @return  self
     */
    public function setColumns2Show(mixed $columns)
    {
        if ($this->isSettableColumns2Show($columns)) {
            $this->columns2Show = $columns; // @phpstan-ignore-line
        }
        return $this;
    }

    /**
     * formats data to show
     * @param   list<array<string, int|float|string|null>>   $data
     * @return  list<array<string, int|float|string|null>>
     */
    protected function formatData2Show(array $data)
    {
        $f = Config::get('columnNumberFormat');
        foreach ($data as $i => $d) {
            foreach ($d as $k => $v) {
                if (isset($f[$k]) && is_numeric($v)) { // @phpstan-ignore-line
                    $data[$i][$k] = number_format(
                        (float) $v,
                        $f[$k]['decimals'] ?? 0, // @phpstan-ignore-line
                        $f[$k]['decimal_separator'] ?? '', // @phpstan-ignore-line
                        $f[$k]['thousands_separator'] ?? '' // @phpstan-ignore-line
                    );
                }
            }
        }
        return $data;
    }
}
