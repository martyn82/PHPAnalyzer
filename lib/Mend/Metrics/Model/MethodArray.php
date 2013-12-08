<?php
namespace Mend\Metrics\Model;

use \Mend\Metrics\Model\Method;
use \Mend\Metrics\Arrayable;

class MethodArray extends \ArrayObject implements Arrayable {
	/**
	 * Constructs a new MethodArray.
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
	 * @param Method $value
	 */
	public function offsetSet( $offset, $value ) {
		$this->_offsetSet( $offset, $value );
	}

	/**
	 * Wrapper for type safety.
	 *
	 * @param integer $offset
	 * @param Method $value
	 */
	private function _offsetSet( $offset, Method $value ) {
		parent::offsetSet( $offset, $value );
	}

	/**
	 * Recursively converts this object to array.
	 *
	 * @return array
	 */
	public function toArray() {
		return array_map(
			function ( Method $method ) {
				return $method->toArray();
			},
			(array) $this
		);
	}
}