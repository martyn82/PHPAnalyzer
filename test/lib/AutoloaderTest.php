<?php
class AutoloaderTest extends \TestCase {
	private $loader;
	private $files;

	public function setUp() {
		$map = $this->createPathToNamespaceMap();
		$this->files = array_keys( $map );

		$this->loader = $this->getMock( 'Autoloader', array( 'includeFile' ) );
		$this->loader->expects( self::any() )
			->method( 'includeFile' )
			->will( self::returnCallback( array( $this, 'includeFileStub' ) ) );

		foreach ( $map as $file => $ns ) {
			$this->loader->addNamespace( $ns, dirname( $file ) );
		}
	}

	public function testExistingFile() {
		$actual = $this->loader->loadClass( 'Foo\Bar\ClassName' );
		self::assertTrue( $actual, '/vendor/foo.bar/src/ClassName.php' );

		$actual = $this->loader->loadClass( 'Foo\Bar\ClassNameTest' );
		self::assertTrue( $actual, '/vendor/foo.bar/tests/ClassNameTest.php' );
	}

	public function testMissingFile() {
		$actual = $this->loader->loadClass( 'NoVendor\Package\NoClass' );
		self::assertFalse( $actual );
	}

	public function testDeepFile() {
		$actual = $this->loader->loadClass( 'Foo\Bar\Baz\Fooz\ClassName' );
		self::assertTrue( $actual, '/vendor/foo.bar.baz.fooz/src/ClassName.php' );
	}

	public function testPrependNamespace() {
		$ns = 'Foo\Bar\Baz\Fooz';
		$file = '/lib/foo.bar.baz/fooz/src/ClassName.php';

		$this->loader->addNamespace( $ns, dirname( $file ), true );
		array_unshift( $this->files, dirname( $file ) );

		$actual = $this->loader->loadClass( 'Foo\Bar\Baz\Fooz\ClassName' );
		self::assertTrue( $actual, '/lib/foo.bar.baz/fooz/src/ClassName.php' );
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
