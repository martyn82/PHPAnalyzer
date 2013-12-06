<?php
namespace Synthesize;

use Report\Report;

class Maintainability {
	public static function getAnalyzability( Report $report ) {
		$volumeRank = $report->volume()->getRank();
		$duplicationRank = $report->duplication()->getRank();
		$unitSizeRank = $report->unitSize()->getRank();

		$ranks = array(
			$volumeRank,
			$duplicationRank,
			$unitSizeRank
		);

		return round(
			array_reduce(
				$ranks,
				function ( $result, $rank ) {
					return $result + $rank;
				},
				0
			) / count( $ranks )
		);
	}

	public static function getChangeability( Report $report ) {
		$complexityRank = $report->complexity()->getRank();
		$duplicationRank = $report->duplication()->getRank();

		$ranks = array(
			$complexityRank,
			$duplicationRank
		);

		return round(
			array_reduce(
				$ranks,
				function ( $result, $rank ) {
					return $result + $rank;
				},
				0
			) / count( $ranks )
		);
	}

	public static function getTestability( Report $report ) {
		$complexityRank = $report->complexity()->getRank();
		$unitSizeRank = $report->unitSize()->getRank();

		$ranks = array(
			$complexityRank,
			$unitSizeRank
		);

		return round(
			array_reduce(
				$ranks,
				function ( $result, $rank ) {
					return $result + $rank;
				},
				0
			) / count( $ranks )
		);
	}

	public static function getMaintainability( Report $report ) {
		$analyzability = self::getAnalyzability( $report );
		$changeability = self::getChangeability( $report );
		$testability = self::getTestability( $report );

		$ranks = array(
			$analyzability,
			$changeability,
			$testability
		);

		return round(
			array_reduce(
				$ranks,
				function ( $result, $rank ) {
					return $result + $rank;
				},
				0
			) / count( $ranks )
		);
	}
}