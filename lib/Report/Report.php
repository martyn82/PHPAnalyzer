<?php
namespace Report;

use Report\VolumeReport;
use Report\ComplexityReport;
use Report\UnitSizeReport;
use Report\DuplicationReport;

class Report {
	private $volume;
	private $complexity;
	private $unitSize;
	private $duplication;

	public function __construct(
		VolumeReport $volume,
		ComplexityReport $complexity,
		UnitSizeReport $unitSize,
		DuplicationReport $duplication
	) {
		$this->volume = $volume;
		$this->complexity = $complexity;
		$this->unitSize = $unitSize;
		$this->duplication = $duplication;
	}

	public function volume() {
		return $this->volume;
	}

	public function complexity() {
		return $this->complexity;
	}

	public function unitSize() {
		return $this->unitSize;
	}

	public function duplication() {
		return $this->duplication;
	}
}