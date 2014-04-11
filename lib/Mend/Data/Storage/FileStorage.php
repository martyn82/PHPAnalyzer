<?php
namespace Mend\Data\Storage;

use Mend\Collections\Map;
use Mend\Data\DataPage;
use Mend\Data\SortDirection;
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

		if ( $sortOptions->getOptions()->getSize() > 0 ) {
			$records = $this->sortRecords( $records, $sortOptions );
		}

		$totalCount = $records->size();

		return new ResultSet( $records, $dataPage, $totalCount );
	}

	/**
	 * Sorts the record set by sort options.
	 *
	 * @param RecordSet $records
	 * @param SortOptions $sortOptions
	 *
	 * @return RecordSet
	 */
	private function sortRecords( RecordSet $records, SortOptions $sortOptions ) {
		$sortFields = $sortOptions->getOptions();
		$result = $records->toArray();

		foreach ( $sortFields->toArray() as $option ) {
			$direction = reset( $option );
			$field = key( $option );

			usort(
				$result,
				function ( Record $itemA, Record $itemB ) use ( $field, $direction ) {
					$a = $itemA->getValue( $field );
					$b = $itemB->getValue( $field );

					if ( $field == 'dateTime' ) {
						$a = \DateTime::createFromFormat( \DateTime::RFC2822, $a )->getTimestamp();
						$b = \DateTime::createFromFormat( \DateTime::RFC2822, $b )->getTimestamp();
					}

					if ( $a == $b ) {
						return 0;
					}

					if ( $direction == SortDirection::DESCENDING ) {
						return $a > $b ? -1 : 1;
					}

					return $a > $b ? 1 : -1;
				}
			);
		}

		return new RecordSet( $result );
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
