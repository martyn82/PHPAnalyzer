<?php
namespace Mend\Metrics\Duplication;

class CodeBlockAnalyzer {
	/**
	 * @var integer
	 */
	const DEFAULT_CODE_BLOCK_SIZE = CodeBlock::DEFAULT_SIZE;

	/**
	 * Finds duplicates in the given array of code blocks and returns a hash table with duplicates.
	 *
	 * @param CodeBlockArray $blocks
	 *
	 * @return CodeBlockTable
	 */
	public function findDuplicates( CodeBlockArray $blocks ) {
		$hashTable = $this->arrayToHashTable( $blocks );
		$duplicateTable = $this->filterDuplicates( $hashTable );
		return new CodeBlockTable( $duplicateTable );
	}

	/**
	 * Counts and returns the number of lines duplicated in given blocks.
	 *
	 * @param CodeBlockTable $blocks
	 * @param integer $blockSize
	 *
	 * @return integer
	 */
	public function getDuplicateLines( CodeBlockTable $blocks, $blockSize = self::DEFAULT_CODE_BLOCK_SIZE ) {
		$startLines = $this->getStartLines( $blocks );
		return $this->countDuplicateLines( $startLines, (int) $blockSize );
	}

	/**
	 * Retrieves the code block start lines from the given hashtable.
	 *
	 * @param CodeBlockTable $blocks
	 *
	 * @return array
	 */
	private function getStartLines( CodeBlockTable $blocks ) {
		$lines = array_reduce(
			(array) $blocks,
			function ( array $result, array $bucket ) {
				$indices = array_map(
					function ( CodeBlock $block ) {
						return $block->getLocation()->getStart()->getLine();
					},
					$bucket
				);

				return array_merge( $result, $indices );
			},
			array()
		);

		$lines = array_unique( $lines );
		sort( $lines );

		return $lines;
	}

	/**
	 * Counts the duplicate lines.
	 *
	 * @param array $startLines
	 * @param integer $blockSize
	 *
	 * @return integer
	 */
	private function countDuplicateLines( array $startLines, $blockSize ) {
		$blockSize = (int) $blockSize;

		if ( empty( $startLines ) ) {
			return 0;
		}

		$start = array_shift( $startLines );
		$result = $blockSize;

		foreach ( $startLines as $index ) {
			if ( $index < ( $start + $blockSize ) ) {
				$result += ( $index - $start );
			}
			else {
				$result += $blockSize;
			}

			$start = $index;
		}

		return $result;
	}

	/**
	 * Filters the items from the table that have less than 2 items in the bucket.
	 *
	 * @param array $blocksHashTable
	 *
	 * @return array
	 */
	private function filterDuplicates( array $blocksHashTable ) {
		return array_filter(
			$blocksHashTable,
			function ( array $bucket ) {
				return count( $bucket ) > 1;
			}
		);
	}

	/**
	 * Converts the given array to a hash map array.
	 *
	 * @param CodeBlockArray $blocks
	 *
	 * @return array
	 */
	private function arrayToHashTable( CodeBlockArray $blocks ) {
		$result = array();

		foreach ( $blocks as $block ) {
			/* @var $block CodeBlock */
			$hash = $this->generateHash( $block->getSourceLines() );

			if ( !isset( $result[ $hash ] ) ) {
				$result[ $hash ] = array(
					$block
				);
			}
			else {
				$result[ $hash ][] = $block;
			}
		}

		return $result;
	}

	/**
	 * Hashes the given array of lines.
	 *
	 * @param array $lines
	 *
	 * @return string
	 */
	private function generateHash( array $lines ) {
		$normalized = array_map( 'trim', $lines );
		return implode( "\n", $normalized );
	}

	/**
	 * Retrieves the hash for the given lines.
	 *
	 * @param array $lines
	 *
	 * @return string
	 */
	public function getHash( array $lines ) {
		return $this->generateHash( $lines );
	}
}
