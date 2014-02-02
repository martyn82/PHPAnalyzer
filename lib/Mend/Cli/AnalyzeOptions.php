<?php
namespace Mend\Cli;

class AnalyzeOptions {
	const DEFAULT_CONFIG_FILE = null;
	const DEFAULT_OUTPUT_FORMAT = 'text';
	const DEFAULT_VERBOSE = false;
	const DEFAULT_TEMPLATE = null;
	const DEFAULT_EXTENSIONS = 'php';
	const DEFAULT_MEMORY_LIMIT = '1G';
	const DEFAULT_ANALYSIS_PATH = null;

	/**
	 * @var string
	 */
	private $configFile;

	/**
	 * @var string
	 */
	private $outputFormat;

	/**
	 * @var boolean
	 */
	private $verbose;

	/**
	 * @var string
	 */
	private $template;

	/**
	 * @var string
	 */
	private $extensions;

	/**
	 * @var string
	 */
	private $memoryLimit;

	/**
	 * @var string
	 */
	private $analysisPath;

	/**
	 * Constructs a new options object.
	 */
	public function __construct() {
		$this->configFile = self::DEFAULT_CONFIG_FILE;
		$this->outputFormat = self::DEFAULT_OUTPUT_FORMAT;
		$this->verbose = self::DEFAULT_VERBOSE;
		$this->template = self::DEFAULT_TEMPLATE;
		$this->extensions = self::DEFAULT_EXTENSIONS;
		$this->memoryLimit = self::DEFAULT_MEMORY_LIMIT;
		$this->analysisPath = self::DEFAULT_ANALYSIS_PATH;
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
	 * Sets the output format.
	 *
	 * @param string $value
	 */
	public function setOutputFormat( $value ) {
		$this->outputFormat = (string) $value;
	}

	/**
	 * Sets the template.
	 *
	 * @param string $value
	 */
	public function setTemplate( $value ) {
		$this->template = (string) $value;
	}

	/**
	 * Sets the file extensions.
	 *
	 * @param string $value
	 */
	public function setFileExtensions( $value ) {
		$this->extensions = (string) $value;
	}

	/**
	 * Sets the memory limit.
	 *
	 * @param string $value
	 */
	public function setMemoryLimit( $value ) {
		$this->memoryLimit = (string) $value;
	}

	/**
	 * Sets the analysis path.
	 *
	 * @param string $value
	 */
	public function setAnalysisPath( $value ) {
		$this->analysisPath = (string) $value;
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
	 * Retrieves the output format.
	 *
	 * @return string
	 */
	public function getOutputFormat() {
		return $this->outputFormat;
	}

	/**
	 * Retrieves the template path.
	 *
	 * @return string
	 */
	public function getTemplate() {
		return $this->template;
	}

	/**
	 * Retrieves the file extensions.
	 *
	 * @return string
	 */
	public function getFileExtensions() {
		return $this->extensions;
	}

	/**
	 * Gets memory limit.
	 *
	 * @return string
	 */
	public function getMemoryLimit() {
		return $this->memoryLimit;
	}

	/**
	 * Gets analysis path.
	 *
	 * @return string
	 */
	public function getAnalysisPath() {
		return $this->analysisPath;
	}

	/**
	 * Converts the options to an array.
	 *
	 * @return array
	 */
	public function toArray() {
		return array(
			'report' => array(
				'type' => $this->outputFormat,
				'template' => $this->template
			),
			'system' => array(
				'memory' => $this->memoryLimit
			),
			'analysis' => array(
				'extensions' => $this->extensions
			),
			'project' => array(
				'path' => $this->analysisPath
			)
		);
	}
}