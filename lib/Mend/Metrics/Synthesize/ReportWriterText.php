<?php
namespace Mend\Metrics\Synthesize;

use \Mend\Metrics\Report\ComplexityReport;
use \Mend\Metrics\Report\DuplicationReport;
use \Mend\Metrics\Report\MaintainabilityReport;
use \Mend\Metrics\Report\Report;
use \Mend\Metrics\Report\Project;
use \Mend\Metrics\Report\UnitSizeReport;
use \Mend\Metrics\Report\VolumeReport;

class ReportWriterText extends ReportWriter {
	/**
	 * @var string
	 */
	private $template = <<<TPL
-------------- Analysis report --------------
Project                  : %projectName%
Location                 : %projectLocation%

Volume
---------------------------------------------
Total lines              : %totalLines%
Total lines of code      : %totalLOC%
Blank / commented lines  : %blankAndComments%
Number of files          : %fileCount%
Number of packages       : %packageCount%
Number of classes        : %classCount%
Number of methods        : %methodCount%
---------------------------------------------
Volume rank              : %volumeScore%

Duplication
---------------------------------------------
Number of blocks         : %blocksCount%
Absolute duplicated lines: %absDuplication%
Relative duplication     : %relDuplication% %%
---------------------------------------------
Duplication rank         : %duplicationScore%

Method size
---------------------------------------------
Small sized methods
  Number of methods      : %smallMethodCount%
  Absolute lines         : %absSmallUnits%
  Relative lines         : %relSmallUnits% %%

Medium sized methods
  Number of methods      : %mediumMethodCount%
  Absolute lines         : %absMediumUnits%
  Relative lines         : %relMediumUnits% %%

Large sized methods
  Number of methods      : %largeMethodCount%
  Absolute lines         : %absLargeUnits%
  Relative lines         : %relLargeUnits% %%

Very large sized methods
  Number of methods      : %veryLargeMethodCount%
  Absolute lines         : %absVeryLargeUnits%
  Relative lines         : %relVeryLargeUnits% %%
---------------------------------------------
Method size rank         : %unitSizeScore%

Method complexity
---------------------------------------------
Low risk methods
  Number of methods      : %lowMethodCount%
  Absolute lines         : %absLowComplexity%
  Relative lines         : %relLowComplexity% %%

Moderate risk methods
  Number of methods      : %moderateMethodCount%
  Absolute lines         : %absModerateComplexity%
  Relative lines         : %relModerateComplexity% %%

High risk methods
  Number of methods      : %highMethodCount%
  Absolute lines         : %absHighComplexity%
  Relative lines         : %relHighComplexity% %%

Very high risk methods
  Number of methods      : %veryHighMethodCount%
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
	 * @param Report $report
	 *
	 * @return string
	 */
	public function write( Report $report ) {
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
		$template = $this->fillProject( $template, $report->project() );
		$template = $this->fillVolume( $template, $report->volume() );
		$template = $this->fillDuplication( $template, $report->duplication() );
		$template = $this->fillUnitSize( $template, $report->unitSize() );
		$template = $this->fillComplexity( $template, $report->complexity() );
		$template = $this->fillMaintainability( $template, $report->maintainability() );

		return $this->finalizeReport( $template );
	}

	/**
	 * Fills template with project data.
	 *
	 * @param string $template
	 * @param Project $report
	 *
	 * @return string
	 */
	private function fillProject( $template, Project $report ) {
		return str_replace(
			array(
				'%projectName%',
				'%projectLocation%'
			),
			array(
				$report->getName(),
				$report->getPath()
			),
			$template
		);
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
				'%fileCount%',
				'%packageCount%',
				'%classCount%',
				'%methodCount%',
				'%volumeScore%'
			),
			array(
				$report->getTotalLines(),
				$report->getTotalLinesOfCode(),
				$report->getTotalLines() - $report->getTotalLinesOfCode(),
				$report->getFileCount(),
				$report->getPackageCount(),
				$report->getClassCount(),
				$report->getMethodCount(),
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
				'%blocksCount%',
				'%absDuplication%',
				'%relDuplication%',
				'%duplicationScore%'
			),
			array(
				count( $report ),
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
				'%smallMethodCount%',
				'%mediumMethodCount%',
				'%largeMethodCount%',
				'%veryLargeMethodCount%',
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
				count( $report->small()->getMethods() ),
				count( $report->medium()->getMethods() ),
				count( $report->large()->getMethods() ),
				count( $report->veryLarge()->getMethods() ),
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
				'%lowMethodCount%',
				'%moderateMethodCount%',
				'%highMethodCount%',
				'%veryHighMethodCount%',
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
				count( $report->low()->getMethods() ),
				count( $report->moderate()->getMethods() ),
				count( $report->high()->getMethods() ),
				count( $report->veryHigh()->getMethods() ),
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