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
require_once 'Pinoy/WriterInterface.php';

/**
 * Tests for Pinoy_Logger
 *
 * @author Yuya Takeyama
 */
class Pinoy_Tests_LoggerTest extends PHPUnit_Framework_TestCase
{
    const TAG_DEFAULT = 'default';

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

    /**
     * @test
     * @dataProvider provideValidPatternAndTag
     */
    public function matchPattern_should_be_true_if_matched($pattern, $tag)
    {
        $logger = $this->createLogger();

        $this->assertTrue($logger->matchPattern($pattern, $tag));
    }

    /**
     * @test
     * @dataProvider provideInvalidPatternAndTag
     */
    public function matchPattern_should_be_false_if_not_matched($pattern, $tag)
    {
        $logger = $this->createLogger();

        $this->assertFalse($logger->matchPattern($pattern, $tag));
    }

    public function provideValidPatternAndTag()
    {
        return array(
            array('foo', 'foo'),
            array('*', 'foo'),
            array('**', 'foo'),
            array('foo.*.baz', 'foo.bar.baz'),
        );
    }

    public function provideInvalidPatternAndTag()
    {
        return array(
            array('foo', 'bar'),
            array('*', 'foo.bar'),
            array('foo.*.baz', 'foo.baz'),
        );
    }

    /**
     * @test
     */
    public function findWriterByTag_should_be_matched_writer()
    {
        $logger = $this->createLogger();
        $fooWriter = $this->createWriterMock();
        $barWriter = $this->createWriterMock();

        $logger['foo'] = $fooWriter;

        $this->assertSame($fooWriter, $logger->findWriterByTag('foo'));
    }

    /**
     * @test
     */
    public function debug_should_call_writer_if_logging_level_is_debug()
    {
        $logger = new Pinoy_Logger(Pinoy::LEVEL_DEBUG);

        $writer = $this->createWriterMock();
        $writer->expects($this->once())->method('write');

        $logger['*'] = $writer;

        $logger->debug('foo');
    }

    /**
     * @test
     */
    public function debug_should_not_call_writer_if_logging_level_is_info()
    {
        $logger = new Pinoy_Logger(Pinoy::LEVEL_INFO);

        $writer = $this->createWriterMock();
        $writer->expects($this->never())->method('write');

        $logger['*'] = $writer;

        $logger->debug('foo');
    }

    private function createLogger()
    {
        return new Pinoy_Logger(Pinoy::LEVEL_DEBUG, self::TAG_DEFAULT);
    }

    public function createWriterMock()
    {
        return $this->getMock('Pinoy_WriterInterface');
    }
}
