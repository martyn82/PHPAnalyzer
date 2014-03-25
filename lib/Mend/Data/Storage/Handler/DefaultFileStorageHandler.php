<?php
namespace Mend\Data\Storage\Handler;

use Mend\IO\FileSystem\Directory;
use Mend\IO\FileSystem\File;
use Mend\IO\FileSystem\FileArray;
use Mend\IO\FileSystem\FileSystem;
use Mend\Data\Storage\Record;
use Mend\Data\Storage\RecordSet;
use Mend\Data\Storage\ResultSet;
use Mend\Data\DataPage;

class DefaultFileStorageHandler extends FileStorageHandler {
	/**
	 * @var EntityMap
	 */
	private $entities;

	/**
	 * Constructs a new DefaultFileStorageHandler instance.
	 *
	 * @param EntityMap $entities
	 */
	public function __construct( EntityMap $entities ) {
		$this->entities = $entities;
	}

	/**
	 * @see FileStorageHandler::find()
	 *
	 * @throws \InvalidArgumentException
	 */
	public function find( $entity, $identity = null ) {
		if ( !$this->entityExists( $entity ) ) {
			throw new \InvalidArgumentException( "Unknown entity: '{$entity}'." );
		}

		$entityDirectory = $this->getEntityPath( $entity );
		$iterator = $entityDirectory->iterator();

		$files = new FileArray();
		$fileExtension = 'json';

		foreach ( $iterator as $entry ) {
			// @todo: The extension of JSON is a separate abstraction
			if ( !$entry->isFile() || $entry->getExtension() != $fileExtension ) {
				continue;
			}

			if ( $entry->getSize() == 0 ) {
				continue;
			}

			if ( is_null( $identity ) ) {
				$files[] = $this->createFile( $entry );
			}
			else if ( $entry->getFilename() == "{$identity}.{$fileExtension}" ) {
				$files[] = $this->createFile( $entry );
				break;
			}
		}

		return $this->createRecordSetFromFileArray( $files );
	}

	/**
	 * Creates a File object from iterator.
	 *
	 * @param \DirectoryIterator $iterator
	 *
	 * @return File
	 */
	private function createFile( \DirectoryIterator $iterator ) {
		return new File( $iterator->getPath() . FileSystem::DIRECTORY_SEPARATOR . $iterator->getFilename() );
	}

	/**
	 * @see FileStorageHandler::save()
	 *
	 * @throws \InvalidArgumentException
	 */
	public function save( $entity, RecordSet $records ) {
		if ( !$this->entityExists( $entity ) ) {
			throw new \InvalidArgumentException( "Unknown entity: '{$entity}'." );
		}

		$directory = $this->getEntityPath( $entity );
		$files = $this->createFileArrayFromRecordSet( $directory, $records );

		return $this->createRecordSetFromFileArray( $files );
	}

	/**
	 * @see FileStorageHandler::delete()
	 *
	 * @throws \InvalidArgumentException
	 */
	public function delete( $entity, RecordSet $records ) {
		if ( !$this->entityExists( $entity ) ) {
			throw new \InvalidArgumentException( "Unknown entity: '{$entity}'." );
		}

		$directory = $this->getEntityPath( $entity );
		$this->deleteRecords( $directory, $records );

		return new RecordSet( array() );
	}

	/**
	 * @see FileStorageHandler::entityExists()
	 */
	public function entityExists( $entityName ) {
		return $this->entities->hasKey( $entityName );
	}

	/**
	 * Retrieves the path to the given entity.
	 *
	 * @param string $entity
	 *
	 * @return Directory
	 *
	 * @throws \InvalidArgumentException
	 */
	private function getEntityPath( $entity ) {
		return $this->entities->get( $entity );
	}
}
