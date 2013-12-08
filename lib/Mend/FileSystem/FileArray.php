<?php
namespace Mend\FileSystem;

class FileArray extends \ArrayObject {
	/**
	 * Constructs a new file array.
	 *
	 * @param array $values
	 */
	public function __construct( array $values ) {
		foreach ( $values as $value ) {
			$this[] = $value;
		}
	}

	/**
	 * Sets a value at a offset.
	 *
	 * @param integer $offset
	 * @param File $value
	 */
	public function offsetSet( $offset, $value ) {
		$this->_offsetSet( $offset, $value );
	}

	/**
	 * Wrapper around default method for type safety.
	 *
	 * @param integer $offset
	 * @param File $value
	 */
	private function _offsetSet( $offset, File $value ) {
		parent::offsetSet( $offset, $value );
	}
}