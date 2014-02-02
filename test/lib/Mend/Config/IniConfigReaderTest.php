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
		$stream = $this->getMock(
			'\Mend\IO\Stream\FileStreamReader',
			array( 'read', 'open', 'close' ),
			array(),
			'',
			false
		);
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
		$stream = $this->getMock(
			'\Mend\IO\Stream\FileStreamReader',
			array( 'read', 'open', 'close' ),
			array(),
			'',
			false
		);
		$stream->expects( self::any() )
			->method( 'read' )
			->will( self::returnValue( self::$INI_CONTENTS ) );

		$iniReader = new IniConfigReader( $stream );
		$iniReader->getValue( 'non-existent' );
	}

	public function iniStringProvider() {
		return array(
			array( self::$INI_CONTENTS, 'project.key', 'ProjectKey' ),
			array( self::$INI_CONTENTS, 'project.name', 'Project name' ),
			array( self::$INI_SECTIONS, 'production:project.key', 'ProjectKeyProd' ),
			array( self::$INI_SECTIONS, 'development:project.key', 'ProjectKeyDev' )
		);
	}
}
