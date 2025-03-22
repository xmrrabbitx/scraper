<?php

namespace Macocci7\PhpScatterplot\Traits;

trait JudgeTrait
{
    /**
     * judges if all items are integer or not
     * @param   array<mixed>    $items
     * @return  bool
     */
    public static function isIntAll(array $items): bool
    {
        if (empty($items)) {
            return false;
        }
        foreach ($items as $item) {
            if (!is_int($item)) {
                return false;
            }
        }
        return true;
    }

    /**
     * judges if the param is number
     * @param   mixed   $item
     * @return  bool
     */
    public static function isNumber(mixed $item): bool
    {
        return is_int($item) || is_float($item);
    }

    /**
     * judges if all items are number or not
     * @param   mixed   $items
     * @return  bool
     */
    public static function isNumbersAll(mixed $items): bool
    {
        if (!is_array($items)) {
            return false;
        }
        if (empty($items)) {
            return false;
        }
        foreach ($items as $item) {
            if (!self::isNumber($item)) {
                return false;
            }
        }
        return true;
    }

    /**
     * judges if all items are string or not
     * @param   mixed   $items
     * @return  bool
     */
    public static function isStringsAll(mixed $items): bool
    {
        if (!is_array($items)) {
            return false;
        }
        if (empty($items)) {
            return false;
        }
        foreach ($items as $item) {
            if (!is_string($item)) {
                return false;
            }
        }
        return true;
    }

    /**
     * judges if the param is in '#rrggbb' format or not
     * @param   mixed  $item
     * @return  bool
     */
    public static function isColorCode(mixed $item): bool
    {
        if (!is_string($item)) {
            return false;
        }
        return preg_match('/^#[A-Fa-f0-9]{3}$|^#[A-Fa-f0-9]{6}$/', $item) ? true : false;
    }

    /**
     * judges if all of params are colorcode or not
     * @param   mixed   $colors
     * @return  bool
     */
    public static function isColorCodesAll(mixed $colors): bool
    {
        if (!is_array($colors)) {
            return false;
        }
        if (empty($colors)) {
            return false;
        }
        foreach ($colors as $color) {
            if (!self::isColorCode($color)) {
                return false;
            }
        }
        return true;
    }

    /**
     * judges if type of $input is valid or not
     * @param   mixed   $input
     * @param   string  $defs
     * @return  bool
     */
    public static function isValidType(mixed $input, string $defs): bool
    {
        $r = false;
        foreach (explode('|', $defs) as $def) {
            $r = $r || match ($def) {
                'int' => is_int($input),
                'float' => is_float($input),
                'string' => is_string($input),
                'bool' => is_bool($input),
                'array' => is_array($input),
                'null' => is_null($input),
                'number' => self::isNumber($input),
                'colorCode' => self::isColorCode($input),
                'colorCodes' => self::isColorCodesAll($input),
                default => false,
            };
        }
        return $r;
    }

    /**
     * judgees whether $data is valid or not for analysis
     * @param   mixed   $data
     * @return  bool
     */
    public static function isValidData(mixed $data): bool
    {
        if (!is_array($data)) {
            return false;
        }
        if (empty($data)) {
            return false;
        }
        if (!self::isNumbersAll($data)) {
            return false;
        }
        return true;
    }

    /**
     * judges whether $layer is valid or not
     * @param   array<string, list<int|float>>  $layer
     * @return  bool
     */
    public static function isValidLayer(array $layer): bool
    {
        if (empty($layer)) {
            return false;
        }
        if (!array_key_exists('x', $layer)) {
            return false;
        }
        if (!array_key_exists('y', $layer)) {
            return false;
        }
        if (!self::isNumbersAll($layer['x'])) {
            return false;
        }
        if (!self::isNumbersAll($layer['y'])) {
            return false;
        }
        if (count($layer['x']) <> count($layer['y'])) {
            return false;
        }
        return true;
    }

    /**
     * judges whether $layers is valid or not
     * @param   array<int|string, array<string, list<int|float>>>   $layers
     * @return  bool
     */
    public static function isValidLayers(array $layers): bool
    {
        if (count($layers) > LIMIT_LAYERS) {
            echo "too many layers. there's more than " . LIMIT_LAYERS . " layers.\n";
            return false;
        }
        foreach ($layers as $layer) {
            if (!self::isValidLayer($layer)) {
                return false;
            }
        }
        return true;
    }
}
