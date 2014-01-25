<?php
namespace Mend\Metrics\Extract;

use Mend\Metrics\Model\Code\SourceUrl;

class CodeBlock {
	/**
	 * @var integer
	 */
	const DEFAULT_SIZE = 6;

	/**
	 * @var array
	 */
	private $sourceLines;

	/**
	 * @var SourceUrl
	 */
	private $location;

	/**
	 * @var integer
	 */
	private $index;

	/**
	 * Constructs a new code block.
	 *
	 * @param SourceUrl $location
	 * @param array $sourceLines
	 * @param integer $index
	 */
	public function __construct( SourceUrl $location, array $sourceLines, $index ) {
		$this->location = $location;
		$this->sourceLines = $sourceLines;
		$this->index = (int) $index;
	}

	/**
	 * Retrieves the index.
	 *
	 * @return integer
	 */
	public function getIndex() {
		return $this->index;
	}

	/**
	 * Retrieves the source lines.
	 *
	 * @return array
	 */
	public function getSourceLines() {
		return $this->sourceLines;
	}

	/**
	 * Retrieves the source url.
	 *
	 * @return SourceUrl
	 */
	public function getLocation() {
		return $this->location;
	}
}
