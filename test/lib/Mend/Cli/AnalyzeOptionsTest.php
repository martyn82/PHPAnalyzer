<?php
namespace Mend\Cli;

class AnalyzeOptionsTest extends \TestCase {
	public function testConstructor() {
		$options = new AnalyzeOptions();

		self::assertNull( $options->getConfigFile() );
		self::assertNull( $options->getTemplatePath() );
		self::assertFalse( $options->getSummarize() );
		self::assertFalse( $options->getVerbose() );
	}

	/**
	 * @dataProvider dataProvider
	 *
	 * @param string $configFile
	 * @param string $templatePath
	 * @param boolean $summarize
	 * @param boolean $verbose
	 */
	public function testAccessors( $configFile, $templatePath, $summarize, $verbose ) {
		$options = new AnalyzeOptions();
		$options->setConfigFile( $configFile );
		$options->setTemplatePath( $templatePath );
		$options->setSummarize( $summarize );
		$options->setVerbose( $verbose );

		self::assertEquals( $configFile, $options->getConfigFile() );
		self::assertEquals( $templatePath, $options->getTemplatePath() );
		self::assertEquals( $summarize, $options->getSummarize() );
		self::assertEquals( $verbose, $options->getVerbose() );
	}

	public function dataProvider() {
		return array(
			array( 'foo config', 'bar template', false, false ),
			array( 'foo config', 'bar template', true, true ),
			array( 'foo config', 'bar template', false, true ),
			array( 'foo config', 'bar template', true, false )
		);
	}
}