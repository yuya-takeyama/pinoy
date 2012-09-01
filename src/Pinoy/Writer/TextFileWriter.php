<?php
/**
 * This file is part of Pinoy.
 *
 * (c) Yuya Takeyama
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class Pinoy_Writer_TextFileWriter
{
    protected $file;

    public function __construct($file)
    {
        $this->file = $file;
    }

    public function write(DateTime $now, $level, $tag, $message, $options, $trace, $tracePos, $traces)
    {
        $fp = fopen($this->file, 'a');
        if ($fp) {
            flock($fp, LOCK_EX);

            $level = Pinoy_Util::getLevelAsString($level);
            $line  = "{$now->format('Y-m-d H:i:s')}\t{$level}\t{$tag}\t{$message}";

            if (array_key_exists('file', $trace) && array_key_exists('line', $trace)) {
                $line .= "\t{$trace['file']}:{$trace['line']}";
            }

            $line .= "\n";

            fputs($fp, $line);

            flock($fp, LOCK_UN);
            fclose($fp);
        }
    }
}
