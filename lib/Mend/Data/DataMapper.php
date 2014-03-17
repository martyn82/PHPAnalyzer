<?php
namespace Mend\Data;

use Mend\Data\Storage\Storage;

abstract class DataMapper {
	private $storage;

	public function __construct( Storage $storage ) {
		$this->storage = $storage;
	}

	/**
	 * @return Storage
	 */
	protected function getStorage() {
		return $this->storage;
	}

	abstract public function select();
	abstract public function insert();
	abstract public function update();
	abstract public function delete();
}
