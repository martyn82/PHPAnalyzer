<?php
namespace Mend\Parser;

use \Mend\Parser\Adapter;

use \Mend\Logging\Logger;

class Parser {
	/**
	 * @var Adapter
	 */
	private $adapter;

	/**
	 * Constructs a new Parser.
	 *
	 * @param Adapter $adapter
	 */
	public function __construct( Adapter $adapter ) {
		$this->adapter = $adapter;

		Logger::info( "Parser constructed with adapter " . get_class( $adapter ) );
	}

	/**
	 * Parses the given source.
	 *
	 * @param string $source
	 *
	 * @return array
	 */
	public function parse( $source ) {
		Logger::info( "Start parsing..." );

		$ast = $this->adapter->parse( $source );

		Logger::info( "Parsing done." );

		return $ast;
	}
}