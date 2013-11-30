<?php
namespace Analyze;

use Extract\LinesExtractor;

use FileSystem\File;
use FileSystem\FileArray;

class Duplication {
	const BLOCK_SIZE = 6;
	
	public static function getRelativeDuplicationCount( $duplicationCount, $totalSize ) {
		return ( $duplicationCount * 100 ) / $totalSize;
	}
	
	public static function getDuplicationCount( FileArray $files ) {
		$codeBlocks = array();
		
		foreach ( $files as $file ) {
			$fileSource = LinesExtractor::getFileLinesOfCode( $file );
			$codeBlocks = array_merge( $codeBlocks, self::getCodeBlocks( $file, $fileSource ) );
		}
		
		$codeBlocksMap = array();
		
		foreach ( $codeBlocks as $block ) {
			$blockHash = self::generateBlockHash( $block[ 'source' ] );
			
			if ( !isset( $codeBlocksMap[ $blockHash ] ) ) {
				$codeBlocksMap[ $blockHash ] = array(
					$block[ 'location' ]
				);
			}
			else {
				$codeBlocksMap[ $blockHash ][] = $block[ 'location' ];
			}
		}
		
		$duplicateCodeBlocks = array_filter(
			$codeBlocksMap,
			function ( array $bucket ) {
				return count( $bucket ) > 1;
			}
		);
		
		$blockSize = self::BLOCK_SIZE;
		
		return array_reduce(
			$duplicateCodeBlocks,
			function ( $result, array $bucket ) use ( $blockSize ) {
				return $result + count( $bucket ) * $blockSize;
			},
			0
		);
	}
	
	private static function getCodeBlocks( File $file, array $fileSource ) {
		$length = count( $fileSource );
		$index = 0;
		$blocks = array();
		
		while ( $length >= ( $index + self::BLOCK_SIZE ) ) {
			$lines = array_slice( $fileSource, $index, self::BLOCK_SIZE );
			
			$blocks[] = array(
				'source' => $lines,
				'location' => array(
					'file' => $file->getName(),
					'index' => $index
				)
			);
			
			$index++;
		}
		
		return $blocks;
	}
	
	private static function generateBlockHash( array $block ) {
		return md5( implode( "\n", $block ) );
	}
}