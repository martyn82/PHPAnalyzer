<?php
namespace Mend\Source\Code\Model;

use Mend\Network\Web\Url;
use Mend\Metrics\Complexity\ComplexityResult;
use Mend\Metrics\UnitSize\UnitSizeResult;
use Mend\Source\Code\Location\SourceUrl;

class MethodTest extends \TestCase {
	public function testAccessors() {
		$node = $this->getMock( '\Mend\Parser\Node\Node', array(), array() );
		$sourceUrl = new SourceUrl( Url::createFromString( 'file:///tmp/foo#(1,0),(10,10)' ) );

		$method = new Method( $node, $sourceUrl );
		$unitSize = new UnitSizeResult( 1, 1 );
		$complexity = new ComplexityResult( 1, 1 );

		$method->unitSize( $unitSize );
		$method->complexity( $complexity );

		self::assertEquals( $node, $method->getNode() );
		self::assertEquals( $sourceUrl, $method->getSourceUrl() );
		self::assertEquals( $unitSize, $method->unitSize() );
		self::assertEquals( $complexity, $method->complexity() );

		$expectedArray = array(
			'name' => $node->getName(),
			'location' => $sourceUrl->__toString(),
			'unitSize' => $unitSize->toArray(),
			'complexity' => $complexity->toArray()
		);

		self::assertEquals( $expectedArray, $method->toArray() );
	}
}
