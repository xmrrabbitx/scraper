<?php

namespace Macocci7\PhpFrequencyTable;

use Macocci7\PhpFrequencyTable\Helper\Config;

/**
 * Creates A Frequency Distribution Table
 * @author  macocci7 <macocci7@yahoo.co.jp>
 * @license MIT
 */
class FrequencyTable
{
    use Traits\ClassTrait;
    use Traits\FrequencyTrait;
    use Traits\DataTrait;
    use Traits\QuartileTrait;
    use Traits\JudgeTrait;
    use Traits\AttributesTrait;
    use Traits\TableTrait;
    use Traits\DataFormatsTrait;
    use Traits\SubtotalTrait;

    /**
     * @var string[]    $supportedFormats
     */
    private array $supportedFormats;

    /**
     * constructor
     * @param   mixed   $param = []
     */
    public function __construct(mixed $param = [])
    {
        $this->loadConf();
        if (array_key_exists('classRange', $param)) {
            $this->setClassRange($param['classRange']);
        }
        if (array_key_exists('data', $param)) {
            $this->setData($param['data']);
        }
        $this->setColumns2Show($this->defaultColumns2Show);
        if (array_key_exists('columns2Show', $param)) {
            $this->setColumns2Show($param['columns2Show']);
        }
        $this->setDefaultTableSeparator();
    }

    /**
     * loads config.
     * @return  void
     */
    private function loadConf()
    {
        Config::load();
        $props = [
            'validColumns2Show',
            'defaultColumns2Show',
            'defaultTableColumnAligns',
            'lang',
            'supportedLangs',
            'supportedFormats',
        ];
        foreach ($props as $prop) {
            $this->{$prop} = Config::get($prop);
        }
    }

    /**
     * returns parsed data
     * @return  null|array<string, mixed>
     */
    public function parse()
    {
        if (!$this->isSettableClassRange($this->getClassRange())) {
            return null;
        }
        if (!$this->isSettableData($this->getData())) {
            return null;
        }
        return [
            'classRange' => $this->getClassRange(),
            'data' => $this->getData(),
            'Max' => $this->getMax($this->getData()),
            'Min' => $this->getMin($this->getData()),
            'DataRange' => $this->getDataRange($this->getData()),
            'Mode' => $this->getMode(),
            'Total' => $this->getTotal(),
            'Mean' => $this->getMean(),
            'Median' => $this->getMedian($this->getData()),
            'MedianClass' => $this->getMedianClass(),
            'FirstQuartile' => $this->getFirstQuartile($this->getData()),
            'ThirdQuartile' => $this->getThirdQuartile($this->getData()),
            'InterQuartileRange' => $this->getInterQuartileRange($this->getData()),
            'QuartileDeviation' => $this->getQuartileDeviation($this->getData()),
            'Classes' => $this->getClasses(),
            'Frequencies' => $this->getFrequencies(),
            'Subtotals' => $this->getSubtotals(),
            'FrequencyTable' => $this->getTableData(),
        ];
    }

    /**
     * saves the frequency table into a file
     * @param   string  $path
     * @return  int|bool
     */
    public function save(string $path)
    {
        if (strlen($path) === 0) {
            return false;
        }
        $pathParts = pathinfo($path);
        $extension = strtolower($pathParts['extension']);
        if (!array_key_exists($extension, $this->supportedFormats)) {
            return false;
        }
        return $this->{$this->supportedFormats[$extension]}($path);
    }
}
