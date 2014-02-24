<?php
namespace Mend\Metrics\Report\Builder;

use Mend\Collections\Map;

use Mend\Metrics\Project\EntityReport;
use Mend\Metrics\Project\ProjectReader;

use Mend\Metrics\Report\Partition\ClassPartition;
use Mend\Metrics\Report\Partition\FilePartition;
use Mend\Metrics\Report\Partition\MethodPartition;
use Mend\Metrics\Report\Partition\PackagePartition;

use Mend\Metrics\Report\ReportBuilder;

use Mend\Source\Code\Model\ClassModelArray;
use Mend\Source\Code\Model\MethodArray;
use Mend\Source\Code\Model\PackageHashTable;
use Mend\Source\Code\Model\Package;

use Mend\Parser\Node\PHPNode;
use Mend\Logging\Logger;

class EntityReportBuilder extends ReportBuilder {
	/**
	 * @see ReportBuilder::init()
	 */
	protected function init() {
		$this->setReport( new EntityReport() );
	}

	/**
	 * Extracts entities from current project.
	 *
	 * @return EntityReportBuilder
	 */
	public function extractEntities() {
		$this->methodStart( __METHOD__ );

		$this->methodUpdate( __METHOD__, "Getting files..." );
		$files = $this->getFiles( $this->getFileExtensions() );

		$packagesTable = new PackageHashTable();
		$classesArray = array();
		$methodsArray = array();

		foreach ( $files as $file ) {
			/* @var $file File */
			$this->methodUpdate( __METHOD__, "Handling file {$file->getName()}" );

			$this->methodUpdate( __METHOD__, "Creating entity extractor..." );
			$extractor = $this->getEntityExtractor( $file );

			$this->methodUpdate( __METHOD__, "Getting packages..." );
			$packages = $extractor->getPackages();

			foreach ( $packages as $package ) {
				/* @var $package Package */
				$this->methodUpdate( __METHOD__, "Handling package {$package->getName()}" );

				$this->methodUpdate( __METHOD__, "Getting classes..." );
				$classes = $extractor->getClasses( $package );
				$classesArray = array_merge( $classesArray, (array) $classes );

				foreach ( $classes as $class ) {
					/* @var $class ClassModel */
					$this->methodUpdate( __METHOD__, "Handling class {$class->getName()}" );

					$this->methodUpdate( __METHOD__, "Getting methods..." );
					$methods = $extractor->getMethods( $class );
					$methodsArray = array_merge( $methodsArray, (array) $methods );
					$class->methods( $methods );
				}

				$package->classes( $classes );
				$packagesTable->add( $package->getName(), $package );
			}
		}

		$this->methodUpdate( __METHOD__, "Sorting packages..." );
		$packagesTable->ksort();

		$this->methodUpdate( __METHOD__, "Creating report..." );

		$report = $this->getReport();
		/* @var $report EntityReport */

		$packagePartition = new PackagePartition( 0, 0, $packagesTable );
		$report->packages( $packagePartition );

		$classPartition = new ClassPartition( 0, 0, new ClassModelArray( $classesArray ) );
		$report->classes( $classPartition );

		$methodPartition = new MethodPartition( 0, 0, new MethodArray( $methodsArray ) );
		$report->methods( $methodPartition );

		$filePartition = new FilePartition( 0, 0, $files );
		$report->files( $filePartition );

		$this->methodFinish( __METHOD__ );
		return $this;
	}

	/**
	 * Method start event.
	 *
	 * @param string $name
	 */
	private function methodStart( $name ) {
		Logger::debug( "{$name}(): start." );
	}

	/**
	 * Method update event.
	 *
	 * @param string $name
	 * @param string $message
	 */
	private function methodUpdate( $name, $message ) {
		Logger::debug( "{$name}(): {$message}" );
	}

	/**
	 * Method finish event.
	 *
	 * @param string $name
	 */
	private function methodFinish( $name ) {
		Logger::debug( "{$name}(): finished." );
	}
}