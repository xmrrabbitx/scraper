<?php

namespace Macocci7\PhpFrequencyTable\Traits;

trait DataFormatsTrait
{
    /**
     * saves or returns the frequency table in xsv format
     * @param   string|null $path
     * @param   string      $separator
     * @param   string      $quotation = '"'
     * @param   string      $eol = "\n"
     * @return  null|string|int|bool
     */
    public function xsv(
        string|null $path,
        string $separator,
        string $quotation = '"',
        string $eol = "\n"
    ) {
        if (empty($separator)) {
            return null;
        }
        $qm = $quotation;
        $splitter = $qm . $separator . $qm;
        $buffer = null;
        $buffer .= $qm . implode($splitter, $this->getTableHead()) . $qm . $eol;
        $data4EachClass = $this->filterData2Show($this->getDataOfEachClass());
        foreach ($this->formatData2Show($data4EachClass) as $data) { // @phpstan-ignore-line
            $buffer .= $qm . implode($splitter, $data) . $qm . $eol;
        }
        $totals = $this->formatData2Show(
            $this->filterData2Show( // @phpstan-ignore-line
                [$this->getTableTotal2Show($data4EachClass)]
            )
        );
        $buffer .= $qm . implode($splitter, $totals[0]) . $qm . $eol;
        if ($this->showMean) {
            $means = $this->formatData2Show(
                $this->filterData2Show([$this->getMean2Show()]) // @phpstan-ignore-line
            );
            $buffer .= $qm . implode($splitter, $means[0]) . $qm . $eol;
        }
        return empty($path) ? $buffer : file_put_contents($path, $buffer);
    }

    /**
     * saves or returns the frequency table in csv format
     * @param   string|null     $path = null
     * @param   string          $quotation = '"'
     * @param   string          $eol = "\n"
     * @return  null|string|int|bool
     */
    public function csv(
        string|null $path = null,
        string $quotation = '"',
        string $eol = "\n"
    ) {
        return $this->xsv($path, ',', $quotation, $eol);
    }

    /**
     * saves or returns the frequency table in tsv format
     * @param   string|null     $path = null
     * @param   string          $quotation = '"'
     * @param   string          $eol = "\n"
     * @return  null|string|int|bool
     */
    public function tsv(
        string|null $path = null,
        string $quotation = '"',
        string $eol = "\n"
    ) {
        return $this->xsv($path, "\t", $quotation, $eol);
    }

    /**
     * saves or returns the frequency table in html format
     * @param   string|null   $path = null
     * @return  null|string|int|bool
     */
    public function html(string|null $path = null)
    {
        $eol = "\n";
        $data4EachClass = $this->filterData2Show($this->getDataOfEachClass());
        if (empty($data4EachClass)) {
            return empty($path) ? 'no data' : file_put_contents($path, 'no data');
        }
        $buffer = "<table>" . $eol;
        $pre = '<tr><th>';
        $pro = '</th></tr>';
        $splitter = '</th><th>';
        $buffer .= $pre . implode($splitter, $this->getTableHead()) . $pro . $eol;
        $pre = '<tr><td>';
        $pro = '</td></tr>';
        $splitter = '</td><td>';
        foreach ($this->formatData2Show($data4EachClass) as $data) { // @phpstan-ignore-line
            $buffer .= $pre . implode($splitter, $data) . $pro . $eol;
        }
        $totals = $this->formatData2Show(
            $this->filterData2Show( // @phpstan-ignore-line
                [$this->getTableTotal2Show($data4EachClass)]
            )
        );
        $buffer .= $pre . implode($splitter, $totals[0]) . $pro . $eol;
        if ($this->showMean) {
            $means = $this->formatData2Show(
                $this->filterData2Show([$this->getMean2Show()]) // @phpstan-ignore-line
            );
            $buffer .= $pre . implode($splitter, $means[0]) . $pro . $eol;
        }
        $buffer .= "</table>" . $eol;
        return empty($path) ? $buffer : file_put_contents($path, $buffer);
    }

    /**
     * saves or returns the frequency table in markdown format
     * @param   string|null   $path = null
     * @return  null|string|int|bool
     */
    public function markdown(string|null $path = null)
    {
        $separator = $this->getTableSeparator();
        $eol = "\n";
        $buffer = null;
        // @phpstan-ignore-next-line
        $buffer .= $separator . implode($separator, $this->getTableHead()) . $separator . $eol;
        // @phpstan-ignore-next-line
        $buffer .= $separator . implode($separator, $this->getTableColumnAligns2Show()) . $separator . $eol;
        $data4EachClass = $this->filterData2Show($this->getDataOfEachClass());
        if (empty($data4EachClass)) {
            return empty($path) ? 'no data' : file_put_contents($path, 'no data');
        }
        foreach ($this->formatData2Show($data4EachClass) as $data) { // @phpstan-ignore-line
            // @phpstan-ignore-next-line
            $buffer .= $separator . implode($separator, $data) . $separator . $eol;
        }
        $totals = $this->formatData2Show(
            $this->filterData2Show( // @phpstan-ignore-line
                [$this->getTableTotal2Show($data4EachClass)]
            )
        );
        // @phpstan-ignore-next-line
        $buffer .= $separator . implode($separator, $totals[0]) . $separator . $eol;
        if ($this->showMean) {
            $means = $this->formatData2Show(
                $this->filterData2Show([$this->getMean2Show()]) // @phpstan-ignore-line
            );
            // @phpstan-ignore-next-line
            $buffer .= $separator . implode($separator, $means[0]) . $separator . $eol;
        }
        return empty($path) ? $buffer : file_put_contents($path, $buffer);
    }
}
