<?php
namespace Mend;

class PHPFactoryTest extends \TestCase {
	public function testCreateNodeMapper() {
		$factory = new PHPFactory();
		$mapper = $factory->createNodeMapper();

		self::assertInstanceOf( '\Mend\Parser\Node\PHPNodeMapper', $mapper );
	}

	public function testCreateSourceLineFilter() {
		$factory = new PHPFactory();
		$filter = $factory->createSourceLineFilter();

		self::assertInstanceOf( '\Mend\Source\Filter\PHPSourceLineFilter', $filter );
	}

	public function testCreateParserAdapter() {
		$factory = new PHPFactory();
		$adapter = $factory->createParserAdapter();

		self::assertInstanceOf( '\Mend\Parser\Adapter\PHPParserAdapter', $adapter );
	}
}