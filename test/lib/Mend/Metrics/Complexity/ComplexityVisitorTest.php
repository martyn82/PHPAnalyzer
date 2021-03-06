<?php
namespace Mend\Metrics\Complexity;

use Mend\Metrics\Model\ModelVisitor;
use Mend\Parser\Node\Node;
use Mend\Parser\Node\NodeType;

class ComplexityVisitorTest extends \TestCase {
	public function testAddResult() {
		$ifNode = $this->getIfNode();
		$otherNode = $this->getOtherNode();

		$visitor = $this->getMockBuilder( '\Mend\Metrics\Complexity\ComplexityVisitor' )
			->setMethods( array( 'addResult' ) )
			->setConstructorArgs( array( array( 'PHPParser_Node_Stmt_If' ) ) )
			->getMock();

		$visitor->expects( self::once() )->method( 'addResult' );

		$visitor->enterNode( $ifNode );
		$visitor->enterNode( $otherNode );
	}

	public function testGetResult() {
		$ifNode = $this->getIfNode();
		$otherNode = $this->getOtherNode();

		$visitor = new ComplexityVisitor( array( 'PHPParser_Node_Stmt_If' ) );

		$visitor->enterNode( $ifNode );
		$visitor->enterNode( $otherNode );

		self::assertEquals( 1, $visitor->getResult() );
	}

	private function getIfNode() {
		$condition = $this->getConditionNode();
		return new \PHPParser_Node_Stmt_If( $condition );
	}

	private function getOtherNode() {
		$condition = $this->getConditionNode();
		return new \PHPParser_Node_Stmt_While( $condition );
	}

	private function getConditionNode() {
		$var = new \PHPParser_Node_Expr_Variable( 'x' );
		return new \PHPParser_Node_Expr_BooleanNot( $var );
	}
}
