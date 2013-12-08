<?php
namespace Metrics\Model;

class Model {
	/**
	 * @var array
	 */
	private $ast;

	/**
	 * @var \Metrics\Model\Location
	 */
	private $location;

	/**
	 * Constructs a new model.
	 *
	 * @param \Metrics\Model\Location $location
	 * @param array $ast
	 */
	public function __construct( Location $location, array $ast ) {
		$this->location = $location;
		$this->ast = $ast;
	}

	/**
	 * Retrieves the location.
	 *
	 * @return \Metrics\Model\Location
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