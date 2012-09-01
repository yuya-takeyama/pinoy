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
 * Facade object of Pinoy.
 *
 * @author Yuya Takeyama
 */
class Pinoy_Logger implements ArrayAccess
{
    const DEFAULT_TRACE_POS = 2;

    /**
     * @var int
     */
    private $loggingLevel;

    /**
     * @var int
     */
    private $tracePos = self::DEFAULT_TRACE_POS;

    /**
     * @var array<Pinoy_WriterInterface>
     */
    private $writers;

    /**
     * @var Pinoy_BacktraceFactory
     */
    private $backtraceFactory;

    /**
     * @var array<Pinoy_WriterInterface>
     */
    private $cachedWriters = array();

    /**
     * @var array<Pinoy_Logger>
     */
    private $cachedLoggers = array();

    /**
     * @param int $level
     * @param Pinoy_WriterInterface $defaultWriter
     */
    public function __construct($loggingLevel = Pinoy::LEVEL_DEBUG, $defaultTag = 'default', array $writers = array(), Pinoy_BacktraceFactory $backtraceFactory = null)
    {
        if (is_null($backtraceFactory)) {
            $backtraceFactory = new Pinoy_BacktraceFactory;
        }

        $this->loggingLevel     = $loggingLevel;
        $this->defaultTag       = $defaultTag;
        $this->writers          = $writers;
        $this->backtraceFactory = $backtraceFactory;
    }

    public function offsetSet($tagPattern, $writer)
    {
        $this->writers[$tagPattern] = $writer;
    }

    public function offsetGet($tag)
    {
        if (!array_key_exists($tag, $this->cachedLoggers)) {
            $this->cachedLoggers[$tag] = new self($this->loggingLevel, $tag, $this->writers);
        }

        return $this->cachedLoggers[$tag];
    }

    public function offsetExists($key)
    {
        return isset($this->writers[$key]);
    }

    public function offsetUnset($key)
    {
        unset($this->writers[$key]);
    }

    public function write($level, $tag, $message, $options = array())
    {
        if ($level >= $this->loggingLevel) {
            $writer = $this->findWriterByTag($tag);

            if ($writer) {
                $traces   = $this->backtraceFactory->create();
                $tracePos = $this->getTracePos();

                if (array_key_exists('trace_pos', $options)) {
                    $tracePos += (int) $options['trace_pos'];
                }

                $trace = $traces[$tracePos];

                $writer->write(new DateTime, $level, $tag, $message, $options, $trace, $tracePos, $traces);
            }
        }
    }

    public function findWriterByTag($tag)
    {
        if (array_key_exists($tag, $this->cachedWriters)) {
            return $this->cachedWriters[$tag];
        } else {
            foreach ($this->writers as $pattern => $writer) {
                if (Pinoy_Util::matchPattern($pattern, $tag)) {
                    $this->cachedWriters[$tag] = $writer;

                    return $writer;
                }
            }
        }
    }

    public function debug()
    {
        $args = func_get_args();
        list($tag, $message, $options) = Pinoy_Util::parseArgs($args, $this->defaultTag);

        return $this->write(Pinoy::LEVEL_DEBUG, $tag, $message, $options);
    }

    public function info()
    {
        $args = func_get_args();
        list($tag, $message, $options) = Pinoy_Util::parseArgs($args, $this->defaultTag);

        return $this->write(Pinoy::LEVEL_INFO, $tag, $message, $options);
    }

    public function warn()
    {
        $args = func_get_args();
        list($tag, $message, $options) = Pinoy_Util::parseArgs($args, $this->defaultTag);

        return $this->write(Pinoy::LEVEL_WARN, $tag, $message, $options);
    }

    public function error()
    {
        $args = func_get_args();
        list($tag, $message, $options) = Pinoy_Util::parseArgs($args, $this->defaultTag);

        return $this->write(Pinoy::LEVEL_ERROR, $tag, $message, $options);
    }

    public function fatal()
    {
        $args = func_get_args();
        list($tag, $message, $options) = Pinoy_Util::parseArgs($args, $this->defaultTag);

        return $this->write(Pinoy::LEVEL_FATAL, $tag, $message, $options);
    }

    public function getTracePos()
    {
        return $this->tracePos;
    }

    public function setTracePos($pos)
    {
        $this->tracePos = $pos;
    }

    public function incrementTracePos($count = 1)
    {
        $this->tracePos += $count;
    }
}
