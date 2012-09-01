<?php
/**
 * This file is part of Pinoy.
 *
 * (c) Yuya Takeyama
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Util class for Pinoy
 *
 * @author Yuya Takeyama
 */
class Pinoy_Util
{
    private static $levels = array(
        Pinoy::LEVEL_DEBUG => 'DEBUG',
        Pinoy::LEVEL_INFO  => 'INFO',
        Pinoy::LEVEL_WARN  => 'WARN',
        Pinoy::LEVEL_ERROR => 'ERROR',
        Pinoy::LEVEL_FATAL => 'FATAL',
    );

    public static function parseArgs($args, $defaultTag)
    {
        $argCount = count($args);

        if ($argCount === 1) {
            return array($defaultTag, $args[0], array());
        } else if ($argCount === 2) {
            if (is_array($args[1])) {
                return array($defaultTag, $args[0], $args[1]);
            } else {
                return array($args[0], $args[1], array());
            }
        } else {
            return $args;
        }
    }

    public static function matchPattern($pattern, $tag)
    {
        $pattern = str_replace('.', '\.', $pattern);
        $pattern = str_replace('**', '[a-zA-Z0-9_\-\.]+', $pattern);
        $pattern = str_replace('*', '[a-zA-Z0-9_\-]+', $pattern);
        $pattern = '/^' . $pattern . '$/';

        return preg_match($pattern, $tag) === 1;
    }

    public static function getLevelAsString($level)
    {
        return self::$levels[$level];
    }
}
