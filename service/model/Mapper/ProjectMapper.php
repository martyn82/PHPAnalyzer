<?php
namespace Mapper;

use Mend\Data\DataMapper;

class ProjectMapper extends DataMapper {
	private static final $_;

	public function select() {
		$storage = $this->getStorage();
		$resultSet = $storage->read( self::$_, $id );
	}

	public function insert() {}
	public function update() {}
	public function delete() {}
}
