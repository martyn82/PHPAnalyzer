<?php
namespace Mend\Metrics\Project;

use Mend\IO\FileSystem\Directory;

class ProjectReaderTest extends \TestCase {
	public function setUp() {
		\FileSystem::resetResults();
	}

	public function tearDown() {
		\FileSystem::resetResults();
	}

	private function getDirName() {
		return \FileSystem::PROTOCOL . ':///foo';
	}

	public function testConstructor() {
		$root = new Directory( $this->getDirName() );

		$project = $this->getMock( '\Mend\Metrics\Project\Project', array( 'getRoot' ), array( 'Foo', 'foo', $root ) );

		$project->expects( self::any() )
			->method( 'getRoot' )
			->will( self::returnValue( $root ) );

		$reader = new ProjectReader( $project );
		self::assertNotNull( $reader );
	}

	public function testGetFilesEmptyDir() {
		$root = $this->getMock( '\Mend\IO\FileSystem\Directory', array(), array( $this->getDirName() ), '', false );

		$root->expects( self::any() )
			->method( 'getName' )
			->will( self::returnValue( $this->getDirName() ) );

		$project = $this->getMock( '\Mend\Metrics\Project\Project', array( 'getRoot' ), array( 'foo', 'bar', $root ) );

		$project->expects( self::any() )
			->method( 'getRoot' )
			->will( self::returnValue( $root ) );

		$entries = array(
			basename( $this->getDirName() ) => array(
				'.' => \FileSystem::DIR_MODE,
				'..' => \FileSystem::DIR_MODE
			)
		);

		\FileSystem::setReadDirResult( $entries );

		$reader = new ProjectReader( $project );
		$files = $reader->getFiles();

		self::assertEmpty( (array) $files );
	}

	/**
	 * @group current
	 */
	public function testGetFiles() {
		$root = $this->getMock(
			'\Mend\IO\FileSystem\Directory',
			array(),
			array( $this->getDirName() ),
			'',
			false
		);

		$root->expects( self::any() )
			->method( 'getName' )
			->will( self::returnValue( $this->getDirName() ) );

		$project = $this->getMock(
			'\Mend\Metrics\Project\Project',
			array( 'getRoot' ),
			array( 'foo', 'bar', $root )
		);

		$project->expects( self::any() )
			->method( 'getRoot' )
			->will( self::returnValue( $root ) );

		$rootName = $this->getDirName();

		$entries = array(
			'.' => \FileSystem::DIR_MODE,
			'..' => \FileSystem::DIR_MODE,
			'bar.php' => \FileSystem::FILE_MODE,
			'bar.ext' => \FileSystem::FILE_MODE,
			'baz' => \FileSystem::DIR_MODE
		);

		\FileSystem::setReadDirResult( $entries );

		$reader = new ProjectReader( $project );
		$files = $reader->getFiles( array( 'php' ) );

		self::assertEquals( 1, count( $files ) );
		self::assertEquals( 'test:///foo/bar.php', $files[ 0 ]->getName() );
	}
}
