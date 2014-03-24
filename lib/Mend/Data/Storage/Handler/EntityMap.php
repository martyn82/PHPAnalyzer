<?php
namespace Mend\Data\Storage\Handler;

use Mend\Collections\Map;
use Mend\IO\FileSystem\Directory;

class EntityMap extends Map {
	/**
	 * @see Map::set()
	 */
	public function set( $key, $value ) {
		$this->_set( $key, $value );
	}

	/**
	 * Type-safe set.
	 *
	 * @param string $key
	 * @param Directory $value
	 */
	private function _set( $key, Directory $value ) {
		parent::set( $key, $value );
	}
}
