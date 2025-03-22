<?php

namespace Macocci7\PhpScatterplot;

use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Geometry\Factories\CircleFactory;
use Intervention\Image\Geometry\Factories\LineFactory;
use Intervention\Image\Geometry\Factories\RectangleFactory;
use Intervention\Image\Typography\FontFactory;
use Macocci7\PhpFrequencyTable\FrequencyTable;
use Macocci7\PhpScatterplot\Helpers\Config;
use Macocci7\PhpScatterplot\Analyzer;

/**
 * Class for plotting
 * @author  macocci7 <macocci7@yahoo.co.jp>
 * @license MIT
 */
class Plotter extends Analyzer
{
    use Traits\JudgeTrait;
    use Traits\AttributeTrait;
    use Traits\StyleCoreTrait;
    use Traits\StyleAppendixTrait;
    use Traits\VisibilityCoreTrait;
    use Traits\VisibilityAppendixTrait;

    protected string $imageDriver = 'imagick';
    protected ImageManager $imageManager;
    protected ImageInterface $image;
    /**
     * @var array<int|string, array<string, list<int|float>>>   $layers
     */
    protected array $layers;
    protected int|float $gridXMax;
    protected int|float $gridXMin;
    protected int|float $gridYMax;
    protected int|float $gridYMin;
    protected int|float $pixPitchX;
    protected int|float $pixPitchY;
    protected int|float $baseX;
    protected int|float $baseY;
    /**
     * @var string[]    $labels
     */
    protected array $labels;
    protected int $legendCount;

    /**
     * constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->loadConf();
        $this->imageManager = ImageManager::{$this->imageDriver}();
    }

    /**
     * loads config.
     * @return  void
     */
    private function loadConf()
    {
        Config::load();
        $props = [
            'imageDriver',
            'layers',
            'canvasWidth',
            'canvasHeight',
            'canvasBackgroundColor',
            'frameXRatio',
            'frameYRatio',
            'axisColor',
            'axisWidth',
            'gridColor',
            'gridWidth',
            'gridXPitch',
            'gridYPitch',
            'gridXMax',
            'gridXMin',
            'gridYMax',
            'gridYMin',
            'gridX',
            'gridY',
            'pixPitchX',
            'pixPitchY',
            'xLimitUpper',
            'xLimitLower',
            'yLimitUpper',
            'yLimitLower',
            'plotDiameter',
            'plotColor',
            'fontPath',
            'fontSize',
            'fontColor',
            'outlier',
            'outlierDiameter',
            'outlierColor',
            'mean',
            'meanColor',
            'referenceLineX',
            'referenceLineXValue',
            'referenceLineXWidth',
            'referenceLineXColor',
            'referenceLineY',
            'referenceLineYValue',
            'referenceLineYWidth',
            'referenceLineYColor',
            'specificationLimitX',
            'specificationLimitXLower',
            'specificationLimitXUpper',
            'specificationLimitXWidth',
            'specificationLimitXColor',
            'specificationLimitY',
            'specificationLimitYLower',
            'specificationLimitYUpper',
            'specificationLimitYWidth',
            'specificationLimitYColor',
            'regressionLine',
            'regressionLineWidth',
            'regressionLineColor',
            'labels',
            'labelX',
            'labelY',
            'caption',
            'legend',
            'legendCount',
            'legends',
            'legendWidth',
            'legendFontSize',
            'colors',
            'regressionLineColors',
        ];
        foreach (Config::get('props') as $prop => $value) {
            if (in_array($prop, $props, true)) {
                $this->{$prop} = $value;
            }
        }
    }

    /**
     * sets properties for preparation
     * @return self
     */
    private function setProperties()
    {
        $this->ft = new FrequencyTable();
        $this->legendCount = count($this->layers);
        $counts = [];
        foreach ($this->layers as $values) {
            $counts[] = count($values);
        }
        $this->baseX = (int) ($this->canvasWidth * (1 - $this->frameXRatio) * 3 / 4);
        $this->baseY = (int) ($this->canvasHeight * (1 + $this->frameYRatio) / 2);
        list($xMax, $yMax) = $this->layerMax($this->layers);
        list($xMin, $yMin) = $this->layerMin($this->layers);
        if (isset($this->xLimitUpper)) {
            $this->gridXMax = $this->xLimitUpper;
        } else {
            $this->gridXMax = (int) (($xMax + ($xMax - $xMin) * 0.1) * 10 ) / 10;
        }
        if (isset($this->xLimitLower)) {
            $this->gridXMin = $this->xLimitLower;
        } else {
            $this->gridXMin = (int) (($xMin - ($xMax - $xMin) * 0.1) * 10 ) / 10;
        }
        if (isset($this->yLimitUpper)) {
            $this->gridYMax = $this->yLimitUpper;
        } else {
            $this->gridYMax = (int) (($yMax + ($yMax - $yMin) * 0.1) * 10 ) / 10;
        }
        if (isset($this->yLimitLower)) {
            $this->gridYMin = $this->yLimitLower;
        } else {
            $this->gridYMin = (int) (($yMin - ($yMax - $yMin) * 0.1) * 10 ) / 10;
        }
        $gridXRange = $this->gridXMax - $this->gridXMin;
        $gridYRange = $this->gridYMax - $this->gridYMin;
        $this->pixPitchX = $this->canvasWidth * $this->frameXRatio / $gridXRange;
        $this->pixPitchY = $this->canvasHeight * $this->frameYRatio / $gridYRange;
        $this->ft->setClassRange(1);
        // Note:
        // - If $this->gridXPitch has a value, that value takes precedence.
        // - The value of $this->girdXPitch may be set by the method gridXPitch().
        if (!$this->gridXPitch) {
            $this->gridXPitch = 1;
            if ($this->gridXPitch < 0.125 * $gridXRange) {
                $this->gridXPitch = ( (int) (0.125 * $gridXRange * 10)) / 10;
            }
            if ($this->gridXPitch > 0.2 * $gridXRange) {
                $this->gridXPitch = ( (int) (0.200 * $gridXRange * 10)) / 10;
            }
        }
        if (!$this->gridYPitch) {
            $this->gridYPitch = 1;
            if ($this->gridYPitch < 0.125 * $gridYRange) {
                $this->gridYPitch = ( (int) (0.125 * $gridYRange * 10)) / 10;
            }
            if ($this->gridYPitch > 0.2 * $gridYRange) {
                $this->gridYPitch = ( (int) (0.200 * $gridYRange * 10)) / 10;
            }
        }
        // Creating an instance of intervention/image.
        $this->image = $this->imageManager->create($this->canvasWidth, $this->canvasHeight);
        if (self::isColorCode($this->canvasBackgroundColor)) {
            $this->image = $this->image->fill($this->canvasBackgroundColor);
        }
        // Note:
        // - If $this->labels has values, those values takes precedence.
        // - The values of $this->labels may be set by the method labels().
        if (empty($this->labels)) {
            $keys = array_keys($this->layers);
            $this->labels = array_keys($this->layers[$keys[0]]);
        }
        return $this;
    }

    /**
     * calculates the x-coordinate in pixels
     * @param   float   $x
     * @return  int
     */
    private function pX(float $x)
    {
        return (int) ($this->baseX + ($x - $this->gridXMin) * $this->pixPitchX);
    }

    /**
     * calculates the y-coordinate in pixels
     * @param   float   $y
     * @return  int
     */
    private function pY(float $y)
    {
        return (int) ($this->baseY - ($y - $this->gridYMin) * $this->pixPitchY);
    }

    /**
     * plots axis
     * @return  self
     */
    private function plotAxis()
    {
        // horizontal axis
        $x1 = (int) $this->pX($this->gridXMin);
        $y1 = (int) $this->pY($this->gridYMin);
        $x2 = (int) $this->pX($this->gridXMax);
        $y2 = (int) $y1;
        $this->image->drawLine(
            function (LineFactory $line) use ($x1, $y1, $x2, $y2) {
                $line->from($x1, $y1);
                $line->to($x2, $y2);
                $line->color($this->axisColor);
                $line->width($this->axisWidth);
            }
        );
        // vertical axis
        $x1 = (int) $this->pX($this->gridXMin);
        $y1 = (int) $this->pY($this->gridYMax);
        $x2 = (int) $x1;
        $y2 = (int) $this->pY($this->gridYMin);
        $this->image->drawLine(
            function (LineFactory $line) use ($x1, $y1, $x2, $y2) {
                $line->from($x1, $y1);
                $line->to($x2, $y2);
                $line->color($this->axisColor);
                $line->width($this->axisWidth);
            }
        );
        return $this;
    }

    /**
     * plots x-grids
     * @return  self
     */
    private function plotGridsX()
    {
        if (!$this->gridX) {
            return $this;
        }
        for ($i = $this->gridXMin; $i <= $this->gridXMax; $i += $this->gridXPitch) {
            $x1 = (int) $this->pX($i);
            $y1 = (int) $this->pY($this->gridYMax);
            $x2 = (int) $x1;
            $y2 = (int) $this->pY($this->gridYMin);
            $this->image->drawLine(
                function (LineFactory $line) use ($x1, $y1, $x2, $y2) {
                    $line->from($x1, $y1);
                    $line->to($x2, $y2);
                    $line->color($this->gridColor);
                    $line->width($this->gridWidth);
                }
            );
        }
        return $this;
    }

    /**
     * plots y-grids
     * @return  self
     */
    private function plotGridsY()
    {
        if (!$this->gridY) {
            return $this;
        }
        for ($i = $this->gridYMin; $i <= $this->gridYMax; $i += $this->gridYPitch) {
            $x1 = (int) $this->pX($this->gridXMin);
            $y1 = (int) $this->pY($i);
            $x2 = (int) $this->pX($this->gridXMax);
            $y2 = (int) $y1;
            $this->image->drawLine(
                function (LineFactory $line) use ($x1, $y1, $x2, $y2) {
                    $line->from($x1, $y1);
                    $line->to($x2, $y2);
                    $line->color($this->gridColor);
                    $line->width($this->gridWidth);
                }
            );
        }
        return $this;
    }

    /**
     * plots grid values of x
     * @return  self
     */
    private function plotGridValuesX()
    {
        for ($i = $this->gridXMin; $i <= $this->gridXMax; $i += $this->gridXPitch) {
            $x = $this->pX($i);
            $y = (int) ($this->baseY + $this->fontSize * 1.2);
            $this->image->text(
                (string) $i,
                $x,
                $y,
                function (FontFactory $font) {
                    $font->filename($this->fontPath);
                    $font->size($this->fontSize);
                    $font->color($this->fontColor);
                    $font->align('center');
                    $font->valign('bottom');
                }
            );
        }
        return $this;
    }

    /**
     * plots grid values of y
     * @return  self
     */
    private function plotGridValuesY()
    {
        for ($i = $this->gridYMin; $i <= $this->gridYMax; $i += $this->gridYPitch) {
            $x = (int) ($this->baseX - $this->fontSize * 0.4);
            $y = (int) ($this->pY($i) + $this->fontSize * 0.4);
            $this->image->text(
                (string) $i,
                $x,
                $y,
                function (FontFactory $font) {
                    $font->filename($this->fontPath);
                    $font->size($this->fontSize);
                    $font->color($this->fontColor);
                    $font->align('right');
                    $font->valign('bottom');
                }
            );
        }
        return $this;
    }

    /**
     * plots x-label
     * @return  self
     */
    private function plotLabelX()
    {
        $x = (int) ($this->canvasWidth / 2);
        $y = (int) ($this->baseY + (1 - $this->frameYRatio) * $this->canvasHeight / 3);
        $this->image->text(
            (string) $this->labelX,
            $x,
            $y,
            function (FontFactory $font) {
                $font->filename($this->fontPath);
                $font->size($this->fontSize);
                $font->color($this->fontColor);
                $font->align('center');
                $font->valign('bottom');
            }
        );
        return $this;
    }

    /**
     * plots y-label
     * @return  self
     */
    private function plotLabelY()
    {
        $width = $this->canvasHeight;
        $height = (int) ($this->canvasWidth * (1 - $this->frameXRatio) / 3);
        $image = $this->imageManager->create($width, $height);
        $x = (int) ($width / 2);
        $y = (int) (($height + $this->fontSize) / 2);
        $image->text(
            (string) $this->labelY,
            $x,
            $y,
            function (FontFactory $font) {
                $font->filename($this->fontPath);
                $font->size($this->fontSize);
                $font->color($this->fontColor);
                $font->align('center');
                $font->valign('bottom');
            }
        );
        $image->rotate(90);
        $this->image->place($image, 'left');
        return $this;
    }

    /**
     * plots caption
     * @return  self
     */
    private function plotCaption()
    {
        $x = (int) ($this->canvasWidth / 2);
        $y = (int) ($this->canvasHeight * (1 - $this->frameYRatio) / 3);
        $this->image->text(
            (string) $this->caption,
            $x,
            $y,
            function (FontFactory $font) {
                $font->filename($this->fontPath);
                $font->size($this->fontSize);
                $font->color($this->fontColor);
                $font->align('center');
                $font->valign('bottom');
            }
        );
        return $this;
    }

    /**
     * plots layers
     * @return  self
     */
    private function plotLayers()
    {
        if (!self::isValidLayers($this->layers)) {
            return $this;
        }
        $i = 0;
        foreach ($this->layers as $data) {
            $this->plotColor = $this->colors[$i];
            $this->regressionLineColor = $this->regressionLineColors[$i];
            $this->plotLayer($data);
            $this->plotRegressionLine($data);
            $i++;
        }
        return $this;
    }

    /**
     * plots layer
     * @param   array<string, array<int|float>> $data
     * @return  self
     */
    private function plotLayer(array $data)
    {
        $count = count($data['x']);
        for ($i = 0; $i < $count; $i++) {
            $this->plotXY($data['x'][$i], $data['y'][$i]);
        }
        return $this;
    }

    /**
     * plots a dot
     * @param   int|float   $x
     * @param   int|float   $y
     * @return  self
     */
    private function plotXY(int|float $x, int|float $y)
    {
        if (!self::isNumber($x) || !self::isNumber($y)) {
            return $this;
        }
        $px = $this->pX($x);
        $py = $this->pY($y);
        $this->image->drawCircle(
            $px,
            $py,
            function (CircleFactory $circle) {
                $circle->radius((int) ($this->plotDiameter / 2));
                $circle->background($this->plotColor);
            }
        );
        return $this;
    }

    /**
     * plots a regression line
     * @param   array<string, array<int|float>>   $layer
     * @return  self
     */
    private function plotRegressionLine(array $layer)
    {
        if (!$this->regressionLine) {
            return $this;
        }
        if (!self::isValidLayer($layer)) {
            return $this;
        }
        $formula = $this->regressionLineFormula($layer['x'], $layer['y']);
        $a = $formula['a'];
        $b = $formula['b'];
        $xMin = min($layer['x']);
        $xMax = max($layer['x']);
        $x1 = $this->pX($xMin);
        $y1 = $this->pY($a * $xMin + $b);
        $x2 = $this->pX($xMax);
        $y2 = $this->pY($a * $xMax + $b);
        $this->image->drawLine(
            function (LineFactory $line) use ($x1, $y1, $x2, $y2) {
                $line->from($x1, $y1);
                $line->to($x2, $y2);
                $line->width($this->regressionLineWidth);
                $line->color($this->regressionLineColor);
            }
        );
        return $this;
    }

    /**
     * plots a reference line of x
     * @return  self
     */
    private function plotReferenceLineX()
    {
        if (!$this->referenceLineX) {
            return $this;
        }
        $x1 = (int) $this->pX($this->referenceLineXValue);
        $y1 = (int) $this->pY($this->gridYMax);
        $x2 = (int) $x1;
        $y2 = (int) $this->pY($this->gridYMin);
        $this->image->drawLine(
            function (LineFactory $line) use ($x1, $y1, $x2, $y2) {
                $line->from($x1, $y1);
                $line->to($x2, $y2);
                $line->width($this->referenceLineXWidth);
                $line->color($this->referenceLineXColor);
            }
        );
        return $this;
    }

    /**
     * plots a reference line of Y
     * @return  self
     */
    private function plotReferenceLineY()
    {
        if (!$this->referenceLineY) {
            return $this;
        }
        $x1 = (int) $this->pX($this->gridXMin);
        $y1 = (int) $this->pY($this->referenceLineYValue);
        $x2 = (int) $this->pX($this->gridXMax);
        $y2 = (int) $y1;
        $this->image->drawLine(
            function (LineFactory $line) use ($x1, $y1, $x2, $y2) {
                $line->from($x1, $y1);
                $line->to($x2, $y2);
                $line->width($this->referenceLineYWidth);
                $line->color($this->referenceLineYColor);
            }
        );
        return $this;
    }

    /**
     * plots specification limit lines of X
     * @return  self
     */
    private function plotSpecificationLimitX()
    {
        if (!$this->specificationLimitX) {
            return $this;
        }
        // lower limit
        $x1 = (int) $this->pX($this->specificationLimitXLower);
        $y1 = (int) $this->pY($this->gridYMax);
        $x2 = (int) $x1;
        $y2 = (int) $this->pY($this->gridYMin);
        $this->image->drawLine(
            function (LineFactory $line) use ($x1, $y1, $x2, $y2) {
                $line->from($x1, $y1);
                $line->to($x2, $y2);
                $line->width($this->specificationLimitXWidth);
                $line->color($this->specificationLimitXColor);
            }
        );
        // upper limit
        $x1 = (int) $this->pX($this->specificationLimitXUpper);
        $x2 = (int) $x1;
        $this->image->drawLine(
            function (LineFactory $line) use ($x1, $y1, $x2, $y2) {
                $line->from($x1, $y1);
                $line->to($x2, $y2);
                $line->width($this->specificationLimitXWidth);
                $line->color($this->specificationLimitXColor);
            }
        );
        return $this;
    }

    /**
     * plots specification limit lines of y
     * @return  self
     */
    private function plotSpecificationLimitY()
    {
        if (!$this->specificationLimitY) {
            return $this;
        }
        // lower limit
        $x1 = (int) $this->pX($this->gridXMin);
        $y1 = (int) $this->pY($this->specificationLimitYLower);
        $x2 = (int) $this->pX($this->gridXMax);
        $y2 = (int) $y1;
        $this->image->drawLine(
            function (LineFactory $line) use ($x1, $y1, $x2, $y2) {
                $line->from($x1, $y1);
                $line->to($x2, $y2);
                $line->width($this->specificationLimitYWidth);
                $line->color($this->specificationLimitYColor);
            }
        );
        // upper limit
        $y1 = (int) $this->pY($this->specificationLimitYUpper);
        $y2 = (int) $y1;
        $this->image->drawLine(
            function (LineFactory $line) use ($x1, $y1, $x2, $y2) {
                $line->from($x1, $y1);
                $line->to($x2, $y2);
                $line->width($this->specificationLimitYWidth);
                $line->color($this->specificationLimitYColor);
            }
        );
        return $this;
    }

    /**
     * plots legends
     * @return  self
     */
    private function plotLegend()
    {
        if (!$this->legend) {
            return $this;
        }
        $baseX = (int) ($this->canvasWidth * (3 + $this->frameXRatio) / 4 - $this->legendWidth);
        $baseY = 10;
        $x1 = $baseX;
        $y1 = $baseY;
        $x2 = $x1 + $this->legendWidth;
        $y2 = (int) ($y1 + $this->legendFontSize * 1.2 * $this->legendCount + 8);
        $this->image->drawRectangle(
            $x1,
            $y1,
            function (RectangleFactory $rectangle) use ($x1, $y1, $x2, $y2) {
                $rectangle->size($x2 - $x1, $y2 - $y1);
                $rectangle->background($this->canvasBackgroundColor);
                $rectangle->border($this->axisColor, $this->axisWidth);
            }
        );
        for ($i = 0; $i < $this->legendCount; $i++) {
            if (empty($this->legends[$i])) {
                $label = 'unknown ' . $i;
            } else {
                $label = $this->legends[$i];
            }
            $x1 = (int) ($baseX + 4);
            $y1 = (int) ($baseY + $i * $this->legendFontSize * 1.2 + 4);
            $x2 = (int) ($x1 + 20);
            $y2 = (int) ($y1 + $this->legendFontSize);
            $this->image->drawRectangle(
                $x1,
                $y1,
                function (RectangleFactory $rectangle) use ($i, $x1, $y1, $x2, $y2) {
                    $rectangle->size($x2 - $x1, $y2 - $y1);
                    $rectangle->background($this->colors[$i]);
                    $rectangle->border($this->axisColor, 1);
                }
            );
            $x = $x2 + 4;
            $y = $y1;
            $this->image->text(
                (string) $label,
                $x,
                $y,
                function (FontFactory $font) {
                    $font->filename($this->fontPath);
                    $font->size($this->legendFontSize);
                    $font->color($this->fontColor);
                    $font->align('left');
                    $font->valign('top');
                }
            );
        }
        return $this;
    }

    /**
     * creates a scatter plot image and save it
     * @param   string  $filePath
     * @return  self
     * @thrown  \Exception
     */
    public function create(string $filePath)
    {
        if (strlen($filePath) == 0) {
            throw new \Exception("Empty string specified for file path.");
        }
        $this->setProperties();
        $this->plotLabelX();
        $this->plotLabelY();
        $this->plotCaption();
        $this->plotGridsX();
        $this->plotGridsY();
        $this->plotGridValuesX();
        $this->plotGridValuesY();
        $this->plotLayers();
        $this->plotAxis();
        $this->plotReferenceLineX();
        $this->plotReferenceLineY();
        $this->plotSpecificationLimitX();
        $this->plotSpecificationLimitY();
        $this->plotLegend();
        $this->image->save($filePath);
        return $this;
    }
}
