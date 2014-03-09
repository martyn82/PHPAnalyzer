<?php
namespace Mend\Config;

class ConfigProvider {
	/**
	 * @var ConfigReader
	 */
	private $reader;

	/**
	 * Constructs a new configuration provider.
	 *
	 * @param ConfigReader $reader
	 */
	public function __construct( ConfigReader $reader ) {
		$this->reader = $reader;
	}

	/**
	 * Retrieves a string value.
	 *
	 * @param string $name
	 * @param mixed $default
	 *
	 * @return string
	 */
	public function getString( $name, $default = null ) {
		return (string) $this->getValue( $name, $default );
	}

	/**
	 * Retrieves an integer value.
	 *
	 * @param string $name
	 * @param mixed $default
	 *
	 * @return integer
	 */
	public function getInteger( $name, $default = null ) {
		return (int) $this->getValue( $name, $default );
	}

	/**
	 * Retrieves a boolean value.
	 *
	 * @param string $name
	 * @param mixed $default
	 *
	 * @return boolean
	 */
	public function getBoolean( $name, $default = null ) {
		$value = $this->getValue( $name, $default );

		if ( !is_string( $value ) ) {
			return (bool) $value;
		}

		return $value === 'true' || $value === '1';
	}

	/**
	 * Retrieves a float value.
	 *
	 * @param string $name
	 * @param mixed $default
	 *
	 * @return float
	 */
	public function getFloat( $name, $default = null ) {
		return (float) $this->getValue( $name, $default );
	}

	/**
	 * Retrieves an array value.
	 *
	 * @param string $name
	 * @param mixed $default
	 *
	 * @return array
	 */
	public function getArray( $name, $default = null ) {
		$value = $this->getString( $name );

		if ( empty( $value ) ) {
			return array();
		}

		return explode( ',',  $value );
	}

	/**
	 * Retrieves a generic value.
	 *
	 * @param string $name
	 * @param mixed $default
	 *
	 * @return mixed
	 */
	public function getValue( $name, $default = null ) {
		if ( !$this->reader->entryExists( $name ) ) {
			return $default;
		}

		return $this->reader->getValue( $name );
	}
}