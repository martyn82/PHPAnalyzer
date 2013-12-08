<?php
namespace Mend\Metrics\Model;

use \Mend\Metrics\Arrayable;

class DuplicationModel extends DuplicationArray implements Arrayable {
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

	/**
	 * Converts this object to an array.
	 *
	 * @return array
	 */
	public function toArray() {
		return array(
			'duplicatedLinesOfCode' => $this->duplicatedLinesOfCode,
			'duplications' => parent::toArray()
		);
	}
}