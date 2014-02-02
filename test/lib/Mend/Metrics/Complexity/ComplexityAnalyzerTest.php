<?php
namespace Mend\Metrics\Complexity;

require_once PARSER_BOOTSTRAP;

use Mend\Network\Web\Url;
use Mend\Parser\Adapter\PHPParserAdapter;
use Mend\Parser\Parser;
use Mend\Parser\Node\Node;
use Mend\Parser\Node\NodeMapper;
use Mend\Parser\Node\NodeType;
use Mend\Parser\Node\PHPNode;
use Mend\Parser\Node\PHPNodeMapper;
use Mend\Source\Code\Location\SourceUrl;
use Mend\Source\Code\ModelVisitor;
use Mend\Source\Code\ModelTraverser;
use Mend\Source\Code\Model\Method;

class ComplexityAnalyzerTest extends \TestCase {
	private static $CODE_FRAGMENT_1 = <<<PHP
<?php

function fooMethod() {
	return null;
}
PHP;

	private static $CODE_FRAGMENT_2 = <<<PHP
<?php

function fooMethod() {
	if ( 1 < 12 ) {
		// do this
	}
	else if ( 33 > 1 ) {
	}
	else {
	}

	while ( true ) {
	}

	switch ( \$bla ) {
		case 1:
			break;
		case 2:
			break;
		case 3:
			break;
	}

	do {
	} while ( false );

	for ( \$i = 0; \$i < 100; \$i++ ) {
	}

	foreach ( \$iterable as \$i ) {
	}

	try {
	}
	catch ( UnexpectedValueException \$e ) {
	}
	catch ( Exception \$e ) {
	}
}
PHP;

	private static $CODE_FRAGMENT_3 = <<<PHP
<?php

function fooMethod() {
	\$a = true || false;
	\$b = true && false;
	\$c = true AND true OR false AND false;
}
PHP;

	/**
	 * @dataProvider methodSourceProvider
	 *
	 * @param string $source
	 * @param integer $complexity
	 * @param integer $risk
	 */
	public function testComplexityIntegration( $source, $complexity, $risk ) {
		$methodNode = $this->createMethodNode( $source );
		$sourceUrl = new SourceUrl( Url::createFromString( 'file:///tmp/foo.php' ) );

		$method = new Method( new PHPNode( $methodNode ), $sourceUrl );
		$mapper = new PHPNodeMapper();

		$analyzer = new ComplexityAnalyzer();
		$result = $analyzer->computeComplexity( $method, $mapper );

		self::assertEquals( $complexity, $result->getComplexity() );
		self::assertEquals( $risk, $result->getLevel() );
	}

	private function createMethodNode( $source ) {
		$adapter = new PHPParserAdapter();
		$parser = new Parser( $adapter );
		$ast = $parser->parse( $source );

		if ( empty( $ast ) ) {
			self::fail( "Parser returned an empty AST." );
		}

		return $ast[ 0 ];
	}

	public function methodSourceProvider() {
		return array(
			array( self::$CODE_FRAGMENT_1,  1, ComplexityRisk::RISK_LOW ),
			array( self::$CODE_FRAGMENT_2, 11, ComplexityRisk::RISK_MODERATE ),
			array( self::$CODE_FRAGMENT_3,  6, ComplexityRisk::RISK_LOW )
		);
	}
}
