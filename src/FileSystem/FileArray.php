<?php
namespace FileSystem;

class FileArray extends \ArrayObject {
	public function __construct( array $values ) {
		foreach ( $values as $value ) {
			$this[] = $value;
		}
	}
	
	public function offsetSet( $offset, $value ) {
		$this->_offsetSet( $offset, $value );
	}
	
	/* Wrapper around default method for type safety */
	private function _offsetSet( $offset, File $value ) {
		parent::offsetSet( $offset, $value );
	}
}