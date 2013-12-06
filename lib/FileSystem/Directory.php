<?php
namespace FileSystem;

class Directory {
	private $location;
	
	public function __construct( $location ) {
		if ( !is_dir( $location ) ) {
			throw new \Exception( "Given location is not a directory." );
		}
		
		$this->location = $location;
	}
	
	public function getName() {
		return $this->location;
	}
	
	public function getFiles( $pattern = null ) {
		$oldWorkingDir = getcwd();
		chdir( $this->location );
		
		$files = array();
		
		if ( is_null( $pattern ) ) {
			$files = $this->getFileEntries();
		}
		else {
			$files = $this->getFilesByPattern( $pattern );
		}
		
		chdir( $oldWorkingDir );
		
		return new FileArray( $files );
	}
	
	private function getFileEntries() {
		$self = $this;
		return array_map(
			function ( $entry ) use ( $self ) {
				return new File( $self->location . DIRECTORY_SEPARATOR . $entry );
			},
			array_filter(
				scandir( $this->location ),
				function ( $entry ) {
					return is_file( $entry );
				}
			)
		);
	}
	
	private function getDirectoryEntries() {
		return array_filter(
			scandir( $this->location ),
			function ( $entry ) {
				return is_dir( $entry );
			}
		);
	}
	
	private function getFilesByPattern( $pattern ) {
		$self = $this;
		return array_map(
			function ( $entry ) use ( $self ) {
				return new File( $self->location . DIRECTORY_SEPARATOR . $entry );
			},
			glob( $pattern )
		);
	}
}