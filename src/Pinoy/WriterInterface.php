<?php
/**
 * This file is part of Pinoy.
 *
 * (c) Yuya Takeyama
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

interface Pinoy_WriterInterface
{
    /**
     * Writes log.
     *
     * @param int    $level     Logging level
     * @param string $tag       Tag
     * @param mixed  $message   Log message
     * @param array  $options   Logging option
     * @param array  $trace     Backtrace on the message written
     * @param int    $tracePos  Position of the message written in all traces
     * @param array  $allTraces All backtraces
     */
    public function write($level, $tag, $message, $options, $trace, $tracePos, $allTraces);
}
