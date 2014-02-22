<?php
namespace Mend\Metrics\Project;

require_once realpath( __DIR__ . "/../../IO/Stream" ) . "/FileStreamTest.php";

use Mend\IO\FileSystem\Directory;
use Mend\IO\Stream\FileStreamTest;

class ProjectReaderTest extends FileStreamTest {
	public function testConstructor() {
		$root = new Directory( '/tmp/foo' );

		$project = $this->getMock( '\Mend\Metrics\Project\Project', array( 'getRoot' ), array( 'Foo', 'foo', $root ) );

		$project->expects( self::any() )
			->method( 'getRoot' )
			->will( self::returnValue( $root ) );

		$reader = new ProjectReader( $project );
		self::assertNotNull( $reader );
	}

	public function testGetFiles() {
		$root = $this->getMock( '\Mend\IO\FileSystem\Directory', array(), array( '/foo' ), '', false );

		$root->expects( self::any() )
			->method( 'getName' )
			->will( self::returnValue( '/foo' ) );

		$project = $this->getMock( '\Mend\Metrics\Project\Project', array( 'getRoot' ), array( 'foo', 'bar', $root ) );

		$project->expects( self::any() )
			->method( 'getRoot' )
			->will( self::returnValue( $root ) );

		$reader = $this->getMock(
			'\Mend\Metrics\Project\ProjectReader',
			array(),
			array( $project )
		);

		$reader->getFiles();
	}
}
