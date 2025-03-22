<?php

namespace Macocci7\PhpScatterplot\Helpers;

use Macocci7\PhpScatterplot\Traits\JudgeTrait;
use Nette\Neon\Neon;

/**
 * Config operator.
 * @author  macocci7 <macocci7@yahoo.co.jp>
 * @license MIT
 */
class Config
{
    use JudgeTrait;

    /**
     * @var mixed[] $conf
     */
    private static mixed $conf = [];

    /**
     * loads config from a file
     * @return  void
     */
    public static function load()
    {
        $class = self::class();
        $cl = self::className($class);
        $path = __DIR__ . '/../../conf/' . $cl . '.neon';
        self::$conf[$class] = Neon::decodeFile($path);
    }

    /**
     * returns the fully qualified class name of the caller
     * @return  string
     */
    public static function class()
    {
        return debug_backtrace()[2]['class'];
    }

    /**
     * returns just the class name splitted parent namespace
     * @param   string  $class
     * @return  string
     */
    public static function className(string $class)
    {
        $pos = strrpos($class, '\\');
        if ($pos) {
            return substr($class, $pos + 1);
        }
        return $class;
    }

    /**
     * returns config data
     * @param   string  $key = null
     * @return  mixed
     */
    public static function get(?string $key = null)
    {
        // get fully qualified class name of the caller
        $class = self::class();
        if (!self::$conf[$class]) {
            return null;
        }
        if (is_null($key)) {
            return self::$conf[$class];
        }
        $keys = explode('.', $key);
        $conf = self::$conf[$class];
        foreach ($keys as $k) {
            if (!isset($conf[$k])) {
                return null;
            }
            $conf = $conf[$k];
        }
        return $conf;
    }

    /**
     * filters config items
     * @param   string|mixed[]  $configResource
     * @return  mixed[]
     */
    public static function filter(string|array $configResource)
    {
        $class = self::class();
        if (is_string($configResource)) {
            return self::filterFromFile($configResource, $class);
        }
        return self::filterFromArray($configResource, $class);
    }

    /**
     * returns valid config items from specified file
     * @param   string  $path
     * @param   string  $class
     * @return  mixed[]
     * @thrown  \Exception
     */
    private static function filterFromFile(string $path, string $class)
    {
        if (strlen($path) === 0) {
            throw new \Exception("Specify valid filename.");
        }
        if (!is_readable($path)) {
            throw new \Exception("Cannot read file $path.");
        }
        $content = Neon::decodeFile($path);
        return self::filterFromArray($content, $class);
    }

    /**
     * returns valid config items from specified array
     * @param   mixed[] $content
     * @param   string  $class
     * @return  mixed[]
     * @thrown  \Exception
     */
    private static function filterFromArray(array $content, string $class)
    {
        $conf = [];
        $validConfig = self::$conf[$class]['validConfig'];
        foreach ($validConfig as $key => $def) {
            if (array_key_exists($key, $content)) {
                if (self::isValidType($content[$key], $def['type'])) {
                    $conf[$key] = $content[$key];
                } else {
                    $message = $key . " must be type of " . $def['type'] . ".";
                    throw new \Exception($message);
                }
            }
        }
        return $conf;
    }
}
