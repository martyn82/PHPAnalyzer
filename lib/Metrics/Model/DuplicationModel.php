<?php
namespace Metrics\Model;

class DuplicationModel extends DuplicationArray {
	private $duplicatedLinesOfCode;

	/**
	 * Constructs a new model.
	 *
	 * @param array $values
	 * @param integer $duplicatedLinesOfCode
	 */
	public function __construct( array $values, $duplicatedLinesOfCode ) {
		$this->duplicatedLinesOfCode = (int) $duplicatedLinesOfCode;
		parent::__construct( $values );
	}

	/**
	 * @return integer
	 */
	public function getDuplicatedLinesOfCode() {
		return $this->duplicatedLinesOfCode;
	}
}