<?php

use sqonk\phext\plotlib\BulkPlot;

function multiLines(bool $writeToFile = false): string
{
    $plot = new BulkPlot;
    $plot->output_path('./');
    
    $l1 = [19,3,2,7,9,18,4,15,12,13];
    $l2 = [17,20,19,17,19,5,13,8,15,8];
     
    $plot->add('line', [$l1, $l2], [
        'title' => 'lines',
        'xseries' => range(1, 10),
        'font' => FF_FONT1,
        'xformatter' => function ($v) {
            return "Pt $v";
        }
    ]);
    
    [$img] = $plot->render(700, 500, false); 
    
    if ($writeToFile)
        file_put_contents('lines.png', $img);
    
    return $img;
}

function lineFills(bool $writeToFile = false): string
{
    $plot = new BulkPlot;
    $plot->output_path('./');
    
    $l1 = [19,3,2,7,9,18,4,15,12,13];
    $l2 = [17,20,19,17,19,5,13,8,15,8];
     
    $plot->add('linefill', [$l2, $l1], [
        'title' => 'line fill',
        'font' => FF_FONT1,
        'xseries' => range(1, 10),
        'xformatter' => function ($v) {
            return "Pt $v";
        },
        'labelangle' => 90,
    ]);
    
    [$img] = $plot->render(700, 500, false);
    
    if ($writeToFile)
         file_put_contents('line_fills.png', $img);
    
    return $img;
}

function basicScatter(bool $writeToFile = false): string
{
    $plot = new BulkPlot;
    $plot->output_path('./');
    
    $l1 = [19,3,2,7,9,18,4,15,12,13];
    $l2 = [17,20,19,17,19,5,13,8,15,8];
    
    $plot->add('scatter', [$l1, $l2], [
        'title' => 'scatter basic',
        'font' => FF_FONT1
    ]);
    
    [$img] = $plot->render(700, 500, false);
    
    if ($writeToFile)
         file_put_contents('scatter.png', $img);
    
    return $img; 
}

function squareScatter(bool $writeToFile = false): string
{
    $plot = new BulkPlot;
    $plot->output_path('./');
    
    $l1 = [19,3,2,7,9,18,4,15,12,13];
    $l2 = [17,20,19,17,19,5,13,8,15,8];
    
    $plot->add('scatter', [$l1, $l2], [
        'scattershape' => MARK_SQUARE,
        'title' => 'square scatter',
        'font' => FF_FONT1
    ]);
    
    [$img] = $plot->render(700, 500, false);
    
    if ($writeToFile)
         file_put_contents('square_scatter.png', $img);
    
    return $img; 
}

function scatterLine(bool $writeToFile = false): string
{
    $plot = new BulkPlot;
    $plot->output_path('./');
    
    $l1 = [19,3,2,7,9,18,4,15,12,13];
    $l2 = [17,20,19,17,19,5,13,8,15,8];
    
    $plot->add('scatter', [$l1, $l2], [
        'scatterline' => true,
        'title' => 'scatter with lines',
        'font' => FF_FONT1
    ]);
    
    [$img] = $plot->render(700, 500, false);
    
    if ($writeToFile)
         file_put_contents('scatter_lines.png', $img);
    
    return $img; 
}

function scatterImpulse(bool $writeToFile = false): string
{
    $plot = new BulkPlot;
    $plot->output_path('./');
    
    $l1 = [19,3,2,7,9,18,4,15,12,13];
    
    $plot->add('scatter', [$l1], [
        'scatterimpulse' => true,
        'scattershape' => MARK_SQUARE,
        'title' => 'impulses scatter',
        'font' => FF_FONT1
    ]);
    
    [$img] = $plot->render(700, 500, false);
    
    if ($writeToFile)
         file_put_contents('scatter_impulse.png', $img);
    
    return $img; 
}

function bars(bool $writeToFile = false): string
{
    $plot = new BulkPlot;
    $plot->output_path('./');
    
    $l1 = [19,3,2,7,9,18,4,15,12,13];
    $l2 = [17,20,19,17,19,5,13,8,15,8];
    
    $plot->add('bar', [$l1, $l2], [
        'title' => 'bars',
        'font' => FF_FONT1
    ]);
    
    [$img] = $plot->render(700, 500, false);
    
    if ($writeToFile)
         file_put_contents('bars.png', $img);
    
    return $img; 
}

function stackedBarsAndAuxlines(bool $writeToFile = false): string
{
    $plot = new BulkPlot;
    $plot->output_path('./');
    
    $l1 = [19,3,2,7,9,18,4,15,12,13];
    $l2 = [17,20,19,17,19,5,13,8,15,8];
    $l3 = [2,5,13,12,17,16,7,4,17,17];
    
    $plot->add('barstacked', [$l1, $l2], [
        'title' => 'stacked bars',
        'font' => FF_FONT1,
        'auxlines' => [
            ['values' => $l3, 'legend' => 'auxlines']
        ]
    ]);
    
    [$img] = $plot->render(700, 500, false);
    
    if ($writeToFile)
         file_put_contents('stack_bars.png', $img);
    
    return $img; 
}

function backgroundBars(bool $writeToFile = false): string
{
    $plot = new BulkPlot;
    $plot->output_path('./');
    
    $l1 = [19,3,2,7,9,18,4,15,12,13];
    $l2 = [17,20,19,17,19,5,13,8,15,8];
    
    $plot->add('line', [$l1], [
        'title' => 'background bars',
        'font' => FF_FONT1,
        'xseries' => range(1, 10),
        'xformatter' => function($v) {
            return "Pt $v";
        },
        'yformatter' => function($v) {
            return "Vl $v";
        },
        'bars' => $l2,
        'barWidth' => 10,
        'barColor' => 'gray',
    ]);
    
    [$img] = $plot->render(700, 500, false);
    
    if ($writeToFile)
         file_put_contents('auxbars.png', $img);
    
    return $img;
}

function infiniteLines(bool $writeToFile = false): string
{
    $plot = new BulkPlot;
    $plot->output_path('./');
    
    $l1 = [19,3,2,7,9,18,4,15,12,13];
    $l2 = [17,20,19,17,19,5,13,8,15,8];
    
    $plot->add('line', [$l1, $l2], [
        'title' => 'infinite lines',
        'font' => FF_FONT1,
        'xseries' => range(1, 10),
        'lines' => [
            ['direction' => 'h', 'value' => 7, 'color' => 'red'],
            ['direction' => 'v', 'value' => 4, 'color' => 'blue']
        ]
    ]);
    
    [$img] = $plot->render(700, 500, false);
    
    if ($writeToFile)
         file_put_contents('Infinite_Lines.png', $img);
    
    return $img;
}

function regions(bool $writeToFile = false): string
{
    $plot = new BulkPlot;
    $plot->output_path('./');
    
    $l1 = [19,3,2,7,9,18,4,15,12,13];
    $l2 = [17,20,19,17,19,5,13,8,15,8];
    
    $plot->add('line', [$l1], [
        'title' => 'regions',
        'font' => FF_FONT1,
        'xseries' => range(1, 10),
        'regions' => [
            ['x' => 3, 'y' => 20, 'x2' => 7, 'y2' => 15, 'color' => 'red@0.3'],
            ['x' => 6, 'y' => 2, 'x2' => 10, 'y2' => 0, 'color' => 'red@0.3']
        ]
    ]);
    
    [$img] = $plot->render(700, 500, false);
    
    if ($writeToFile)
         file_put_contents('regions.png', $img);
    
    return $img;
}

function stockplot(bool $writeToFile = false): string
{
    $plot = new BulkPlot;
    $plot->output_path('./');
    
    $l1 = [3,6,2,6, 6,4,3,6, 4,6,1,8, 3,2,1,7, 2,6,2,9];
    $plot->add('stock', [$l1], [
        'title' => 'stock plot',
        'font' => FF_FONT1,
        'xseries' => range(1, 5),
        'margin' => [55,55,55,55],
        'configCallback' => function($chart) {
            $chart->yscale->ticks->SupressZeroLabel(false);
            $chart->xscale->ticks->SupressZeroLabel(false);
            $chart->SetClipping(false);
        }
    ]);
    
    [$img] = $plot->render(700, 500, false);
    
    if ($writeToFile)
         file_put_contents('candlesticks.png', $img);
    
    return $img;
}

function boxplot(bool $writeToFile = false): string
{
    $plot = new BulkPlot;
    $plot->output_path('./');
    
    $l1 = [3,6,2,6,4, 6,4,3,6,4, 4,6,1,8,5, 3,2,1,7,4, 2,6,2,9,4];
    $plot->add('box', [$l1], [
        'title' => 'box plot',
        'font' => FF_FONT1,
        'xseries' => range(1, 5),
        'margin' => [55,55,55,55],
        'configCallback' => function($chart) {
            $chart->yscale->ticks->SupressZeroLabel(false);
            $chart->xscale->ticks->SupressZeroLabel(false);
            $chart->SetClipping(false);
        }
    ]);
    
    [$img] = $plot->render(700, 500, false);
    
    if ($writeToFile)
         file_put_contents('box.png', $img);
    
    return $img;
}
