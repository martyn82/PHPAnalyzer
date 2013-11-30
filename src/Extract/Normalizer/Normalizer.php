<?php
namespace Extract\Normalizer;

abstract class Normalizer {
	private $inComment;
	
	public function __construct() {
		$this->inComment = false;
	}
	
	public function isCode( $line ) {
		return !$this->isBlank( $line ) && !$this->isWhitespace( $line ) && !$this->isComment( $line );
	}
	
	public function isBlank( $line ) {
		return $line == "";
	}
	
	public function isWhitespace( $line ) {
		return $this->isBlank( trim( $line ) );
	}
	
	protected function inComment() {
		return $this->inComment;
	}
	
	protected function setInComment( $flag ) {
		$this->inComment = (bool) $flag;
	}
	
	abstract public function isComment( $line );
}