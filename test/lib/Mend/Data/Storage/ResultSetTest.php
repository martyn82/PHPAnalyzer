<?php
namespace Mend\Data\Storage;

use Mend\Collections\Map;
use Mend\Data\DataPage;

class ResultSetTest extends \TestCase {
	public function testAccessors() {
		$records = $this->createRecordSet();
		$dataPage = new DataPage();
		$totalCount = 1;

		$result = new ResultSet( $records, $dataPage, $totalCount );

		self::assertEquals( $records, $result->getRecordSet() );
		self::assertEquals( $dataPage, $result->getDataPage() );
		self::assertEquals( $totalCount, $result->getTotalCount() );
	}

	private function createRecordSet() {
		return $this->getMockBuilder( '\Mend\Data\Storage\RecordSet' )
			->disableOriginalConstructor()
			->getMock();
	}
}
