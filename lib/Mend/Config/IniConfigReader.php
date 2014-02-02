<?php
namespace Mend\Config;

use Mend\IO\Stream\FileStreamReader;

class IniConfigReader extends ArrayConfigReader {
	/**
	 * @var FileStreamReader
	 */
	private $reader;

	/**
	 * Constructs a new INI configuration reader.
	 *
	 * @param FileStreamReader $reader
	 */
	public function __construct( FileStreamReader $reader ) {
		parent::__construct( array() );
		$this->reader = $reader;
		$this->loadSettings();
	}

	/**
	 * Frees all resources for this object.
	 */
	public function __destruct() {
		$this->reader->__destruct();
	}

	/**
	 * @see ConfigReader::reload()
	 */
	public function reload() {
		$this->loadSettings();
	}

	/**
	 * Loads the settings from file.
	 */
	private function loadSettings() {
		if ( $this->reader->isClosed() ) {
			$this->reader->open();
		}

		$contents = $this->reader->read();

		if ( $this->reader->isOpen() ) {
			$this->reader->close();
		}

		$settings = parse_ini_string( $contents, true );
		$this->setSettings( $settings );
	}
}