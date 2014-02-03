<?php
namespace Mend\Metrics\Report;

use Mend\Metrics\Complexity\ComplexityAnalyzer;
use Mend\Metrics\Project\Project;
use Mend\Metrics\Project\ProjectReport;
use Mend\Metrics\Report\Builder\ComplexityReportBuilder;
use Mend\Metrics\Report\Builder\EntityReportBuilder;
use Mend\Metrics\Report\Builder\UnitSizeReportBuilder;
use Mend\Metrics\Report\Builder\VolumeReportBuilder;
use Mend\Metrics\Report\Builder\DuplicationReportBuilder;
use Mend\Metrics\UnitSize\UnitSizeAnalyzer;
use Mend\Metrics\Volume\VolumeReport;
use Mend\Logging\Logger;

class ProjectReportBuilder {
	/**
	 * @var Project
	 */
	private $project;

	/**
	 * @var ProjectReport
	 */
	private $report;

	/**
	 * @var array
	 */
	private $fileExtensions;

	/**
	 * Constructs a new project report builder.
	 *
	 * @param Project $project
	 * @param array $extensions
	 */
	public function __construct( Project $project, array $extensions = null ) {
		$this->project = $project;
		$this->fileExtensions = $extensions;
		$this->report = new ProjectReport( $project );
	}

	/**
	 * Retrieves the project.
	 *
	 * @return Project
	 */
	protected function getProject() {
		return $this->project;
	}

	/**
	 * Retrieves the built report.
	 *
	 * @return ProjectReport
	 */
	public function getReport() {
		return $this->report;
	}

	/**
	 * Retrieves the file extensions.
	 *
	 * @return array
	 */
	private function getFileExtensions() {
		return $this->fileExtensions;
	}

	/**
	 * Retrieves the entity report.
	 *
	 * @return EntityReport
	 */
	private function getEntityReport() {
		if ( !$this->report->hasReport( ReportType::REPORT_ENTITY ) ) {
			$this->report->addReport( ReportType::REPORT_ENTITY, new EntityReport() );
		}

		return $this->report->getReport( ReportType::REPORT_ENTITY );
	}

	/**
	 * Retrieves the volume report.
	 *
	 * @return VolumeReport
	 */
	private function getVolumeReport() {
		if ( !$this->report->hasReport( ReportType::REPORT_VOLUME ) ) {
			$this->report->addReport( ReportType::REPORT_VOLUME, new VolumeReport() );
		}

		return $this->report->getReport( ReportType::REPORT_VOLUME );
	}

	/**
	 * Extracts entities from project.
	 *
	 * @return ProjectReportBuilder
	 */
	public function extractEntities() {
		$this->methodStart( __METHOD__ );

		$entityBuilder = new EntityReportBuilder( $this->getProject(), $this->getFileExtensions() );

		$this->methodUpdate( __METHOD__, "Extracting entities..." );
		$entityBuilder->extractEntities();

		$report = $entityBuilder->getReport();
		$this->report->addReport( ReportType::REPORT_ENTITY, $report );

		$this->methodFinish( __METHOD__ );
		return $this;
	}

	/**
	 * Extracts volume facts for the current report.
	 *
	 * @return ProjectReportBuilder
	 */
	public function extractVolume() {
		$this->methodStart( __METHOD__ );

		$volumeBuilder = new VolumeReportBuilder( $this->getProject(), $this->getFileExtensions() );

		$this->methodUpdate( __METHOD__, "Extracting volume facts..." );
		$volumeBuilder->extractVolume();

		$report = $volumeBuilder->getReport();
		$this->getReport()->addReport( ReportType::REPORT_VOLUME, $report );

		$this->methodFinish( __METHOD__ );
		return $this;
	}

	/**
	 * Analyzes complexity for the current report.
	 *
	 * @return ProjectReportBuilder
	 */
	public function analyzeComplexity() {
		$this->methodStart( __METHOD__ );

		$complexityBuilder = new ComplexityReportBuilder( $this->getProject(), $this->getFileExtensions() );

		$this->methodUpdate( __METHOD__, "Analyzing complexity..." );
		$complexityBuilder->analyzeComplexity( $this->getEntityReport(), $this->getVolumeReport() );

		$report = $complexityBuilder->getReport();
		$this->getReport()->addReport( ReportType::REPORT_COMPLEXITY, $report );

		$this->methodFinish( __METHOD__ );
		return $this;
	}

	/**
	 * Analyzes unit size for the current report.
	 *
	 * @return ProjectReportBuilder
	 */
	public function analyzeUnitSize() {
		$this->methodStart( __METHOD__ );

		$unitSizeBuilder = new UnitSizeReportBuilder( $this->getProject(), $this->getFileExtensions() );

		$this->methodUpdate( __METHOD__, "Computing unit size..." );
		$unitSizeBuilder->analyzeUnitSize( $this->getEntityReport(), $this->getVolumeReport() );

		$report = $unitSizeBuilder->getReport();
		$this->getReport()->addReport( ReportType::REPORT_UNITSIZE, $report );

		$this->methodFinish( __METHOD__ );
		return $this;
	}

	/**
	 * Computes duplicated code blocks.
	 *
	 * @return ProjectReportBuilder
	 */
	public function computeDuplications() {
		$this->methodStart( __METHOD__ );

		$duplicationBuilder = new DuplicationReportBuilder( $this->getProject(), $this->getFileExtensions() );

		$this->methodUpdate( __METHOD__, "Computing duplications..." );
		$duplicationBuilder->computeDuplications( $this->getVolumeReport() );

		$report = $duplicationBuilder->getReport();
		$this->getReport()->addReport( ReportType::REPORT_DUPLICATION, $report );

		$this->methodFinish( __METHOD__ );
		return $this;
	}

	/**
	 * Method start event.
	 *
	 * @param string $name
	 */
	private function methodStart( $name ) {
		Logger::debug( "{$name}(): start." );
	}

	/**
	 * Method update event.
	 *
	 * @param string $name
	 * @param string $message
	 */
	private function methodUpdate( $name, $message ) {
		Logger::debug( "{$name}(): {$message}" );
	}

	/**
	 * Method finished event.
	 *
	 * @param string $name
	 */
	private function methodFinish( $name ) {
		Logger::debug( "{$name}(): finished." );
	}
}