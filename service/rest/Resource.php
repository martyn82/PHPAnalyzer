<?php
namespace rest;

abstract class Resource {
	/**
	 * Called before method dispatch.
	 */
	public function preDispatch() {
		/* noop */
	}

	/**
	 * Called after method dispatch.
	 */
	public function postDispatch() {
		/* noop */
	}

	protected function readData( $file ) {
		$file = basename( $file );
		$path = realpath( __DIR__ . "/../data" ) . "/{$file}";
		$contents = file_get_contents( $path );
		return json_decode( $contents, true );
	}

	protected function dataIndex( $id ) {
		$path = realpath( __DIR__ . "/../data" );
		$cwd = getcwd();
		chdir( $path );
		$entries = glob( "{$id}_*.json" );
		chdir( $cwd );

		return $entries;
	}

	public function create( array $data ) {}
	public function select( $id, array $properties = array() ) {}
	public function update( $id, array $data ) {}
	public function delete( $id ) {}
}