<?php
namespace Mend\Data;

use Mend\Collections\AbstractCollection;

class DataObjectCollection extends AbstractCollection {
	/**
	 * @var integer
	 */
	private $totalCount;

	/**
	 * Constructs a new DataObjectCollection instance.
	 *
	 * @param integer $totalCount
	 */
	public function __construct( $totalCount = null ) {
		$this->totalCount = abs( (int) $totalCount );
	}

	/**
	 * Retrieves the total count.
	 *
	 * @return integer
	 */
	public function getTotalCount() {
		return $this->totalCount ? : $this->size();
	}

	/**
	 * @see AbstractCollection::add()
	 */
	public function add( $value ) {
		$this->_add( $value );
	}

	/**
	 * Type-safe add.
	 *
	 * @param DataObject $object
	 */
	protected function _add( DataObject $object ) {
		parent::add( $object );
	}
}
