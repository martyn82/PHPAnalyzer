<?php
namespace Mend\Cli;

use Mend\Cli\Command\AnalyzeCommand;
use Mend\Cli\Command\HelpCommand;

class Analyzer {
	const CURRENT_SCRIPT = '__CURRENT_SCRIPT__';

	/**
	 * @var array
	 */
	private $options;

	/**
	 * @var AnalyzeOptions
	 */
	private $settings;

	/**
	 * @var callable
	 */
	private $mapper;

	/**
	 * Retrieves a string that can be used to read CLI script options.
	 *
	 * @return string
	 */
	public static function getOptions() {
		$opts = array(
			Options::OPT_FILE_EXTENSIONS . ':',
			Options::OPT_CONFIGURATION_FILE . ':',
			Options::OPT_MEMORY_LIMIT . ':',
			Options::OPT_OUTPUT_FORMAT . ':',
			Options::OPT_ANALYSIS_PATH . ':',
			Options::OPT_TEMPLATE_PATH . ':',
			Options::OPT_VERBOSITY_FLAG,
			Options::OPT_HELP
		);

		return implode( '', $opts );
	}

	/**
	 * Constructs a new CLI analyzer with given options.
	 *
	 * @param array $options
	 * @param callable $variableMapper
	 *
	 * @throws \InvalidArgumentException
	 */
	public function __construct( array $options, callable $variableMapper ) {
		if ( !isset( $options[ self::CURRENT_SCRIPT ] ) ) {
			throw new \InvalidArgumentException( "The options array must contain __CURRENT_SCRIPT__ key." );
		}

		$this->options = $options;
		$this->mapper = $variableMapper;
		$this->settings = new AnalyzeOptions();
	}

	/**
	 * Retrieves the settings.
	 *
	 * @return AnalyzeOptions
	 */
	public function getSettings() {
		return $this->settings;
	}

	/**
	 * Runs the CLI analyzer.
	 *
	 * @return CommandResult
	 *
	 * @throws \InvalidArgumentException
	 */
	public function run() {
		foreach ( $this->options as $opt => $value ) {
			switch ( $opt ) {
				case Options::OPT_HELP:
					$help = new HelpCommand(
						$this->options[ self::CURRENT_SCRIPT ],
						AnalyzeOptions::DEFAULT_MEMORY_LIMIT
					);
					return $help->run();

				case Options::OPT_CONFIGURATION_FILE:
					$this->settings->setConfigFile( $value );
					break;

				case Options::OPT_MEMORY_LIMIT:
					$this->settings->setMemoryLimit( $value );
					break;

				case Options::OPT_OUTPUT_FORMAT:
					$this->settings->setOutputFormat( $value );
					break;

				case Options::OPT_FILE_EXTENSIONS:
					$this->settings->setFileExtensions( $value );
					break;

				case Options::OPT_ANALYSIS_PATH:
					$this->settings->setAnalysisPath( $value );
					break;

				case Options::OPT_TEMPLATE_PATH:
					$this->settings->setTemplate( $value );
					break;

				case Options::OPT_VERBOSITY_FLAG:
					$this->settings->setVerbose( true );
					break;

				case self::CURRENT_SCRIPT:
					// no-op
					break;

				default:
					throw new \InvalidArgumentException( "Unrecognized option: '{$opt}'." );
			}
		}

		$analyze = new AnalyzeCommand( $this->settings, $this->mapper );
		return $analyze->run();
	}
}