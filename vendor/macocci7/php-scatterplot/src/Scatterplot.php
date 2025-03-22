<?php

namespace Macocci7\PhpScatterplot;

use Macocci7\PhpScatterplot\Helpers\Config;
use Macocci7\PhpScatterplot\Plotter;

/**
 * Class for management of scatter plot
 * @author  macocci7 <macocci7@yahoo.co.jp>
 * @license MIT
 */
class Scatterplot extends Plotter
{
    /**
     * constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->loadConf();
    }

    /**
     * loads config.
     * @return  void
     */
    private function loadConf()
    {
        Config::load();
        $props = [
            'CANVAS_WIDTH_LIMIT_LOWER',
            'CANVAS_HEIGHT_LIMIT_LOWER',
        ];
        foreach (Config::get('props') as $prop => $value) {
            if (in_array($prop, $props, true)) {
                $this->{$prop} = $value;
            }
        }
    }

    /**
     * set config from specified resource
     * @param   string|mixed[]  $configResource
     * @return  self
     */
    public function config(string|array $configResource)
    {
        foreach (Config::filter($configResource) as $key => $value) {
            $this->{$key} = $value;
            if (strcmp('dataSet', $key) === 0 && empty($this->legends)) {
                $this->legends = array_keys($value);
            }
        }
        return $this;
    }

    /**
     * sets a layer
     * @param   array<string, array<int|float>> $layer
     * @return  self
     */
    public function layer(array $layer)
    {
        if (self::isValidLayer($layer)) {
            $this->layers[] = $layer;
        }
        return $this;
    }

    /**
     * sets layers
     * @param   array<int|string, array<string, list<int|float>>>   $layers
     * @return  self
     */
    public function layers(array $layers)
    {
        if (!self::isValidLayers($layers)) {
            throw new \Exception(
                "Invalid layers specified. "
                . "array<int|string, array<string, list<int|float>>> expected."
            );
        }
        $this->layers = $layers;
        return $this;
    }

    /**
     * returns the config values
     * @param   string|null $key = null
     * @return  mixed
     */
    public function getConfig(string|null $key = null)
    {
        if (is_null($key)) {
            $config = [];
            foreach (array_keys(Config::get('validConfig')) as $key) {
                $config[$key] = $this->{$key};
            }
            return $config;
        }
        if (in_array($key, array_keys(Config::get('validConfig')))) {
            return $this->{$key};
        }
        return null;
    }
}
