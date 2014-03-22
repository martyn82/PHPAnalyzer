<?php
namespace Model\Project;

use Mend\Collections\Map;
use Mend\Data\DataPage;
use Mend\Data\SortOptions;
use Mend\Data\DataObjectCollection;

class ProjectMapperTest extends \TestCase {
	/**
	 * @dataProvider selectProvider
	 *
	 * @param Map $criteria
	 * @param SortOptions $sort
	 * @param DataPage $page
	 */
	public function testSelect( Map $criteria, SortOptions $sort, DataPage $page ) {
		$recordSet = $this->createRecordSet( array() );

		$resultSet = $this->createResultSet();

		$resultSet->expects( self::once() )
			->method( 'getRecordSet' )
			->will( self::returnValue( $recordSet ) );

		$storage = $this->createStorage();
		$mapper = new DummyProjectMapper( $storage );

		$entity = $mapper->getEntity();

		$storage->expects( self::once() )
			->method( 'select' )
			->with(
				self::equalTo( $entity ),
				self::equalTo( $criteria ),
				self::equalTo( $sort ),
				self::equalTo( $page )
			)
			->will( self::returnValue( $resultSet ) );

		$actual = $mapper->select( $criteria, $sort, $page );
		self::assertInstanceOf( '\Mend\Data\DataObjectCollection', $actual );
	}

	public function testInsert() {
		$fields = array( 'name' => 'Foo', 'key' => 'bar', 'root' => 'foo:///tmp' );

		$storage = $this->createStorage();
		$mapper = new DummyProjectMapper( $storage );

		$objects = new DataObjectCollection();
		$objects->add( $this->createDataObject( $fields ) );

		$records = $this->createRecords( 1, $fields );
		$recordSet = $this->createRecordSet( $records );

		$resultSet = $this->createResultSet();

		$resultSet->expects( self::any() )
			->method( 'getRecordSet' )
			->will( self::returnValue( $recordSet ) );

		$entity = $mapper->getEntity();

		$storage->expects( self::once() )
			->method( 'insert' )
			->with(
				self::equalTo( $entity ),
				self::isInstanceOf( '\Mend\Data\Storage\RecordSet' )
			)
			->will( self::returnValue( $resultSet ) );

		$actual = $mapper->insert( $objects );

		$objects->rewind();
		$actual->rewind();

		self::assertEquals( $objects->size(), $actual->size() );
	}

	public function testUpdate() {
		$fields = array( 'name' => 'Foo', 'key' => 'bar', 'root' => 'foo:///tmp' );

		$storage = $this->createStorage();
		$mapper = new DummyProjectMapper( $storage );

		$objects = new DataObjectCollection();
		$objects->add( $this->createDataObject( $fields ) );

		$records = $this->createRecords( 1, $fields );
		$recordSet = $this->createRecordSet( $records );

		$resultSet = $this->createResultSet();

		$resultSet->expects( self::once() )
			->method( 'getRecordSet' )
			->will( self::returnValue( $recordSet ) );

		$entity = $mapper->getEntity();

		$storage->expects( self::once() )
			->method( 'update' )
			->with(
				self::equalTo( $entity ),
				self::isInstanceOf( '\Mend\Data\Storage\RecordSet' )
			)
			->will( self::returnValue( $resultSet ) );

		$actual = $mapper->update( $objects );

		$objects->rewind();
		$actual->rewind();

		self::assertEquals( $objects->size(), $actual->size() );
	}

	public function testDelete() {
		$storage = $this->createStorage();
		$mapper = new DummyProjectMapper( $storage );

		$resultSet = $this->createResultSet();
		$recordSet = $this->createRecordSet( array() );

		$resultSet->expects( self::any() )
			->method( 'getRecordSet' )
			->will( self::returnValue( $recordSet ) );

		$entity = $mapper->getEntity();

		$storage->expects( self::once() )
			->method( 'delete' )
			->with(
				self::equalTo( $entity ),
				self::isInstanceOf( '\Mend\Data\Storage\RecordSet' )
			)
			->will( self::returnValue( $resultSet ) );

		$objects = new DataObjectCollection();
		$objects->add( $this->createDataObject() );

		$actual = $mapper->delete( $objects );

		self::assertEquals( 0, $actual->size() );
	}

	private function createRecords( $count, array $fieldValues = array() ) {
		$fieldValues = !empty( $fieldValues ) ? $fieldValues : array( 'id' => 1 );
		$records = array();

		for ( $i = 0; $i < abs( (int) $count ); $i++ ) {
			$fields = new Map( $fieldValues );

			$record = $this->getMockBuilder( '\Mend\Data\Storage\Record' )
				->setConstructorArgs( array( $fields ) )
				->getMock();

			$record->expects( self::any() )
				->method( 'getValue' )
				->will(
					self::returnCallback(
						function ( $field ) use ( $fields ) {
							return $fields->get( $field );
						}
					)
				);

			$records[] = $record;
		}

		return $records;
	}

	private function createStorage() {
		return $this->getMock( '\Mend\Data\Storage\Storage' );
	}

	private function createCriteria() {
		return new Map( array( 'key' => 'bar' ) );
	}

	private function createResultSet() {
		return $this->getMockBuilder( '\Mend\Data\Storage\ResultSet' )
			->disableOriginalConstructor()
			->getMock();
	}

	private function createRecordSet( array $records ) {
		return new \Mend\Data\Storage\RecordSet( $records );
	}

	private function createDataObject( array $fields = array() ) {
		$fields = !empty( $fields ) ? $fields : array( 'id' => 1 );

		$dataObject = $this->getMockBuilder( '\Model\Project\Project' )
			->disableOriginalConstructor()
			->setMethods( array( 'toArray' ) )
			->getMock();

		$dataObject->expects( self::any() )
			->method( 'toArray' )
			->will( self::returnValue( $fields ) );

		return $dataObject;
	}

	public function selectProvider() {
		return array(
			array( $this->createCriteria(), new SortOptions(), new DataPage() )
		);
	}
}

class DummyProjectMapper extends ProjectMapper {
	public function getEntity() {
		return parent::getEntity();
	}
}
