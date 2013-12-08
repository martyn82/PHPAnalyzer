<?php
namespace Mend\Metrics\Model;

class Model {
	/**
	 * @var array
	 */
	private $ast;

	/**
	 * @var Location
	 */
	private $location;

	/**
	 * Constructs a new model.
	 *
	 * @param Location $location
	 * @param array $ast
	 */
	public function __construct( Location $location, array $ast ) {
		$this->location = $location;
		$this->ast = $ast;
	}

	/**
	 * Retrieves the location.
	 *
	 * @return Location
	 */
	public function getLocation() {
		return $this->location;
	}

	/**
	 * Retrieves the AST.
	 *
	 * @return array
	 */
	public function getAST() {
		return $this->ast;
	}
}