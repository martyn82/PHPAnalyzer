<?php
namespace Mend\Data\Storage;

use Mend\Collections\AbstractSet;

class RecordSet extends AbstractSet {
	/**
	 * Constructs a new RecordSet instance.
	 *
	 * @param array $records
	 */
	public function __construct( array $records ) {
		foreach ( $records as $record ) {
			$this->add( $record );
		}
	}

	/**
	 * @see AbstractSet::add()
	 */
	public function add( $value ) {
		$this->_add( $value );
	}

	/**
	 * Type-safe version of add().
	 *
	 * @param Record $record
	 */
	private function _add( Record $record ) {
		parent::add( $record );
	}
}
