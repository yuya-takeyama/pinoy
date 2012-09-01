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
 * Interface for writer of Pinoy
 *
 * @author Yuya Takeyama
 */
interface Pinoy_WriterInterface
{
    /**
     * Writes log.
     *
     * @param DateTime $now      DateTime object of now
     * @param int      $level    Logging level
     * @param string   $tag      Tag
     * @param mixed    $message  Log message
     * @param array    $options  Logging option
     * @param array    $trace    Backtrace on the message written
     * @param int      $tracePos Position of the message written in all traces
     * @param array    $traces   All backtraces
     */
    public function write(DateTime $now, $level, $tag, $message, $options, $trace, $tracePos, $traces);
}
