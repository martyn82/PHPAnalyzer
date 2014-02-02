<?php
namespace Mend\Collections;

abstract class HashTable extends \ArrayObject {
	/**
	 * Constructs a new hash table.
	 *
	 * @param array $items
	 */
	public function __construct( array $items = array() ) {
		foreach ( $items as $hash => $bucket ) {
			$this[ $hash ] = $bucket;
		}
	}

	/**
	 * @see ArrayObject::offsetSet()
	 */
	public function offsetSet( $offset, $value ) {
		if ( !is_array( $value ) ) {
			$value = array( $value );
		}

		$this->_offsetSet( $offset, $value );
	}

	/**
	 * @param mixed $index
	 * @param array $value
	 */
	protected function _offsetSet( $index, array $value ) {
		parent::offsetSet( $index, $value );
	}

	/**
	 * Adds the given value to the bucket at given index.
	 *
	 * @param mixed $index
	 * @param mixed $value
	 */
	public function add( $index, $value ) {
		if ( !$this->offsetExists( $index ) ) {
			$bucket = array();
		}
		else {
			$bucket = $this->offsetGet( $index );
		}

		$bucket[] = $value;
		$this->offsetSet( $index, $bucket );
	}
}