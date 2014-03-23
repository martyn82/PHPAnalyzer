<?php
namespace Mend\Metrics\Report\Formatter;

abstract class ReportFormatterTest extends \TestCase {
	protected function getReportArray() {
		return array(
			'name' => 'Report Foo',
			'key' => 'report_foo',
			'array' => array(
				'foo', 'bar', 'baz'
			),
			'obj' => array(
				'foo' => 'yes',
				'bar' => 'no'
			),
			'numeric' => 12121,
			'floatnum' => 31.52
		);
	}

	protected function getReport( array $reportArray ) {
		$report = $this->getMockBuilder( '\Mend\Metrics\Project\ProjectReport' )
			->setMethods( array( 'toArray' ) )
			->disableOriginalConstructor()
			->getMock();

		$report->expects( self::any() )
			->method( 'toArray' )
			->will( self::returnValue( $reportArray ) );

		return $report;
	}
}