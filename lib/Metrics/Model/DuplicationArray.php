<?php
namespace Metrics\Model;

class DuplicationArray extends \ArrayObject {
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
	 * @param \Metrics\Model\Duplication $value
	 */
	public function offsetSet( $offset, $value ) {
		$this->_offsetSet( $offset, $value );
	}

	/**
	 * Wrapper for type safety.
	 *
	 * @param integer $ofsset
	 * @param \Metrics\Model\Duplication $value
	 */
	private function _offsetSet( $ofsset, Duplication $value ) {
		parent::offsetSet( $offset, $value );
	}
 }