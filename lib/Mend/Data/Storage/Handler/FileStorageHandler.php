<?php
namespace Mend\Data\Storage\Handler;

use Mend\Collections\Map;
use Mend\Data\Storage\Record;
use Mend\Data\Storage\RecordSet;
use Mend\IO\FileSystem\Directory;
use Mend\IO\FileSystem\File;
use Mend\IO\FileSystem\FileArray;
use Mend\IO\FileSystem\FileSystem;
use Mend\IO\Stream\FileStreamReader;
use Mend\IO\Stream\FileStreamWriter;

abstract class FileStorageHandler {
	/**
	 * Returns true if given entity exists.
	 *
	 * @param string $entityName
	 *
	 * @return boolean
	 */
	abstract public function entityExists( $entityName );

	/**
	 * Finds a single instance of the given entity.
	 *
	 * @param string $entity
	 * @param string $identity
	 *
	 * @return RecordSet
	 */
	abstract public function find( $entity, $identity = null );

	/**
	 * Saves the given records.
	 *
	 * @param string $entity
	 * @param RecordSet $records
	 *
	 * @return RecordSet
	 */
	abstract public function save( $entity, RecordSet $records );

	/**
	 * Deletes the given records.
	 *
	 * @param string $entity
	 * @param RecordSet $records
	 *
	 * @return RecordSet
	 */
	abstract public function delete( $entity, RecordSet $records );

	/**
	 * Creates a RecordSet from FileArray.
	 *
	 * @param FileArray $files
	 *
	 * @return RecordSet
	 */
	protected function createRecordSetFromFileArray( FileArray $files ) {
		$records = array();

		foreach ( $files as $file ) {
			$records[] = $this->createRecordFromFile( $file );
		}

		return new RecordSet( $records );
	}

	/**
	 * Creates a Record from File.
	 *
	 * @param File $file
	 *
	 * @return Record
	 */
	protected function createRecordFromFile( File $file ) {
		$reader = new FileStreamReader( $file );
		$reader->open();

		$contents = $reader->read();

		$reader->close();

		$data = json_decode( $contents, true );
		$fields = new Map( $data );

		return new Record( $fields );
	}

	/**
	 * Creates a File from Record.
	 *
	 * @param Directory $directory
	 * @param Record $record
	 *
	 * @return File
	 */
	protected function createFileFromRecord( Directory $directory, Record $record ) {
		$data = $record->getFields();
		// @todo abstract the json knowledge
		$contents = json_encode( $data, JSON_NUMERIC_CHECK );

		$identity = !empty( $data[ 'id' ] ) ? $data[ 'id' ] : uniqid( 'record_' );
		$file = new File( $directory->getName() . FileSystem::DIRECTORY_SEPARATOR . $identity . '.json' );

		$writer = new FileStreamWriter( $file );
		$writer->open();

		$writer->write( $contents );

		$writer->close();

		$record->setValue( 'id', $identity );

		return $file;
	}

	/**
	 * Creates a FileArray from RecordSet.
	 *
	 * @param Directory $directory
	 * @param RecordSet $records
	 *
	 * @return FileArray
	 */
	protected function createFileArrayFromRecordSet( Directory $directory, RecordSet $records ) {
		$files = new FileArray();

		foreach ( $records as $record ) {
			$files[] = $this->createFileFromRecord( $directory, $record );
		}

		return $files;
	}

	/**
	 * Deletes the files corresponding to given records.
	 *
	 * @param Directory $directory
	 * @param RecordSet $records
	 */
	protected function deleteRecords( Directory $directory, RecordSet $records ) {
		foreach ( $records as $record ) {
			$file = $this->createFileFromRecord( $directory, $record );
			$file->delete();
		}
	}
}
