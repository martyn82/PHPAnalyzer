<?php
namespace Mend\Metrics\Report\Writer;

class ReportWriterTest extends \TestCase {
	public function testConstructor() {
		$report = $this->getMock( '\Mend\Metrics\Project\ProjectReport', array(), array(), '', false );
		$formatter = $this->getMock( '\Mend\Metrics\Report\Formatter\ReportFormatter' );

		$writer = new DummyReportWriter( $report, $formatter );

		self::assertNotNull( $writer );
		self::assertEquals( $report, $writer->getReport() );
		self::assertEquals( $formatter, $writer->getFormatter() );
	}

	public function testWrite() {
		$report = $this->getMock( '\Mend\Metrics\Project\ProjectReport', array(), array(), '', false );

		$formatter = $this->getMock( '\Mend\Metrics\Report\Formatter\ReportFormatter', array( 'format' ) );

		$formatter->expects( self::once() )
			->method( 'format' );

		$stream = $this->getMock(
			'\Mend\IO\Stream\StreamWriter',
			array( 'isClosed', 'open', 'close', 'isOpen', 'write', 'isWritable' )
		);

		$stream->expects( self::once() )
			->method( 'isClosed' )
			->will( self::returnValue( true ) );

		$stream->expects( self::once() )
			->method( 'open' );

		$stream->expects( self::once() )
			->method( 'close' );

		$writer = new ReportWriter( $report, $formatter );
		$writer->write( $stream );
	}

	public function testReportAsString() {
		$report = $this->getMock( '\Mend\Metrics\Project\ProjectReport', array(), array(), '', false );
		$formatter = $this->getMock( '\Mend\Metrics\Report\Formatter\ReportFormatter', array( 'format' ) );

		$reportString = 'foo report string';
		$formatter->expects( self::any() )
			->method( 'format' )
			->will( self::returnValue( $reportString ) );

		$writer = new ReportWriter( $report, $formatter );
		$asString = $writer->getReportAsString();
		$toString = $writer->__toString();

		self::assertEquals( $reportString, $toString );
		self::assertEquals( $toString, $asString );
	}
}

class DummyReportWriter extends ReportWriter {
	public function getReport() {
		return parent::getReport();
	}

	public function getFormatter() {
		return parent::getFormatter();
	}
}
