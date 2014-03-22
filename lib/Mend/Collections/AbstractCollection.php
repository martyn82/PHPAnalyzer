<?php
namespace Mend\Collections;

abstract class AbstractCollection implements Collection {
	/**
	 * @var array
	 */
	private $inner = array();

	/**
	 * @var integer
	 */
	private $cursor = 0;

	/**
	 * @see Collection::add()
	 */
	public function add( $value ) {
		$this->inner[] = $value;
	}

	/**
	 * @see Collection::addAll()
	 */
	public function addAll( Collection $values ) {
		$this->inner = array_merge( $this->inner, $values->toArray() );
	}

	/**
	 * @see Collection::clear()
	 */
	public function clear() {
		$this->inner = array();
	}

	/**
	 * @see Collection::contains()
	 */
	public function contains( $value ) {
		return array_search( $value, $this->inner, true ) !== false;
	}

	/**
	 * @see Collection::containsAll()
	 */
	public function containsAll( Collection $values ) {
		return count( array_diff( $values->toArray(), $this->inner ) ) == 0;
	}

	/**
	 * @see Collection::isEmpty()
	 */
	public function isEmpty() {
		return $this->size() == 0;
	}

	/**
	 * @see Collection::iterator()
	 */
	public function iterator() {
		return new \ArrayIterator( $this->inner );
	}

	/**
	 * @see Collection::remove()
	 */
	public function remove( $value ) {
		$index = array_search( $value, $this->inner );

		if ( $index === false ) {
			return;
		}

		unset( $this->inner[ $index ] );
		$this->inner = array_values( $this->inner );
	}

	/**
	 * @see Collection::removeAll()
	 */
	public function removeAll( Collection $values ) {
		$this->inner = array_values( array_diff( $this->inner, $values->toArray() ) );
	}

	/**
	 * @see Collection::retainAll()
	 */
	public function retainAll( Collection $values ) {
		$this->inner = array_values( array_intersect( $this->inner, $values->toArray() ) );
	}

	/**
	 * @see Collection::size()
	 */
	public function size() {
		return count( $this->inner );
	}

	/**
	 * @see Collection::toArray()
	 */
	public function toArray() {
		return $this->inner;
	}

	/**
	 * @see Iterator::current()
	 */
	public function current() {
		return $this->inner[ $this->cursor ];
	}

	/**
	 * @see Iterator::next()
	 */
	public function next() {
		++$this->cursor;
	}

	/**
	 * @see Iterator::key()
	 */
	public function key() {
		return $this->cursor;
	}

	/**
	 * @see Iterator::valid()
	 */
	public function valid() {
		return isset( $this->inner[ $this->cursor ] );
	}

	/**
	 * @see Iterator::rewind()
	 */
	public function rewind() {
		$this->cursor = 0;
	}
}
