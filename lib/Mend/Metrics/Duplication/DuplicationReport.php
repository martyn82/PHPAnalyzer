<?php
namespace Mend\Metrics\Duplication;

use Mend\Metrics\Report\Report;
use Mend\Metrics\Report\Partition\CodeBlockPartition;

class DuplicationReport extends Report {
	/**
	 * @var string
	 */
	const PARTITION_KEY = 'duplications';

	/**
	 * Constructs a new report.
	 */
	public function __construct() {
		parent::__construct();
		$this->duplications( CodeBlockPartition::createEmpty() );
	}

	/**
	 * Gets/sets the duplications partition.
	 *
	 * @param CodeBlockPartition $partition
	 *
	 * @return CodeBlockPartition
	 */
	public function duplications( CodeBlockPartition $partition = null ) {
		if ( !is_null( $partition ) ) {
			$this->addPartition( self::PARTITION_KEY, $partition );
		}

		return $this->getPartition( self::PARTITION_KEY );
	}
}
