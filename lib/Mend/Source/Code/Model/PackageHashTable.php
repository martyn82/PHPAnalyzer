<?php
namespace Mend\Source\Code\Model;

use Mend\Collections\HashTable;

class PackageHashTable extends HashTable {
	/**
	 * @param string $index
	 * @param array $value
	 */
	protected function _offsetSet( $index, array $value ) {
		parent::_offsetSet(
			$index,
			array_filter(
				$value,
				function ( Package $item ) {
					return true;
				}
			)
		);
	}
}