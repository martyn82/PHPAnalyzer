<?php
namespace Analyze;

use Extract\LinesExtractor;
use Extract\Normalizer\Normalizer;

use Model\Method;
use Model\MethodArray;
use Model\ModelTree;

class UnitSize {
	public static function getUnitSize( Method $method, Normalizer $normalizer ) {
		$body = self::getUnitBody( $method, $normalizer );
		return count( $body );
	}

	public static function getRelativeSizes( array $partitions, $totalSize ) {
		$small = array_reduce(
			$partitions[ 'small' ],
			function ( $result, array $item ) {
				return $result + $item[ 'size' ];
			},
			0
		);
		$medium = array_reduce(
			$partitions[ 'medium' ],
			function ( $result, array $item ) {
				return $result + $item[ 'size' ];
			},
			0
		);
		$large = array_reduce(
			$partitions[ 'large' ],
			function ( $result, array $item ) {
				return $result + $item[ 'size' ];
			},
			0
		);
		$huge = array_reduce(
			$partitions[ 'huge' ],
			function ( $result, array $item ) {
				return $result + $item[ 'size' ];
			},
			0
		);

		return array(
			'small' => ( $small * 100 ) / $totalSize,
			'medium' => ( $medium * 100 ) / $totalSize,
			'large' => ( $large * 100 ) / $totalSize,
			'huge' => ( $huge * 100 ) / $totalSize
		);
	}

	public static function getPartitions( MethodArray $methods, Normalizer $normalizer ) {
		$small = array();
		$medium = array();
		$large = array();
		$huge = array();

		foreach ( $methods as $method ) {
			$methodSize = self::getUnitSize( $method, $normalizer );

			if ( $methodSize <= 10 ) {
				$small[] = array(
					'method' => $method->getName(),
					'size' => $methodSize
				);
			}

			if ( $methodSize > 10 && $methodSize <= 20 ) {
				$medium[] = array(
					'method' => $method->getName(),
					'size' => $methodSize
				);
			}

			if ( $methodSize > 20 && $methodSize <= 50 ) {
				$large[] = array(
					'method' => $method->getName(),
					'size' => $methodSize
				);
			}

			if ( $methodSize > 50 ) {
				$huge[] = array(
					'method' => $method->getName(),
					'size' => $methodSize
				);
			}
		}

		return array(
			'small' => $small,
			'medium' => $medium,
			'large' => $large,
			'huge' => $huge
		);
	}

	private static function getUnitBody( Method $method, Normalizer $normalizer ) {
		$model = $method->getModel();
		$source = $model->getSource();
		$sourceLines = LinesExtractor::getSourceLines( $source );
		$loc = LinesExtractor::getLinesOfCode( $sourceLines, $normalizer );

		return self::lineSlice( $loc, $method->getStartLine(), $method->getEndLine() );
	}

	private static function lineSlice( array $lines, $start, $end ) {
		$slice = array();

		foreach ( $lines as $lineNumber => $line ) {
			if ( $lineNumber < $start ) {
				continue;
			}

			if ( $lineNumber > $end ) {
				break;
			}

			$slice[ $lineNumber ] = $line;
		}

		return $slice;
	}
}