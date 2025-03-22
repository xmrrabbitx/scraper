<?php

namespace Macocci7\PhpScatterplot\Traits;

trait StyleCoreTrait
{
    protected string|null $canvasBackgroundColor = '#ffffff';
    protected string|null $axisColor = '#666666';
    protected int $axisWidth = 1;
    protected string|null $gridColor = '#dddddd';
    protected int $gridWidth = 1;
    protected int|float|null $gridXPitch;
    protected int|float|null $gridYPitch;
    /**
     * @var string[]    $colors
     */
    protected array $colors = [
        '#3333cc',
        '#cc3333',
        '#339933',
        '#33cccc',
        '#cc3333',
        '#ffcc33',
        '#cccc33',
        '#cc33cc',
    ];
    protected int $plotDiameter = 2;
    protected string|null $plotColor = '#000000';
    protected string $fontPath = 'fonts/ipaexg.ttf'; // IPA ex Gothic 00401
    //protected string  $fontPath = 'fonts/ipaexm.ttf'; // IPA ex Mincho 00401
    protected int|float $fontSize = 16;
    protected string|null $fontColor = '#333333';

    /**
     * sets the background color of the canvas
     * @param   string|null $color = null (null results in transparent)
     * @return  self
     */
    public function bgcolor(string|null $color = null)
    {
        if (!self::isColorCode($color) && !is_null($color)) {
            throw new \Exception("param must be null or color code (in '#rgb' or '#rrggbb' format).");
        }
        $this->canvasBackgroundColor = $color;
        return $this;
    }

    /**
     * sets the width and color of the axis
     * @param   int         $width
     * @param   string|null $color
     * @return  self
     * @thrown  \Exception
     */
    public function axis(int $width, string|null $color = null)
    {
        if ($width < 1) {
            throw new \Exception("width must be positive integer.");
        }
        if (!is_null($color) && !self::isColorCode($color)) {
            throw new \Exception("color code must be null or string (in '#rgb' or '#rrggbb' format).");
        }
        $this->axisWidth = $width;
        if (null !== $color) {
            $this->axisColor = $color;
        }
        return $this;
    }

    /**
     * sets the width and color of the grids
     * @param   int         $width
     * @param   string|null $color
     * @return  self
     * @thrown  \Exception
     */
    public function grid(int $width, string|null $color = null)
    {
        if ($width < 1) {
            throw new \Exception("width must be positive integer.");
        }
        if (!is_null($color) && !self::isColorCode($color)) {
            throw new \Exception("color code must be null or string (in '#rgb' or '#rrggbb' format).");
        }
        $this->gridWidth = $width;
        if (null !== $color) {
            $this->gridColor = $color;
        }
        return $this;
    }

    /**
     * sets the grid pitch of x
     * @param   int|float   $pitch
     * @return  self
     */
    public function gridXPitch(int|float $pitch)
    {
        if ($pitch <= 0) {
            throw new \Exception("specify positive integer.");
        }
        $this->gridXPitch = $pitch;
        return $this;
    }

    /**
     * sets the grid pitch of y
     * @param   int|float   $pitch
     * @return  self
     */
    public function gridYPitch(int|float $pitch)
    {
        if ($pitch <= 0) {
            throw new \Exception("specify positive integer.");
        }
        $this->gridYPitch = $pitch;
        return $this;
    }

    /**
     * sets the color of dots
     * @param   string[]    $colors
     * @return  self
     * @thrown  \Exception
     */
    public function colors(array $colors)
    {
        if (!self::isColorCodesAll($colors)) {
            throw new \Exception("color codes must be in '#rgb' or '#rrggbb' format.");
        }
        foreach (
            array_slice(array_values($colors), 0, LIMIT_LAYERS) as $i => $color
        ) {
            $this->colors[$i] = $color;
        }
        return $this;
    }

    /**
     * sets the size (diameter) of dots in pixels
     * @param   int     $size
     * @return  self
     * @thrown  \Exception
     */
    public function plotSize(int $size)
    {
        if ($size < 1) {
            throw new \Exception("size must be positive integer.");
        }
        $this->plotDiameter = $size;
        return $this;
    }

    /**
     * sets the font path
     * @param   string  $path
     * @return  self
     * @thrown  \Exception
     */
    public function fontPath(string $path)
    {
        if (!file_exists($path)) {
            throw new \Exception("File does not exists.");
        }
        if (!is_readable($path)) {
            throw new \Exception("Cannot read the file.");
        }
        $pathinfo = pathinfo($path);
        if (0 !== strcmp("ttf", strtolower($pathinfo['extension']))) {
            throw new \Exception("Specify .ttf file.");
        }
        $this->fontPath = $path;
        return $this;
    }

    /**
     * sets the font size
     * @param   int|float   $size
     * @return  self
     * @thrown  \Exception
     */
    public function fontSize(int|float $size)
    {
        if ($size < 6) {
            throw new \Exception("Size must be 6 or above.");
        }
        $this->fontSize = $size;
        return $this;
    }

    /**
     * sets the font color
     * @param   string  $color
     * @return  self
     * @thrown  \Exception
     */
    public function fontColor(string $color)
    {
        if (!self::isColorCode($color)) {
            throw new \Exception("Color code must be in '#rgb' or '#rrggbb' format.");
        }
        $this->fontColor = $color;
        return $this;
    }
}
