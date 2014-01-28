<?php
namespace Mend\Metrics\Report;

use Mend\Collections\Map;
use Mend\Factory;
use Mend\FactoryCreator;
use Mend\IO\FileSystem\File;
use Mend\IO\FileSystem\FileArray;
use Mend\Metrics\Complexity\ComplexityAnalyzer;
use Mend\Metrics\Project\Project;
use Mend\Metrics\Project\ProjectReader;
use Mend\Metrics\Project\ProjectReport;
use Mend\Metrics\Project\EntityReport;
use Mend\Metrics\Report\Partition\PackagePartition;
use Mend\Metrics\UnitSize\UnitSizeAnalyzer;
use Mend\Parser\Node\Node;
use Mend\Parser\Node\PHPNode;
use Mend\Source\Code\Model\ClassModel;
use Mend\Source\Code\Model\Package;
use Mend\Source\Code\Model\PackageArray;
use Mend\Source\Code\Model\PackageHashTable;
use Mend\Source\Extract\EntityExtractor;
use Mend\Source\Code\Model\ClassModelArray;
use Mend\Source\Code\Model\MethodArray;
use Mend\Metrics\Report\Partition\ClassPartition;
use Mend\Metrics\Report\Partition\MethodPartition;
use Mend\Metrics\Report\Partition\FilePartition;

class ReportBuilder {
	/**
	 * @var Project
	 */
	private $project;

	/**
	 * @var ProjectReport
	 */
	private $report;

	/**
	 * @var Map
	 */
	private $factories;

	/**
	 * @var Map
	 */
	private $entityExtractors;

	/**
	 * @var ProjectReader
	 */
	private $projectReader;

	/**
	 * @var FileArray
	 */
	private $files;

	/**
	 * Constructs a new report builder for given project.
	 *
	 * @param Project $project
	 */
	public function __construct( Project $project ) {
		$this->project = $project;
		$this->report = new ProjectReport( $this->project );
		$this->factories = new Map();
		$this->entityExtractors = new Map();
	}

	/**
	 * Retrieves the built report.
	 *
	 * @return ProjectReport
	 */
	public function getReport() {
		return $this->report;
	}

	/**
	 * Retrieves the entity report.
	 *
	 * @return EntityReport
	 */
	private function getEntityReport() {
		if ( !$this->isEntityReportAvailable() ) {
			$this->report->addReport( ReportType::REPORT_ENTITY, new EntityReport() );
		}

		return $this->report->getReport( ReportType::REPORT_ENTITY );
	}

	/**
	 * Determines whether the entity report is generated.
	 *
	 * @return boolean
	 */
	private function isEntityReportAvailable() {
		return $this->report->hasReport( ReportType::REPORT_ENTITY );
	}

	/**
	 * Extracts entities from project.
	 *
	 * @return ReportBuilder
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

		$report = $this->getEntityReport();

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

	/**
	 * Retrieves an entity extractor by file.
	 *
	 * @param File $file
	 *
	 * @return EntityExtractor
	 */
	private function getEntityExtractor( File $file ) {
		$name = $file->getName();

		if ( !$this->entityExtractors->hasKey( $name ) ) {
			$factory = $this->getFactoryByFile( $file );
			$adapter = $factory->createParserAdapter();
			$mapper = $factory->createNodeMapper();

			$extractor = new EntityExtractor( $file, $adapter, $mapper );
			$this->entityExtractors->set( $name, $extractor );
		}

		return $this->entityExtractors->get( $name );
	}

	/**
	 * Analyzes complexity for the current report.
	 *
	 * @return ReportBuilder
	 */
	public function analyzeComplexity() {
		$entityReport = $this->getEntityReport();
		$methods = $entityReport->methods()->getMethods();

		$complexityAnalyzer = new ComplexityAnalyzer();

		foreach ( $methods as $method ) {
			/* @var $method Method */
			$factory = $this->getFactoryByNode( $method->getNode() );
			$mapper = $factory->createNodeMapper( $factory );
			$result = $complexityAnalyzer->computeComplexity( $method, $mapper );
			$method->complexity( $result );
		}

		return $this;
	}

	/**
	 * Analyzes unit size for the current report.
	 *
	 * @return ReportBuilder
	 */
	public function analyzeUnitSize() {
		$this->extractEntities();

		$entityReport = $this->getEntityReport();
		$methods = $entityReport->methods()->getMethods();

		$unitSizeAnalyzer = new UnitSizeAnalyzer();

		foreach ( $methods as $method ) {
			/* @var $method Method */
			$result = $unitSizeAnalyzer->calculateMethodSize( $method );
			$method->unitSize( $result );
		}

		return $this;
	}

	/**
	 * Retrieves a Factory instance for the given file.
	 *
	 * @param File $file
	 *
	 * @return Factory
	 */
	private function getFactoryByFile( File $file ) {
		$extension = strtolower( $file->getExtension() );
		return $this->getFactoryByType( $extension );
	}

	/**
	 * Retrieves a Factory instance from the given Node.
	 *
	 * @param Node $node
	 *
	 * @return Factory
	 *
	 * @throws \UnexpectedValueException
	 */
	private function getFactoryByNode( Node $node ) {
		if ( $node instanceof PHPNode ) {
			return $this->getFactoryByType( FactoryCreator::EXTENSION_PHP );
		}

		throw new \UnexpectedValueException( "Unknown node type " . get_class( $node ) );
	}

	/**
	 * Retrieves a Factory instance from the given type.
	 *
	 * @param string $type
	 *
	 * @return Factory
	 */
	private function getFactoryByType( $type ) {
		if ( !$this->factories->hasKey( $type ) ) {
			$creator = new FactoryCreator();
			$factory = $creator->createFactoryByFileExtension( $type );

			$this->factories->set( $type, $factory );
			return $factory;
		}

		return $this->factories->get( $type );
	}

	/**
	 * Retrieves the project reader.
	 *
	 * @return ProjectReader
	 */
	private function getProjectReader() {
		if ( is_null( $this->projectReader ) ) {
			$this->projectReader = new ProjectReader( $this->project );
		}

		return $this->projectReader;
	}

	/**
	 * Retrieves the files of the project.
	 *
	 * @return FileArray
	 */
	private function getFiles() {
		if ( is_null( $this->files ) ) {
			$reader = $this->getProjectReader();
			$this->files = $reader->getFiles();
		}

		return $this->files;
	}
}