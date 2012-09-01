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
 * Tests for Pinoy_Util
 *
 * @author Yuya Takeyama
 */
class Pinoy_Tests_UtilTest extends PHPUnit_Framework_TestCase
{
    const TAG_DEFAULT = 'default';

    /**
     * @test
     * @dataProvider provideInputAndExpectedOutputForParseArgs
     */
    public function parseArgs_should_parse_args_correctly($input, $expected)
    {
        $this->assertEquals($expected, Pinoy_Util::parseArgs($input, self::TAG_DEFAULT));
    }

    public function provideInputAndExpectedOutputForParseArgs()
    {
        return array(
            array(
                array('MESSAGE'),
                array(self::TAG_DEFAULT, 'MESSAGE', array()),
            ),
            array(
                array('TAG', 'MESSAGE'),
                array('TAG', 'MESSAGE', array()),
            ),
            array(
                array('MESSAGE', array('trace_pos' => 1)),
                array(self::TAG_DEFAULT, 'MESSAGE', array('trace_pos' => 1)),
            ),
            array(
                array('TAG', 'MESSAGE', array('trace_pos' => 1)),
                array('TAG', 'MESSAGE', array('trace_pos' => 1)),
            ),
        );
    }
}
