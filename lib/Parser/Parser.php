<?php
namespace Parser;

use \Parser\Adapter;

use \Logging\Logger;

class Parser {
	/**
	 * @var \Parser\Adapter
	 */
	private $adapter;

	/**
	 * Constructs a new Parser.
	 *
	 * @param \Parser\Adapter $adapter
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