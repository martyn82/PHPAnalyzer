<?php
namespace Parser;

use Parser\ParserAdapter;

class Parser {
	/**
	 * @var ParserAdapter
	 */
	private $adapter;

	/**
	 * Constructs a new Parser.
	 *
	 * @param ParserAdapter $adapter
	 */
	public function __construct( ParserAdapter $adapter ) {
		$this->adapter = $adapter;
	}

	/**
	 * Parses the given source.
	 *
	 * @param string $source
	 *
	 * @return AST\ParserNodeArray
	 */
	public function parse( $source ) {
		return $this->adapter->parse( $source );
	}
}