<?php
namespace Mend\IO;

use Mend\IO\FileSystem\Directory;
use Mend\IO\FileSystem\File;
use Mend\IO\FileSystem\FileSystem;

class DirectoryStreamTest extends \TestCase {
	public function testDirectoryReturns() {
		$directory = $this->getMockBuilder( '\Mend\IO\FileSystem\Directory' )
			->setMethods( array( 'getName' ) )
			->setConstructorArgs( array( '/tmp' ) )
			->getMock();

		$directory->expects( self::any() )
			->method( 'getName' )
			->will( self::returnValue( '/tmp' ) );

		$stream = new DirectoryStream( $directory );
		self::assertEquals( $directory, $stream->getDirectory() );
	}

	public function testIteratorReturns() {
		$directory = $this->getMockBuilder( '\Mend\IO\FileSystem\Directory' )
			->setMethods( array( 'getName' ) )
			->setConstructorArgs( array( '/tmp' ) )
			->getMock();

		$directory->expects( self::any() )
			->method( 'getName' )
			->will( self::returnValue( '/tmp' ) );

		$stream = new DirectoryStream( $directory );
		self::assertTrue( $stream->getIterator() instanceof \DirectoryIterator );
	}

	public function testVisitDirectoryTree() {
		$dirItem = $this->createIterator( '/tmp/dir' );
		$dirItem->expects( self::any() )->method( 'valid' )->will( self::returnValue( false ) );

		$fileItem = $this->createIterator( '/tmp', 'file' );
		$fileItem->expects( self::any() )->method( 'valid' )->will( self::returnValue( false ) );

		$cursor = 0;

		$iterator = $this->createIterator( '/tmp' );
		$iterator->expects( self::any() )
			->method( 'valid' )
			->will(
				self::returnCallback(
					function () use ( & $cursor ) {
						return $cursor < 2;
					}
				)
			);
		$iterator->expects( self::any() )
			->method( 'next' )
			->will(
				self::returnCallback(
					function () use ( & $cursor ) {
						$cursor++;
					}
				)
			);
		$iterator->expects( self::any() )
			->method( 'current' )
			->will(
				self::returnCallback(
					function () use ( & $cursor, $dirItem, $fileItem ) {
						switch ( $cursor ) {
							case 0:
								return $dirItem;
							case 1:
								return $fileItem;
							default:
								return null;
						}
					}
				)
			);
	}

	private function createIterator( $path, $fileName = null ) {
		$isFile = !empty( $fileName );

		$iterator = $this->getMockBuilder( '\DirectoryIterator' )
			->setMethods( array(
				'current',
				'valid',
				'next',
				'isDir',
				'isFile',
				'getPath',
				'getFilename'
			) )
			->setConstructorArgs( array( $path . ( $isFile ? '' : FileSystem::DIRECTORY_SEPARATOR . $fileName ) ) )
			->disableOriginalConstructor()
			->getMock();

		$iterator->expects( self::any() )->method( 'isDir' )->will( self::returnValue( !$isFile ) );
		$iterator->expects( self::any() )->method( 'isFile' )->will( self::returnValue( $isFile ) );
		$iterator->expects( self::any() )->method( 'getPath' )->will( self::returnValue( $path ) );
		$iterator->expects( self::any() )->method( 'getFilename' )->will( self::returnValue( $fileName ) );

		return $iterator;
	}
}