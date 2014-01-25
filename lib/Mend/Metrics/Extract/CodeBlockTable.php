<?php
namespace Mend\Metrics\Extract;

use Mend\Collections\HashTable;

class CodeBlockTable extends HashTable {
	/**
	 * @param string $index
	 * @param array $value
	 */
	protected function _offsetSet( $index, array $value ) {
		parent::_offsetSet(
			$index,
			array_filter(
				$value,
				function ( CodeBlock $item ) {
					return true;
				}
			)
		);
	}
}
