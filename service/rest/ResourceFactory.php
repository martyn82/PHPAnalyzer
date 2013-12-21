<?php
namespace rest;

class ResourceFactory {
	/**
	 * Creates a resource class from string.
	 *
	 * @param string $resource
	 *
	 * @return Resource
	 *
	 * @throws \Exception
	 */
	public static function createResourceFromString( $resource ) {
		$resourceClass = self::getResourceClassName( $resource );

		if ( !class_exists( $resourceClass ) ) {
			throw new \Exception( "No such resource: <{$resource}>." );
		}

		return new $resourceClass();
	}

	/**
	 * Retrieves the class name for given resource.
	 *
	 * @param string $resource
	 *
	 * @return string
	 */
	private static function getResourceClassName( $resource ) {
		$suffix = 'Resource';
		$class = ucfirst( $resource ) . $suffix;
		$namespace = 'resource';
		return implode( "\\", array( $namespace, $class ) );
	}
}