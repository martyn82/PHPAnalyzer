<?php
namespace Mend\Data;

use Mend\Collections\Map;
use Mend\IO\FileSystem\Directory;

class DataMapperTest extends \TestCase {
	public function test() {
		$repository = new DummyRepository(
			new DummyDataMapper(
				new DummyStorage(
					new Directory( '/Users/martijn/Projects/PHPAnalyzer/service/data' )
				)
			)
		);

		$object = $repository->get( 1 );
		print_r($object);

		$objects = $repository->find( new Map(), new SortOptions(), new DataPage() );
		print_r($objects);
	}
}

abstract class DomainObject {
	private $identity;

	public function setId( $identity ) {
		$this->identity = $identity;
	}

	public function getId() {
		return $identity;
	}
}
class Dummy extends DomainObject {
	private $key;
	private $name;
	private $root;

	public function setKey( $value ) {
		$this->key = $value;
	}

	public function getKey() {
		return $this->key;
	}

	public function setName( $value ) {
		$this->name = $value;
	}

	public function getName() {
		return $this->value;
	}

	public function setRoot( $value ) {
		$this->root = $value;
	}

	public function getRoot() {
		return $this->root;
	}
}

abstract class Repository {
	private $mapper;

	public function __construct( DataMapper $mapper ) {
		$this->mapper = $mapper;
	}

	protected function getMapper() {
		return $this->mapper;
	}

	abstract protected function getIdentityField();

	public function get( $identity ) {
		$mapper = $this->getMapper();

		/* @var $result ResultSet */
		$result = $mapper->select(
			new Map(
				array( $this->getIdentityField() => $identity )
			),
			new SortOptions(),
			new DataPage( 1 )
		);

		if ( $result->getTotalCount() != 1 ) {
			throw new \Exception( $result->getTotalCount() . " results found." );
		}

		$iterator = $result->getRecordSet()->iterator();
		$first = reset( $iterator );

		return $first->getDomainObject();
	}

	public function add( DomainObject $object ) {}

	public function set( DomainObject $object ) {}

	public function delete( DomainObject $object ) {}

	public function find( Map $criteria, SortOptions $sortOptions, DataPage $page ) {
		$mapper = $this->getMapper();
		return $mapper->select( $criteria, $sortOptions, $page );
	}
}

class DummyRepository extends Repository {
	private static $identityField = 'id';

	protected function getIdentityField() {
		return self::$identityField;
	}
}

abstract class DataMapper {
	private $storage;

	public function __construct( Storage $storage ) {
		$this->storage = $storage;
	}

	protected function getStorage() {
		return $this->storage;
	}

	abstract public function select( Map $criteria, SortOptions $sort, DataPage $page );
	abstract public function insert( Map $fields );
	abstract public function update( Map $fields, $identity );
	abstract public function delete( $identity );
}

class DummyDataMapper extends DataMapper {
	private static $entity = 'dummy';

	public function select( Map $criteria, SortOptions $sort, DataPage $page ) {
		$storage = $this->getStorage();

		/* @var $result ResultSet */
		$result = $storage->select( self::$entity, $criteria, $sort, $page );

		$records = $result->getRecordSet();
		$iterator = $records->iterator();
		$domainRecords = array();

		foreach ( $iterator as $record ) {
			$domainRecord = new DomainRecord( $record );

			$dummy = new Dummy();
			$dummy->setKey( $record[ 'key' ] );
			$dummy->setName( $record[ 'name' ] );
			$dummy->setRoot( $record[ 'root' ] );
			$dummy->setId( $record[ 'id' ] );

			$domainRecord->setDomainObject( $dummy );
			$domainRecords[] = $domainRecord;
		}

		return new ResultSet( new RecordSet( $domainRecords ), $result->getDataPage(), $result->getTotalCount() );
	}

	public function insert( Map $fields ) {
		$storage = $this->getStorage();
		$storage->insert( self::$entity, $fields );
	}

	public function update( Map $fields, $identity ) {
		$storage = $this->getStorage();
		$storage->update( self::$entity, $fields, $identity );
	}

	public function delete( $identity ) {
		$storage = $this->getStorage();
		$storage->delete( self::$entity, $identity );
	}
}

abstract class Storage {
	/**
	 * Creates the entity with given fields map.
	 *
	 * @param string $entity
	 * @param Map $fields
	 *
	 * @return string
	 */
	abstract public function insert( $entity, Map $fields );

	/**
	 * Updates the entity with given fields map and identity.
	 *
	 * @param string $entity
	 * @param Map $fields
	 * @param string $identity
	 *
	 * @return ResultSet
	 */
	abstract public function update( $entity, Map $fields, $identity );

	/**
	 * Deletes the entity with given identity.
	 *
	 * @param string $entity
	 * @param string $identity
	 *
	 * @return ResultSet
	 */
	abstract public function delete( $entity, $identity );

	/**
	 * Searches the entity for given criteria.
	 *
	 * @param string $entity
	 * @param Map $criteria
	 * @param SortOptions $sortOptions
	 * @param DataPage $dataPage
	 *
	 * @return ResultSet
	 */
	abstract public function select( $entity, Map $criteria, SortOptions $sortOptions, DataPage $dataPage );
}

class DummyStorage extends Storage {
	public function select( $entity, Map $criteria, SortOptions $sortOptions, DataPage $dataPage ) {
		return new ResultSet(
			new RecordSet( array( array( 'id' => 1, 'key' => 'foo', 'name' => 'bar', 'root' => 'baz' ) ) ),
			new DataPage( 1 ),
			1
		);
	}

	public function insert( $entity, Map $fields ) {}
	public function update( $entity, Map $fields, $identity ) {}
	public function delete( $entity, $identity ) {}
}

class RecordSet {
	private $records;

	public function __construct( array $records ) {
		$this->records = $records;
	}

	public function iterator() {
		return new \ArrayIterator( $this->records );
	}
}

class Record {
	private $row;

	public function __construct( array $row ) {
		$this->row = $row;
	}

	public function getRow() {
		return $this->row;
	}
}

class DomainRecord extends Record {
	private $domainObject;

	public function setDomainObject( DomainObject $object ) {
		$this->domainObject = $object;
	}

	public function getDomainObject() {
		return $this->domainObject;
	}
}

class ResultSet {
	private $records;
	private $totalCount;
	private $dataPage;

	public function __construct( RecordSet $records, DataPage $dataPage, $totalCount ) {
		$this->records = $records;
		$this->dataPage = $dataPage;
		$this->totalCount = (int) $totalCount;
	}

	public function getTotalCount() {
		return $this->totalCount;
	}

	public function getDataPage() {
		return $this->dataPage;
	}

	public function getRecordSet() {
		return $this->records;
	}
}
