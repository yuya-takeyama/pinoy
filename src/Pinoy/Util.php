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
}
