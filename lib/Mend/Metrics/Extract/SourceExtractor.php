<?php
namespace Mend\Metrics\Extract;

use \Mend\Logging\Logger;

class SourceExtractor {
	/**
	 * @var array
	 */
	private static $normalizers = array();

	/**
	 * Retrieves a numbered array that is a map of line number to source line from given source string.
	 *
	 * @param string $source
	 *
	 * @return array( $lineNumber => $lineString )
	 */
	public static function getLines( $source ) {
		$lines = explode( "\n", $source );
		return array_combine(
			range( 1, count( $lines ) ),
			$lines
		);
	}

	/**
	 * Retrieves a numbered array that is a map of line number to source code line from the given source string.
	 *
	 * @param string $source
	 * @param string $language
	 *
	 * @return array( $lineNumber => $lineString )
	 */
	public static function getLinesOfCode( $source, $language ) {
		Logger::info( "Analyzing source lines of code..." );

		$normalizer = self::getNormalizer( $language );
		$lines = self::getLines( (string) $source );

		$lineIterator = new LineIterator( $lines );
		$linesOfCode = array();

		foreach ( $lineIterator as $lineNumber => $line ) {
			$line = trim( $line );

			if ( !$normalizer->isCode( $line ) ) {
				continue;
			}

			$linesOfCode[ $lineNumber ] = $line;
		}

		Logger::info( "Analysis of source lines of code done." );
		return $linesOfCode;
	}

	/**
	 * Retrieves a normalizer by extension.
	 *
	 * @param string $extension
	 *
	 * @return SourceNormalizer
	 */
	private static function getNormalizer( $extension ) {
		if ( !isset( self::$normalizers[ $extension ] ) ) {
			self::$normalizers[ $extension ] = SourceNormalizerFactory::createNormalizerByExtension( $extension );
		}

		return self::$normalizers[ $extension ];
	}
}