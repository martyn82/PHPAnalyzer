<?php
namespace Mend\Data;

class Page {
	/**
	 * @var integer
	 */
	private $limit;

	/**
	 * @var integer
	 */
	private $offset;

	/**
	 * Constructs a new Page instance.
	 *
	 * @param integer $limit
	 * @param integer $offset
	 */
	public function __construct( $limit = null, $offset = 0 ) {
		$this->limit = (int) $limit;
		$this->offset = (int) $offset;
	}

	/**
	 * Retrieves the limit.
	 *
	 * @return integer
	 */
	public function getLimit() {
		return $this->limit;
	}

	/**
	 * Retrieves the offset.
	 *
	 * @return integer
	 */
	public function getOffset() {
		return $this->offset;
	}
}