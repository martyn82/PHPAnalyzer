<?php
namespace Mend\Config;

class ArrayConfigReader extends ConfigReader {
	/**
	 * @var array
	 */
	private $settings;

	/**
	 * Constructs a new array configuration reader.
	 *
	 * @param array $settings
	 */
	public function __construct( array $settings ) {
		$this->settings = $settings;
	}

	/**
	 * Sets the settings.
	 *
	 * @param array $settings
	 */
	protected function setSettings( array $settings ) {
		$this->settings = $settings;
	}

	/**
	 * @see ConfigReader::reload()
	 */
	public function reload() {
		// no-op
	}

	/**
	 * @see ConfigReader::read()
	 *
	 * @throws ConfigurationException
	 */
	protected function read( $name ) {
		$nameParts = $this->parseName( $name );

		if ( !isset( $this->settings[ $nameParts[ 0 ] ] ) ) {
			throw new ConfigurationException( "Configuration entry not found: '{$name}'." );
		}

		if ( count( $nameParts ) > 1 && !isset( $this->settings[ $nameParts[ 0 ] ][ $nameParts[ 1 ] ] ) ) {
			throw new ConfigurationException( "Configuration entry not found: '{$name}'." );
		}
		else if ( count( $nameParts ) > 1 ) {
			return $this->settings[ $nameParts[ 0 ] ][ $nameParts[ 1 ] ];
		}

		return $this->settings[ $name ];
	}

	/**
	 * @see ConfigReader::entryExists()
	 */
	public function entryExists( $name ) {
		$nameParts = $this->parseName( $name );

		if ( count( $nameParts ) == 1 && isset( $this->settings[ $nameParts[ 0 ] ] ) ) {
			return true;
		}

		if ( count( $nameParts ) > 1 && isset( $this->settings[ $nameParts[ 0 ] ][ $nameParts[ 1 ] ] ) ) {
			return true;
		}

		return false;
	}
}
