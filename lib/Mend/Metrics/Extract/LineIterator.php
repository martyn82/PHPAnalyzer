<?php
namespace Mend\Metrics\Extract;

class LineIterator implements \Iterator {
	/**
	 * @var array
	 */
	private $lines = array();

	/**
	 * Constructs a new iterator.
	 *
	 * @param array $lines
	 */
	public function __construct( array $lines ) {
		$this->lines = $lines;
		$this->rewind();
	}

	/**
	 * Retrieves the current line.
	 *
	 * @return string
	 */
	public function current() {
		return current( $this->lines );
	}

	/**
	 * Retrieves the current line number.
	 *
	 * @return integer
	 */
	public function key() {
		return key( $this->lines );
	}

	/**
	 * Advances to the next line.
	 */
	public function next() {
		next( $this->lines );
		$this->cursor = key( $this->lines );
	}

	/**
	 * Rewinds the iterator to the first line.
	 */
	public function rewind() {
		reset( $this->lines );
		$this->cursor = key( $this->lines );
	}

	/**
	 * Determines whether the current cursor position is valid.
	 *
	 * @return boolean
	 */
	public function valid() {
		return isset( $this->lines[ $this->cursor ] );
	}
}