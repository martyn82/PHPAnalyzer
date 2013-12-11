<?php
namespace Mend\Metrics\Report;

use \Mend\Metrics\Arrayable;

class Report implements Arrayable {
	/**
	 * @var VolumeReport
	 */
	private $volume;

	/**
	 * @var UnitSizeReport;
	 */
	private $unitSize;

	/**
	 * @var ComplexityReport
	 */
	private $complexity;

	/**
	 * @var DuplicationReport
	 */
	private $duplication;

	/**
	 * @var MaintainabilityReport
	 */
	private $maintainability;

	/**
	 * @var Project
	 */
	private $project;

	/**
	 * Constructs a new report.
	 *
	 * @param Project $project
	 * @param VolumeReport $volume
	 * @param UnitSizeReport $unitSize
	 * @param ComplexityReport $complexity
	 * @param DuplicationReport $duplication
	 */
	public function __construct(
		Project $project,
		VolumeReport $volume,
		UnitSizeReport $unitSize,
		ComplexityReport $complexity,
		DuplicationReport $duplication
	) {
		$this->project = $project;
		$this->volume = $volume;
		$this->unitSize = $unitSize;
		$this->complexity = $complexity;
		$this->duplication = $duplication;
	}

	/**
	 * Retrieves the Project.
	 *
	 * @return Project
	 */
	public function project() {
		return $this->project;
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

	/**
	 * Converts this Report to an array.
	 *
	 * @return array
	 */
	public function toArray() {
		return array(
			'project' => $this->project->toArray(),
			'volume' => $this->volume->toArray(),
			'unitSize' => $this->unitSize->toArray(),
			'complexity' => $this->complexity->toArray(),
			'duplication' => $this->duplication->toArray(),
			'maintainability' => $this->maintainability()->toArray()
		);
	}
}