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
		$files = $this->getFiles();
		$packagesTable = new PackageHashTable();
		$classesArray = array();
		$methodsArray = array();

		foreach ( $files as $file ) {
			/* @var $file File */
			$extractor = $this->getEntityExtractor( $file );
			$packages = $extractor->getPackages();

			foreach ( $packages as $package ) {
				/* @var $package Package */
				$classes = $extractor->getClasses( $package );
				$classesArray = array_merge( $classesArray, (array) $classes );

				foreach ( $classes as $class ) {
					/* @var $class ClassModel */
					$methods = $extractor->getMethods( $class );
					$methodsArray = array_merge( $methodsArray, (array) $methods );
					$class->methods( $methods );
				}

				$package->classes( $classes );
				$packagesTable->add( $package->getName(), $package );
			}
		}

		$packagesTable->ksort();

		$report = $this->getReport();

		$packagePartition = new PackagePartition( 0, 0, $packagesTable );
		$report->packages( $packagePartition );

		$classPartition = new ClassPartition( 0, 0, new ClassModelArray( $classesArray ) );
		$report->classes( $classPartition );

		$methodPartition = new MethodPartition( 0, 0, new MethodArray( $methodsArray ) );
		$report->methods( $methodPartition );

		$filePartition = new FilePartition( 0, 0, $files );
		$report->files( $filePartition );

		return $this;
	}
}