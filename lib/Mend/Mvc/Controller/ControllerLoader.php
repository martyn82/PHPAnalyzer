<?php
namespace Mend\Mvc\Controller;

use Mend\Collections\Map;
use Mend\Mvc\Controller;
use Mend\Mvc\ControllerException;

class ControllerLoader {
	const DEFAULT_SUFFIX = 'Controller';

	/**
	 * @var array
	 */
	private $mapping;

	/**
	 * @var string
	 */
	private $classSuffix;

	/**
	 * Constructs a new controller loader.
	 *
	 * @param array $mapping
	 */
	public function __construct( array $mapping, $controllerClassSuffix = self::DEFAULT_SUFFIX ) {
		$this->mapping = $mapping;
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

		foreach ( $this->mapping as $prefix ) {
			$fullyQualifiedName = $prefix . '\\' . $controllerClassName;

			if ( !class_exists( $fullyQualifiedName, true ) ) {
				continue;
			}

			return $fullyQualifiedName;
		}

		throw new ControllerException( "Controller does not exist: '{$controllerName}'." );
	}
}
