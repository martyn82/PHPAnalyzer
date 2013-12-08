<?php
namespace Mend\Metrics\Analyze;

use \Mend\FileSystem\File;

use \Mend\Metrics\Model\Method;
use \Mend\Metrics\Extract\SourceExtractor;

use \Mend\Logging\Logger;

class UnitSizeAnalyzer {
	const LEVEL_SMALL = 1;
	const LEVEL_MEDIUM = 2;
	const LEVEL_LARGE = 3;
	const LEVEL_VERY_LARGE = 4;

	/**
	 * @var array
	 */
	private static $fileToSource = array();

	/**
	 * Retrieves the size of the method in lines of code.
	 *
	 * @param Method $method
	 *
	 * @return integer
	 */
	public static function getUnitSize( Method $method ) {
		$methodBody = self::getUnitBody( $method );
		return count( $methodBody );
	}

	/**
	 * Retrieves the body of the method.
	 *
	 * @param Method $method
	 */
	private static function getUnitBody( Method $method ) {
		Logger::info( "Getting body source for method <{$method->getName()}>..." );

		$location = $method->getLocation();
		$fileName = $location->getFileName();
		$fileNameParts = explode( ".", $fileName );

		$source = self::getFileSource( $fileName );
		$loc = SourceExtractor::getLinesOfCode( $source, end( $fileNameParts ) );

		$result = self::lineSlice( $loc, $location->getStartLine(), $location->getEndLine() );

		Logger::info( "Body source for method done." );
		return $result;
	}

	/**
	 * Retrieves a slice of the lines array.
	 *
	 * @param array $lines
	 * @param integer $start
	 * @param integer $end
	 *
	 * @return array
	 */
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

	/**
	 * Retrieves the size level of the given size.
	 *
	 * @param integer $size
	 *
	 * @return integer
	 */
	public static function getSizeLevel( $size ) {
		if ( $size <= 10 ) {
			return self::LEVEL_SMALL;
		}

		if ( $size > 10 && $size <= 20 ) {
			return self::LEVEL_MEDIUM;
		}

		if ( $size > 20 && $size <= 50 ) {
			return self::LEVEL_LARGE;
		}

		return self::LEVEL_VERY_LARGE;
	}

	/**
	 * Retrieves the source of the given file.
	 *
	 * @param string $fileName
	 *
	 * @return string
	 */
	private static function getFileSource( $fileName ) {
		if ( !isset( self::$fileToSource[ $fileName ] ) ) {
			$file = new File( $fileName );
			$source = $file->getContents();

			self::$fileToSource[ $fileName ] = $source;
		}

		return self::$fileToSource[ $fileName ];
	}
}