<?php
namespace Mend\Data\Storage;

abstract class Storage {
	/**
	 * Retrieves an object of given type and ID.
	 *
	 * @param string $type
	 * @param string $id
	 *
	 * @return ResultSet
	 */
	abstract public function read( $type, $id );

	abstract public function create();
	abstract public function update();
	abstract public function delete();
	abstract public function search();
}
