<?php
namespace Mend\Data\Storage\Handler;

use Mend\Collections\Map;

class DefaultFileStorageHandlerTest extends \TestCase {
	public function setUp() {
		\FileSystem::resetResults();
		\FileSystem::setStatModeResult(
			octdec( \FileSystem::MODE_FILE )
			+ octdec( \FileSystem::MODE_READ_ALL )
			+ octdec( \FileSystem::MODE_WRITE_ALL )
		);
	}

	public function tearDown() {
		\FileSystem::resetResults();
	}

	public function testEntityExists() {
		$entities = $this->createEntityMap();
		$handler = new DefaultFileStorageHandler( $entities );

		self::assertTrue( $handler->entityExists( 'foo' ) );
		self::assertTrue( $handler->entityExists( 'bar' ) );
		self::assertFalse( $handler->entityExists( 'baz' ) );
	}

	/**
	 * @dataProvider identityProvider
	 *
	 * @param string $identity
	 */
	public function testFind( $identity ) {
		$entities = $this->createEntityMap();
		$directory = $entities->get( 'foo' );

		$iterator = $this->createIterator();

		$iterator->expects( self::any() )
			->method( 'valid' )
			->will(
				self::onConsecutiveCalls(
					true,
					true,
					true,
					true,
					false
				)
			);

		$iterator->expects( self::any() )
			->method( 'current' )
			->will(
				self::onConsecutiveCalls(
					$this->createIterator( false ),
					$this->createIterator( true ),
					$this->createIterator( true, null, 'json' ),
					$this->createIterator( true, $identity, 'json', 'test://foo', 1 )
				)
			);

		$directory->expects( self::any() )
			->method( 'iterator' )
			->will( self::returnValue( $iterator ) );

		\FileSystem::setFReadResult(
			json_encode(
				array( 'id' => 42, 'foo' => 'bar', 'baz' => 'bow' ),
				JSON_NUMERIC_CHECK
			)
		);

		$handler = new DefaultFileStorageHandler( $entities );
		$records = $handler->find( 'foo', $identity );

		self::assertInstanceOf( '\Mend\Data\Storage\RecordSet', $records );
	}

	public function testSave() {
		$recordSet = $this->createRecordSet();
		$records = $this->createRecords( 1 );

		$recordSet->expects( self::any() )
			->method( 'valid' )
			->will( self::onConsecutiveCalls( true, false ) );

		$recordSet->expects( self::any() )
			->method( 'current' )
			->will( self::onConsecutiveCalls( reset( $records ) ) );

		$entities = $this->createEntityMap();

		$directory = $entities->get( 'foo' );

		$directory->expects( self::any() )
			->method( 'getName' )
			->will( self::returnValue( 'test:///foo' ) );

		\FileSystem::setFReadResult( '{"foo": "bar"}' );

		$handler = new DefaultFileStorageHandler( $entities );
		$result = $handler->save( 'foo', $recordSet );

		self::assertInstanceOf( '\Mend\Data\Storage\RecordSet', $result );
	}

	public function testDelete() {
		$recordSet = $this->createRecordSet();
		$records = $this->createRecords( 1 );

		$recordSet->expects( self::any() )
			->method( 'valid' )
			->will( self::onConsecutiveCalls( true, false ) );

		$recordSet->expects( self::any() )
			->method( 'current' )
			->will( self::onConsecutiveCalls( reset( $records ) ) );

		$entities = $this->createEntityMap();

		$directory = $entities->get( 'foo' );

		$directory->expects( self::any() )
			->method( 'getName' )
			->will( self::returnValue( 'test:///foo' ) );

		$handler = new DefaultFileStorageHandler( $entities );
		$handler->delete( 'foo', $recordSet );
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testFindNonExistentEntity() {
		$entities = $this->createEntityMap();
		$handler = new DefaultFileStorageHandler( $entities );

		$handler->find( 'baz' );

		self::fail( "Test should have triggered an exception." );
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testSaveNonExistentEntity() {
		$entities = $this->createEntityMap();
		$records = $this->createRecordSet();

		$handler = new DefaultFileStorageHandler( $entities );

		$handler->save( 'baz', $records );

		self::fail( "Test should have triggered an exception." );
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testDeleteNonExistentEntity() {
		$entities = $this->createEntityMap();
		$records = $this->createRecordSet( 1 );

		$handler = new DefaultFileStorageHandler( $entities );

		$handler->delete( 'baz', $records );

		self::fail( "Test should have triggered an exception." );
	}

	public function identityProvider() {
		return array(
			array( null ),
			array( 42 )
		);
	}

	private function createRecordSet() {
		return $this->getMockBuilder( '\Mend\Data\Storage\RecordSet' )
			->disableOriginalConstructor()
			->getMock();
	}

	private function createRecords( $count = 0 ) {
		$records = array();

		for ( $i = 0; $i < $count; $i++ ) {
			$record = $this->getMockBuilder( '\Mend\Data\Storage\Record' )
				->disableOriginalConstructor()
				->getMock();

			$record->expects( self::any() )
				->method( 'getFields' )
				->will( self::returnValue( new Map() ) );

			$records[] = $record;
		}

		return $records;
	}

	private function createEntityMap() {
		return new EntityMap(
			array(
				'foo' => $this->createDirectory( 'foo' ),
				'bar' => $this->createDirectory( 'bar' )
			)
		);
	}

	private function createDirectory( $name ) {
		return $this->getMockBuilder( '\Mend\IO\FileSystem\Directory' )
			->setConstructorArgs( array( 'test://' . $name ) )
			->getMock();
	}

	/**
	 * @param boolean $isFile
	 * @param string $fileName
	 * @param string $extension
	 * @param string $path
	 * @param integer $size
	 *
	 * @return \DirectoryIterator
	 */
	private function createIterator( $isFile = false, $fileName = null, $extension = null, $path = null, $size = 0 ) {
		$iterator = $this->getMockBuilder( '\DirectoryIterator' )
			->disableOriginalConstructor()
			->getMock();

		$iterator->expects( self::any() )
			->method( 'isFile' )
			->will( self::returnValue( $isFile ) );

		$iterator->expects( self::any() )
			->method( 'getExtension' )
			->will( self::returnValue( $extension ) );

		$iterator->expects( self::any() )
			->method( 'getSize' )
			->will( self::returnValue( $size ) );

		$iterator->expects( self::any() )
			->method( 'getFilename' )
			->will( self::returnValue( $fileName ) );

		$iterator->expects( self::any() )
			->method( 'getPath' )
			->will( self::returnValue( $path ) );

		return $iterator;
	}
}
