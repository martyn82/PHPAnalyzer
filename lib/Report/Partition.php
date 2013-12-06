<?php
namespace Report;

class Partition {
	private $absoluteLOC;
	private $relativeLOC;

	public function __construct( $absoluteLOC, $relativeLOC ) {
		$this->absoluteLOC = (int) $absoluteLOC;
		$this->relativeLOC = (float) $relativeLOC;
	}

	public function getAbsoluteLOC() {
		return $this->absoluteLOC;
	}

	public function getRelativeLOC() {
		return round( $this->relativeLOC, 3 );
	}
}