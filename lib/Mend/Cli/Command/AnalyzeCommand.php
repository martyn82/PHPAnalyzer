<?php
namespace Mend\Cli\Command;

use Mend\Cli\AnalyzeOptions;
use Mend\Cli\Command;
use Mend\Cli\CommandResult;
use Mend\Cli\Status;

use Mend\Config\ArrayConfigReader;
use Mend\Config\ConfigProvider;
use Mend\Config\IniConfigReader;

use Mend\IO\FileSystem\File;
use Mend\IO\FileSystem\Directory;
use Mend\IO\Stream\FileStreamReader;

use Mend\Metrics\Project\Project;

use Mend\Metrics\Report\ProjectReportBuilder;
use Mend\Metrics\Report\ReportType;

use Mend\Metrics\Report\Formatter\JsonReportFormatter;
use Mend\Metrics\Report\Formatter\TextReportFormatter;

use Mend\Metrics\Report\Writer\ReportWriter;
use Mend\Logging\Logger;

class AnalyzeCommand extends Command {
	/**
	 * @var AnalyzeOptions
	 */
	private $options;

	/**
	 * @var callable
	 */
	private $mapper;

	/**
	 * Constructs the analyze command.
	 *
	 * @param AnalyzeOptions $options
	 * @param callable $variableMapper
	 */
	public function __construct( AnalyzeOptions $options, callable $variableMapper ) {
		$this->options = $options;
		$this->mapper = $variableMapper;
	}

	/**
	 * @see Command::run()
	 */
	public function run() {
		$config = $this->createConfigProvider();
		$this->validateOptions( $config );

		$this->setMemoryLimit( $config->getString( 'system:memory' ) );

		$report = $this->createFormattedReport( $config );
		return new CommandResult( $report, Status::STATUS_OK );
	}

	/**
	 * Creates a configuration provider.
	 *
	 * @return ConfigProvider
	 *
	 * @throws \UnexpectedValueException
	 */
	private function createConfigProvider() {
		if ( $this->options->getConfigFile() == null ) {
			throw new \UnexpectedValueException( "No path to configuration file set." );
		}

		$file = new File( $this->options->getConfigFile() );

		// @todo abstract the choice of config reader instance into a factory
		if ( strtolower( $file->getExtension() ) != 'ini' ) {
			throw new \UnexpectedValueException( "Configuration file must be of type INI." );
		}

		$reader = new FileStreamReader( $file );
		$configReader = new IniConfigReader( $reader );

		return new ConfigProvider( $configReader );
	}

	/**
	 * Validates the options.
	 *
	 * @param ConfigProvider $config
	 *
	 * @return boolean
	 *
	 * @throws \UnexpectedValueException
	 */
	private function validateOptions( ConfigProvider $config ) {
		$memoryLimit = $config->getString( 'system:memory' );
		$validMemLimit = $this->validateMemoryLimit( $memoryLimit );

		return $validMemLimit;
	}

	/**
	 * Validates memory limit.
	 *
	 * @param string $memoryLimit
	 *
	 * @return boolean
	 *
	 * @throws \UnexpectedValueException
	 */
	private function validateMemoryLimit( $memoryLimit ) {
		if ( !preg_match( '/^\d+(M|G)$/', $memoryLimit ) ) {
			throw new \UnexpectedValueException( "The given memory limit is invald: '{$memoryLimit}'" );
		}

		return true;
	}

	/**
	 * Sets memory limit.
	 *
	 * @param string $memoryLimit
	 */
	private function setMemoryLimit( $memoryLimit ) {
		if ( $this->validateMemoryLimit( $memoryLimit ) ) {
			ini_set( 'memory_limit', $memoryLimit );
			Logger::info( "Set memory limit to {$memoryLimit}." );
		}
	}

	/**
	 * Creates and formats the report.
	 *
	 * @param ConfigProvider $config
	 *
	 * @return string
	 */
	private function createFormattedReport( ConfigProvider $config ) {
		$dateTime = $this->options->getDate() ? : new \DateTime();

		$report = $this->createReport( $config, $dateTime );

		if ( $this->options->getSummarize() ) {
			$template = $this->getTemplate( $config );
			$mapped = call_user_func( $this->mapper, $report );
			$formatter = new TextReportFormatter( $template, $mapped );
		}
		else {
			$formatter = new JsonReportFormatter();
		}

		$writer = new ReportWriter( $report, $formatter );
		return $writer->getReportAsString();
	}

	/**
	 * Creates a report from config.
	 *
	 * @param ConfigProvider $config
	 * @param \DateTime $dateTime
	 *
	 * @return ProjectReport
	 */
	private function createReport( ConfigProvider $config, \DateTime $dateTime ) {
		$builder = $this->createReportBuilder( $config, $dateTime );

		return $builder->extractEntities()
			->extractVolume()
			->analyzeUnitSize()
			->analyzeComplexity()
			->computeDuplications()
			->getReport();
	}

	/**
	 * Creates a report builder from config.
	 *
	 * @param ConfigProvider $config
	 * @param \DateTime $dateTime
	 *
	 * @return ProjectReportBuilder
	 */
	private function createReportBuilder( ConfigProvider $config, \DateTime $dateTime ) {
		$project = $this->createProject( $config );
		$fileExtensions = $config->getArray( 'analysis:extensions', array() );

		return new ProjectReportBuilder( $project, $dateTime, $fileExtensions );
	}

	/**
	 * Creates a project instance from config.
	 *
	 * @param ConfigProvider $config
	 *
	 * @return Project
	 */
	private function createProject( ConfigProvider $config ) {
		$projectKey = $config->getString( 'project:key', uniqid( 'proj' ) );
		$projectName = $config->getString( 'project:name', $projectKey );
		$projectRoot = $config->getString( 'project:path', getcwd() );

		return new Project( $projectName, $projectKey, new Directory( realpath( $projectRoot ) ) );
	}

	/**
	 * Retrieves the template.
	 *
	 * @return string
	 *
	 * @throws \InvalidArgumentException
	 */
	private function getTemplate() {
		$templatePath = $this->options->getTemplatePath();

		if ( $templatePath == null ) {
			throw new \InvalidArgumentException( "No summary template configured." );
		}

		$templateReader = new FileStreamReader( new File( $templatePath ) );
		$templateReader->open();
		$template = $templateReader->read();
		$templateReader->close();

		return $template;
	}
}