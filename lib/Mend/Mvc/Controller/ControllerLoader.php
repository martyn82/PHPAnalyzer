<?php
namespace Mend\Mvc\Controller;

use Mend\Collections\Map;
use Mend\Mvc\Controller;
use Mend\Mvc\ControllerException;

class ControllerLoader {
	/**
	 * @var array
	 */
	private $mapping;

	/**
	 * Constructs a new controller loader.
	 *
	 * @param array $mapping
	 */
	public function __construct( array $mapping ) {
		$this->mapping = $mapping;
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
		$controllerClassName = ucfirst( $controllerName ) . 'Controller';

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
