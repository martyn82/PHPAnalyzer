<?php
namespace Mend\Cli\Command;

use Mend\Cli\AnalyzeOptions;
use Mend\Cli\Options;
use Mend\IO\Stream\IsReadable;

// mock functions {
function realpath( $path ) {
	return $path; // idempotent
}
// }

class AnalyzeCommandTest extends \TestCase {
	private static $INI_CONFIG = <<<INI
[project]
key = projectkey
name = Project Name
path = test:///foo

[system]
memory = 200M
INI;

	private static $INI_CONFIG_INVALID_MEMORY = <<<INI
[project]
key = projectkey
name = Project Name
path = test:///foo

[system]
memory = 200
INI;

	public function setUp() {
		IsReadable::$result = true;
		\FileSystem::resetResults();
	}

	public function tearDown() {
		IsReadable::$result = false;
		\FileSystem::resetResults();
	}

	/**
	 * @expectedException \UnexpectedValueException
	 */
	public function testCreateConfigProviderNoConfig() {
		$variableMapper = array( $this, 'variableMapper' );
		$options = $this->createOptions( array() );
		$command = new AnalyzeCommand( $options, $variableMapper );
		$command->run();

		self::fail( "Test should have triggered an exception." );
	}

	/**
	 * @expectedException \UnexpectedValueException
	 */
	public function testCreateConfigProviderNoIni() {
		$variableMapper = array( $this, 'variableMapper' );
		$options = $this->createOptions( array( Options::OPT_CONFIGURATION_FILE => 'test:///config' ) );
		$command = new AnalyzeCommand( $options, $variableMapper );
		$command->run();

		self::fail( "Test should have triggered an exception." );
	}

	/**
	 * @expectedException \UnexpectedValueException
	 */
	public function testValidateMemoryLimitInvalid() {
		\FileSystem::setFReadResult( self::$INI_CONFIG_INVALID_MEMORY );

		$variableMapper = array( $this, 'variableMapper' );
		$options = $this->createOptions( array( Options::OPT_CONFIGURATION_FILE => 'test:///config.ini' ) );
		$command = new AnalyzeCommand( $options, $variableMapper );
		$command->run();

		self::fail( "Test should have triggered an exception." );
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testSummarizeNoTemplate() {
		\FileSystem::setFReadResult( self::$INI_CONFIG );

		$variableMapper = array( $this, 'variableMapper' );
		$options = $this->createOptions(
			array(
				Options::OPT_CONFIGURATION_FILE => 'test:///config.ini',
				Options::OPT_SUMMARIZE => true
			)
		);

		self::assertTrue( $options->getSummarize() );

		$command = new AnalyzeCommand( $options, $variableMapper );
		$command->run();

		self::fail( "Test should have triggered an exception." );
	}

	/**
	 * @dataProvider optionsProvider
	 *
	 * @param AnalyzeOptions $options
	 */
	public function testRun( AnalyzeOptions $options ) {
		\FileSystem::setFReadResult( self::$INI_CONFIG );

		$variableMapper = array( $this, 'variableMapper' );

		if ( $options->getSummarize() ) {
			$options->setTemplatePath( 'test:///bar' );
		}

		$command = new AnalyzeCommand( $options, $variableMapper );
		$result = $command->run();

		self::assertInstanceOf( '\Mend\Cli\CommandResult', $result );
	}

	public function optionsProvider() {
		return array(
			array(
				$this->createOptions(
					array(
						Options::OPT_CONFIGURATION_FILE => 'test:///config.ini'
					)
				)
			),
			array(
				$this->createOptions(
					array(
						Options::OPT_CONFIGURATION_FILE => 'test:///config.iNi',
						Options::OPT_SUMMARIZE => true
					)
				)
			)
		);
	}

	private function createOptions( array $options ) {
		$opts = new AnalyzeOptions();

		$opts->setSummarize( isset( $options[ Options::OPT_SUMMARIZE ] ) );
		$opts->setVerbose( isset( $options[ Options::OPT_VERBOSITY_FLAG ] ) );

		if ( isset( $options[ Options::OPT_CONFIGURATION_FILE ] ) ) {
			$opts->setConfigFile( $options[ Options::OPT_CONFIGURATION_FILE ] );
		}

		return $opts;
	}

	public function variableMapper() {
		return array();
	}
}