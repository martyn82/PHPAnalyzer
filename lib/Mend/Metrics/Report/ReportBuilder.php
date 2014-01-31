<?php
namespace Mend\Metrics\Report;

use Mend\Collections\Map;
use Mend\FactoryCreator;
use Mend\IO\FileSystem\File;
use Mend\IO\FileSystem\FileArray;
use Mend\Metrics\Project\Project;
use Mend\Metrics\Project\ProjectReader;
use Mend\Parser\Node\Node;
use Mend\Parser\Node\PHPNode;
use Mend\Source\Extract\EntityExtractor;

abstract class ReportBuilder {
	/**
	 * @var Report
	 */
	private $report;

	/**
	 * @var Project
	 */
	private $project;

	/**
	 * @var Map
	 */
	private $entityExtractors;

	/**
	 * @var Map
	 */
	private $factories;

	/**
	 * @var ProjectReader
	 */
	private $projectReader;

	/**
	 * @var array
	 */
	private $fileExtensions;

	/**
	 * Constructs a new report builder.
	 *
	 * @param Project $project
	 * @param array $fileExtensions
	 */
	public function __construct( Project $project, array $fileExtensions = null ) {
		$this->project = $project;
		$this->fileExtensions = $fileExtensions;
		$this->projectReader = new ProjectReader( $this->project );
		$this->entityExtractors = new Map();
		$this->factories = new Map();

		$this->init();
	}

	/**
	 * Initializer.
	 */
	abstract protected function init();

	/**
	 * Retrieves the file extensions.
	 *
	 * @return array
	 */
	protected function getFileExtensions() {
		return $this->fileExtensions;
	}

	/**
	 * Sets the report.
	 *
	 * @param Report $report
	 */
	protected function setReport( Report $report ) {
		$this->report = $report;
	}

	/**
	 * Retrieves the built report.
	 *
	 * @return Report
	 */
	public function getReport() {
		return $this->report;
	}

	/**
	 * Retrieves the project.
	 *
	 * @return Project
	 */
	protected function getProject() {
		return $this->project;
	}

	/**
	 * Retrieves an entity extractor by file.
	 *
	 * @param File $file
	 *
	 * @return EntityExtractor
	 */
	protected function getEntityExtractor( File $file ) {
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
	 * Retrieves the files from project.
	 *
	 * @param array $extensions
	 *
	 * @return FileArray
	 */
	protected function getFiles( array $extensions = null ) {
		return $this->projectReader->getFiles( $extensions );
	}

	/**
	 * Retrieves a Factory instance for the given file.
	 *
	 * @param File $file
	 *
	 * @return Factory
	 */
	protected function getFactoryByFile( File $file ) {
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
	protected function getFactoryByNode( Node $node ) {
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
	protected function getFactoryByType( $type ) {
		if ( !$this->factories->hasKey( $type ) ) {
			$creator = new FactoryCreator();
			$factory = $creator->createFactoryByFileExtension( $type );

			$this->factories->set( $type, $factory );
			return $factory;
		}

		return $this->factories->get( $type );
	}
}