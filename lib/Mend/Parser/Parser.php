<?php
namespace Mend\Parser;

use \Mend\Parser\Adapter;

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
	}

	/**
	 * Parses the given source.
	 *
	 * @param string $source
	 *
	 * @return array
	 */
	public function parse( $source ) {
		return $this->adapter->parse( $source );
	}
}