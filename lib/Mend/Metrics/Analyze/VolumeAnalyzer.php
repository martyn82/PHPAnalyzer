<?php
namespace Mend\Metrics\Analyze;

use \Mend\FileSystem\File;
use \Mend\FileSystem\FileArray;

use \Mend\Metrics\Extract\SourceExtractor;

use \Mend\Logging\Logger;
use Mend\Metrics\Extract\ModelExtractor;

class VolumeAnalyzer {
	/**
	 * Counts the total lines of given files.
	 *
	 * @param FileArray $files
	 *
	 * @return integer
	 */
	public static function getTotalLineCount( FileArray $files ) {
		Logger::info( "Counting total lines for files." );

		return array_reduce(
			(array) $files,
			function ( $result, File $file ) {
				return $result + VolumeAnalyzer::getLineCount( $file );
			},
			0
		);
	}

	/**
	 * Counts the total lines of code of given files.
	 *
	 * @param FileArray $files
	 *
	 * @return integer
	 */
	public static function getTotalLinesOfCodeCount( FileArray $files ) {
		Logger::info( "Counting total lines of code for files." );

		return array_reduce(
			(array) $files,
			function ( $result, File $file ) {
				return $result + VolumeAnalyzer::getLinesOfCodeCount( $file );
			},
			0
		);
	}

	/**
	 * Counts the number of files.
	 *
	 * @param FileArray $files
	 *
	 * @return integer
	 */
	public static function getFileCount( FileArray $files ) {
		return count( $files );
	}

	/**
	 * Counts the number of packages.
	 *
	 * @param FileArray $files
	 *
	 * @return integer
	 */
	public static function getPackageCount( FileArray $files ) {
		return 0; // TODO implement
	}

	/**
	 * Counts the number of classes.
	 *
	 * @param FileArray $files
	 *
	 * @return integer
	 */
	public static function getClassCount( FileArray $files ) {
		return 0; // TODO implement
	}

	/**
	 * Counts the number of methods.
	 *
	 * @param FileArray $files
	 *
	 * @return integer
	 */
	public static function getMethodCount( FileArray $files ) {
		return 0; // TODO implement
	}

	/**
	 * Counts the number of lines of the given file.
	 *
	 * @param File $file
	 *
	 * @return integer
	 */
	public static function getLineCount( File $file ) {
		Logger::info( "Getting line count for file <{$file->getName()}>..." );

		$fileLines = SourceExtractor::getLines( $file->getContents() );

		Logger::info( "Done getting line count." );
		return count( $fileLines );
	}

	/**
	 * Counts the number of lines of code of the given file.
	 *
	 * @param File $file
	 *
	 * @return integer
	 */
	public static function getLinesOfCodeCount( File $file ) {
		Logger::info( "Getting lines of code count for file <{$file->getName()}>..." );

		$fileLOC = SourceExtractor::getLinesOfCode( $file->getContents(), $file->getExtension() );

		Logger::info( "Done getting lines of code count." );
		return count( $fileLOC );
	}
}