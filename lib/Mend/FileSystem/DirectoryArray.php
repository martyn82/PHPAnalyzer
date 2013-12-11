<?php
namespace Mend\FileSystem;

class DirectoryArray extends \ArrayObject {
	/**
	 * Constructs a new directory array.
	 *
	 * @param array $values
	 */
	public function __construct( array $values = array() ) {
		foreach ( $values as $value ) {
			$this[] = $value;
		}
	}

	/**
	 * Sets value at an offset.
	 *
	 * @param integer $offset
	 * @param Directory $value
	 */
	public function offsetSet( $offset, $value ) {
		$this->_offsetSet( $offset, $value );
	}

	/**
	 * Wrapper for type safety.
	 *
	 * @param integer $offset
	 * @param Directory $value
	 */
	private function _offsetSet( $offset, Directory $value ) {
		parent::offsetSet( $offset, $value );
	}
}