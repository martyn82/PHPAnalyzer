<?php
namespace Mend\Rest;

class ResourceResultTest extends \TestCase {
	/**
	 * @dataProvider resultDataProvider
	 *
	 * @param array $data
	 * @param integer $pageNumber
	 * @param integer $resultCount
	 * @param integer $resultsPerPage
	 */
	public function testAccessors( array $data, $pageNumber, $resultCount, $resultsPerPage ) {
		$resourceResult = new ResourceResult( $data, $pageNumber, $resultCount, $resultsPerPage );

		self::assertEquals( $data, $resourceResult->getData() );
		self::assertEquals( $pageNumber, $resourceResult->getPageNumber() );
		self::assertEquals( $resultCount, $resourceResult->getTotalResultsCount() );
		self::assertEquals( $resultsPerPage, $resourceResult->getResultsPerPage() );
	}

	public function resultDataProvider() {
		return array(
			array(
				array(),
				mt_rand( 0, PHP_INT_MAX ),
				mt_rand( 0, PHP_INT_MAX ),
				mt_rand( 0, PHP_INT_MAX )
			),
			array(
				array( 1, 'foo', 'bar' ),
				mt_rand( 0, PHP_INT_MAX ),
				mt_rand( 0, PHP_INT_MAX ),
				mt_rand( 0, PHP_INT_MAX )
			)
		);
	}
}
