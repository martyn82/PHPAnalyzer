<?php
namespace Mend\Metrics\Report\Partition;

use Mend\Metrics\Duplication\CodeBlockTable;
use Mend\Metrics\Duplication\CodeBlock;

class CodeBlockPartition extends CodePartition {
	/**
	 * @var CodeBlockTable
	 */
	private $blocks;

	/**
	 * Creates an empty instance.
	 *
	 * @return CodeBlockPartition
	 */
	public static function createEmpty() {
		return new self( 0, 0, new CodeBlockTable() );
	}

	/**
	 * Constructs a new partition.
	 *
	 * @param integer $absolute
	 * @param float $relative
	 * @param CodeBlockTable $blocks
	 */
	public function __construct( $absolute, $relative, CodeBlockTable $blocks ) {
		parent::__construct( $absolute, $relative );
		$this->blocks = $blocks;
	}

	/**
	 * Retrieves the code blocks.
	 *
	 * @return CodeBlockTable
	 */
	public function getBlocks() {
		return $this->blocks;
	}

	/**
	 * @see CodePartition::toArray()
	 */
	public function toArray() {
		$result = parent::toArray();
		$blocks = array();

		foreach ( (array) $this->blocks as $hash => $bucket ) {
			$blocks[] = array_map(
				function ( CodeBlock $block ) {
					return array(
						'block' => $block->getSourceLines(),
						'location' => $block->getLocation()->__toString()
					);
				},
				$bucket
			);
		}

		$result[ 'blocks' ] = $blocks;
		return $result;
	}
}
