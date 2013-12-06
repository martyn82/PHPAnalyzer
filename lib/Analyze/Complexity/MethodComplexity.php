<?php
namespace Analyze\Complexity;

use Model\Method;

class MethodComplexity {
	private $method;
	private $size;
	private $complexity;

	public function __construct( $method, $complexity, $size ) {
		$this->method = (string) $method;
		$this->size = (int) $size;
		$this->complexity = (int) $complexity;
	}

	public function getMethod() {
		return $this->method;
	}

	public function getSize() {
		return $this->size;
	}

	public function getComplexity() {
		return $this->complexity;
	}
}