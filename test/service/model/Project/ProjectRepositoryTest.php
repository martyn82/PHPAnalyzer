<?php
namespace Model\Project;

class ProjectRepositoryTest extends \TestCase {
	public function testGet() {
		$mapper = $this->createMapper();

		$repository = new ProjectRepository( $mapper );
	}

	private function createMapper() {
		return $this->getMockBuilder( '\Mend\Data\DataMapper' )
			->disableOriginalConstructor()
			->setMethods(
				array(
					'getEntity',
					'createDataObjectFromRecord',
					'createRecordFromDataObject',
					'select',
					'insert',
					'update',
					'delete'
				)
			)
			->getMock();
	}
}
