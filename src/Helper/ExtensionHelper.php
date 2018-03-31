<?php

namespace AT\CodeQualityTool\Helper;

/**
 * Class ExtensionHelper
 *
 * @method static bool isPhp(string $file)
 * @method static bool isJs(string $file)
 * @method static bool isJson(string $file)
 * @method static bool isTwig(string $file)
 */
class ExtensionHelper
{
    public static function __callStatic($name, $arguments)
    {
        if ('is' === substr($name, 0, 2) && strlen($name) > 2 && 1 === count($arguments)) {
            $extension = substr($name, 2);

            return (bool) preg_match('/(.*)\.' . strtolower($extension) . '$/', $arguments[0]);
        }
    }
}
