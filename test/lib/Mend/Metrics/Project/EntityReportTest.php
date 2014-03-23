<?php
namespace Mend\Metrics\Project;

use Mend\Metrics\Report\Partition\ClassPartition;
use Mend\Metrics\Report\Partition\FilePartition;
use Mend\Metrics\Report\Partition\MethodPartition;
use Mend\Metrics\Report\Partition\PackagePartition;

class EntityReportTest extends \TestCase {
	public function testAccessors() {
		$report = new EntityReport();
		$emptyMethods = MethodPartition::createEmpty();
		$emptyFiles = FilePartition::createEmpty();
		$emptyPackages = PackagePartition::createEmpty();
		$emptyClasses = ClassPartition::createEmpty();

		self::assertEquals( $emptyMethods, $report->methods() );
		self::assertEquals( $emptyFiles, $report->files() );
		self::assertEquals( $emptyPackages, $report->packages() );
		self::assertEquals( $emptyClasses, $report->classes() );

		$methodArray = $this->getMock( '\Mend\Source\Code\Model\MethodArray' );

		$methods = $this->getMockBuilder( '\Mend\Metrics\Report\Partition\MethodPartition' )
			->setConstructorArgs( array( 10, 12, $methodArray ) )
			->getMock();

		$report->methods( $methods );

		self::assertEquals( $methods, $report->methods() );

		$expectedArray = array(
			EntityType::ENTITY_CLASSES => $emptyClasses->toArray(),
			EntityType::ENTITY_FILES => $emptyFiles->toArray(),
			EntityType::ENTITY_METHODS => $methods->toArray(),
			EntityType::ENTITY_PACKAGES => $emptyPackages->toArray()
		);

		self::assertEquals( $expectedArray, $report->toArray() );
	}
}
