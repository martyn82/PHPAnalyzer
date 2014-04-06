<?php
namespace Mend\Data\Storage;

use Mend\Collections\Map;
use Mend\Data\DataPage;
use Mend\Data\SortOptions;
use Mend\Data\Storage\Handler\FileStorageHandler;

class FileStorage extends Storage {
	/**
	 * @var FileStorageHandler
	 */
	private $handler;

	/**
	 * Constructs a new FileStorage instance.
	 *
	 * @param FileStorageHandler $handler
	 */
	public function __construct( FileStorageHandler $handler ) {
		$this->handler = $handler;
	}

	/**
	 * @see Storage::select()
	 *
	 * @throws \InvalidArgumentException
	 */
	public function select( $entity, Map $criteria, SortOptions $sortOptions, DataPage $dataPage ) {
		if ( !$this->handler->entityExists( $entity ) ) {
			throw new \InvalidArgumentException( "Entity does not exist: '{$entity}'." );
		}

		$identity = null;

		if ( $criteria->hasKey( 'id' ) ) {
			$identity = $criteria->get( 'id' );
		}

		$records = $this->handler->find( $entity, $identity );
		$totalCount = $records->size();

		return new ResultSet( $records, $dataPage, $totalCount );
	}

	/**
	 * @see Storage::insert()
	 *
	 * @throws \InvalidArgumentException
	 */
	public function insert( $entity, RecordSet $records ) {
		if ( !$this->handler->entityExists( $entity ) ) {
			throw new \InvalidArgumentException( "Entity does not exist: '{$entity}'." );
		}

		$records = $this->handler->save( $entity, $records );
		$totalCount = $records->size();

		return new ResultSet( $records, new DataPage(), $totalCount );
	}

	/**
	 * @see Storage::update()
	 *
	 * @throws \InvalidArgumentException
	 */
	public function update( $entity, RecordSet $records ) {
		if ( !$this->handler->entityExists( $entity ) ) {
			throw new \InvalidArgumentException( "Entity does not exist: '{$entity}'." );
		}

		$records = $this->handler->save( $entity, $records );
		$totalCount = $records->size();

		return new ResultSet( $records, new DataPage(), $totalCount );
	}

	/**
	 * @see Storage::delete()
	 *
	 * @throws \InvalidArgumentException
	 */
	public function delete( $entity, RecordSet $records ) {
		if ( !$this->handler->entityExists( $entity ) ) {
			throw new \InvalidArgumentException( "Entity does not exist: '{$entity}'." );
		}

		$records = $this->handler->delete( $entity, $records );
		$totalCount = $records->size();

		return new ResultSet( $records, new DataPage(), $totalCount );
	}
}
