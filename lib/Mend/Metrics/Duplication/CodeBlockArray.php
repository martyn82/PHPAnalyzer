<?php
namespace Mend\Metrics\Duplication;

use Mend\Collections\ArrayList;

class CodeBlockArray extends ArrayList {
	/**
	 * @see ArrayList::offsetSet()
	 */
	public function offsetSet( $offset, $item ) {
		array_map(
			function ( CodeBlock $item ) use ( $offset ) {
				parent::offsetSet( $offset, $item );
 			},
 			array( $item )
		);
	}
}
