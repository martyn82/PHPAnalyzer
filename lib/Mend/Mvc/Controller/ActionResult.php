<?php
namespace Mend\Mvc\Controller;

class ActionResult {
	/**
	 * @var mixed
	 */
	private $data;

	/**
	 * @param mixed $data
	 */
	public function __construct( $data ) {
		$this->data = $data;
	}

	/**
	 * Retrieves the data.
	 *
	 * @return mixed
	 */
	public function getData() {
		return $this->data;
	}
}
