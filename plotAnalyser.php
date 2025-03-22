<?php

include "./vendor/autoload.php";

use Macocci7\PhpScatterplot\Scatterplot;

class plotAnalyser
{
    protected $plot;

    public function __construct()
    {
        $this->plot = new Scatterplot();

    }
    public function medianScatter()
    {
        $layers = [
            [
                'x' => [ 10000, 100000, 200000, 300000, 1000000 ],
                'y' => [ 10000, 100000, 200000, 300000, 1000000 ],
            ],
        ];

        $png = $this->plot->layers($layers)
            ->create('./BasicUsage.png');

    }
}

$plot = new plotAnalyser();
$plot->medianScatter();