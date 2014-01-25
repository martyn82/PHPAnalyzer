<?php
namespace Mend\Metrics\Report;

use Mend\Collections\Map;
use Mend\Metrics\Model\Project;

class ProjectReport {
	/**
	 * @var Map
	 */
	private $reports;

	/**
	 * @var Project
	 */
	private $project;

	/**
	 * Constructs a new Project report.
	 *
	 * @param Project $project
	 */
	public function __construct( Project $project ) {
		$this->project = $project;
		$this->reports = new Map();
	}

	/**
	 * Adds a named report.
	 *
	 * @param string $name
	 * @param Report $report
	 */
	public function addReport( $name, Report $report ) {
		$this->reports->set( $name, $report );
	}

	/**
	 * Retrieves a named report.
	 *
	 * @param string $name
	 *
	 * @return Report
	 *
	 * @throws \InvalidArgumentException
	 */
	public function getReport( $name ) {
		if ( !$this->reports->hasKey( $name ) ) {
			throw new \InvalidArgumentException( "No such report: '{$name}'." );
		}

		return $this->reports->get( $name );
	}

	/**
	 * Retrieves all reports.
	 *
	 * @return Map
	 */
	public function getReports() {
		return $this->reports;
	}
}
