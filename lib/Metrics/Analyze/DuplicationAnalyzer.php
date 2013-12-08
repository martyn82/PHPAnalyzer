<?php
namespace Metrics\Analyze;

use \FileSystem\File;
use \FileSystem\FileArray;

use \Metrics\Extract\SourceExtractor;

use \Metrics\Model\Duplication;
use \Metrics\Model\DuplicationModel;
use \Metrics\Model\Location;
use \Metrics\Model\LocationArray;

use \Logging\Logger;

class DuplicationAnalyzer {
	const BLOCK_SIZE = 6;

	/**
	 * Retrieves a series of duplications.
	 *
	 * @param \FileSystem\FileArray $files
	 *
	 * @return \Metrics\Model\DuplicationModel
	 */
	public static function getDuplications( FileArray $files ) {
		Logger::info( "Duplication analysis..." );

		$codeBlocks = array();

		// Divide file contents into blocks
		foreach ( $files as $file ) {
			$fileSource = SourceExtractor::getLinesOfCode( $file->getContents(), $file->getExtension() );
			$codeBlocks = array_merge( $codeBlocks, self::getCodeBlocks( $file, $fileSource ) );
		}

		// Map blocks to buckets of occurances locations
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

		// Filter to keep only the duplicate blocks
		$duplicateBlocks = array_filter(
			$codeBlocksMap,
			function ( array $bucket ) {
				return count( $bucket ) > 1;
			}
		);

		// TODO combine blocks that form a larger block

		$duplicatedLinesOfCode = self::getDuplicatedLineCount( $duplicateBlocks );

		$result = new DuplicationModel(
			array_map(
				function ( $block, array $locations ) {
					$locationArray = array_map(
						function ( array $location ) {
							return new Location(
								$location[ 'fileName' ],
								$location[ 'startLine' ],
								$location[ 'endLine' ]
							);
						},
						$locations
					);

					return new Duplication( $block, new LocationArray( $locationArray ) );
				},
				array_keys( $duplicateBlocks ),
				$duplicateBlocks
			),
			$duplicatedLinesOfCode
		);

		Logger::info( "Duplication analysis done." );

		return $result;
	}

	/**
	 * Divides the given file's contents into blocks.
	 *
	 * @param \FileSystem\File $file
	 * @param array $fileSource
	 *
	 * @return array
	 */
	private static function getCodeBlocks( File $file, array $fileSource ) {
		Logger::info( "Building code blocks for file <{$file->getName()}>..." );

		$length = count( $fileSource );
		$blocks = array();

		for ( $index = 0; $length >= ( $index + self::BLOCK_SIZE ); $index++ ) {
			$numbers = array_slice( array_keys( $fileSource ), $index, self::BLOCK_SIZE );
			$lines = array_slice( $fileSource, $index, self::BLOCK_SIZE );

			$blocks[] = array(
				'source' => $lines,
				'location' => array(
					'fileName' => $file->getName(),
					'startLine' => reset( $numbers ),
					'endLine' => end( $numbers ),
					'index' => $index
				)
			);
		}

		Logger::info( "Blocks created." );
		return $blocks;
	}

	/**
	 * Generates a hash for the given block.
	 *
	 * @param array $block
	 *
	 * @return string
	 */
	private static function generateBlockHash( array $block ) {
		return implode( "\n", $block );
	}

	/**
	 * Retrieves the number of duplicated lines.
	 *
	 * @param array $duplicateBlocks
	 *
	 * @return integer
	 */
	private static function getDuplicatedLineCount( array $duplicateBlocks ) {
		Logger::info( "Counting duplicated lines..." );

		$blockSize = self::BLOCK_SIZE;

		$indices = array_reduce(
			$duplicateBlocks,
			function ( array $result, array $bucket ) use ( $blockSize ) {
				$indices = array_map(
					function ( array $item ) {
						return $item[ 'index' ];
					},
					$bucket
				);

				return array_merge( $result, $indices );
			},
			array()
		);

		$result = DuplicationAnalyzer::countDuplicateLines( array_unique( $indices ), $blockSize );

		Logger::info( "Done counting duplicated lines." );
		return $result;
	}

	/**
	 * Counts the lines from line indices.
	 *
	 * @param array $indices
	 * @param integer $blockSize
	 *
	 * @return integer
	 */
	private static function countDuplicateLines( array $indices, $blockSize ) {
		if ( empty( $indices ) ) {
			return 0;
		}

		if ( count( $indices ) == 1 ) {
			return $blockSize;
		}

		sort( $indices );

		$start = array_shift( $indices );
		$size = $blockSize;

		foreach ( $indices as $index ) {
			if ( $index < ( $start + $blockSize ) ) {
				$size += ( $index - $start );
			}
			else {
				$size += $blockSize;
			}

			$start = $index;
		}

		return $size;
	}
}