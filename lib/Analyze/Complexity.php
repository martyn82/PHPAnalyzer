<?php
namespace Analyze;

use Extract\Normalizer\Normalizer;

use Model\Method;
use Model\MethodArray;

use Analyze\Complexity\MethodComplexity;

class Complexity implements \PHPParser_NodeVisitor {
	private $result;
	private $nodeTypes;

	public static function computeComplexity( Method $method ) {
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

	private static function traverse( \PHPParser_Node $node, array $nodeTypes ) {
		$visitor = new self( $nodeTypes );

		$traverser = new \PHPParser_NodeTraverser();
		$traverser->addVisitor( $visitor );
		$traverser->traverse( array( $node ) );

		return $visitor->getResult();
	}

	/**
	 * @deprecated
	 *
	 * @param array $partitions
	 * @param integer $totalSize
	 *
	 * @return array
	 */
	public static function getRelativeComplexities( array $partitions, $totalSize ) {
		$low = array_reduce(
			$partitions[ 'low' ],
			function ( $result, array $item ) {
				return $result + $item[ 'size' ];
			},
			0
		);
		$moderate = array_reduce(
			$partitions[ 'moderate' ],
			function ( $result, array $item ) {
				return $result + $item[ 'size' ];
			},
			0
		);
		$high = array_reduce(
			$partitions[ 'high' ],
			function ( $result, array $item ) {
				return $result + $item[ 'size' ];
			},
			0
		);
		$veryHigh = array_reduce(
			$partitions[ 'veryHigh' ],
			function ( $result, array $item ) {
				return $result + $item[ 'size' ];
			},
			0
		);

		return array(
			'low' => ( $low * 100 ) / $totalSize,
			'moderate' => ( $moderate * 100 ) / $totalSize,
			'high' => ( $high * 100 ) / $totalSize,
			'veryHigh' => ( $veryHigh * 100 ) / $totalSize
		);
	}

	public static function getPartitions( MethodArray $methods, Normalizer $normalizer ) {
		$low = array();
		$moderate = array();
		$high = array();
		$veryHigh = array();

		foreach ( $methods as $method ) {
			$methodSize = UnitSize::getUnitSize( $method, $normalizer );
			$complexity = self::computeComplexity( $method );
			$methodComplexity = new MethodComplexity( $method->getName(), $complexity, $methodSize );

			if ( $complexity <= 10 ) {
				$low[] = $methodComplexity;
			}

			if ( $complexity > 10 && $complexity <= 20 ) {
				$moderate[] = $methodComplexity;
			}

			if ( $complexity > 20 && $complexity <= 50 ) {
				$high[] = $methodComplexity;
			}

			if ( $complexity > 50 ) {
				$veryHigh[] = $methodComplexity;
			}
		}

		return array(
			'low' => $low,
			'moderate' => $moderate,
			'high' => $high,
			'veryHigh' => $veryHigh
		);
	}

	private function __construct( array $nodeTypes ) {
		$this->result = 0;
		$this->nodeTypes = $nodeTypes;
	}

	private function getResult() {
		return $this->result;
	}

	public function enterNode( \PHPParser_Node $node ) {
		$nodeClass = get_class( $node );

		if ( in_array( $nodeClass, $this->nodeTypes ) ) {
			$this->result++;
		}
	}

	public function beforeTraverse( array $nodes ) { /* noop */ }
	public function leaveNode( \PHPParser_Node $node ) { /* noop */ }
	public function afterTraverse( array $nodes ) { /* noop */ }
}