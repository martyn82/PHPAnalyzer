<?php
namespace Analyze;

use FileSystem\File;
use FileSystem\FileArray;
use Extract\LinesExtractor;

class Volume {
	public static function getTotalLineCount( FileArray $files ) {
		return array_reduce(
			(array) $files,
			function ( $result, File $file ) {
				return $result + Volume::getLineCount( $file );
			},
			0
		);
	}

	public static function getTotalLinesOfCodeCount( FileArray $files ) {
		return array_reduce(
			(array) $files,
			function ( $result, File $file ) {
				return $result + Volume::getLinesOfCodeCount( $file );
			},
			0
		);
	}

	public static function getLineCount( File $file ) {
		$fileLines = LinesExtractor::getFileLines( $file );
		return count( $fileLines );
	}

	public static function getLinesOfCodeCount( File $file ) {
		$fileLOC = LinesExtractor::getFileLinesOfCode( $file );
		return count( $fileLOC );
	}
}
