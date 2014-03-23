<?php
namespace Mend\Data;

use Mend\Collections\AbstractCollection;
use Mend\Collections\Collection;
use Mend\Collections\Map;
use Mend\Data\Storage\Record;
use Mend\Data\Storage\RecordSet;

class DataMapperTest extends \TestCase {
	public static $mockDataObject;

	public function testSelect() {
		$criteria = $this->createCriteria();
		$sortOptions = $this->createSortOptions();
		$dataPage = $this->createDataPage();

		$mapper = $this->createDataMapper();
		$objects = $mapper->select( $criteria, $sortOptions, $dataPage );

		self::assertInstanceOf( '\Mend\Collections\Collection', $objects );
	}

	public function testInsert() {
		$criteria = $this->createCriteria();
		$sortOptions = $this->createSortOptions();
		$dataPage = $this->createDataPage();

		$mapper = $this->createDataMapper();

		$objectsA = new DataObjectCollection();
		$objectsA->add( $this->createDataObject() );

		$objectsB = $mapper->insert( $objectsA );

		self::assertNotEmpty( $objectsB );
	}

	public function testUpdate() {
		$criteria = $this->createCriteria();
		$sortOptions = $this->createSortOptions();
		$dataPage = $this->createDataPage();

		$mapper = $this->createDataMapper();

		$objectsA = new DataObjectCollection();
		$objectsA->add( $this->createDataObject() );

		$objectsB = $mapper->update( $objectsA );

		self::assertNotEmpty( $objectsB );
	}

	public function testDelete() {
		$criteria = $this->createCriteria();
		$sortOptions = $this->createSortOptions();
		$dataPage = $this->createDataPage();

		$mapper = $this->createDataMapper();

		$objectsA = new DataObjectCollection();
		$objectsA->add( $this->createDataObject() );

		$objectsB = $mapper->delete( $objectsA );

		self::assertNotEmpty( $objectsB );
	}

	private function createDataMapper() {
		$recordSet = $this->createRecordSet();

		$resultSet = $this->createResultSet();

		$resultSet->expects( self::any() )
			->method( 'getTotalCount' )
			->will( self::returnValue( 1 ) );

		$resultSet->expects( self::any() )
			->method( 'getRecordSet' )
			->will( self::returnValue( $recordSet ) );

		$storage = $this->createStorage();

		$storage->expects( self::any() )
			->method( 'select' )
			->will( self::returnValue( $resultSet ) );

		$storage->expects( self::any() )
			->method( 'insert' )
			->will( self::returnValue( $resultSet ) );

		$storage->expects( self::any() )
			->method( 'update' )
			->will( self::returnValue( $resultSet ) );

		$storage->expects( self::any() )
			->method( 'delete' )
			->will( self::returnValue( $resultSet ) );

		self::$mockDataObject = $this->createDataObject();
		return new DummyDataMapper( $storage );
	}

	private function createRecordSet() {
		$records = array( new Record( new Map( array( 'id' => 1 ) ) ) );
		return new RecordSet( $records );
	}

	private function createStorage() {
		return $this->getMock( '\Mend\Data\Storage\Storage' );
	}

	private function createResultSet() {
		return $this->getMockBuilder( '\Mend\Data\Storage\ResultSet' )
			->disableOriginalConstructor()
			->getMock();
	}

	private function createCriteria() {
		return $this->getMock( '\Mend\Collections\Map' );
	}

	private function createSortOptions() {
		return $this->getMock( '\Mend\Data\SortOptions' );
	}

	private function createDataPage() {
		return $this->getMock( '\Mend\Data\DataPage' );
	}

	private function createDataObject() {
		return $this->getMock( '\Mend\Data\DataObject' );
	}
}

class DummyDataMapper extends DataMapper {
	/**
	 * @see DataMapper::getEntity()
	 */
	protected function getEntity() {
		return 'dummy';
	}

	/**
	 * @see DataMapper::createDataObjectFromRecord()
	 */
	protected function createDataObjectFromRecord( Record $record ) {
		return DataMapperTest::$mockDataObject;
	}

	/**
	 * @see DataMapper::createRecordFromDataObject()
	 */
	protected function createRecordFromDataObject( DataObject $object ) {
		$fields = new Map();
		$fields->set( 'id', $object->getIdentity() );
		return new Record( $fields );
	}

	/**
	 * @see DataMapper::select()
	 */
	public function select( Map $criteria, SortOptions $sortOptions, DataPage $dataPage ) {
		$storage = $this->getStorage();
		$result = $storage->select( $this->getEntity(), $criteria, $sortOptions, $dataPage );

		return $this->createCollectionFromResultSet( $result );
	}

	/**
	 * @see DataMapper::insert()
	 */
	public function insert( DataObjectCollection $objects ) {
		$records = $this->createRecordSetFromCollection( $objects );

		$storage = $this->getStorage();
		$result = $storage->insert( $this->getEntity(), $records );

		return $this->createCollectionFromResultSet( $result );
	}

	/**
	 * @see DataMapper::update()
	 */
	public function update( DataObjectCollection $objects ) {
		$records = $this->createRecordSetFromCollection( $objects );

		$storage = $this->getStorage();
		$result = $storage->update( $this->getEntity(), $records );

		return $this->createCollectionFromResultSet( $result );
	}

	/**
	 * @see DataMapper::delete()
	 */
	public function delete( DataObjectCollection $objects ) {
		$records = $this->createRecordSetFromCollection( $objects );

		$storage = $this->getStorage();
		$result = $storage->delete( $this->getEntity(), $records );

		return $this->createCollectionFromResultSet( $result );
	}
}
