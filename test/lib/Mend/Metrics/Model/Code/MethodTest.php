<?php
namespace Mend\Metrics\Model\Code;

use Mend\Metrics\Model\Code\Location;
use Mend\Metrics\Model\Code\SourceUrl;
use Mend\Network\Web\Url;

class MethodTest extends \TestCase {
	public function testAccessors() {
		$node = $this->getMock( '\Mend\Parser\Node\Node', array(), array() );
		$sourceUrl = new SourceUrl( Url::createFromString( 'file:///tmp/foo#(1,0),(10,10)' ) );

		$method = new Method( $node, $sourceUrl );

		self::assertEquals( $node, $method->getNode() );
		self::assertEquals( $sourceUrl, $method->getSourceUrl() );
	}
}
