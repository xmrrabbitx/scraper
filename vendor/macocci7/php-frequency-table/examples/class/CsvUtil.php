<?php

namespace Macocci7;

/**
 * class for treating CSV
 */
class CsvUtil
{
    /**
     * constructor
     */
    public function __construct()
    {
    }

    /**
     * groups $valueColumn by $keyColumn
     * @param   string[]    $csv
     * @param   string      $keyColumn
     * @param   string      $valueColumn
     * @return  array<string, string[]>
     */
    public function groupBy($csv, $keyColumn, $valueColumn)
    {
        // CSV MUST INCLUDES COLUMN NAMES IN HEAD LINE
        $data = $csv;
        $head = array_shift($data);
        $indexKeyColumn = array_search($keyColumn, $head);
        $indexValueColumn = array_search($valueColumn, $head);
        if (!$indexKeyColumn || !$indexValueColumn) {
            return;
        }
        $groupBy = [];
        foreach ($data as $row) {
            if (null == $row[$indexValueColumn]) {
                continue;
            }
            $key = $row[$indexKeyColumn];
            $groupBy[$key][] = $row[$indexValueColumn];
        }
        ksort($groupBy);
        return $groupBy;
    }

    /**
     * converts string to integer in array
     * @param   string[]    $strings
     * @return  array<int|string, int|string>
     */
    public function convertString2IntegerInArray(array $strings)
    {
        foreach ($strings as $value) {
            if (!(is_numeric($value) || '' === $value)) {
                return;
            }
        }
        $integers = [];
        foreach ($strings as $key => $value) {
            $integers[$key] = (int) $value;
        }
        return $integers;
    }

    /**
     * returns daily data
     * @param   string  $csvFileName
     * @return  array<string, list<int|string>>
     */
    public function getDailyData(string $csvFileName)
    {
        if (!file_exists($csvFileName)) {
            echo "CsvUtil::getDailyData(): '" . $csvFileName . "' does not exist.\n";
        }
        $csv = array_map('str_getcsv', file($csvFileName));
        $groupBy = $this->groupBy($csv, "game_date", "release_speed");
        foreach ($groupBy as $index => $row) {
            $groupBy[$index] = $this->convertString2IntegerInArray($row);
        }
        return $groupBy;
    }
}
