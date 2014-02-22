<?php
namespace Mend\Metrics\Project;

class ProjectTest extends \TestCase {
	public function testAccessors() {
		$name = 'TestProject';
		$key = 'test';
		$root = $this->getMock( '\Mend\IO\FileSystem\Directory', array(), array( '/tmp/foo' ) );

		$project = new Project( $name, $key, $root );

		self::assertEquals( $name, $project->getName() );
		self::assertEquals( $key, $project->getKey() );
		self::assertEquals( $root, $project->getRoot() );
		self::assertEquals( $root->getBaseName(), $project->getBaseFolder() );

		$expectedArray = array(
			'key' => $project->getKey(),
			'name' => $project->getName(),
			'path' => $project->getRoot()->getName()
		);

		self::assertEquals( $expectedArray, $project->toArray() );
	}
}