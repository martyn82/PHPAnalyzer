<?php
namespace Mend\Data;

use Mend\Collections\Map;
use Mend\Data\Storage\Record;
use Mend\Data\Storage\ResultSet;
use Mend\Data\Storage\Storage;
use Mend\Data\Storage\RecordSet;

abstract class DataMapper {
	/**
	 * @var Storage
	 */
	private $storage;

	/**
	 * Constructs a new DataMapper instance.
	 *
	 * @param Storage $storage
	 */
	public function __construct( Storage $storage ) {
		$this->storage = $storage;
	}

	/**
	 * Retrieves the storage instance.
	 *
	 * @return Storage
	 */
	protected function getStorage() {
		return $this->storage;
	}

	/**
	 * Creates a collection with data objects from record set.
	 *
	 * @param ResultSet $result
	 *
	 * @return DataObjectCollection
	 */
	protected function createCollectionFromResultSet( ResultSet $result ) {
		$collection = new DataObjectCollection( $result->getTotalCount() );

		foreach ( $result->getRecordSet() as $record ) {
			/* @var $record Record */
			$object = $this->createDataObjectFromRecord( $record );
			$collection->add( $object );
		}

		return $collection;
	}

	/**
	 * Creates a record set from a collection with data objects.
	 *
	 * @param DataObjectCollection $collection
	 *
	 * @return RecordSet
	 */
	protected function createRecordSetFromCollection( DataObjectCollection $collection ) {
		$records = array();

		foreach ( $collection as $object ) {
			$records[] = $this->createRecordFromDataObject( $object );
		}

		return new RecordSet( $records );
	}

	/**
	 * Retrieves the name of the entity for the mapper.
	 *
	 * @return string
	 */
	abstract protected function getEntity();

	/**
	 * Creates a data object instance from record.
	 *
	 * @param Record $record
	 *
	 * @return DataObject
	 */
	abstract protected function createDataObjectFromRecord( Record $record );

	/**
	 * Creates a Record instance from data object.
	 *
	 * @param DataObject $object
	 *
	 * @return Record
	 */
	abstract protected function createRecordFromDataObject( DataObject $object );

	/**
	 * Retrieves a collection of data objects that match given criteria.
	 *
	 * @param Map $criteria
	 * @param SortOptions $sortOptions
	 * @param DataPage $dataPage
	 *
	 * @return DataObjectCollection
	 */
	abstract public function select( Map $criteria, SortOptions $sortOptions, DataPage $dataPage );

	/**
	 * Inserts the given collection of data objects into storage.
	 *
	 * @param DataObjectCollection $objects
	 *
	 * @return DataObjectCollection
	 */
	abstract public function insert( DataObjectCollection $objects );

	/**
	 * Updates the given collection of data objects in storage.
	 *
	 * @param DataObjectCollection $objects
	 *
	 * @return DataObjectCollection
	 */
	abstract public function update( DataObjectCollection $objects );

	/**
	 * Deletes all data objects in given collection from storage.
	 *
	 * @param DataObjectCollection $object
	 *
	 * @return DataObjectCollection
	 */
	abstract public function delete( DataObjectCollection $objects );
}
