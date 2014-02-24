<?php
namespace Mend\Metrics\Report\Formatter;

require_once "ReportFormatterTest.php";

class TextReportFormatterTest extends ReportFormatterTest {
	public function testFormat() {
		$expectedText = <<<TEXT
Foo: Report Foo
Bar: 10 %
TEXT;

		$template = <<<TPL
Foo: %foo%
Bar: %bar% %%
TPL;

		$variableMapping = array(
			'foo' => 'Report Foo',
			'bar' => '10'
		);

		$reportArray = $this->getReportArray();
		$report = $this->getReport( $reportArray );

		$formatter = new TextReportFormatter( $template, $variableMapping );
		self::assertEquals( $expectedText, $formatter->format( $report ) );
	}
}