<?php
namespace Model\Project;

use Mend\Collections\Map;
use Mend\Data\DataObjectCollection;
use Mend\Data\DataPage;
use Mend\Data\SortOptions;

class ProjectRepositoryTest extends \TestCase {
	public function testGet() {
		$criteria = new Map( array( 'id' => 1 ) );
		$sortOptions = new SortOptions();
		$dataPage = new DataPage();

		$mapper = $this->createMapper();

		$mapper->expects( self::once() )
			->method( 'select' )
			->with(
				self::equalTo( $criteria ),
				self::equalTo( $sortOptions ),
				self::equalTo( $dataPage )
			)
			->will( self::returnValue( new DataObjectCollection() ) );

		$repository = new ProjectRepository( $mapper );
		$results = $repository->get( 1 );

		self::assertInstanceOf( '\Mend\Data\DataObjectCollection', $results );
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
