<?php
namespace Model\Project;

use Mend\Collections\Map;
use Mend\Data\DataObjectCollection;
use Mend\Data\DataPage;
use Mend\Data\SortOptions;
use Mend\IO\FileSystem\Directory;

class ProjectRepositoryTest extends \TestCase {
	public function testGet() {
		$criteria = new Map( array( 'id' => 1 ) );
		$sortOptions = new SortOptions();
		$dataPage = new DataPage();

		$collection = $this->getMockBuilder( '\Mend\Data\DataObjectCollection' )
			->setMethods( array( 'isEmpty', 'toArray' ) )
			->getMock();

		$collection->expects( self::once() )
			->method( 'isEmpty' )
			->will( self::returnValue( false ) );

		$collection->expects( self::once() )
			->method( 'toArray' )
			->will( self::returnValue( array( new Project( 'foo', 'bar', new Directory( 'baz' ) ) ) ) );

		$mapper = $this->createMapper();

		$mapper->expects( self::once() )
			->method( 'select' )
			->with(
				self::equalTo( $criteria ),
				self::equalTo( $sortOptions ),
				self::equalTo( $dataPage )
			)
			->will( self::returnValue( $collection ) );

		$repository = new ProjectRepository( $mapper );
		$result = $repository->get( 1 );

		self::assertInstanceOf( '\Model\Project\Project', $result );
	}

	public function testAll() {
		$mapper = $this->createMapper();

		$mapper->expects( self::once() )
			->method( 'select' )
			->will( self::returnValue( new DataObjectCollection() ) );

		$repository = new ProjectRepository( $mapper );
		$results = $repository->all( new SortOptions(), new DataPage() );

		self::assertInstanceOf( '\Mend\Data\DataObjectCollection', $results );
	}

	public function testMatching() {
		$mapper = $this->createMapper();

		$mapper->expects( self::once() )
			->method( 'select' )
			->will( self::returnValue( new DataObjectCollection() ) );

		$repository = new ProjectRepository( $mapper );
		$results = $repository->matching( new Map(), new SortOptions(), new DataPage() );

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
