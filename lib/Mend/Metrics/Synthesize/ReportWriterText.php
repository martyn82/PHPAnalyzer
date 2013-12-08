<?php
namespace Mend\Metrics\Synthesize;

use \Mend\Metrics\Report\ComplexityReport;
use \Mend\Metrics\Report\DuplicationReport;
use \Mend\Metrics\Report\MaintainabilityReport;
use \Mend\Metrics\Report\Report;
use \Mend\Metrics\Report\UnitSizeReport;
use \Mend\Metrics\Report\VolumeReport;

class ReportWriterText extends ReportWriter {
	/**
	 * @var string
	 */
	private $template = <<<TPL
-------------- Analysis report --------------

Volume
---------------------------------------------
Total lines              : %totalLines%
Total lines of code      : %totalLOC%
Blank / commented lines  : %blankAndComments%
---------------------------------------------
Volume rank              : %volumeScore%

Duplication
---------------------------------------------
Absolute duplicated lines: %absDuplication%
Relative duplication     : %relDuplication% %%
---------------------------------------------
Duplication rank         : %duplicationScore%

Method size
---------------------------------------------
Small sized methods
  Absolute lines         : %absSmallUnits%
  Relative lines         : %relSmallUnits% %%

Medium sized methods
  Absolute lines         : %absMediumUnits%
  Relative lines         : %relMediumUnits% %%

Large sized methods
  Absolute lines         : %absLargeUnits%
  Relative lines         : %relLargeUnits% %%

Very large sized methods
  Absolute lines         : %absVeryLargeUnits%
  Relative lines         : %relVeryLargeUnits% %%
---------------------------------------------
Method size rank         : %unitSizeScore%

Method complexity
---------------------------------------------
Low risk methods
  Absolute lines         : %absLowComplexity%
  Relative lines         : %relLowComplexity% %%

Moderate risk methods
  Absolute lines         : %absModerateComplexity%
  Relative lines         : %relModerateComplexity% %%

High risk methods
  Absolute lines         : %absHighComplexity%
  Relative lines         : %relHighComplexity% %%

Very high risk methods
  Absolute lines         : %absVeryHighComplexity%
  Relative lines         : %relVeryHighComplexity% %%
---------------------------------------------
Complexity rank          : %complexityScore%

Maintainability scores
---------------------------------------------
Analyzability            : %analyzabilityScore%
Changeability            : %changeabilityScore%
Stability                : %stabilityScore%
Testability              : %testabilityScore%
---------------------------------------------
Total score              : %maintainabilityScore%

TPL;

	/**
	 * Writes the report.
	 *
	 * @return string
	 *
	 * @throws \Exception
	 */
	public function write() {
		$report = $this->getReport();

		if ( is_null( $report ) ) {
			throw new \Exception( "No report set." );
		}

		return $this->fillTemplate( $this->template, $report );
	}

	/**
	 * Fills the template with report data.
	 *
	 * @param string $template
	 * @param Report $report
	 *
	 * @return string
	 */
	private function fillTemplate( $template, Report $report ) {
		$template = $this->fillVolume( $template, $report->volume() );
		$template = $this->fillDuplication( $template, $report->duplication() );
		$template = $this->fillUnitSize( $template, $report->unitSize() );
		$template = $this->fillComplexity( $template, $report->complexity() );
		$template = $this->fillMaintainability( $template, $report->maintainability() );

		return $this->finalizeReport( $template );
	}

	/**
	 * Fills template with volume data.
	 *
	 * @param string $template
	 * @param VolumeReport $report
	 *
	 * @return string
	 */
	private function fillVolume( $template, VolumeReport $report ) {
		return str_replace(
			array(
				'%totalLines%',
				'%totalLOC%',
				'%blankAndComments%',
				'%volumeScore%'
			),
			array(
				$report->getTotalLines(),
				$report->getTotalLinesOfCode(),
				$report->getTotalLines() - $report->getTotalLinesOfCode(),
				$this->rankToString( $report->getRank() )
			),
			$template
		);
	}

	/**
	 * Fills template with duplication data.
	 *
	 * @param string $template
	 * @param DuplicationReport $report
	 *
	 * @return string
	 */
	private function fillDuplication( $template, DuplicationReport $report ) {
		return str_replace(
			array(
				'%absDuplication%',
				'%relDuplication%',
				'%duplicationScore%'
			),
			array(
				$report->getAbsoluteLOC(),
				$report->getRelativeLOC(),
				$this->rankToString( $report->getRank() )
			),
			$template
		);
	}

	/**
	 * Fills template with unit size data.
	 *
	 * @param string $template
	 * @param UnitSizeReport $report
	 *
	 * @return string
	 */
	private function fillUnitSize( $template, UnitSizeReport $report ) {
		return str_replace(
			array(
					'%absSmallUnits%',
					'%relSmallUnits%',
					'%absMediumUnits%',
					'%relMediumUnits%',
					'%absLargeUnits%',
					'%relLargeUnits%',
					'%absVeryLargeUnits%',
					'%relVeryLargeUnits%',
					'%unitSizeScore%'
			),
			array(
					$report->small()->getAbsoluteLOC(),
					$report->small()->getRelativeLOC(),
					$report->medium()->getAbsoluteLOC(),
					$report->medium()->getRelativeLOC(),
					$report->large()->getAbsoluteLOC(),
					$report->large()->getRelativeLOC(),
					$report->veryLarge()->getAbsoluteLOC(),
					$report->veryLarge()->getRelativeLOC(),
					$this->rankToString( $report->getRank() )
			),
			$template
		);
	}

	/**
	 * Fills template with complexity data.
	 *
	 * @param string $template
	 * @param ComplexityReport $report
	 *
	 * @return string
	 */
	private function fillComplexity( $template, ComplexityReport $report ) {
		return str_replace(
			array(
				'%absLowComplexity%',
				'%relLowComplexity%',
				'%absModerateComplexity%',
				'%relModerateComplexity%',
				'%absHighComplexity%',
				'%relHighComplexity%',
				'%absVeryHighComplexity%',
				'%relVeryHighComplexity%',
				'%complexityScore%'
			),
			array(
				$report->low()->getAbsoluteLOC(),
				$report->low()->getRelativeLOC(),
				$report->moderate()->getAbsoluteLOC(),
				$report->moderate()->getRelativeLOC(),
				$report->high()->getAbsoluteLOC(),
				$report->high()->getRelativeLOC(),
				$report->veryHigh()->getAbsoluteLOC(),
				$report->veryHigh()->getRelativeLOC(),
				$this->rankToString( $report->getRank() )
			),
			$template
		);
	}

	/**
	 * Fills template with maintainability data.
	 *
	 * @param string $template
	 * @param MaintainabilityReport $report
	 *
	 * @return string
	 */
	private function fillMaintainability( $template, MaintainabilityReport $report ) {
		return str_replace(
			array(
				'%analyzabilityScore%',
				'%changeabilityScore%',
				'%stabilityScore%',
				'%testabilityScore%',
				'%maintainabilityScore%'
			),
			array(
				$this->rankToString( $report->getAnalyzabilityRank() ),
				$this->rankToString( $report->getChangeabilityRank() ),
				$this->rankToString( $report->getStabilityRank() ),
				$this->rankToString( $report->getTestabilityRank() ),
				$this->rankToString( $report->getRank() )
			),
			$template
		);
	}

	/**
	 * Finalizes the template.
	 *
	 * @param string $template
	 *
	 * @return string
	 */
	private function finalizeReport( $template ) {
		return str_replace(
			array(
				'%%'
			),
			array(
				'%'
			),
			$template
		);
	}
}