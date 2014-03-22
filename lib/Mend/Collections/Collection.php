<?php
namespace Mend\Collections;

interface Collection extends \Iterator {
	/**
	 * Adds the given value to the collection.
	 *
	 * @param mixed $value
	 */
	function add( $value );

	/**
	 * Adds all elements from the given collection to this collection.
	 *
	 * @param Collection $values
	 */
	function addAll( Collection $values );

	/**
	 * Clears this collection.
	 */
	function clear();

	/**
	 * Determines whether this collection has the given value as one of its elements.
	 *
	 * @param mixed $value
	 *
	 * @return boolean
	 */
	function contains( $value );

	/**
	 * Determines whether this collection has all the elements in given collection.
	 *
	 * @param Collection $values
	 *
	 * @return boolean
	 */
	function containsAll( Collection $values );

	/**
	 * Returns true if this collection is empty.
	 *
	 * @return boolean
	 */
	function isEmpty();

	/**
	 * Retrieves an iterator over the elements of this collection.
	 *
	 * @return \Iterator
	 */
	function iterator();

	/**
	 * Removes the given value from this collection.
	 *
	 * @param mixed $value
	 */
	function remove( $value );

	/**
	 * Removes all elements in the given collection from this collection.
	 *
	 * @param Collection $values
	 */
	function removeAll( Collection $values );

	/**
	 * Retains only the elements in this collection that are elements of the given collection.
	 *
	 * @param Collection $values
	 */
	function retainAll( Collection $values );

	/**
	 * Returns the number of elements in this collection.
	 *
	 * @return integer
	 */
	function size();

	/**
	 * Converts this collection to an array.
	 *
	 * @return array
	 */
	function toArray();
}
