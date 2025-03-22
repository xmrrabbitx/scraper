<?php

namespace Macocci7\PhpCsv;

class Csv
{
    /**
     * csv data as an array
     * @var array<int, string[]>|null
     */
    protected array|null $csv = null;

    /**
     * offset of rows
     */
    protected int $offsetRow = 0;

    /**
     * cast type
     */
    protected string|null $castType = null;

    /**
     * constructor
     * @param   string|null $path
     */
    public function __construct(string|null $path = null)
    {
        if (!is_null($path)) {
            $this->load($path);
        }
    }

    /**
     * loads csv fromt $path
     * @param   string  $path
     * @return  self
     * @thrown  \Exception
     */
    public function load(string $path)
    {
        if (!file_exists($path)) {
            throw new \Exception('File does not exist:[' . $path . ']');
        }
        $this->csv = array_map(
            fn ($fn) => str_getcsv($fn, ",", "\"", "\\"),
            file($path)
        );
        return $this;
    }

    /**
     * saves $this->csv into a csv file
     * @param   string|null $path
     * @return  self
     * @thrown  \Exception
     */
    public function save(string|null $path = null)
    {
        if (is_null($path)) {
            $path = $this->newFilename();
        }
        if (strlen($path) < 1) {
            $path = $this->newFilename();
        }
        $f = function ($line): string {
            return '"' . implode('","', $line) . '"';
        };
        if (
            !file_put_contents($path, implode("\n", array_map($f, $this->csv)))
        ) {
            $message = "failed to save data into csv [" . $path . "].";
            throw new \Exception($message);
        }
        return $this;
    }

    /**
     * returns a new file name
     * @return  string
     */
    public function newFilename()
    {
        $name = 'new.csv';
        $i = 0;
        while (file_exists($name)) {
            $i++;
            $name = 'new_' . $i . '.csv';
        }
        return $name;
    }

    /**
     * encodes csv from $from to $to
     * @param   string  $from
     * @param   string  $to
     * @return  self
     */
    public function encode(string $from, string $to)
    {
        foreach ($this->csv as $index => $row) {
            foreach ($row as $column => $value) {
                if (!is_null($value)) {
                    $this->csv[$index][$column]
                    = mb_convert_encoding($value, $to, $from);
                }
            }
        }
        return $this;
    }

    /**
     * returns rows of csv
     * @return  int
     */
    public function countRows()
    {
        return is_null($this->csv) ? 0 : count($this->csv);
    }

    /**
     * returns (max) columns of csv
     * @return  int
     */
    public function countColumns()
    {
        return is_null($this->csv) ? 0 : max(array_map('count', $this->csv));
    }

    /**
     * sets casting type as bool
     * @return self
     */
    public function bool()
    {
        $this->castType = 'bool';
        return $this;
    }

    /**
     * sets casting type as integer
     * @return  self
     */
    public function int()
    {
        $this->castType = 'int';
        return $this;
    }

    /**
     * sets casting type as float
     * @return  self
     */
    public function float()
    {
        $this->castType = 'float';
        return $this;
    }

    /**
     * sets casting type as string
     * @return  self
     */
    public function string()
    {
        $this->castType = 'string';
        return $this;
    }

    /**
     * sets casting type as raw data
     * @return  self
     */
    public function raw()
    {
        $this->castType = null;
        return $this;
    }

    /**
     * returns current cast type
     * @return  string| null
     */
    public function castType()
    {
        return $this->castType;
    }

    /**
     * sets offset of rows to skip
     * @param   int|null $offsetRow = null
     * @return  self|int
     * @thrown  \Exception
     */
    public function offsetRow(int|null $offsetRow = null)
    {
        if (is_null($offsetRow)) {
            return $this->offsetRow;
        }
        if ($offsetRow < 0) {
            throw new \Exception('Offset must be a natural number or zero.');
        }
        $this->offsetRow = $offsetRow;
        return $this;
    }

    /**
     * casts the value as specified type
     * @param   bool|int|float|string   $value
     * @return  bool|int|float|string
     */
    public function cast(bool|int|float|string $value)
    {
        return match ($this->castType) {
            "bool" => (bool) $value,
            "int" => (int) $value,
            "float" => (float) $value,
            "string" => (string) $value,
            default => $value,
        };
    }

    /**
     * casts a row
     * @param   array<int, bool|int|float|string>   $row
     * @return  array<int, bool|int|float|string>
     */
    public function castRow(array $row)
    {
        return array_map(
            fn ($value) => $this->cast($value),
            $row
        );
    }

    /**
     * returns the ($row)th row
     * @param   int $row
     * @return  array<int, bool|int|float|string>|null
     */
    public function row(int $row)
    {
        if ($row < 1) {
            return null;
        }
        if (empty($this->csv)) {
            return null;
        }
        if ($row > count($this->csv)) {
            return null;
        }
        return $this->castRow($this->csv[$row - 1]);
    }

    /**
     * returns ($column)th column
     * @param   int $column
     * @return  array<int, bool|int|float|string>|null
     */
    public function column(int $column)
    {
        if ($column < 1 || $column > $this->countColumns()) {
            return null;
        }
        $csv = $this->offsetRow
             ? array_slice($this->csv, $this->offsetRow)
             : $this->csv;
        $data = array_column($csv, $column - 1);
        if (!$data) {
            return null;
        }
        if ($this->castType) {
            foreach ($data as $index => $value) {
                $data[$index] = $this->cast($value);
            }
        }
        return $data;
    }

    /**
     * dumps $this->csv as csv
     * @return  string
     */
    public function dump()
    {
        return is_null($this->csv) ? null : implode(
            PHP_EOL,
            array_map(
                fn ($row) => '"' . implode('","', $row) . '"',
                $this->csv
            )
        );
    }

    /**
     * returns csv as an array
     * @return  array<int, string[]>
     */
    public function dumpArray()
    {
        return $this->csv;
    }

    /**
     * clears loaded csv data
     * @return  self
     */
    public function clear()
    {
        $this->csv = null;
        return $this;
    }

    /**
     * returns the value of the specified cell
     * @param   int $row
     * @param   int $column
     * @return  bool|int|float|string|null
     */
    public function cell(int $row, int $column)
    {
        $ir = $this->offsetRow + $row - 1;
        $ic = $column - 1;
        return isset($this->csv[$ir][$ic])
            ? $this->cast($this->csv[$ir][$ic])
            : null;
    }

    /**
     * returns rows in specified range
     * @param   int $start
     * @param   int $end
     * @return  array<int, array<int, bool|int|float|string>>|null
     */
    public function rowsBetween(int $start, int $end)
    {
        if (is_null($this->csv) || $start > $end) {
            return null;
        }
        $is = $this->offsetRow + $start - 1;
        $ie = $this->offsetRow + $end - 1;
        $rows = [];
        for ($i = $is; $i <= $ie; $i++) {
            if (isset($this->csv[$i])) {
                $rows[$i] = $this->castRow($this->csv[$i]);
            }
        }
        return $rows;
    }

    /**
     * returns first $n rows
     * @param   int $n
     * @return  array<int, array<int, bool|int|float|string>>|null
     */
    public function rows(int $n)
    {
        return $this->rowsBetween(1, $n);
    }

    /**
     * returns $n rows from the beginning of csv
     * @param   int $n = 5
     * @return  array<int, array<int, bool|int|float|string>>|null
     */
    public function head(int $n = 5)
    {
        if (is_null($this->csv) || $n < 1) {
            return null;
        }
        $is = 0;
        $ie = $n - 1;
        $rows = [];
        for ($i = $is; $i <= $ie; $i++) {
            if (isset($this->csv[$i])) {
                $rows[$i] = $this->castRow($this->csv[$i]);
            }
        }
        return $rows;
    }

    /**
     * returns $n rows from the end of csv
     * @param   int $n = 5
     * @return  array<int, array<int, bool|int|float|string>>|null
     */
    public function tail(int $n = 5)
    {
        if (is_null($this->csv) || $n < 1) {
            return null;
        }
        $ie = $this->countRows() - 1;
        $is = $ie - $n + 1;
        $rows = [];
        for ($i = $is; $i <= $ie; $i++) {
            if (isset($this->csv[$i])) {
                $rows[$i] = $this->castRow($this->csv[$i]);
            }
        }
        return $rows;
    }
}
