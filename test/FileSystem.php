<?php

$wrappers = stream_get_wrappers();

if ( !in_array( FileSystem::PROTOCOL, $wrappers ) ) {
	stream_wrapper_register( FileSystem::PROTOCOL, 'FileSystem' );
}

class FileSystem {
	const PROTOCOL = 'test';
	const DIR_MODE = 040777;
	const FILE_MODE = 0100666;

	private static $fopenResult = true;
	private static $freadResult = null;
	private static $filesize = 1;
	private static $opendirResult = true;
	private static $readdirResult = array( '.' => self::DIR_MODE, '..' => self::FILE_MODE );

	private static $dirCursor = 0;

	/**
	 * @var resource
	 */
	public $context;

	/**
	 * Resets custom results to their defaults.
	 */
	public static function resetResults() {
		self::$fopenResult = true;
		self::$freadResult = null;
		self::$filesize = 1;
		self::$opendirResult = true;
		self::$readdirResult = array( '.' => self::DIR_MODE, '..' => self::FILE_MODE );

		self::$dirCursor = 0;
	}

	/**
	 * Sets fopen result.
	 *
	 * @param mixed $result
	 */
	public static function setFOpenResult( $result ) {
		self::$fopenResult = $result;
	}

	/**
	 * Sets fread result.
	 *
	 * @param mixed $result
	 */
	public static function setFReadResult( $result ) {
		self::$freadResult = $result;

		if ( is_null( $result ) ) {
			self::$filesize = 1;
		}
		else {
			self::$filesize = strlen( $result );
		}
	}

	public static function setReadDirResult( array $result ) {
		self::$readdirResult = $result;
	}

	public function stream_open( $path, $mode, $options, & $openedPath ) {
		return self::$fopenResult;
	}

	public function stream_read( $count ) {
		return self::$freadResult;
	}

	public function url_stat( $path, $flags ) {
		return array(
			'size' => self::$filesize,
			'mode' => ( isset( self::$readdirResult[ basename( $path ) ] )
				? self::$readdirResult[ basename( $path ) ]
				: null )
		);
	}

	public function dir_readdir() {
		if ( count( self::$readdirResult ) == 0 || self::$dirCursor > count( self::$readdirResult ) - 1 ) {
			return false;
		}

		$slice = array_slice( array_keys( self::$readdirResult ), self::$dirCursor++, 1 );
		return reset( $slice );
	}

	public function dir_opendir( $path, $options ) {
		return self::$opendirResult;
	}

	public function dir_rewinddir() {}
	public function dir_closedir() {}

	public function stream_write( $data ) {}
	public function stream_tell() {}
	public function stream_eof() {}
	public function stream_seek( $offset, $whence ) {}
	public function stream_metadata( $path, $option, $var ) {}
	public function stream_truncate( $newSize ) {}
	public function stream_stat() {}
	public function stream_set_option( $option, $arg1, $arg2 ) {}
	public function stream_lock( $operation ) {}
	public function stream_flush() {}
	public function stream_close() {}
	public function stream_cast() {}

	public function rename( $pathFrom, $pathTo ) {}
	public function mkdir( $path, $mode, $options ) {}
	public function rmdir( $path, $options ) {}
	public function unlink( $path ) {}
}
