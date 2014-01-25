<?php
namespace Mend\Metrics\Model\Code;

use Mend\Metrics\Analyze\Complexity\ComplexityResult;
use Mend\Metrics\Analyze\UnitSize\UnitSizeResult;

class Method extends Model {
	/**
	 * @var ComplexityResult
	 */
	private $complexity;

	/**
	 * @var UnitSizeResult
	 */
	private $unitSize;

	/**
	 * Gets or sets the unit size.
	 *
	 * @param UnitSizeResult $value
	 *
	 * @return UnitSizeResult
	 */
	public function unitSize( UnitSizeResult $value = null ) {
		if ( !is_null( $value ) ) {
			$this->unitSize = $value;
		}

		return $this->unitSize;
	}

	/**
	 * Gets or sets the complexity.
	 *
	 * @param ComplexityResult $value
	 *
	 * @return ComplexityResult
	 */
	public function complexity( ComplexityResult $value = null ) {
		if ( !is_null( $value ) ) {
			$this->complexity = $value;
		}

		return $this->complexity;
	}
}