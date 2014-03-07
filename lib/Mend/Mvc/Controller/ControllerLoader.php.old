<?php
namespace Mend\Mvc\Controller;

use Mend\Collections\Map;

class ControllerLoader {
	const DEFAULT_SUFFIX = 'Controller';

	/**
	 * @var array
	 */
	private $prefixes;

	/**
	 * @var string
	 */
	private $classSuffix;

	/**
	 * Constructs a new controller loader.
	 *
	 * @param array $prefixes
	 */
	public function __construct( array $prefixes, $controllerClassSuffix = self::DEFAULT_SUFFIX ) {
		$this->prefixes = $prefixes;
		$this->classSuffix = $controllerClassSuffix;
	}

	/**
	 * Loads the given controller.
	 *
	 * @param string $controllerName
	 *
	 * @return string
	 *
	 * @throws ControllerException
	 */
	public function getControllerClassName( $controllerName ) {
		$controllerClassName = ucfirst( $controllerName ) . $this->classSuffix;

		foreach ( $this->prefixes as $prefix ) {
			$fullyQualifiedName = $prefix . '\\' . $controllerClassName;

			if ( !class_exists( $fullyQualifiedName, true ) ) {
				continue;
			}

			return $fullyQualifiedName;
		}

		throw new ControllerException( "Controller class does not exist: '{$controllerClassName}'." );
	}
}
