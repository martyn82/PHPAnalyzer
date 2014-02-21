<?php
namespace Mend\Metrics\Project;

use Mend\IO\FileSystem\Directory;

class Project {
	/**
	 * @var Directory
	 */
	private $root;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $key;

	/**
	 * Creates a new Project instance.
	 *
	 * @param string $name
	 * @param string $key
	 * @param Directory $root
	 */
	public function __construct( $name, $key, Directory $root ) {
		$this->name = (string) $name;
		$this->key = (string) $key;
		$this->root = $root;
	}

	/**
	 * Retrieves the Project's name.
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Retrieves the Project's key.
	 *
	 * @return string
	 */
	public function getKey() {
		return $this->key;
	}

	/**
	 * Retrieves the Project's root directory.
	 *
	 * @return Directory
	 */
	public function getRoot() {
		return $this->root;
	}

	/**
	 * Retrieves the Project's base folder.
	 *
	 * @return string
	 */
	public function getBaseFolder() {
		return $this->root->getBaseName();
	}

	/**
	 * Converts this object to its array representation.
	 *
	 * @return array
	 */
	public function toArray() {
		return array(
			'key' => $this->getKey(),
			'name' => $this->getName(),
			'path' => $this->getRoot()->getName()
		);
	}
}