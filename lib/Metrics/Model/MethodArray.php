<?php
namespace Metrics\Model;

use \Metrics\Model\Method;

class MethodArray extends \ArrayObject {
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
	 * @param \Metrics\Model\Method $value
	 */
	public function offsetSet( $offset, $value ) {
		$this->_offsetSet( $offset, $value );
	}

	/**
	 * Wrapper for type safety.
	 *
	 * @param integer $offset
	 * @param \Metrics\Model\Method $value
	 */
	private function _offsetSet( $offset, Method $value ) {
		parent::offsetSet( $offset, $value );
	}
}