<?php
namespace Mend\Cli;

class AnalyzeOptions {
	/**
	 * @var string
	 */
	private $configFile;

	/**
	 * @var boolean
	 */
	private $verbose;

	/**
	 * @var boolean
	 */
	private $summarize;

	/**
	 * @var string
	 */
	private $templatePath;

	/**
	 * @var \DateTime
	 */
	private $date;

	/**
	 * Constructs a new options object.
	 */
	public function __construct() {
		$this->verbose = false;
		$this->summarize = false;
		$this->templatePath = null;
		$this->date = null;
	}

	/**
	 * Sets configuration file.
	 *
	 * @param string $value
	 */
	public function setConfigFile( $value ) {
		$this->configFile = (string) $value;
	}

	/**
	 * Retrieves the configuration file.
	 *
	 * @return string
	 */
	public function getConfigFile() {
		return $this->configFile;
	}

	/**
	 * Sets verbosity flag value.
	 *
	 * @param boolean $value
	 */
	public function setVerbose( $value ) {
		$this->verbose = (bool) $value;
	}

	/**
	 * Retrieves the verbosity flag.
	 *
	 * @return boolean
	 */
	public function getVerbose() {
		return $this->verbose;
	}

	/**
	 * Sets the summarize flag value.
	 *
	 * @param boolean $value
	 */
	public function setSummarize( $value ) {
		$this->summarize = (bool) $value;
	}

	/**
	 * Retrieves the summarize flag.
	 *
	 * @return boolean
	 */
	public function getSummarize() {
		return $this->summarize;
	}

	/**
	 * Sets the template path.
	 *
	 * @param string $value
	 */
	public function setTemplatePath( $value ) {
		$this->templatePath = $value;
	}

	/**
	 * Retrieves the template path.
	 *
	 * @return string
	 */
	public function getTemplatePath() {
		return $this->templatePath;
	}

	/**
	 * Sets the report date time.
	 *
	 * @param \DateTime $date
	 */
	public function setDate( \DateTime $date ) {
		$this->date = $date;
	}

	/**
	 * Retrieves the report date time.
	 *
	 * @return \DateTime
	 */
	public function getDate() {
		return $this->date;
	}
}