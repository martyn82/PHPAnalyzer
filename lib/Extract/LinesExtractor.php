<?php
namespace Extract;

use FileSystem\File;
use Extract\Normalizer\Normalizer;
use Extract\Normalizer\NormalizerFactory;

class LinesExtractor {
	public static function getFileLinesOfCode( File $file ) {
		$fileLines = self::getFileLines( $file );
		$normalizer = NormalizerFactory::createNormalizerByFile( $file );
		return self::getLinesOfCode( $fileLines, $normalizer );
	}
	
	public static function getLinesOfCode( array $lines, Normalizer $normalizer ) {
		$lineIterator = new LineIterator( $lines );
		$linesOfCode = array();
		
		foreach ( $lineIterator as $lineNumber => $line ) {
			$line = trim( $line );
				
			if ( !$normalizer->isCode( $line ) ) {
				continue;
			}
				
			$linesOfCode[ $lineNumber ] = $line;
		}
		
		return $linesOfCode;
	}
	
	public static function getSourceLines( $source ) {
		$lines = explode( "\n", $source );
		return array_combine(
			range( 1, count( $lines ) ),
			$lines
		);
	}
	
	public static function getFileLines( File $file ) {
		return self::getSourceLines( $file->getContents() );
	}
}

class LineIterator implements \Iterator {
	private $cursor = 0;
	private $lines = array();
	
	public function __construct( array $lines ) {
		$this->lines = $lines;
		$this->rewind();
	}
	
	public function current() {
		return $this->lines[ $this->cursor ];
	}
	
	public function key() {
		return $this->cursor;
	}
	
	public function next() {
		++$this->cursor;
	}
	
	public function rewind() {
		reset( $this->lines );
		$this->cursor = key( $this->lines );
	}
	
	public function valid() {
		return isset( $this->lines[ $this->cursor ] );
	}
}