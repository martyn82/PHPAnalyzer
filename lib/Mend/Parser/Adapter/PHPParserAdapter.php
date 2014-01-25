<?php
namespace Mend\Parser\Adapter;

use \Mend\Parser\Adapter;

use \PHPParser_Parser;
use \PHPParser_Lexer;

class PHPParserAdapter extends Adapter {
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
	 * @return array
	 */
	public function parse( $source ) {
		return $this->parser->parse( $source );
	}
}