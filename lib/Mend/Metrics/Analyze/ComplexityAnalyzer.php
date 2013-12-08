<?php
namespace Mend\Metrics\Analyze;

use \Mend\Metrics\Model\Method;
use \Mend\Metrics\Model\ModelTraverser;
use \Mend\Metrics\Model\ModelVisitor;

use \Mend\Logging\Logger;

class ComplexityAnalyzer extends ModelVisitor {
	const RISK_LOW = 1;
	const RISK_MODERATE = 2;
	const RISK_HIGH = 3;
	const RISK_VERY_HIGH = 4;

	/**
	 * Computes the given method's complexity.
	 *
	 * @param Method $method
	 *
	 * @return integer
	 */
	public static function computeComplexity( Method $method ) {
		Logger::info( "Computing complexity of method <{$method->getName()}>." );

		$nodeTypes = array(
			'PHPParser_Node_Stmt_If',
			'PHPParser_Node_Stmt_ElseIf',
			'PHPParser_Node_Expr_Ternary',

			'PHPParser_Node_Stmt_For',
			'PHPParser_Node_Stmt_Foreach',
			'PHPParser_Node_Stmt_While',

			'PHPParser_Node_Stmt_Catch',
			'PHPParser_Node_Stmt_Case',

			'PHPParser_Node_Expr_LogicalAnd',
			'PHPParser_Node_Expr_LogicalOr'
		);

		return 1 + self::traverse( $method->getNode(), $nodeTypes );
	}

	/**
	 * Retrieves the risk level of given complexity.
	 *
	 * @param integer $complexity
	 *
	 * @return integer
	 */
	public static function getRiskLevel( $complexity ) {
		if ( $complexity <= 10 ) {
			return self::RISK_LOW;
		}

		if ( $complexity > 10 && $complexity <= 20 ) {
			return self::RISK_MODERATE;
		}

		if ( $complexity > 20 && $complexity <= 50 ) {
			return self::RISK_HIGH;
		}

		return self::RISK_VERY_HIGH;
	}

	/**
	 * Traverses the given node in search for nodes of certain types.
	 *
	 * @param \PHPParser_Node $node
	 * @param array $nodeTypes
	 *
	 * @return integer
	 */
	private static function traverse( \PHPParser_Node $node, array $nodeTypes ) {
		$visitor = new self( $nodeTypes );

		$traverser = new ModelTraverser();
		$traverser->addVisitor( $visitor );
		$traverser->traverse( array( $node ) );

		return $visitor->getResult();
	}

	/**
	 * Initializer.
	 */
	protected function init() {
		$this->result = 0;
	}

	/**
	 * Adds a node to the result.
	 *
	 * @param \PHPParser_Node $node
	 */
	protected function addResult( \PHPParser_Node $node ) {
		$this->result++;
	}
}