<?php
namespace Mend\Metrics\Report;

use \Mend\Metrics\Arrayable;

class Project implements Arrayable {
	/**
	 * @var string
	 */
	private $key;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $path;

	/**
	 * Constructs a new Project.
	 *
	 * @param string $key
	 * @param string $name
	 * @param string $path
	 */
	public function __construct( $key, $name, $path ) {
		$this->key = (string) $key;
		$this->name = (string) $name;
		$this->path = (string) $path;
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
	 * Retrieves the name of the project.
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Retrieves the path.
	 *
	 * @return string
	 */
	public function getPath() {
		return $this->path;
	}

	/**
	 * Converts this object to array.
	 *
	 * @return array
	 */
	public function toArray() {
		return array(
			'key' => $this->key,
			'name' => $this->name,
			'path' => $this->path
		);
	}
}