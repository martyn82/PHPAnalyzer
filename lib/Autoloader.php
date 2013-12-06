<?php
class Autoloader {
	const DIRECTORY_SEPARATOR = DIRECTORY_SEPARATOR;

	private static $ignores = array();
	private static $root;

	public static function register() {
		spl_autoload_register( array( __CLASS__, 'load' ) );
	}

	public static function addIgnorePrefix( $prefix ) {
		self::$ignores[] = $prefix;
	}

	public static function setRootDir( $root ) {
		self::$root = $root;
	}

	public static function load( $className ) {
		if ( self::shouldIgnore( $className ) ) {
			return;
		}

		$path = self::getPath( $className );

		if ( !is_file( $path ) ) {
			throw new \Exception( "Failed to load class <{$className}>." );
		}

		require_once $path;
	}

	private static function getPath( $className ) {
		$parts = explode( "\\", $className );
		return self::$root
			. self::DIRECTORY_SEPARATOR
			. implode( self::DIRECTORY_SEPARATOR, $parts )
			. ".php";
	}

	private static function shouldIgnore( $className ) {
		foreach ( self::$ignores as $ignorePrefix ) {
			if ( strpos( $className, $ignorePrefix ) === 0 ) {
				return true;
			}
		}

		return false;
	}
}