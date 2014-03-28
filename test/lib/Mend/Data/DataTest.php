<?php

use Mend\Collections\Map;
use Mend\Collections\AbstractSet;

class DataTest extends \TestCase {
	public function test() {
		$store = new DummyDataStore();
		$mapper = new PersonMapper( $store );
		$repository = new PersonRepository( $mapper );
	}
}

abstract class Repository {
	private $mapper;

	public function __construct( DataMapper $mapper ) {
		$this->mapper = $mapper;
	}

	public function matching( Criterion $criterion, SortOptions $sort, DataPage $page ) {
		return $this->mapper->findAll( $criterion, $sort, $page );
	}
}

abstract class DataMapper {
	private $store;

	public function __construct( DataStore $store ) {
		$this->store = $store;
	}

	abstract protected function getEntity();
	abstract protected function getIdentityField();
	abstract protected function mapToObject( DataRecord $record );
	abstract protected function mapToRecord( DataObject $object );

	public function find( $identity ) {
		/* @var $resultSet ResultSet */
		$resultSet = $this->store->select(
			$this->getEntity(),
			new Equals( $this->getIdentityField(), $identity ),
			new SortOptions(),
			new DataPage( 1 )
		);

		if ( $resultSet->getTotalCount() == 0 ) {
			$entity = $this->getEntity();
			throw new Exception( "Object '{$entity}' not found with identity: '{$identity}'." );
		}

		$records = $resultSet->getRecords();
		$records->rewind();
		$record = $records->current();

		return $this->mapToObject( $record );
	}

	public function findAll( Criterion $criterion, SortOptions $sort, DataPage $page ) {
		$resultSet = $this->store->select(
			$this->getEntity(),
			$criterion,
			$sort,
			$page
		);

		$objects = array();

		foreach ( $resultSet->getRecords() as $record ) {
			$objects[] = $this->mapToObject( $record );
		}

		return $objects;
	}

	public function add( DataObject $object ) {
		$record = $this->mapToRecord( $object );
		$this->store->insert( $this->getEntity(), $record );
	}

	public function update( DataObject $object) {
		$record = $this->mapToRecord( $object );
		$this->store->update( $this->getEntity(), $record );
	}

	public function delete( DataObject $object ) {
		$record = $this->mapToRecord( $object );
		$this->store->delete( $this->getEntity(), $record );
	}
}

abstract class DataStore {
	abstract public function select( $entity, Criterion $criterion, SortOptions $sort, DataPage $page );
	abstract public function insert( $entity, Record $record );
	abstract public function update( $entity, Record $record );
	abstract public function delete( $entity, Record $record );
}

class DummyDataStore extends DataStore {
	public function select( $entity, Criterion $criterion, SortOptions $sort, DataPage $page ) {}
	public function insert( $entity, Record $record ) {}
	public function update( $entity, Record $record ) {}
	public function delete( $entity, Record $record ) {}
}

class ResultSet {
	private $records;
	private $totalCount;

	public function __construct( RecordSet $records, $totalCount ) {
		$this->records = $records;
		$this->totalCount = abs( (int) $totalCount );
	}

	public function getTotalCount() {
		return $this->totalCount;
	}

	public function getRecords() {
		return $this->records;
	}
}

class Record {
	/**
	 * @var Map
	 */
	private $fields;

	/**
	 * Constructs a new Record instance.
	 *
	 * @param Map $fields
	 *
	 * @throws \UnexpectedValueException
	 */
	public function __construct( Map $fields ) {
		if ( $fields->getSize() == 0 ) {
			throw new \UnexpectedValueException( "Argument \$fields cannot be empty." );
		}

		$this->fields = $fields;
	}

	/**
	 * Retrieves the value of given field.
	 *
	 * @param string $field
	 *
	 * @return mixed
	 *
	 * @throws \InvalidArgumentException
	 */
	public function getValue( $field ) {
		if ( !$this->fields->hasKey( $field ) ) {
			throw new \InvalidArgumentException( "Field does not exist in record: '{$field}'." );
		}

		return $this->fields->get( $field );
	}

	/**
	 * Sets a field value.
	 *
	 * @param string $field
	 * @param mixed $value
	 *
	 * @throws \InvalidArgumentException
	 */
	public function setValue( $field, $value ) {
		if ( !$this->fields->hasKey( $field ) ) {
			throw new \InvalidArgumentException( "Field does not exist in record: '{$field}'." );
		}

		$this->fields->set( $field, $value );
	}

	/**
	 * Retrieves all fields.
	 *
	 * @return Map
	 */
	public function getFields() {
		return $this->fields;
	}
}

class RecordSet extends AbstractSet {
}

abstract class Criterion {
}

class Equals extends Criterion {
	private $field;
	private $value;

	public function __construct( $field, $value ) {
		$this->field = $field;
		$this->value = $value;
	}
}

interface Identifyable {
	function getIdentity();
	function setIdentity( $identity );
}

abstract class DataObject implements Identifyable {
	private $identity;
	private $record;

	public function __construct( Record $record ) {
		$this->record = $record;
	}

	public function getRecord() {
		return $this->record;
	}

	public function getIdentity() {
		return $this->identity;
	}

	public function setIdentity( $identity ) {
		$this->identity = $identity;
	}
}

class Person extends DataObject {}

class PersonMapper extends DataMapper {
	protected function getEntity() {
		return 'person';
	}

	protected function getIdentityField() {
		return 'id';
	}

	protected function mapToObject( DataRecord $record ) {
		return new Person( $record );
	}

	protected function mapToRecord( DataObject $object ) {
		return $object->getRecord();
	}
}

class PersonRepository extends Repository {}
