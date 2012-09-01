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
 * Proxy to combine multiple writers
 *
 * @author Yuya Takeyama
 */
class Pinoy_Proxy_CombiningProxy implements Pinoy_WriterInterface
{
    /**
     * @var array<Pinoy_WriterInterface>
     */
    private $writers;

    public function __construct(array $writers)
    {
        $this->writers = $writers;
    }

    public function write(DateTime $now, $level, $tag, $message, $options, $trace, $tracePos, $traces)
    {
        foreach ($this->writers as $writer) {
            $writer->write($now, $level, $tag, $message, $options, $trace, $tracePos, $traces);
        }
    }
}
