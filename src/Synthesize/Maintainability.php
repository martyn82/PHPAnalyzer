<?php
namespace Synthesize;

use Analyze\Complexity;
use Analyze\Duplication;
use Analyze\UnitSize;
use Analyze\Volume;

use Extract\ModelExtractor;
use Extract\Normalizer\Normalizer;

use FileSystem\Crawler;
use FileSystem\Directory;
use FileSystem\File;

use Model\MethodArray;
use Model\ModelTree;

class Maintainability {
	private static $files;
	private static $methods;
	private static $complexityRank;
	private static $volumeRank;
	private static $unitSizeRank;
	private static $duplicationRank;
	private static $unitTestRank;
	
	private static function init( Directory $root ) {
		self::$files = null;
		self::$methods = null;
		self::$complexityRank = null;
		self::$volumeRank = null;
		self::$unitSizeRank = null;
		self::$unitTestRank = null;
		self::$duplicationRank = null;
	}
	
	public static function computeAnalyzability( Directory $root, Normalizer $normalizer ) {
		$volumeRank = self::getVolumeRank( $root, $normalizer );
		$duplicationRank = self::getDuplicationRank( $root, $normalizer );
		$unitSizeRank = self::getUnitSizeRank( $root, $normalizer );
		
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
	
	public static function computeChangeability( Directory $root, Normalizer $normalizer ) {
		$complexityRank = self::getComplexityRank( $root, $normalizer );
		$duplicationRank = self::getDuplicationRank( $root, $normalizer );
		
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
	
	public static function computeTestability( Directory $root, Normalizer $normalizer ) {
		$complexityRank = self::getComplexityRank( $root, $normalizer );
		$unitSizeRank = self::getUnitSizeRank( $root, $normalizer );
		
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
	
	public static function computeMaintainability( Directory $root, Normalizer $normalizer ) {
		self::init( $root );
		
		$analyzability = self::computeAnalyzability( $root, $normalizer );
		$changeability = self::computeChangeability( $root, $normalizer );
		$testability = self::computeTestability( $root, $normalizer );
		
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
	
	private static function getFiles( Directory $root ) {
		if ( is_null( self::$files ) ) {
			$crawler = new Crawler( $root );
			self::$files = $crawler->getFiles( "*.php" );
		}
		
		return self::$files;
	}
	
	private static function getMethods( Directory $root ) {
		if ( is_null( self::$methods ) ) {
			$methods = array_reduce(
				(array) self::getFiles( $root ),
				function ( array $result, File $file ) {
					$model = ModelTree::createFromFile( $file );
					return array_merge(
						$result,
						(array) ModelExtractor::getMethods( $model )
					);
				},
				array()
			);
			
			self::$methods = new MethodArray( $methods );
		}
		
		return self::$methods;
	}
	
	private static function getVolumeRank( Directory $root ) {
		if ( is_null( self::$volumeRank ) ) {
			$linesOfCode = Volume::getTotalLinesOfCodeCount( self::getFiles( $root ) );
			self::$volumeRank = Rank::getVolumeRank( $linesOfCode );
		}
		
		return self::$volumeRank;
	}
	
	private static function getUnitSizeRank( Directory $root, Normalizer $normalizer ) {
		if ( is_null( self::$unitSizeRank ) ) {
			$totalSize = Volume::getTotalLinesOfCodeCount( self::getFiles( $root ) );
			$partitions = UnitSize::getPartitions( self::getMethods( $root ), $normalizer );
			$relativeSizes = UnitSize::getRelativeSizes( $partitions, $totalSize );
			self::$unitSizeRank = Rank::getUnitSizeRank( $relativeSizes );
		}
		
		return self::$unitSizeRank;
	}
	
	private static function getDuplicationRank( Directory $root ) {
		if ( is_null( self::$duplicationRank ) ) {
			$totalSize = Volume::getTotalLinesOfCodeCount( self::getFiles( $root ) );
			$duplicationCount = Duplication::getDuplicationCount( self::getFiles( $root ) );
			$relativeDuplication = Duplication::getRelativeDuplicationCount( $duplicationCount, $totalSize );
			self::$duplicationRank = Rank::getDuplicationRank( $relativeDuplication );
		}
		
		return self::$duplicationRank;
	}
	
	private static function getComplexityRank( Directory $root, Normalizer $normalizer ) {
		if ( is_null( self::$complexityRank ) ) {
			$totalSize = Volume::getTotalLinesOfCodeCount( self::getFiles( $root ) );
			$partitions = Complexity::getPartitions( self::getMethods( $root ), $normalizer );
			$relativeComplexities = Complexity::getRelativeComplexities( $partitions, $totalSize );
			self::$complexityRank = Rank::getComplexityRank( $relativeComplexities );
		}
		
		return self::$complexityRank;
	}
}