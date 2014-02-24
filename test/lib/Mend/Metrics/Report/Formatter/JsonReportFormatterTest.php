<?php
namespace Mend\Metrics\Report\Formatter;

require_once "ReportFormatterTest.php";

class JsonReportFormatterTest extends ReportFormatterTest {
	public function testFormat() {
		$expectedJson = <<<JSON
{
    "name": "Report Foo",
    "key": "report_foo",
    "array": [
        "foo",
        "bar",
        "baz"
    ],
    "obj": {
        "foo": "yes",
        "bar": "no"
    },
    "numeric": 12121,
    "floatnum": 31.52
}
JSON;

		$reportArray = $this->getReportArray();
		$report = $this->getReport( $reportArray );
		$formatter = new JsonReportFormatter();

		self::assertEquals( $expectedJson, $formatter->format( $report ) );
	}
}