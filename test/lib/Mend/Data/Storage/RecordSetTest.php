<?php
namespace Mend\Data\Storage;

use Mend\Collections\Map;

class RecordSetTest extends \TestCase {
	public function testAccessors() {
		$records = array( $this->createRecord() );
		$recordSet = new RecordSet( $records );
		self::assertEquals( $records, $recordSet->toArray() );

		$newRecord = $this->createRecord();
		$recordSet->add( $newRecord );

		self::assertEquals( 2, $recordSet->size() );
	}

	private function createRecord() {
		return $this->getMockBuilder( '\Mend\Data\Storage\Record' )
			->disableOriginalConstructor()
			->getMock();
	}

	/**
	 * @expectedException \PHPUnit_Framework_Error
	 */
	public function testAddNonRecord() {
		$recordset = new RecordSet( array( new \stdClass() ) );
	}
}
