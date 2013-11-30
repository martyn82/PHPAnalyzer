<?php
namespace FileSystem;

class Crawler {
	private $root;
	
	public function __construct( Directory $dir ) {
		$this->root = $dir;
	}
	
	public function getFiles( $pattern = null ) {
		$oldWorkingDir = getcwd();
		
		if ( !is_null( $pattern ) ) {
			$files = $this->getFilesByPattern( $this->root->getName(), $pattern );
		}
		else {
			$files = $this->getFilesFromDir( $this->root->getName() );
		}
		
		chdir( $oldWorkingDir );
		
		return new FileArray(
			array_map(
				function ( $file ) {
					return new File( $file );
				},
				$files
			)
		);
	}
	
	private function getFilesByPattern( $path, $pattern ) {
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
	
	private function getFilesFromDir( $path ) {
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