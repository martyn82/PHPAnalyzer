<?php
require_once realpath( __DIR__ ) . "/ClassNotFoundException.php";

class Autoloader {
	/**
	 * @var string
	 */
	const DIRECTORY_SEPARATOR = DIRECTORY_SEPARATOR;

	/**
	 * @var array
	 */
	private static $namespaces = array();

	/**
	 * Registers itself to SPL.
	 */
	public static function enable() {
		spl_autoload_register( array( __CLASS__, 'load' ) );
	}

	/**
	 * Registers a namespace to a path.
	 *
	 * @param string $namespace
	 * @param string $path
	 *
	 * @throws \Exception
	 */
	public static function registerNamespace( $namespace, $path ) {
		if ( isset( self::$namespaces[ $namespace ] ) ) {
			throw new \Exception( "The namespace <{$namespace}> is already registered." );
		}

		self::$namespaces[ $namespace ] = realpath( $path );
	}

	/**
	 * Load the given class.
	 *
	 * @param string $className
	 *
	 * @throws \ClassNotFoundException
	 */
	public static function load( $className ) {
		$ns = self::getNamespace( $className );

		if ( !self::isNamespaceRegistered( $ns ) ) {
			return;
		}

		$path = self::getPath( $className );

		if ( !is_file( $path ) ) {
			throw new \ClassNotFoundException( "Failed to load class <{$className}> at <{$path}>." );
		}

		require_once $path;
	}

	/**
	 * Determines whether the namespace was registered.
	 *
	 * @param string $namespace
	 *
	 * @return boolean
	 */
	private static function isNamespaceRegistered( $namespace ) {
		return isset( self::$namespaces[ $namespace ] );
	}

	/**
	 * Retrieves the namespace of the given class name.
	 *
	 * @param string $className
	 *
	 * @return string
	 */
	private static function getNamespace( $className ) {
		$parts = explode( "\\", $className );
		return reset( $parts );
	}

	/**
	 * Retrieves the path to the namespace.
	 *
	 * @param string $namespace
	 *
	 * @return string
	 */
	private static function getNamespacePath( $namespace ) {
		return self::$namespaces[ $namespace ];
	}

	/**
	 * Creates the full path to the class.
	 *
	 * @param string $className
	 *
	 * @return string
	 */
	private static function getPath( $className ) {
		$ns = self::getNamespace( $className );
		$root = self::getNamespacePath( $ns );

		$parts = explode( "\\", $className );
		array_shift( $parts );

		return realpath( $root )
			. self::DIRECTORY_SEPARATOR
			. implode( self::DIRECTORY_SEPARATOR, $parts )
			. ".php";
	}

	/**
	 * Determines whether the given class should not be loaded.
	 *
	 * @param string $className
	 *
	 * @return boolean
	 */
	private static function shouldIgnore( $className ) {
		foreach ( self::$ignores as $ignorePrefix ) {
			if ( strpos( $className, $ignorePrefix ) === 0 ) {
				return true;
			}
		}

		return false;
	}
}