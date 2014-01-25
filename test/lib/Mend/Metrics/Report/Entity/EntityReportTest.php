<?php
namespace Mend\Metrics\Report\Entity;

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

		$methodArray = $this->getMock( '\Mend\Metrics\Model\Code\MethodArray' );
		$methods = $this->getMock(
			'\Mend\Metrics\Report\Partition\MethodPartition',
			array(),
			array( 10, 12, $methodArray )
		);
		$report->methods( $methods );

		self::assertEquals( $methods, $report->methods() );
	}
}
