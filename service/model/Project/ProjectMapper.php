<?php
namespace Model\Project;

use Mend\Collections\Map;
use Mend\Data\DataMapper;
use Mend\Data\DataObject;
use Mend\Data\DataObjectCollection;
use Mend\Data\DataPage;
use Mend\Data\SortOptions;
use Mend\Data\Storage\Record;
use Mend\IO\FileSystem\Directory;

class ProjectMapper extends DataMapper {
	/**
	 * @see DataMapper::getEntity()
	 */
	protected function getEntity() {
		return 'project';
	}

	/**
	 * @see DataMapper::createDataObjectFromRecord()
	 */
	protected function createDataObjectFromRecord( Record $record ) {
		$name = $record->getValue( 'name' );
		$key = $record->getValue( 'key' );
		$root = $record->getValue( 'path' );

		$project = new Project( $name, $key, new Directory( $root ) );
		$project->setIdentity( $record->getValue( 'id' ) );

		return $project;
	}

	/**
	 * @see DataMapper::createRecordFromDataObject()
	 */
	protected function createRecordFromDataObject( DataObject $object ) {
		$objectArray = $object->toArray();

		$fields = new Map( $objectArray );
		$fields->set( 'id', $object->getIdentity() );

		return new Record( $fields );
	}

	/**
	 * @see DataMapper::select()
	 */
	public function select( Map $criteria, SortOptions $sortOptions, DataPage $dataPage ) {
		$storage = $this->getStorage();
		$resultSet = $storage->select( $this->getEntity(), $criteria, $sortOptions, $dataPage );

		return $this->createCollectionFromResultSet( $resultSet );
	}

	/**
	 * @see DataMapper::insert()
	 */
	public function insert( DataObjectCollection $objects ) {
		$records = $this->createRecordSetFromCollection( $objects );
		$storage = $this->getStorage();

		$resultSet = $storage->insert( $this->getEntity(), $records );
		return $this->createCollectionFromResultSet( $resultSet );
	}

	/**
	 * @see DataMapper::update()
	 */
	public function update( DataObjectCollection $objects ) {
		$records = $this->createRecordSetFromCollection( $objects );
		$storage = $this->getStorage();

		$resultSet = $storage->update( $this->getEntity(), $records );
		return $this->createCollectionFromResultSet( $resultSet );
	}

	/**
	 * @see DataMapper::delete()
	 */
	public function delete( DataObjectCollection $objects ) {
		$records = $this->createRecordSetFromCollection( $objects );
		$storage = $this->getStorage();

		$resultSet = $storage->delete( $this->getEntity(), $records );
		$records->retainAll( $resultSet->getRecordSet() );

		return $records;
	}
}
