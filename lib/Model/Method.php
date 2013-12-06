<?php
namespace Model;

class Method {
	private $inner;
	private $model;

	public function __construct( \PHPParser_Node $value, ModelTree $model ) {
		$this->inner = $value;
		$this->model = $model;
	}

	public function getNode() {
		return $this->inner;
	}

	public function getModel() {
		return $this->model;
	}

	public function getName() {
		return $this->inner->name;
	}

	public function getStartLine() {
		return $this->inner->getAttribute( 'startLine' );
	}

	public function getEndLine() {
		return $this->inner->getAttribute( 'endLine' );
	}
}