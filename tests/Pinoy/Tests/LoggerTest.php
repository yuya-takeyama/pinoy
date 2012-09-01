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
        $logger = new Pinoy_Logger(Pinoy::LEVEL_DEBUG, 'default_tag');

        $writer = $this->createWriterMock();
        $writer->expects($this->once())
            ->method('write')
            ->with(Pinoy::LEVEL_DEBUG, 'default_tag', 'message', array('foo' => 'bar'));

        $logger['*'] = $writer;

        $logger->debug('message', array('foo' => 'bar'));
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

    /**
     * @test
     * @dataProvider provideLevelNeedsLoggingForInfo
     */
    public function info_should_call_writer_if_needed($level)
    {
        $logger = new Pinoy_Logger($level);

        $writer = $this->createWriterMock();
        $writer->expects($this->once())->method('write');

        $logger['*'] = $writer;

        $logger->info('foo');
    }

    public function provideLevelNeedsLoggingForInfo()
    {
        return array(
            array(Pinoy::LEVEL_DEBUG),
            array(Pinoy::LEVEL_INFO),
        );
    }

    /**
     * @test
     * @dataProvider provideLevelNotNeedsLoggingForInfo
     */
    public function info_should_call_writer_if_not_needed($level)
    {
        $logger = new Pinoy_Logger($level);

        $writer = $this->createWriterMock();
        $writer->expects($this->never())->method('write');

        $logger['*'] = $writer;

        $logger->info('foo');
    }

    public function provideLevelNotNeedsLoggingForInfo()
    {
        return array(
            array(Pinoy::LEVEL_WARN),
            array(Pinoy::LEVEL_ERROR),
            array(Pinoy::LEVEL_FATAL),
        );
    }

    /**
     * @test
     */
    public function offsetGet_should_create_clone_of_the_logger()
    {
        $logger = $this->createLogger();

        $writer = $this->createWriterMock();
        $logger['foo_tag'] = $writer;

        $fooLogger = $logger['foo_tag'];

        $this->assertInstanceOf('Pinoy_Logger', $fooLogger);
        $this->assertNotSame($logger, $fooLogger);
    }

    /**
     * @test
     */
    public function logger_created_with_offsetGet_is_set_default_tag_as_its_key()
    {
        $logger = $this->createLogger();

        $writer = $this->createWriterMock();
        $writer->expects($this->once())
            ->method('write')
            ->with(Pinoy::LEVEL_FATAL, 'foo_tag', 'fatal error');

        $logger['foo_tag'] = $writer;

        $logger['foo_tag']->fatal('fatal error');
    }

    /**
     * @test
     */
    public function logger_created_with_offsetGet_should_be_cached()
    {
        $logger = $this->createLogger();

        $fooLoggerA = $logger['foo_tag'];
        $fooLoggerB = $logger['foo_tag'];

        $this->assertSame($fooLoggerA, $fooLoggerB);
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
