<?php
namespace Mend\Data;

class SortOptionsTest extends \TestCase {
	public function testEmpty() {
		$options = new SortOptions();
		self::assertEquals( array(), $options->toArray() );
	}

	/**
	 * @dataProvider sortOptionProvider
	 *
	 * @param array $sort
	 */
	public function testAddOption( array $sort ) {
		$options = new SortOptions();

		foreach ( $sort as $sortOption ) {
			$options->addSortField( key( $sortOption ), reset( $sortOption ) );
		}

		self::assertEquals(
			$sort,
			$options->toArray()
		);
	}

	public function testPrependOption() {
		$options = new SortOptions();

		$field1 = 'foo';
		$dir1 = SortDirection::ASCENDING;

		$data = array(
			0 => array( $field1 => $dir1 )
		);

		$options->addSortField( $field1, $dir1 );
		self::assertEquals( $data, $options->toArray() );

		$field2 = 'bar';
		$dir2 = SortDirection::DESCENDING;

		$newData = array(
			0 => array( $field2 => $dir2 ),
			1 => array( $field1 => $dir1 )
		);

		$options->addSortField( $field2, $dir2, true );
		self::assertEquals( $newData, $options->toArray() );
	}

	public function sortOptionProvider() {
		return array(
			array(
				array(
					array( 'foo' => SortDirection::ASCENDING ),
					array( 'bar' => SortDirection::DESCENDING )
				)
			)
		);
	}
}
