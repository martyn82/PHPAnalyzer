<?php
namespace Parser;

abstract class Adapter {
	/**
	 * Parses the given source.
	 *
	 * @param string $source
	 *
	 * @return array
	 */
	abstract public function parse( $source );
}