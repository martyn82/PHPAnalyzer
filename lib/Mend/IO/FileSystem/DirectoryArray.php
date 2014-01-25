<?php
namespace Mend\IO\FileSystem;

use Mend\Collections\ArrayList;

class DirectoryArray extends ArrayList {
	/**
	 * @see ArrayList::offsetSet()
	 */
	public function offsetSet( $offset, $item ) {
		array_map(
			function ( Directory $item ) use ( $offset ) {
				parent::offsetSet( $offset, $item );
			},
			array( $item )
		);
	}
}