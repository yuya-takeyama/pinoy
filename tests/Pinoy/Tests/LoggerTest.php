<?php
/**
 * This file is part of Pinoy.
 *
 * (c) Yuya Takeyama
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once 'Pinoy.php';
require_once 'Pinoy/Logger.php';

/**
 * Tests for Pinoy_Logger
 *
 * @author Yuya Takeyama
 */
class Pinoy_Tests_LoggerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider provideInputAndExpectedOutputForParseArgs
     */
    public function parseArgs_should_parse_args_correctly($input, $expected)
    {
        $logger = $this->createLogger();

        $this->assertEquals($expected, $logger->parseArgs($input));
    }

    public function provideInputAndExpectedOutputForParseArgs()
    {
        return array(
            array(
                array('MESSAGE'),
                array(Pinoy_Logger::TAG_DEFAULT, 'MESSAGE', array()),
            ),
            array(
                array('TAG', 'MESSAGE'),
                array('TAG', 'MESSAGE', array()),
            ),
            array(
                array('MESSAGE', array('trace_pos' => 1)),
                array(Pinoy_Logger::TAG_DEFAULT, 'MESSAGE', array('trace_pos' => 1)),
            ),
            array(
                array('TAG', 'MESSAGE', array('trace_pos' => 1)),
                array('TAG', 'MESSAGE', array('trace_pos' => 1)),
            ),
        );
    }

    private function createLogger()
    {
        return new Pinoy_Logger;
    }
}
