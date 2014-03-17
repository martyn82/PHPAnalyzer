<?php
namespace Mend\Data\Storage;

use Mend\IO\FileSystem\Directory;

class FileStorage extends Storage {
	/**
	 * @var Directory
	 */
	private $directory;

	/**
	 * Constructs a new FileStorage instance.
	 *
	 * @param Directory $directory
	 */
	public function __construct( Directory $directory ) {
		$this->directory = $directory;
	}

	/**
	 * @see Storage::read()
	 */
	public function read( $type, $id ) {
		$dataDir = $this->directory;
		$stream = new DirectoryStream( $dataDir );
		$dirIterator = $stream->getIterator();
		$dataFiles = new FileArray();

		foreach ( $dirIterator as $iterator ) {
			if ( !$iterator->isFile() || $iterator->getExtension() != 'json' ) {
				continue;
			}

			if ( !is_null( $id ) && substr( $iterator->getFilename(), 0, strlen( $id ) ) != $id ) {
				continue;
			}

			if ( $iterator->getSize() == 0 ) {
				continue;
			}

			$dataFiles[] = new File(
				$iterator->getPath()
				. FileSystem::DIRECTORY_SEPARATOR
				. $iterator->getFilename()
			);
		}

		$projects = array();

		foreach ( $dataFiles as $file ) {
			/* @var $file File */
			$reader = new FileStreamReader( $file );
			$reader->open();
			$contents = $reader->read();
			$reader->close();

			$report = json_decode( $contents, true );

			if ( !isset( $projects[ $report[ 'project' ][ 'key' ] ] ) ) {
				$projects[ $report[ 'project' ][ 'key' ] ] = array( $report );
			}
			else {
				$projects[ $report[ 'project' ][ 'key' ] ][] = $report;
			}
		}

		return $projects;
	}

	public function create() {}
	public function update() {}
	public function delete() {}
	public function search() {}

	private function sort() {}
}
