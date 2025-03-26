<?php

namespace Scraper\Trader\scripts;

use Macocci7\PhpScatterplot\Scatterplot;

class plotAnalyser
{
    protected $plot;

    public function __construct()
    {
        $this->plot = new Scatterplot();

    }
    public function medianScatter(array $medArray, array $medArray2)
    {
        $layers = [
            [
                'x' => $medArray,
                'y' => $medArray,
            ],
            [
                'x' => $medArray2,
                'y' => $medArray2,
            ],
        ];

        $png = $this->plot->layers($layers)
            ->create('./BasicUsage.png');

    }
}