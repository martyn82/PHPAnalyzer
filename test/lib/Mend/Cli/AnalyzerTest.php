<?php
namespace Mend\Cli;

use Mend\Logging\Logger;
use Mend\Logging\StreamHandler;
use Mend\IO\Stream\NullStreamWriter;
use Mend\Cli\Command\AnalyzeCommand;

class AnalyzerTest extends \TestCase {
	private static $INI_CONFIG = <<<INI
[project]
key = projectkey
name = Project Name
path = ./

[system]
memory = 200M
INI;

	public function setUp() {
		\FileSystem::resetResults();
		\FileSystem::setStatModeResult( octdec( \FileSystem::MODE_FILE ) + octdec( \FileSystem::MODE_READ_ALL ) );
		Logger::registerHandler( new StreamHandler( new NullStreamWriter() ) );
	}

	public function tearDown() {
		\FileSystem::resetResults();
	}

	public function testGetOptions() {
		$options = Analyzer::getOptions();

		self::assertTrue( in_array( Options::OPT_CONFIGURATION_FILE . ':', $options ) );
		self::assertTrue( in_array( Options::OPT_HELP, $options ) );
		self::assertTrue( in_array( Options::OPT_SUMMARIZE, $options ) );
		self::assertTrue( in_array( Options::OPT_VERBOSITY_FLAG, $options ) );
	}

	/**
	 * @dataProvider optionsProvider
	 *
	 * @param array $options
	 * @param string $config
	 */
	public function testRun( array $options, $config ) {
		$variableMapper = array( $this, 'variableMapper' );
		$analyzer = new DummyAnalyzer( $options, $variableMapper );

		$command = $this->getMockBuilder( '\Mend\Cli\Command\AnalyzeCommand' )
			->disableOriginalConstructor()
			->setMethods( array( 'run' ) )
			->getMock();

		$command->expects( self::any() )
			->method( 'run' )
			->will( self::returnValue( new CommandResult( 'foo', Status::STATUS_OK ) ) );

		$analyzer->setAnalyzeCommand( $command );

		$settings = $analyzer->getSettings();

		self::assertInstanceOf( '\Mend\Cli\AnalyzeOptions', $settings );

		if ( isset( $options[ Options::OPT_CONFIGURATION_FILE ] ) ) {
			self::assertEquals( $options[ Options::OPT_CONFIGURATION_FILE ], $settings->getConfigFile() );
		}

		self::assertEquals( isset( $options[ Options::OPT_SUMMARIZE ] ), $settings->getSummarize() );
		self::assertEquals( isset( $options[ Options::OPT_VERBOSITY_FLAG ] ), $settings->getVerbose() );

		\FileSystem::setFReadResult( $config );

		$result = $analyzer->run();

		self::assertInstanceOf( '\Mend\Cli\CommandResult', $result );
		self::assertFalse( $result->isError() );
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testRunWithoutScriptName() {
		$variableMapper = array( $this, 'variableMapper' );
		$options = array();

		$analyzer = new Analyzer( $options, $variableMapper );
		self::fail( "Test should have triggered an exception." );
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testRunInvalidOption() {
		$variableMapper = array( $this, 'variableMapper' );
		$options = array(
			Analyzer::CURRENT_SCRIPT => 'script',
			'non' => true // non-existent option
		);

		$analyzer = new Analyzer( $options, $variableMapper );
		self::fail( "Test should have triggered an exception." );
	}

	public function variableMapper() { /* no-op */ }

	public function optionsProvider() {
		return array(
			array(
				array(
					Analyzer::CURRENT_SCRIPT => 'script_name',
					Options::OPT_CONFIGURATION_FILE => 'test:///config.ini',
					Options::OPT_SUMMARIZE => true
				),
				self::$INI_CONFIG
			),
			array(
				array(
					Analyzer::CURRENT_SCRIPT => 'script_name',
					Options::OPT_CONFIGURATION_FILE => 'test:///config.ini',
					Options::OPT_VERBOSITY_FLAG => true
				),
				self::$INI_CONFIG
			),
			array(
				array(
					Analyzer::CURRENT_SCRIPT => 'script_name',
					Options::OPT_CONFIGURATION_FILE => 'test:///config.ini',
					Options::OPT_SUMMARIZE => true
				),
				self::$INI_CONFIG
			),
			array(
				array(
					Analyzer::CURRENT_SCRIPT => 'script_name',
					Options::OPT_HELP => true
				),
				null
			)
		);
	}
}

class DummyAnalyzer extends Analyzer {
	private $analyzeCommand;

	public function setAnalyzeCommand( AnalyzeCommand $command ) {
		$this->analyzeCommand = $command;
	}

	protected function runAnalyze() {
		return $this->analyzeCommand->run();
	}
}
