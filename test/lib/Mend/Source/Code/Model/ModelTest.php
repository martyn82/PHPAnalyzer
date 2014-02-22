<?php
namespace Mend\Source\Code\Model;

use Mend\Network\Web\Url;
use Mend\Source\Code\Location\SourceUrl;

class ModelTest extends \TestCase {
	public function testAccessors() {
		$node = $this->getMock( '\Mend\Parser\Node\Node' );
		$sourceUrl = new SourceUrl( Url::createFromString( 'file:///tmp/foo#(1,0),(10,10)' ) );

		$model = new DummyModel( $node, $sourceUrl );

		self::assertTrue( $model->hasNode() );
		self::assertEquals( $sourceUrl, $model->getSourceUrl() );
		self::assertEquals( $node->getName(), $model->getName() );

		$expectedArray = array(
			'name' => $node->getName(),
			'location' => $sourceUrl->__toString()
		);

		self::assertEquals( $expectedArray, $model->toArray() );
	}
}

class DummyModel extends Model {
}