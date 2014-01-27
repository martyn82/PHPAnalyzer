<?php
namespace Mend\Source\Filter;

abstract class SourceLineFilter {
	const FILTER_BLANK = 'isBlank';
	const FILTER_CODE = 'isCode';
	const FILTER_COMMENT = 'isComment';

	/**
	 * @var boolean
	 */
	private $inComment;

	/**
	 * Constructs a new SourceLineFilter.
	 */
	public function __construct() {
		$this->inComment = false;
	}

	/**
	 * Determines whether the given line is physical code.
	 *
	 * @param string $line
	 *
	 * @return boolean
	 */
	public function isCode( $line ) {
		return !$this->isBlank( $line )
		&& !$this->isWhitespace( $line )
		&& !$this->isComment( $line );
	}

	/**
	 * Determines whether the given line is blank.
	 *
	 * @param string $line
	 *
	 * @return boolean
	 */
	public function isBlank( $line ) {
		return $line == "";
	}

	/**
	 * Determines whether the given line is completely whitespace.
	 *
	 * @param string $line
	 *
	 * @return boolean
	 */
	public function isWhitespace( $line ) {
		return $this->isBlank( trim( $line ) );
	}

	/**
	 * Determines whether the normalizer is currently inside a comment.
	 *
	 * @return boolean
	 */
	protected function inComment() {
		return $this->inComment;
	}

	/**
	 * Sets the inComment flag.
	 *
	 * @param boolean $flag
	 */
	protected function setInComment( $flag ) {
		$this->inComment = (bool) $flag;
	}

	/**
	 * Determines whether the given line is a line of comment.
	 *
	 * @param string $line
	 *
	 * @return boolean
	 */
	abstract public function isComment( $line );
}
