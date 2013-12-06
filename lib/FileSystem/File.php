<?php
namespace FileSystem;

class File {
	private $location;
	
	public function __construct( $location ) {
		if ( !is_file( $location ) ) {
			throw new \Exception( "Given location is not a valid file." );
		}
		
		$this->location = $location;
	}
	
	public function getName() {
		return $this->location;
	}
	
	public function getExtension() {
		$parts = explode( ".", $this->location );
		return end( $parts );
	}
	
	public function getContents() {
		if ( !is_readable( $this->location ) ) {
			throw new \Exception( "File is not readable." );
		}
		
		return file_get_contents( $this->location );
	}
}