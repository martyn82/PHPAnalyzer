<?php
namespace Mend\Data\Storage;

use Mend\Collections\Map;
use Mend\Data\DataPage;
use Mend\Data\SortOptions;

class FileStorageTest extends \TestCase {
	/**
	 * @dataProvider criteriaProvider
	 *
	 * @param string $entity
	 * @param boolean $exists
	 * @param Map $criteria
	 * @param SortOptions $sortOptions
	 * @param DataPage $dataPage
	 */
	public function testSelect( $entity, $exists, Map $criteria, SortOptions $sortOptions, DataPage $dataPage ) {
		$handler = $this->createHandler();

		$handler->expects( self::any() )
			->method( 'entityExists' )
			->will( self::returnValue( $exists ) );

		if ( !$exists ) {
			self::setExpectedException( '\InvalidArgumentException' );
		}

		$recordSet = $this->createRecordSet();

		$handler->expects( self::any() )
			->method( 'find' )
			->will( self::returnValue( $recordSet ) );

		$storage = new FileStorage( $handler );
		$resultSet = $storage->select( $entity, $criteria, $sortOptions, $dataPage );

		self::assertInstanceOf( '\Mend\Data\Storage\ResultSet', $resultSet );
	}

	public function criteriaProvider() {
		$criteria = $this->getMockBuilder( '\Mend\Collections\Map' )
			->setConstructorArgs( array( array( 'foo' => 'bar', 'baz' => 42 ) ) )
			->getMock();

		return array(
			array( 'foo', true, $criteria, new SortOptions(), new DataPage() ),
			array( 'foo', false, $criteria, new SortOptions(), new DataPage() )
		);
	}

	/**
	 * @dataProvider criteriaProvider
	 *
	 * @param string $entity
	 * @param boolean $exists
	 */
	public function testInsert( $entity, $exists ) {
		$records = $this->createRecordSet();

		$handler = $this->createHandler();

		$handler->expects( self::any() )
			->method( 'entityExists' )
			->will( self::returnValue( $exists ) );

		$handler->expects( self::any() )
			->method( 'save' )
			->will( self::returnValue( $records ) );

		if ( !$exists ) {
			self::setExpectedException( '\InvalidArgumentException' );
		}

		$storage = new FileStorage( $handler );
		$resultSet = $storage->insert( $entity, $records );

		self::assertInstanceOf( '\Mend\Data\Storage\ResultSet', $resultSet );
	}

	/**
	 * @dataProvider criteriaProvider
	 *
	 * @param string $entity
	 * @param boolean $exists
	 */
	public function testUpdate( $entity, $exists ) {
		$records = $this->createRecordSet();

		$handler = $this->createHandler();

		$handler->expects( self::any() )
			->method( 'entityExists' )
			->will( self::returnValue( $exists ) );

		$handler->expects( self::any() )
			->method( 'save' )
			->will( self::returnValue( $records ) );

		if ( !$exists ) {
			self::setExpectedException( '\InvalidArgumentException' );
		}

		$storage = new FileStorage( $handler );
		$resultSet = $storage->update( $entity, $records );

		self::assertInstanceOf( '\Mend\Data\Storage\ResultSet', $resultSet );
	}

	/**
	 * @dataProvider criteriaProvider
	 *
	 * @param string $entity
	 * @param boolean $exists
	 */
	public function testDelete( $entity, $exists ) {
		$records = $this->createRecordSet();

		$handler = $this->createHandler();

		$handler->expects( self::any() )
			->method( 'entityExists' )
			->will( self::returnValue( $exists ) );

		$handler->expects( self::any() )
			->method( 'delete' )
			->will( self::returnValue( $records ) );

		if ( !$exists ) {
			self::setExpectedException( '\InvalidArgumentException' );
		}

		$storage = new FileStorage( $handler );
		$resultSet = $storage->delete( $entity, $records );

		self::assertInstanceOf( '\Mend\Data\Storage\ResultSet', $resultSet );
	}

	private function createHandler() {
		return $this->getMock( '\Mend\Data\Storage\Handler\FileStorageHandler' );
	}

	private function createRecordSet() {
		return $this->getMockBuilder( '\Mend\Data\Storage\RecordSet' )
			->disableOriginalConstructor()
			->getMock();
	}
}
