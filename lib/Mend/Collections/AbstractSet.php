<?php
namespace Mend\Collections;

abstract class AbstractSet extends AbstractCollection implements Set {
	/**
	 * @see AbstractCollection::add()
	 *
	 * @throws \InvalidArgumentException
	 */
	public function add( $value ) {
		if ( $this->contains( $value ) ) {
			throw new \InvalidArgumentException( "The value already exists in set." );
		}

		parent::add( $value );
	}

	/**
	 * @see AbstractCollection::addAll()
	 */
	public function addAll( Collection $values ) {
		foreach ( $values as $value ) {
			$this->add( $value );
		}
	}
}
