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
	 *
	 * @return string
	 */
	public function getString( $name ) {
		return (string) $this->reader->getValue( $name );
	}

	/**
	 * Retrieves an integer value.
	 *
	 * @param string $name
	 *
	 * @return integer
	 */
	public function getInteger( $name ) {
		return (int) $this->reader->getValue( $name );
	}

	/**
	 * Retrieves a boolean value.
	 *
	 * @param string $name
	 *
	 * @return boolean
	 */
	public function getBoolean( $name ) {
		$value = $this->reader->getValue( $name );

		if ( !is_string( $value ) ) {
			return (bool) $value;
		}

		return $value !== 'false';
	}

	/**
	 * Retrieves a float value.
	 *
	 * @param string $name
	 *
	 * @return float
	 */
	public function getFloat( $name ) {
		return (float) $this->reader->getValue( $name );
	}

	/**
	 * Retrieves an array value.
	 *
	 * @param string $name
	 *
	 * @return array
	 */
	public function getArray( $name ) {
		return explode( ',', $this->getString( $name ) );
	}

	/**
	 * Retrieves a generic value.
	 *
	 * @param string $name
	 *
	 * @return mixed
	 */
	public function getValue( $name ) {
		return $this->reader->getValue( $name );
	}
}