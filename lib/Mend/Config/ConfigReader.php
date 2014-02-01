<?php
namespace Mend\Config;

abstract class ConfigReader {
	/**
	 * Reloads the current configuration.
	 */
	abstract public function reload();

	/**
	 * Reads a configuration entry by name.
	 *
	 * @param string $name
	 *
	 * @return mixed
	 */
	abstract protected function read( $name );

	/**
	 * Reads a configuration value by name.
	 *
	 * @param string $name
	 *
	 * @return mixed
	 */
	public function getValue( $name ) {
		return $this->read( $name );
	}

	/**
	 * Parses a configuration entry key.
	 *
	 * @param string $name
	 *
	 * @return array
	 */
	protected function parseName( $name ) {
		return explode( ':', $name );
	}

	/**
	 * Determines whether the given name exists.
	 *
	 * @param string $name
	 *
	 * @return boolean
	 */
	abstract public function entryExists( $name );
}
