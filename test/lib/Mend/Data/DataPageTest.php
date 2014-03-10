<?php
namespace Mend\Data;

class DataPageTest extends \TestCase {
	public function testAccessors() {
		$page = new DataPage();

		self::assertEquals( 0, $page->getLimit() );
		self::assertEquals( 0, $page->getOffset() );

		$limit = mt_rand( 1, PHP_INT_MAX );
		$offset = mt_rand( 1, PHP_INT_MAX );

		$page = new DataPage( $limit, $offset );

		self::assertEquals( $limit, $page->getLimit() );
		self::assertEquals( $offset, $page->getOffset() );
	}
}
