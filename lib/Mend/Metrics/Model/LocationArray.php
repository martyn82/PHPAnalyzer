<?php
namespace Mend\Metrics\Model;

use \Mend\Metrics\Arrayable;

class LocationArray extends \ArrayObject implements Arrayable {
	/**
	 * Constructs a new location array.
	 *
	 * @param array $values
	 */
	public function __construct( array $values = array() ) {
		foreach ( $values as $value ) {
			$this[] = $value;
		}
	}

	/**
	 * Sets a value at offset.
	 *
	 * @param integer $offset
	 * @param Location $value
	 */
	public function offsetSet( $offset, $value ) {
		$this->_offsetSet( $offset, $value );
	}

	/**
	 * Wrapper for type safety.
	 *
	 * @param integer $offset
	 * @param Location $value
	 */
	private function _offsetSet( $offset, Location $value ) {
		parent::offsetSet( $offset, $value );
	}

	/**
	 * Converts this object to array.
	 *
	 * @return array
	 */
	public function toArray() {
		return array_map(
			function ( Arrayable $location ) {
				return $location->toArray();
			},
			(array) $this
		);
	}
}