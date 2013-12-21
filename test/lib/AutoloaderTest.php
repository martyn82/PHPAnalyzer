<?php
class AutoloaderTest extends \TestCase {
	private $loader;
	private $files;

	public function setUp() {
		$map = $this->createPathToNamespaceMap();
		$this->files = array_keys( $map );

		$this->loader = $this->getMock( 'Autoloader', array( 'includeFile' ), array() );
		$this->loader->expects( self::any() )
			->method( 'includeFile' )
			->will( self::returnCallback( array( $this, 'includeFileStub' ) ) );

		foreach ( $map as $file => $ns ) {
			$this->loader->addNamespace( $ns, dirname( $file ) );
		}
	}

	public function testExistingFile() {
		$actual = $this->loader->loadClass( 'Foo\Bar\ClassName' );
		self::assertEquals( true, $actual, '/vendor/foo.bar/src/ClassName.php' );

		$actual = $this->loader->loadClass( 'Foo\Bar\ClassNameTest' );
		self::assertEquals( true, $actual, '/vendor/foo.bar/tests/ClassNameTest.php' );
	}

	public function testMissingFile() {
		$actual = $this->loader->loadClass( 'NoVendor\Package\NoClass' );
		self::assertEquals( false, $actual );
	}

	public function testDeepFile() {
		$actual = $this->loader->loadClass( 'Foo\Bar\Baz\Fooz\ClassName' );
		self::assertEquals( true, $actual, '/vendor/foo.bar.baz.fooz/src/ClassName.php' );
	}

	public function includeFileStub( $fileName ) {
		return in_array( $fileName, $this->files );
	}

	private function createPathToNamespaceMap() {
		return array(
			'/vendor/foo.bar/src/ClassName.php' => 'Foo\Bar',
			'/vendor/foo.bar/src/FooClassName.php' => 'Foo\Bar',
			'/vendor/foo.bar/tests/ClassNameTest.php' => 'Foo\Bar',
			'/vendor/foo.barfoo/src/ClassName.php' => 'Foo\BarFoo',
			'/vendor/foo.bar.baz/src/ClassName.php' => 'Foo\Bar\Baz',
			'/vendor/foo.bar.baz.fooz/src/ClassName.php' => 'Foo\Bar\Baz\Fooz'
		);
	}
}
