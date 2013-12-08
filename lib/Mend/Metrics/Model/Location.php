<?php
namespace Mend\Metrics\Model;

class Location {
	/**
	 * @var string
	 */
	private $fileName;

	/**
	 * @var integer
	 */
	private $startLine;

	/**
	 * @var integer
	 */
	private $endLine;

	/**
	 * Constructs a new model location.
	 *
	 * @param string $fileName
	 * @param integer $startLine
	 * @param integer $endLine
	 */
	public function __construct( $fileName, $startLine, $endLine ) {
		$this->fileName = (string) $fileName;
		$this->startLine = (int) $startLine;
		$this->endLine = (int) $endLine;
	}

	/**
	 * Retrieves the file name.
	 *
	 * @return string
	 */
	public function getFileName() {
		return $this->fileName;
	}

	/**
	 * Retrieves the start line.
	 *
	 * @return integer
	 */
	public function getStartLine() {
		return $this->startLine;
	}

	/**
	 * Retrieves the end line.
	 *
	 * @return integer
	 */
	public function getEndLine() {
		return $this->endLine;
	}
}