<?php

namespace Macocci7\PhpFrequencyTable\Traits;

trait AttributesTrait
{
    /**
     * @var string  $lang
     */
    private string $lang;

    /**
     * @var array<string, array<string, string>>
     */
    private array $supportedLangs;

    /**
     * @var bool    $showMean
     */
    private bool $showMean = false;

    /**
     * returns supported langs
     * @return  string[]
     */
    public function langs()
    {
        return array_keys($this->supportedLangs);
    }

    /**
     * sets lang or returns current lang
     * @param   string|null $lang = null
     * @return  self|string
     */
    public function lang(string|null $lang = null)
    {
        if (is_null($lang)) {
            return $this->lang;
        }
        if (isset($this->supportedLangs[$lang])) {
            $this->lang = $lang;
        }
        return $this;
    }

    /**
     * sets visibility of mean on
     * @return  self
     */
    public function meanOn()
    {
        $this->showMean = true;
        return $this;
    }

    /**
     * sets visibility of mean off
     * @return  self
     */
    public function meanOff()
    {
        $this->showMean = false;
        return $this;
    }
}
