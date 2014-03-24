<?php
namespace Mend\Collections;

class Map {
	/**
	 * @var array
	 */
	private $data;

	/**
	 * Constructs a new Map.
	 *
	 * @param array $data
	 */
	public function __construct( array $data = array() ) {
		$this->data = array();
		$this->addAll( $data );
	}

	/**
	 * Adds the given array to the map.
	 *
	 * @param array $keyValues
	 */
	public function addAll( array $keyValues ) {
		foreach ( $keyValues as $key => $value ) {
			$this->set( $key, $value );
		}
	}

	/**
	 * Retrieves the size of the map.
	 *
	 * @return integer
	 */
	public function getSize() {
		return count( $this->data );
	}

	/**
	 * Retrieves a value by key.
	 *
	 * @param string $key
	 * @param mixed $default
	 *
	 * @return mixed
	 */
	public function get( $key, $default = null ) {
		if ( !isset( $this->data[ $key ] ) ) {
			return $default;
		}

		return $this->data[ $key ];
	}

	/**
	 * Sets a value with key.
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	public function set( $key, $value ) {
		$this->data[ $key ] = $value;
	}

	/**
	 * Determines whether the given key exists.
	 *
	 * @param string $key
	 *
	 * @return boolean
	 */
	public function hasKey( $key ) {
		return isset( $this->data[ $key ] );
	}

	/**
	 * Converts this Map to an associative array.
	 *
	 * @return array
	 */
	public function toArray() {
		return $this->data;
	}
}