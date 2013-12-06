<?php
namespace Parser;

abstract class ParserAdapter {
	/**
	 * Parses the given source.
	 *
	 * @param string $source
	 *
	 * @return AST\ParserNodeArray
	 */
	abstract public function parse( $source );
}