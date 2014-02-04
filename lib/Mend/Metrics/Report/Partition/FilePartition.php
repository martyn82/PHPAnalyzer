<?php
namespace Mend\Metrics\Report\Partition;

use Mend\IO\FileSystem\FileArray;

class FilePartition extends CodePartition {
	/**
	 * @var FileArray
	 */
	private $files;

	/**
	 * Creates an empty partition.
	 *
	 * @return FilePartition
	 */
	public static function createEmpty() {
		return new self( 0, 0, new FileArray() );
	}

	/**
	 * Constructs a new partition.
	 *
	 * @param integer $absolute
	 * @param float $relative
	 * @param FileArray $files
	 */
	public function __construct( $absolute, $relative, FileArray $files ) {
		parent::__construct( $absolute, $relative );
		$this->files = $files;
	}

	/**
	 * Retrieves the files in this partition.
	 *
	 * @return FileArray
	 */
	public function getFiles() {
		return $this->files;
	}

	/**
	 * @see CodePartition::toArray()
	 */
	public function toArray() {
		$result = parent::toArray();
		$files = array();

		foreach ( $this->files as $file ) {
			/* @var $file File */
			$files[] = array(
				'name' => $file->getName()
			);
		}

		$result[ 'files' ] = $files;
		return $result;
	}
}