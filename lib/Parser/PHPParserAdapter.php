<?php
namespace Parser;

use Parser\ParserAdapter;

use Parser\AST\PHPParserNode;
use Parser\AST\ParserNodeArray;

class PHPParserAdapter extends ParserAdapter {
	/**
	 * @var Parser
	 */
	private $parser;

	/**
	 * Constructs a new PHPParser adapter.
	 */
	public function __construct() {
		$this->parser = new \PHPParser_Parser( new \PHPParser_Lexer() );
	}

	/**
	 * Parses the given source.
	 *
	 * @param string $source
	 *
	 * @return AST\PHPParserNodeArray
	 */
	public function parse( $source ) {
		$nodes = $this->parser->parse( $source );

		return new ParserNodeArray(
				array_map(
				function ( \PHPParser_Node $node ) {
					return new PHPParserNode( $node );
				},
				$nodes
			)
		);
	}
}