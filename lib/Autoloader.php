<?php
class Autoloader {
	/**
	 * @var string
	 */
	const DIRECTORY_SEPARATOR = DIRECTORY_SEPARATOR;

	/**
	 * @var string
	 */
	const NAMESPACE_SEPARATOR = '\\';

	/**
	 * @var array
	 */
	private $prefixes = array();

	/**
	 * Registers this autoloader.
	 */
	public function register() {
		spl_autoload_register( array( $this, 'loadClass' ) );
	}

	/**
	 * Adds a namespace to the loader.
	 *
	 * @param string $prefix
	 * @param string $baseDir
	 * @param boolean $prepend
	 */
	public function addNamespace( $prefix, $baseDir, $prepend = false ) {
		$prefix = trim( $prefix, self::NAMESPACE_SEPARATOR ) . self::NAMESPACE_SEPARATOR;
		$baseDir = rtrim( $baseDir, self::DIRECTORY_SEPARATOR ) . self::DIRECTORY_SEPARATOR;

		if ( !isset( $this->prefixes[ $prefix ] ) ) {
			$this->prefixes[ $prefix ] = array();
		}

		if ( $prepend ) {
			array_unshift( $this->prefixes[ $prefix ], $baseDir );
			return;
		}

		array_push( $this->prefixes[ $prefix ], $baseDir );
	}

	/**
	 * Loads the class.
	 *
	 * @param string $className
	 *
	 * @return boolean
	 */
	public function loadClass( $className ) {
		$prefix = $className;

		while ( $pos = strrpos( $prefix, self::NAMESPACE_SEPARATOR ) ) {
			$prefix = substr( $className, 0, $pos + 1 );
			$relativeClass = substr( $className, $pos + 1 );

			if ( $this->loadMappedFile( $prefix, $relativeClass ) ) {
				return true;
			}

			$prefix = rtrim( $prefix, self::NAMESPACE_SEPARATOR );
		}

		return false;
	}

	/**
	 * Loads class file.
	 *
	 * @param string $prefix
	 * @param string $className
	 *
	 * @return boolean
	 */
	private function loadMappedFile( $prefix, $className ) {
		if ( !isset( $this->prefixes[ $prefix ] ) ) {
			return false;
		}

		foreach ( $this->prefixes[ $prefix ] as $baseDir ) {
			$fileName = $baseDir
				. str_replace( self::NAMESPACE_SEPARATOR, self::DIRECTORY_SEPARATOR, $className )
				. '.php';

			if ( $this->includeFile( $fileName ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Includes the file.
	 *
	 * @param string $fileName
	 *
	 * @return boolean
	 */
	protected function includeFile( $fileName ) {
		if ( !\file_exists( $fileName ) ) {
			return false;
		}

		$dir = \realpath( dirname( $fileName ) );
		require $dir . self::DIRECTORY_SEPARATOR . basename( $fileName );
		return true;
	}
}