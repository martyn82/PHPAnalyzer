<?php
namespace Model\Report;

use Mend\Data\DataObject;
use Mend\Metrics\Project\ProjectReport;
use Mend\Metrics\Project\Project;
use Mend\IO\FileSystem\Directory;
use Mend\Data\Storage\Record;

class Report extends ProjectReport implements DataObject {
	/**
	 * @var string
	 */
	private $identity;

	/**
	 * @var Record
	 */
	private $record;

	/**
	 * Constructs a new Report model.
	 *
	 * @param Record $record
	 */
	public function __construct( Record $record ) {
		$this->record = $record;
	}

	/**
	 * Retrieves the record.
	 *
	 * @return Record
	 */
	public function getRecord() {
		return $this->record;
	}

	/**
	 * @see DataObject::setIdentity()
	 */
	public function setIdentity( $identity ) {
		$this->identity = $identity;
	}

	/**
	 * @see DataObject::getIdentity()
	 */
	public function getIdentity() {
		return $this->identity;
	}

	/**
	 * @see DataObject::toArray()
	 */
	public function toArray() {
		return $this->record->getFields()->toArray();
	}
}
