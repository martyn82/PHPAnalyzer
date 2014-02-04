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

class AnalyzeCommand extends Command {
	const OUTPUT_FORMAT_TEXT = 'text';
	const OUTPUT_FORMAT_JSON = 'json';

	/**
	 * @var AnalyzeOptions
	 */
	private $options;

	/**
	 * @var callable
	 */
	private $mapper;

	/**
	 * Retrieves an array of all supported output formats.
	 *
	 * @return array
	 */
	public static function getOutputFormats() {
		return array(
			self::OUTPUT_FORMAT_TEXT,
			self::OUTPUT_FORMAT_JSON
		);
	}

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
		if ( $this->options->getConfigFile() != null ) {
			$file = new File( $this->options->getConfigFile() );

			if ( $file->getExtension() != 'ini' ) {
				throw new \UnexpectedValueException( "Configuration file must be of type INI." );
			}

			$reader = new FileStreamReader( $file );
			$configReader = new IniConfigReader( $reader );
			return new ConfigProvider( $configReader );
		}

		$options = $this->options->toArray();

		$configReader = new ArrayConfigReader( $options );
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
		$reportType = $config->getString( 'report:type' );
		$validOutput = $this->validateOutputFormat( $reportType );

		$memoryLimit = $config->getString( 'system:memory' );
		$validMemLimit = $this->validateMemoryLimit( $memoryLimit );

		return $validOutput && $validMemLimit;
	}

	/**
	 * Validates the report type.
	 *
	 * @param string $reportType
	 *
	 * @return boolean
	 *
	 * @throws \UnexpectedValueException
	 */
	private function validateOutputFormat( $reportType ) {
		if ( !in_array( $reportType, self::getOutputFormats() ) ) {
			throw new \UnexpectedValueException( "Output format '{$reportType}' is invalid." );
		}

		return true;
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
		$report = $this->createReport( $config );
		$format = $config->getString( 'report:type' );

		switch ( $format ) {
			case self::OUTPUT_FORMAT_TEXT:
				$template = $this->getTemplate( $config );
				$mapped = call_user_func( $this->mapper, $report );
				$formatter = new TextReportFormatter( $template, $mapped );
				break;

			case self::OUTPUT_FORMAT_JSON:
				$formatter = new JsonReportFormatter();
				break;

			default:
				throw new \UnexpectedValueException( "Unrecognized output format: '{$format}'." );
		}

		$writer = new ReportWriter( $report, $formatter );
		return $writer->getReportAsString();
	}

	/**
	 * Creates a report from config.
	 *
	 * @param ConfigProvider $config
	 *
	 * @return ProjectReport
	 */
	private function createReport( ConfigProvider $config ) {
		$builder = $this->createReportBuilder( $config );
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
	 *
	 * @return ProjectReportBuilder
	 */
	private function createReportBuilder( ConfigProvider $config ) {
		$project = $this->createProject( $config );
		$fileExtensions = $config->getArray( 'analysis:extensions', array() );

		return new ProjectReportBuilder( $project, $fileExtensions );
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
		$projectRoot = realpath( $config->getString( 'project:path', getcwd() ) );

		return new Project( $projectName, $projectKey, new Directory( $projectRoot ) );
	}

	/**
	 * Retrieves the template.
	 *
	 * @param ConfigProvider $config
	 *
	 * @return string
	 *
	 * @throws \InvalidArgumentException
	 */
	private function getTemplate( ConfigProvider $config ) {
		$templatePath = $config->getString( 'report:template' );

		if ( $templatePath == null ) {
			throw new \InvalidArgumentException( "No template configured." );
		}

		$templateReader = new FileStreamReader( new File( $templatePath ) );
		$templateReader->open();
		$template = $templateReader->read();
		$templateReader->close();

		return $template;
	}
}