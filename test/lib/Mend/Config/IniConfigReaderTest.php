<?php
namespace Mend\Config;

use Mend\IO\Stream\FileStreamReader;

class IniConfigReaderTest extends \TestCase {
	private static $INI_CONTENTS = <<<INI

project.key = ProjectKey
project.name = Project name

project.root = lib

INI;

	private static $INI_SECTIONS = <<<INI
[production]
project.key = ProjectKeyProd

[development]
project.key = ProjectKeyDev
INI;

	/**
	 * @dataProvider iniStringProvider
	 *
	 * @param string $iniContents
	 * @param string $searchKey
	 * @param mixed $expectedValue
	 */
	public function testReadIni( $iniContents, $searchKey, $expectedValue ) {
		$stream = $this->getMockBuilder( '\Mend\IO\Stream\FileStreamReader' )
			->disableOriginalConstructor()
			->setMethods( array( 'read', 'open', 'close' ) )
			->getMock();

		$stream->expects( self::any() )
			->method( 'read' )
			->will( self::returnValue( $iniContents ) );

		$iniReader = new IniConfigReader( $stream );

		$actualValue = $iniReader->getValue( $searchKey );
		self::assertEquals( $expectedValue, $actualValue );
	}

	/**
	 * @expectedException Mend\Config\ConfigurationException
	 */
	public function testReadIniNonExistent() {
		$stream = $this->getMockBuilder( '\Mend\IO\Stream\FileStreamReader' )
			->disableOriginalConstructor()
			->setMethods( array( 'read', 'open', 'close' ) )
			->getMock();

		$stream->expects( self::any() )
			->method( 'read' )
			->will( self::returnValue( self::$INI_CONTENTS ) );

		$iniReader = new IniConfigReader( $stream );
		$iniReader->getValue( 'non-existent' );

		self::fail( "Unexpected: Test should not reach here." );
	}

	public function iniStringProvider() {
		return array(
			array( self::$INI_CONTENTS, 'project.key', 'ProjectKey' ),
			array( self::$INI_CONTENTS, 'project.name', 'Project name' ),
			array( self::$INI_SECTIONS, 'production:project.key', 'ProjectKeyProd' ),
			array( self::$INI_SECTIONS, 'development:project.key', 'ProjectKeyDev' )
		);
	}

	public function testReload() {
		$stream = $this->getMockBuilder( '\Mend\IO\Stream\FileStreamReader' )
			->disableOriginalConstructor()
			->setMethods( array( 'read', 'open', 'close', 'isOpen' ) )
			->getMock();

		$contents = self::$INI_CONTENTS; // first run

		$stream->expects( self::any() )
			->method( 'isOpen' )
			->will( self::returnValue( true ) );

		$stream->expects( self::exactly( 2 ) )
			->method( 'read' )
			->will( self::returnCallback( function () use ( & $contents ) { return $contents; } ) );

		$iniReader = new IniConfigReader( $stream );
		$value = $iniReader->getValue( 'project.key' );
		self::assertEquals( 'ProjectKey', $value );

		// update for second run
		$contents = self::$INI_SECTIONS;

		$iniReader->reload();
		$value = $iniReader->getValue( 'production:project.key' );
		self::assertEquals( 'ProjectKeyProd', $value );
	}
}
