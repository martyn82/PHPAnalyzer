<?php
namespace Mend\Metrics\Report;

use \Mend\Metrics\Arrayable;

class Project implements Arrayable {
	/**
	 * @var string
	 */
	private $key;

	/**
	 * Constructs a new Project.
	 *
	 * @param string $key
	 */
	public function __construct( $key ) {
		$this->key = $key;
	}

	/**
	 * Retrieves the project key.
	 *
	 * @return string
	 */
	public function getKey() {
		return $this->key;
	}

	/**
	 * Converts this object to array.
	 *
	 * @return array
	 */
	public function toArray() {
		return array(
			'key' => $this->key
		);
	}
}