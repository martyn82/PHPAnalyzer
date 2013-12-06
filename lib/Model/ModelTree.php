<?php
namespace Model;

use FileSystem\File;

class ModelTree {
	private $nodes;
	private $source;
	
	public static function createFromFile( File $file ) {
		$source = $file->getContents();
		return self::createFromSource( $source );
	}
	
	public static function createFromSource( $source ) {
		$parser = new \PHPParser_Parser( new \PHPParser_Lexer() );
		$nodes = $parser->parse( $source );
		
		return new self( $nodes, $source );
	}
	
	private function __construct( array $nodes, $source ) {
		$this->nodes = $nodes;
		$this->source = $source;
	}
	
	public function getNodes() {
		return $this->nodes;
	}
	
	public function getSource() {
		return $this->source;
	}
}