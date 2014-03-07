<?php
namespace Mend\Mvc;

use Mend\RClass;
use Mend\Mvc\Controller;
use Mend\Mvc\Controller\PageController;
use Mend\Network\Web\WebRequest;
use Mend\Network\Web\WebResponse;

class ControllerFactory {
	/**
	 * @var array
	 */
	private $namespaces;

	/**
	 * @var string
	 */
	private $suffix;

	/**
	 * @var RClass
	 */
	private $class;

	/**
	 * Constructs a new ControllerFactory instance.
	 *
	 * @param array $namespaces
	 * @param string $suffix
	 * @param RClass $class
	 */
	public function __construct( array $namespaces, $suffix = null, RClass $class = null ) {
		$this->namespaces = $namespaces;
		$this->suffix = $suffix;
		$this->class = $class ? : new RClass();
	}

	/**
	 * Creates a Controller instance by name.
	 *
	 * @param string $controllerName
	 * @param WebRequest $request
	 * @param WebResponse $response
	 *
	 * @return PageController
	 *
	 * @throws \Exception
	 */
	public function createController( $controllerName, WebRequest $request, WebResponse $response ) {
		$controllerClassName = $this->getControllerClassByName( $controllerName );

		if ( is_null( $controllerClassName ) ) {
			throw new \Exception( "No such controller: '{$controllerName}'." );
		}

		if ( !$this->class->isSubclassOf( $controllerClassName, '\Mend\Mvc\Controller\PageController' ) ) {
			throw new \Exception( "Controller '{$controllerClassName}' must be an instance of PageController." );
		}

		return new $controllerClassName( $request, $response, $this );
	}

	/**
	 * Retrieves controller class name by name.
	 *
	 * @param string $controllerName
	 *
	 * @return string
	 */
	public function getControllerClassByName( $controllerName ) {
		$controllerClassName = ucfirst( $controllerName ) . $this->suffix;

		foreach ( $this->namespaces as $prefix ) {
			$fullyQualifiedName = $prefix . '\\' . $controllerClassName;

			if ( !$this->class->exists( $fullyQualifiedName, true ) ) {
				continue;
			}

			return $fullyQualifiedName;
		}

		return null;
	}

	/**
	 * Retrieves controller name by class name.
	 *
	 * @param string $controllerClassName
	 *
	 * @return string
	 */
	public function getControllerNameByClass( $controllerClassName ) {
		$classParts = explode( '\\', $controllerClassName );
		$className = end( $classParts );

		if ( is_null( $this->suffix ) ) {
			$controllerName = $className;
		}
		else {
			$controllerName = substr( $className, 0, strrpos( $className, $this->suffix ) );
		}

		return strtolower( $controllerName );
	}
}
