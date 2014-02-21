<?php
namespace Mend\Metrics\Complexity;

class ComplexityResultTest extends \TestCase {
	public function testAccessors() {
		$complexity = mt_rand( 1, PHP_INT_MAX );
		$level = mt_rand( 1, PHP_INT_MAX );
		$complexityResult = new ComplexityResult( $complexity, $level );

		self::assertEquals( $complexity, $complexityResult->getComplexity() );
		self::assertEquals( $level, $complexityResult->getLevel() );
	}

	public function testArrayConversion() {
		$complexity = mt_rand( 1, PHP_INT_MAX );
		$level = mt_rand( 1, PHP_INT_MAX );

		$subject = new ComplexityResult( $complexity, $level );
		$expected = array(
			'complexity' => $complexity,
			'level' => $level
		);

		self::assertEquals( $expected, $subject->toArray() );
	}
}
