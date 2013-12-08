<?php
namespace Mend\Metrics\Model;

class LocationArray extends \ArrayObject {
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
}