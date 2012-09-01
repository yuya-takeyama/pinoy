<?php
/**
 * This file is part of Pinoy.
 *
 * (c) Yuya Takeyama
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once dirname(__FILE__) . '/Pinoy/Logger.php';
require_once dirname(__FILE__) . '/Pinoy/WriterInterface.php';
require_once dirname(__FILE__) . '/Pinoy/BacktraceFactory.php';

class Pinoy
{
    const VERSION = '0.0.0-dev';

    const LEVEL_DEBUG   = 0;
    const LEVEL_INFO    = 1;
    const LEVEL_WARN    = 2;
    const LEVEL_ERROR   = 3;
    const LEVEL_FATAL   = 4;
}
