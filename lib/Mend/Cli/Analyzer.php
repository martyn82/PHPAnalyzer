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
	 * Retrieves a the supported options.
	 *
	 * @return array
	 */
	public static function getOptions() {
		return array(
			Options::OPT_CONFIGURATION_FILE . ':',
			Options::OPT_SUMMARIZE,
			Options::OPT_VERBOSITY_FLAG,
			Options::OPT_HELP
		);
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

		$this->setOptions();
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
	 * Initializes the analyzer by setting options.
	 *
	 * @throws \InvalidArgumentException
	 */
	private function setOptions() {
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

				case Options::OPT_SUMMARIZE:
					$this->settings->setSummarize( true );
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
	}

	/**
	 * Runs the CLI analyzer.
	 *
	 * @return CommandResult
	 */
	public function run() {
		$this->setOptions();
		$analyze = new AnalyzeCommand( $this->settings, $this->mapper );
		return $analyze->run();
	}
}