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

		$project = $this->getMockBuilder( '\Mend\Metrics\Project\Project' )
			->setMethods( array( 'getRoot' ) )
			->setConstructorArgs( array( 'Foo', 'foo', $root ) )
			->getMock();

		$project->expects( self::any() )
			->method( 'getRoot' )
			->will( self::returnValue( $root ) );

		$reader = new ProjectReader( $project );
		self::assertNotNull( $reader );
	}

	public function testGetFilesEmptyDir() {
		$root = $this->getMockBuilder( '\Mend\IO\FileSystem\Directory' )
			->setConstructorArgs( array( $this->getDirName() ) )
			->disableOriginalConstructor()
			->getMock();

		$root->expects( self::any() )
			->method( 'getName' )
			->will( self::returnValue( $this->getDirName() ) );

		$root->expects( self::any() )
			->method( 'iterator' )
			->will( self::returnValue( new \DirectoryIterator( $this->getDirName() ) ) );

		$project = $this->getMockBuilder( '\Mend\Metrics\Project\Project' )
			->setMethods( array( 'getRoot' ) )
			->setConstructorArgs( array( 'foo', 'bar', $root ) )
			->getMock();

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
		$root = $this->getMockBuilder( '\Mend\IO\FileSystem\Directory' )
			->setConstructorArgs( array( $this->getDirName() ) )
			->disableOriginalConstructor()
			->getMock();

		$root->expects( self::any() )
			->method( 'getName' )
			->will( self::returnValue( $this->getDirName() ) );

		$root->expects( self::any() )
			->method( 'iterator' )
			->will( self::returnValue( new \DirectoryIterator( $this->getDirName() ) ) );

		$project = $this->getMockBuilder( '\Mend\Metrics\Project\Project' )
			->setMethods( array( 'getRoot' ) )
			->setConstructorArgs( array( 'foo', 'bar', $root ) )
			->getMock();

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
