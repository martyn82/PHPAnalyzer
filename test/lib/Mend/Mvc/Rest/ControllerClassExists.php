<?php
namespace Mend\Mvc\Rest;

function class_exists( $class_name, $autoload = null ) {
	return ControllerClassExists::class_exists( $class_name, $autoload );
}

class ControllerClassExists {
	public static $classExistsResult;

	public static function class_exists( $class_name, $autoload = null ) {
		if ( is_null( self::$classExistsResult ) ) {
			return \class_exists( $class_name, $autoload );
		}

		return self::$classExistsResult;
	}
}
