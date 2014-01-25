<?php
namespace Mend\Metrics\Model\Code;

use Mend\Collections\ArrayList;

class PackageArray extends ArrayList {
	/**
	 * @see ArrayList::offsetSet()
	 */
	public function offsetSet( $offset, $item ) {
		array_map(
			function ( Package $item ) use ( $offset ) {
				parent::offsetSet( $offset, $item );
			},
			array( $item )
		);
	}
}