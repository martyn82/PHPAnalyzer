<?php
namespace Mend\Metrics\Project;

use Mend\Metrics\Report\Partition\ClassPartition;
use Mend\Metrics\Report\Partition\FilePartition;
use Mend\Metrics\Report\Partition\MethodPartition;
use Mend\Metrics\Report\Partition\PackagePartition;
use Mend\Metrics\Report\Report;

class EntityReport extends Report {
	/**
	 * Constructs a new report.
	 */
	public function __construct() {
		parent::__construct();

		$this->classes( ClassPartition::createEmpty() );
		$this->packages( PackagePartition::createEmpty() );
		$this->methods( MethodPartition::createEmpty() );
		$this->files( FilePartition::createEmpty() );
	}

	/**
	 * Gets/sets the classes partition.
	 *
	 * @param ClassPartition $partition
	 *
	 * @return ClassPartition
	 */
	public function classes( ClassPartition $partition = null ) {
		if ( !is_null( $partition ) ) {
			$this->addPartition( EntityType::ENTITY_CLASSES, $partition );
		}

		return $this->getPartition( EntityType::ENTITY_CLASSES );
	}

	/**
	 * Gets/sets the methods partition.
	 *
	 * @param MethodPartition $partition
	 *
	 * @return MethodPartition
	 */
	public function methods( MethodPartition $partition = null ) {
		if ( !is_null( $partition ) ) {
			$this->addPartition( EntityType::ENTITY_METHODS, $partition );
		}

		return $this->getPartition( EntityType::ENTITY_METHODS );
	}

	/**
	 * Gets/sets the files partition.
	 *
	 * @param FilePartition $partition
	 *
	 * @return FilePartition
	 */
	public function files( FilePartition $partition = null ) {
		if ( !is_null( $partition ) ) {
			$this->addPartition( EntityType::ENTITY_FILES, $partition );
		}

		return $this->getPartition( EntityType::ENTITY_FILES );
	}

	/**
	 * Gets/sets the packages partition.
	 *
	 * @param PackagePartition $partition
	 *
	 * @return PackagePartition
	 */
	public function packages( PackagePartition $partition = null ) {
		if ( !is_null( $partition ) ) {
			$this->addPartition( EntityType::ENTITY_PACKAGES, $partition );
		}

		return $this->getPartition( EntityType::ENTITY_PACKAGES );
	}
}
