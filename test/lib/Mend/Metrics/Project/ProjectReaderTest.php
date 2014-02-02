<?php
namespace Mend\Metrics\Project;

use Mend\IO\FileSystem\Directory;

class ProjectReaderTest extends \TestCase {
	public function testGetFiles() {
		$root = new Directory( '/tmp/foo' );

		$project = $this->getMock( '\Mend\Metrics\Project\Project', array( 'getRoot' ), array( 'Foo', 'foo', $root ) );
		$project->expects( self::any() )->method( 'getRoot' )->will( self::returnValue( $root ) );

		$reader = new ProjectReader( $project );
		self::assertNotNull( $reader );
	}
}
