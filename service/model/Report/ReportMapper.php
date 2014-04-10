<?php
namespace Model\Report;

use Mend\Collections\Map;
use Mend\Data\DataMapper;
use Mend\Data\DataObject;
use Mend\Data\DataObjectCollection;
use Mend\Data\DataPage;
use Mend\Data\SortOptions;
use Mend\Data\Storage\Record;
use Mend\IO\FileSystem\Directory;
use Mend\Metrics\Report\ReportType;
use Mend\Metrics\Report\ProjectReportBuilder;
use Mend\Metrics\Complexity\ComplexityReport;
use Mend\Metrics\Report\Partition\MethodPartition;
use Mend\Metrics\Complexity\ComplexityRisk;
use Mend\Source\Code\Model\MethodArray;
use Mend\Source\Code\Model\Method;

class ReportMapper extends DataMapper {
	/**
	 * @see DataMapper::getEntity()
	 */
	protected function getEntity() {
		return 'report';
	}

	/**
	 * @see DataMapper::createDataObjectFromRecord()
	 */
	protected function createDataObjectFromRecord( Record $record ) {
		$report = new Report( $record );
		$report->setIdentity( $record->getValue( 'dateTime' ) );

		return $report;
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
