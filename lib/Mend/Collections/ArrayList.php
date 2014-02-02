<?php
namespace Mend\Collections;

abstract class ArrayList extends \ArrayObject {
	/**
	 * Constructs a new array list with given items.
	 *
	 * @param array $items
	 */
	public function __construct( array $items = array() ) {
		foreach ( $items as $item ) {
			$this[] = $item;
		}
	}
}