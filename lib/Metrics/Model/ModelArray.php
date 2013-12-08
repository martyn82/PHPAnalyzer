<?php
namespace Metrics\Model;

use \Metrics\Model\Model;

class ModelArray extends \ArrayObject {
	/**
	 * Constructs a new model array.
	 *
	 * @param array $values
	 */
	public function __construct( array $values = array() ) {
		foreach ( $values as $value ) {
			$this[] = $value;
		}
	}

	/**
	 * Set value at offset.
	 *
	 * @param integer $offset
	 * @param \Metrics\Model\Model $value
	 */
	public function offsetSet( $offset, $value ) {
		$this->_offsetSet( $offset, $value );
	}

	/**
	 * Wrapper for type safety.
	 *
	 * @param integer $offset
	 * @param \Metrics\Model\Model $value
	 */
	private function _offsetSet( $offset, Model $value ) {
		parent::offsetSet( $offset, $value );
	}
}