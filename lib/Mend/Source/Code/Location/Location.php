<?php
namespace Mend\Source\Code\Location;

class Location {
	/**
	 * @var integer
	 */
	private $line;

	/**
	 * @var integer
	 */
	private $column;

	/**
	 * Ceates an empty instance.
	 *
	 * @return Location
	 */
	public static function createEmpty() {
		$result = new self( null, null );
		$result->line = null;
		$result->column = null;

		return $result;
	}

	/**
	 * Constructs a new Location.
	 *
	 * @param integer $line
	 * @param integer $column
	 */
	public function __construct( $line, $column ) {
		$this->line = (int) $line;
		$this->column = (int) $column;
	}

	/**
	 * Retrieves the line number.
	 *
	 * @return integer
	 */
	public function getLine() {
		return $this->line;
	}

	/**
	 * Retrieves the column number.
	 *
	 * @return integer
	 */
	public function getColumn() {
		return $this->column;
	}

	/**
	 * Converts this object to string.
	 *
	 * @return string
	 */
	public function __toString() {
		return sprintf( "(%d,%d)", $this->getLine(), $this->getColumn() );
	}
}