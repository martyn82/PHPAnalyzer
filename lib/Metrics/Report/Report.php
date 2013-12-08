<?php
namespace Metrics\Report;

class Report {
	/**
	 * @var \Metrics\Report\VolumeReport
	 */
	private $volume;

	/**
	 * @var \Metrics\Report\UnitSizeReport;
	 */
	private $unitSize;

	/**
	 * @var \Metrics\Report\ComplexityReport
	 */
	private $complexity;

	/**
	 * @var \Metrics\Report\DuplicationReport
	 */
	private $duplication;

	/**
	 * @var \Metrics\Report\MaintainabilityReport
	 */
	private $maintainability;

	/**
	 * Constructs a new report.
	 *
	 * @param VolumeReport $volume
	 * @param UnitSizeReport $unitSize
	 * @param ComplexityReport $complexity
	 * @param DuplicationReport $duplication
	 */
	public function __construct(
		VolumeReport $volume,
		UnitSizeReport $unitSize,
		ComplexityReport $complexity,
		DuplicationReport $duplication
	) {
		$this->volume = $volume;
		$this->unitSize = $unitSize;
		$this->complexity = $complexity;
		$this->duplication = $duplication;
	}

	/**
	 * Retrieves the maintainability report.
	 *
	 * @return MaintainabilityReport
	 */
	public function maintainability() {
		if ( is_null( $this->maintainability ) ) {
			$this->maintainability = new MaintainabilityReport( $this );
		}

		return $this->maintainability;
	}

	/**
	 * Get volume report.
	 *
	 * @return VolumeReport
	 */
	public function volume() {
		return $this->volume;
	}

	/**
	 * Get unit size report.
	 *
	 * @return UnitSizeReport;
	 */
	public function unitSize() {
		return $this->unitSize;
	}

	/**
	 * Get complexity report.
	 *
	 * @return ComplexityReport
	 */
	public function complexity() {
		return $this->complexity;
	}

	/**
	 * Get duplication report.
	 *
	 * @return DuplicationReport
	 */
	public function duplication() {
		return $this->duplication;
	}
}