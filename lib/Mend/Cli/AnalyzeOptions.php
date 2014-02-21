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
	 * Constructs a new options object.
	 */
	public function __construct() {
		$this->verbose = false;
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
	 * Sets verbosity flag value.
	 *
	 * @param boolean $value
	 */
	public function setVerbose( $value ) {
		$this->verbose = (bool) $value;
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
	 * Sets the template path.
	 *
	 * @param string $value
	 */
	public function setTemplatePath( $value ) {
		$this->templatePath = $value;
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
	 * Retrieves the verbosity flag.
	 *
	 * @return boolean
	 */
	public function getVerbose() {
		return $this->verbose;
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
	 * Retrieves the template path.
	 *
	 * @return string
	 */
	public function getTemplatePath() {
		return $this->templatePath;
	}
}