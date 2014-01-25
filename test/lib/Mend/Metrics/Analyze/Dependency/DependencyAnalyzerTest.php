<?php
namespace Mend\Metrics\Analyze\Dependency;

use Mend\IO\FileSystem\File;
use Mend\IO\FileSystem\FileArray;

use Mend\Metrics\Extract\EntityExtractor;
use Mend\Metrics\Extract\SourceFileExtractor;

use Mend\Metrics\Model\ModelTraverser;
use Mend\Metrics\Model\ModelVisitor;

use Mend\Metrics\Model\Code\ClassModel;
use Mend\Metrics\Model\Code\ClassModelArray;
use Mend\Metrics\Model\Code\Method;
use Mend\Metrics\Model\Code\MethodArray;
use Mend\Metrics\Model\Code\Package;
use Mend\Metrics\Model\Code\SourceUrl;

use Mend\Network\Web\Url;

use Mend\Parser\Adapter;
use Mend\Parser\Adapter\PHPParserAdapter;

use Mend\Parser\Node\PHPNode;
use Mend\Parser\Node\PHPNodeMapper;
use Mend\Parser\Node\Node;
use Mend\Parser\Node\NodeArray;
use Mend\Parser\Node\NodeMapper;
use Mend\Parser\Node\NodeType;

use Mend\Parser\Parser;

class DependencyAnalyzerTest extends \TestCase {
	private static $CODE_FRAGMENT_1 = <<<PHP
<?php
namespace Vendor;

use Vendor\Package\Bar;

class Foo {
	public function getFoo() {
		\$baz = new Baz();
		\Vendor\Package\Bar::boo();
		Bar::bah();
	}
}
PHP;

	private static $CODE_FRAGMENT_2 = <<<PHP
<?php
namespace Other;

class Baz {
	public function getBar() {
	}
}

PHP;

	private static $CODE_FRAGMENT_3 = <<<PHP
<?php
namespace Other;

use Vendor;
use Something;
use DoesNotCount;

class Baz {
	public function getBar() {
		Vendor::bla();
		Something::boo();
	}
}

class Baz2 {
	public function getBar2() {
		\$ext = new Extra();
		Something::boo();
	}
}

PHP;

	/**
	 * @var SourceUrl
	 */
	private $location;

	/**
	 * @dataProvider methodProvider
	 *
	 * @param array $methods
	 * @param array $expectedFanOuts
	 */
	public function testMethodFan( array $methods, array $expectedFanOuts ) {
		$analyzer = new DependencyAnalyzer();

		for ( $index = 0; $index < count( $methods ); $index++ ) {
			$fanOut = $analyzer->countMethodFanOut( $methods[ $index ], new PHPNodeMapper() );
			self::assertEquals( $expectedFanOuts[ $index ], $fanOut );
		}

		self::markTestIncomplete( "Need to implement methodFanIn" );
	}

	/**
	 * @return array
	 */
	public function methodProvider() {
		return array(
			array( $this->findMethods( self::$CODE_FRAGMENT_1, $this->getLocation() ), array( 2 ) ),
			array( $this->findMethods( self::$CODE_FRAGMENT_2, $this->getLocation() ), array( 0 ) ),
			array( $this->findMethods( self::$CODE_FRAGMENT_3, $this->getLocation() ), array( 2, 2 ) )
		);
	}

	/**
	 * @dataProvider classProvider
	 *
	 * @param array $classes
	 * @param array $expectedFanOuts
	 */
	public function testClassFan( array $classes, array $expectedFanOuts ) {
		$analyzer = new DependencyAnalyzer();

		for ( $index = 0; $index < count( $classes ); $index++ ) {
			$fanOut = $analyzer->countClassFanOut( $classes[ $index ], new PHPNodeMapper() );
			self::assertEquals( $expectedFanOuts[ $index ], $fanOut );
		}

		self::markTestIncomplete( "Need to implement classFanIn" );
	}

	/**
	 * @return array
	 */
	public function classProvider() {
		return array(
			array( $this->findClasses( self::$CODE_FRAGMENT_1, $this->getLocation() ), array( 2 ) ),
			array( $this->findClasses( self::$CODE_FRAGMENT_2, $this->getLocation() ), array( 0 ) ),
			array( $this->findClasses( self::$CODE_FRAGMENT_3, $this->getLocation() ), array( 2, 2 ) )
		);
	}

	/**
	 * @dataProvider packageProvider
	 *
	 * @param array $packages
	 * @param array $expectedFanOuts
	 */
	public function testPackageFan( array $packages, array $expectedFanOuts ) {
		$analyzer = new DependencyAnalyzer();

		for ( $index = 0; $index < count( $packages ); $index++ ) {
			$fanOut = $analyzer->countPackageFanOut( $packages[ $index ], new PHPNodeMapper() );
			self::assertEquals( $expectedFanOuts[ $index ], $fanOut );
		}

		self::markTestIncomplete( "Need to implement packageFanIn" );
	}

	/**
	 * @return array
	 */
	public function packageProvider() {
		return array(
			array( $this->findPackages( self::$CODE_FRAGMENT_1, $this->getLocation() ), array( 2 ) ),
			array( $this->findPackages( self::$CODE_FRAGMENT_2, $this->getLocation() ), array( 0 ) ),
			array( $this->findPackages( self::$CODE_FRAGMENT_3, $this->getLocation() ), array( 3 ) ),
		);
	}

	/**
	 * Retrieves a location.
	 *
	 * @return SourceUrl
	 */
	private function getLocation() {
		if ( is_null( $this->location ) ) {
			$this->location = new SourceUrl( Url::createFromString( "file:///tmp/foo.php#(0,0),(0,0)" ) );
		}

		return $this->location;
	}

	/**
	 * Parses the source and returns the ASTs.
	 *
	 * @param string $source
	 *
	 * @return array
	 */
	private function getAST( $source ) {
		$parser = new Parser( new PHPParserAdapter() );
		return $parser->parse( $source );
	}

	/**
	 * Finds method node from source.
	 *
	 * @param string $source
	 * @param SourceUrl $location
	 *
	 * @return array
	 */
	private function findMethods( $source, SourceUrl $location ) {
		$ast = $this->getAST( $source );
		$innerNodes = $this->searchMethods( $ast );

		if ( empty( $innerNodes ) ) {
			self::fail( "No method found in AST." );
		}

		$methods = array();
		foreach ( $innerNodes as $innerNode ) {
			$methods[] = new Method( new PHPNode( $innerNode ), $location );
		}
		return $methods;
	}

	/**
	 * @param array $tree
	 *
	 * @return array
	 */
	private function searchMethods( array $tree ) {
		$result = array();

		foreach ( $tree as $node ) {
			if ( $node instanceof \PHPParser_Node_Stmt_Class || $node instanceof \PHPParser_Node_Stmt_Namespace ) {
				$result = array_merge( $result, $this->searchMethods( $node->stmts ) );
			}

			if ( $node instanceof \PHPParser_Node_Stmt_ClassMethod ) {
				$result[] = $node;
			}
		}

		return $result;
	}

	/**
	 * Finds class node from source.
	 *
	 * @param string $source
	 * @param SourceUrl $location
	 *
	 * @return array
	 */
	private function findClasses( $source, SourceUrl $location ) {
		$ast = $this->getAST( $source );
		$innerNodes = $this->searchClasses( $ast );

		if ( empty( $innerNodes ) ) {
			self::fail( "No class found in AST." );
		}

		$classes = array();

		foreach ( $innerNodes as $innerNode ) {
			$class = new ClassModel( new PHPNode( $innerNode ), $location );
			$methods = $this->searchMethods( array( $innerNode ) );

			$classMethods = array();

			foreach ( $methods as $method ) {
				$classMethods[] = new Method( new PHPNode( $method ), $location );
			}

			$class->methods( new MethodArray( $classMethods ) );
			$classes[] = $class;
		}

		return $classes;
	}

	/**
	 * @param array $tree
	 *
	 * @return array
	 */
	private function searchClasses( array $tree ) {
		$result = array();
		foreach ( $tree as $node ) {
			if ( $node instanceof \PHPParser_Node_Stmt_Namespace ) {
				$result = array_merge( $result, $this->searchClasses( $node->stmts ) );
			}

			if ( $node instanceof \PHPParser_Node_Stmt_Class ) {
				$result[] = $node;
			}
		}

		return $result;
	}

	/**
	 * Finds package nodes in given source.
	 *
	 * @param string $source
	 * @param SourceUrl $location
	 *
	 * @return array
	 */
	private function findPackages( $source, SourceUrl $location ) {
		$ast = $this->getAST( $source );
		$innerNodes = $this->searchPackages( $ast );

		if ( empty( $innerNodes ) ) {
			self::fail( "No package found in AST." );
		}

		$packages = array();

		foreach ( $innerNodes as $innerNode ) {
			$package = new Package( new PHPNode( $innerNode ), $location );
			$classes = $this->searchClasses( array( $innerNode ) );

			$classModels = array();

			foreach ( $classes as $class ) {
				$classModel = new ClassModel( new PHPNode( $class ), $location );
				$methods = $this->searchMethods( array( $class ) );

				$classMethods = array();

				foreach ( $methods as $method ) {
					$classMethods[] = new Method( new PHPNode( $method ), $location );
				}

				$classModel->methods( new MethodArray( $classMethods ) );
				$classModels[] = $classModel;
			}

			$package->classes( new ClassModelArray( $classModels ) );
			$packages[] = $package;
		}

		return $packages;
	}

	/**
	 * @param array $tree
	 *
	 * @return array
	 */
	private function searchPackages( array $tree ) {
		$result = array();
		foreach ( $tree as $node ) {
			if ( $node instanceof \PHPParser_Node_Stmt_Namespace ) {
				$result[] = $node;
			}
		}

		return $result;
	}
}
