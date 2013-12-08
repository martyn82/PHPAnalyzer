<?php
namespace Parser\Adapter;

use Parser\Adapter;
use Parser\AST\PHPNodeArray;

use \PHPParser_Parser;
use \PHPParser_Lexer;

class PHPParserAdapter extends Adapter {
	/**
	 * @var \Parser\Parser
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
	 * @return array
	 */
	public function parse( $source ) {
		return $this->parser->parse( $source );
	}
}