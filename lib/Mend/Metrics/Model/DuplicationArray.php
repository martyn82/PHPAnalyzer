<?php
namespace Mend\Metrics\Model;

use \Mend\Metrics\Arrayable;

class DuplicationArray extends \ArrayObject implements Arrayable {
	/**
	 * Constructs a new duplication array.
	 *
	 * @param array $values
	 */
	public function __construct( array $values = array() ) {
		foreach ( $values as $value ) {
			$this[] = $value;
		}
	}

	/**
	 * Sets a value at an offset.
	 *
	 * @param integer $offset
	 * @param Duplication $value
	 */
	public function offsetSet( $offset, $value ) {
		$this->_offsetSet( $offset, $value );
	}

	/**
	 * Wrapper for type safety.
	 *
	 * @param integer $offset
	 * @param Duplication $value
	 */
	private function _offsetSet( $offset, Duplication $value ) {
		parent::offsetSet( $offset, $value );
	}

	/**
	 * Converts this object to array.
	 *
	 * @return array
	 */
	public function toArray() {
		return array_map(
			function ( Arrayable $duplication ) {
				return $duplication->toArray();
			},
			(array) $this
		);
	}
 }