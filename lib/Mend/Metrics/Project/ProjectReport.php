<?php
namespace Mend\Metrics\Project;

use Mend\Collections\Map;
use Mend\Metrics\Report\Report;

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
	 * @var \DateTime
	 */
	private $dateTime;

	/**
	 * Constructs a new Project report.
	 *
	 * @param Project $project
	 * @param \DateTime $dateTime
	 */
	public function __construct( Project $project, \DateTime $dateTime = null ) {
		$this->project = $project;
		$this->dateTime = is_null( $dateTime ) ? new \DateTime() : $dateTime;
		$this->reports = new Map();
	}

	/**
	 * Retrieves the project.
	 *
	 * @return Project
	 */
	public function getProject() {
		return $this->project;
	}

	/**
	 * Retrieves the date and time of the report.
	 *
	 * @return \DateTime
	 */
	public function getDateTime() {
		return $this->dateTime;
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
	 * Determines whether a report with given name exists.
	 *
	 * @param string $name
	 *
	 * @return boolean
	 */
	public function hasReport( $name ) {
		return $this->reports->hasKey( $name );
	}

	/**
	 * Converts this object to its array representation.
	 *
	 * @return array
	 */
	public function toArray() {
		$result = array(
			'project' => $this->project->toArray(),
			'dateTime' => $this->dateTime->format( 'r' )
		);

		$reports = array();

		foreach ( $this->reports->toArray() as $name => $report ) {
			/* @var $report Report */
			$reports[ $name ] = $report->toArray();
		}

		return array_merge( $result, $reports );
	}
}
