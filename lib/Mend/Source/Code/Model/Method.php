<?php
namespace Mend\Source\Code\Model;

use Mend\Metrics\Complexity\ComplexityResult;
use Mend\Metrics\UnitSize\UnitSizeResult;

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

	/**
	 * @see Model::toArray()
	 */
	public function toArray() {
		$result = parent::toArray();
		$result[ 'unitSize' ] = $this->unitSize->toArray();
		$result[ 'complexity' ] = $this->complexity->toArray();

		return $result;
	}
}