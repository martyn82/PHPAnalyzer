<?php
namespace Mend\Parser\Node;

class NodeMapperTest extends \TestCase {
	public function testGetMapped() {
		$mapping = array(
			'If' => 'Fi',
			'Else' => 'Esle',
			'While' => 'Elihw'
		);

		$mapper = new DummyMapper();
		$mapper->setMapping( $mapping );

		self::assertEquals( $mapping, $mapper->getMapping() );

		$nodes = array(
			'If',
			'While'
		);

		$expectedMapped = array_map(
			function ( $node ) use ( $mapping ) {
				return $mapping[ $node ];
			},
			$nodes
		);

		self::assertEquals( $expectedMapped, $mapper->getMapped( $nodes ) );
	}

	/**
	 * @expectedException \Exception
	 */
	public function testMapUnknown() {
		$mapper = new DummyMapper();
		$mapper->setMapping( array() );
		$mapper->getMapped( array( 'Foo' ) );

		self::fail( "Expected failure of non-existent mapping." );
	}
}

class DummyMapper extends NodeMapper {
	private $mapping;

	public function setMapping( array $mapping ) {
		$this->mapping = $mapping;
	}

	public function getMapping() {
		return $this->mapping;
	}
}