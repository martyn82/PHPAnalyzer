<?php
namespace Mend\Metrics\Report\Formatter;

use Mend\Metrics\Project\ProjectReport;

class TextReportFormatter extends ReportFormatter {
	/**
	 * @var string
	 */
	private $template;

	/**
	 * @var array
	 */
	private $mapping;

	/**
	 * Constructs a new text report formatter.
	 *
	 * @param string $template
	 * @param array $variableMapping
	 */
	public function __construct( $template, array $variableMapping ) {
		$this->template = (string) $template;
		$this->mapping = $variableMapping;
	}

	/**
	 * @see ReportFormatter::format()
	 */
	public function format( ProjectReport $report ) {
		$mapping = $this->getVariableMapping();
		$template = $this->getTemplate();

		$preparedKeys = array_map(
			function ( $value ) {
				return "%{$value}%";
			},
			array_keys( $mapping )
		);

		$preparedMapping = array_combine( $preparedKeys, $mapping );
		$preparedMapping += array( '%%' => '%' );

		return str_replace(
			array_keys( $preparedMapping ),
			array_values( $preparedMapping ),
			$template
		);
	}

	/**
	 * Retrieves the variable mapping.
	 *
	 * @return array
	 */
	private function getVariableMapping() {
		return $this->mapping;
	}

	/**
	 * Retrieves the template.
	 *
	 * @return string
	 */
	private function getTemplate() {
		return $this->template;
	}
}