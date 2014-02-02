<?php
namespace Mend\Metrics\Complexity;

use Mend\Parser\Node\Node;
use Mend\Parser\Node\NodeMapper;
use Mend\Parser\Node\NodeType;
use Mend\Source\Code\ModelTraverser;
use Mend\Source\Code\Model\Method;

class ComplexityAnalyzer {
	/**
	 * Computes the complexity of the given method.
	 *
	 * @param Method $method
	 * @param NodeMapper $mapper
	 *
	 * @return ComplexityResult
	 */
	public function computeComplexity( Method $method, NodeMapper $mapper ) {
		$nodeTypes = $mapper->getMapped( $this->getNodeTypes() );

		$complexity = 1 + $this->traverse( $method->getNode(), $nodeTypes );
		$level = $this->getLevel( $complexity );

		return new ComplexityResult( $complexity, $level );
	}

	/**
	 * Traverses the given node in search of nodes of certain types.
	 *
	 * @param Node $node
	 * @param array $nodeTypes
	 *
	 * @return integer
	 */
	private function traverse( Node $node, array $nodeTypes ) {
		$visitor = new ComplexityVisitor( $nodeTypes );

		$traverser = new ModelTraverser();
		$traverser->addVisitor( $visitor );
		$traverser->traverse( array( $node->getInnerNode() ) );

		return $visitor->getResult();
	}

	/**
	 * Retrieves the level of the given complexity.
	 *
	 * @param integer $complexity
	 *
	 * @return integer
	 */
	private function getLevel( $complexity ) {
		if ( $complexity <= 10 ) {
			return ComplexityRisk::RISK_LOW;
		}

		if ( $complexity > 10 && $complexity <= 20 ) {
			return ComplexityRisk::RISK_MODERATE;
		}

		if ( $complexity > 20 && $complexity <= 50 ) {
			return ComplexityRisk::RISK_HIGH;
		}

		return ComplexityRisk::RISK_VERY_HIGH;
	}

	/**
	 * Retrieves the nodetypes to count for complexity.
	 *
	 * @return array
	 */
	private function getNodeTypes() {
		return array(
			NodeType::STATEMENT_IF,
			NodeType::STATEMENT_ELSEIF,
			NodeType::EXPRESSION_TERNARY,

			NodeType::STATEMENT_FOR,
			NodeType::STATEMENT_FOREACH,
			NodeType::STATEMENT_WHILE,

			NodeType::STATEMENT_CATCH,
			NodeType::STATEMENT_CASE,

			NodeType::EXPRESSION_LOGICAL_AND,
			NodeType::EXPRESSION_LOGICAL_OR,
			NodeType::EXPRESSION_BOOLEAN_AND,
			NodeType::EXPRESSION_BOOLEAN_OR
		);
	}
}
