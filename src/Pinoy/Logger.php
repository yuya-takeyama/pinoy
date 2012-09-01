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
class Pinoy_Logger
{
    const TAG_DEFAULT = 'default';

    /**
     * @var int
     */
    private $loggingLevel;

    /**
     * @var array<Pinoy_WriterInterface>
     */
    private $writers;

    /**
     * @param int $level
     * @param Pinoy_WriterInterface $defaultWriter
     */
    public function __construct($loggingLevel = Pinoy::LEVEL_DEBUG, Pinoy_WriterInterface $defaultWriter = null)
    {
        $this->loggingLevel = $loggingLevel;
        $this->writers = array();

        if ($defaultWriter) {
            $this->writers['default'] = $defaultWriter;
        }
    }

    public function write($level, $tag, $message, $options = array())
    {
        if ($level >= $this->loggingLevel) {
            $writer = $this->findWriterByTag($tag);

            if ($writer) {
                $traces   = debug_backtrace();
                $tracePos = self::DEFAULT_TRACE_POS;

                if (array_key_exists('trace_pos', $options)) {
                    $tracePos += (int) $options['trace_pos'];
                }

                $trace    = $trace[$tracePos];

                $writer->write($level, $tag, $message, $options, $trace, $tracePos, $allTraces);
            }
        }
    }

    public function debug()
    {
        $args = func_get_args();
        list($tag, $message, $options) = $this->parseArgs($args);

        return $this->write(Pinoy::LEVEL_DEBUG, $tag, $message, $options);
    }

    public function info()
    {
        $args = func_get_args();
        list($tag, $message, $options) = $this->parseArgs($args);

        return $this->write(Pinoy::LEVEL_INFO, $tag, $message, $options);
    }

    public function warn()
    {
        $args = func_get_args();
        list($tag, $message, $options) = $this->parseArgs($args);

        return $this->write(Pinoy::LEVEL_WARN, $tag, $message, $options);
    }

    public function error()
    {
        $args = func_get_args();
        list($tag, $message, $options) = $this->parseArgs($args);

        return $this->write(Pinoy::LEVEL_ERROR, $tag, $message, $options);
    }

    public function fatal()
    {
        $args = func_get_args();
        list($tag, $message, $options) = $this->parseArgs($args);

        return $this->write(Pinoy::LEVEL_FATAL, $tag, $message, $options);
    }

    public function parseArgs($args)
    {
        $argCount = count($args);

        if ($argCount === 1) {
            return array(self::TAG_DEFAULT, $args[0], array());
        } else if ($argCount === 2) {
            if (is_array($args[1])) {
                return array(self::TAG_DEFAULT, $args[0], $args[1]);
            } else {
                return array($args[0], $args[1], array());
            }
        } else {
            return $args;
        }
    }
}
