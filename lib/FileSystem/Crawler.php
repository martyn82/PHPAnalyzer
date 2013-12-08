<?php
namespace FileSystem;

use \Logging\Logger;

class Crawler {
	/**
	 * @var \FileSystem\Directory
	 */
	private $root;

	/**
	 * Constructs a new crawler.
	 *
	 * @param \FileSystem\Directory $dir
	 */
	public function __construct( Directory $dir ) {
		$this->root = $dir;
		Logger::info( "Crawler constructed for <{$dir->getName()}>." );
	}

	/**
	 * Retrieves the files in the directory.
	 *
	 * @param string $pattern
	 *
	 * @return \FileSystem\FileArray
	 */
	public function getFiles( $pattern = null ) {
		Logger::info( "Crawler started, pattern: <{$pattern}>" );

		$oldWorkingDir = getcwd();

		if ( !is_null( $pattern ) ) {
			$files = $this->getFilesByPattern( $this->root->getName(), $pattern );
		}
		else {
			$files = $this->getFilesFromDir( $this->root->getName() );
		}

		Logger::info( "Restore working dir." );

		chdir( $oldWorkingDir );

		$fileCount = count( $files );
		Logger::info( "Crawler done and found <{$fileCount}> files." );

		return new FileArray(
			array_map(
				function ( $file ) {
					return new File( $file );
				},
				$files
			)
		);
	}

	/**
	 * Retrieves files by pattern in the given path.
	 *
	 * @param string $path
	 * @param string $pattern
	 *
	 * @return array
	 */
	private function getFilesByPattern( $path, $pattern ) {
		Logger::info( "Getting files by pattern: <{$pattern}>, in path: <{$path}>." );

		$filePaths = array();
		$entries = scandir( $path );

		foreach ( $entries as $entry ) {
			if ( in_array( $entry, array( '.', '..' ) ) ) {
				continue;
			}

			$entryPath = $path . DIRECTORY_SEPARATOR . $entry;

			if ( is_dir( $entryPath ) ) {
				$filePaths = array_merge( $filePaths, $this->getFilesByPattern( $entryPath, $pattern ) );
			}
		}

		chdir( $path );

		$filePaths = array_merge(
			$filePaths,
			array_map(
				function ( $entry ) use ( $path ) {
					return $path . DIRECTORY_SEPARATOR . $entry;
				},
				glob( $pattern )
			)
		);

		return array_unique( $filePaths );
	}

	/**
	 * Get files from path.
	 *
	 * @param string $path
	 *
	 * @return array
	 */
	private function getFilesFromDir( $path ) {
		Logger::info( "Getting files in path: <{$path}>." );

		$filePaths = array();
		$entries = scandir( $path );

		foreach ( $entries as $entry ) {
			if ( in_array( $entry, array( '.', '..' ) ) ) {
				continue;
			}

			$entryPath = $path . DIRECTORY_SEPARATOR . $entry;

			if ( is_dir( $entryPath ) ) {
				$filePaths = array_merge( $filePaths, $this->getFilesFromDir( $entryPath ) );
			}

			if ( is_file( $entryPath ) ) {
				$filePaths[] = $entryPath;
			}
		}

		return array_unique( $filePaths );
	}
}