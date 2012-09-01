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
 * Factory class for backtrace
 *
 * @author Yuya Takeyama
 */
class Pinoy_BacktraceFactory
{
    public function create()
    {
        return debug_backtrace();
    }
}
