<?php
namespace Extract;

use Model\Method;
use Model\MethodArray;
use Model\ModelTree;
use Model\ModelVisitor;

use Parser\AST\PHPParserNode;

class ModelExtractor {
	public static function getMethods( ModelTree $model ) {
		return new MethodArray(
			array_map(
				function ( \PHPParser_Node $item ) use ( $model ) {
					return new Method( $item, $model );
				},
				self::traverse( $model, array( 'PHPParser_Node_Stmt_Function', 'PHPParser_Node_Stmt_ClassMethod' ) )
			)
		);
	}

	public static function getClasses( ModelTree $model ) {
		return self::traverse( $model, array( 'PHPParser_Node_Stmt_Class', 'PHPParser_Node_Stmt_Interface' ) );
	}

	public static function getImports( ModelTree $model ) {
		return self::traverse( $model, array( 'PHPParser_Node_Expr_Include' ) );
	}

	public static function getPackages( ModelTree $model ) {
		return self::traverse( $model, array( 'PHPParser_Node_Stmt_Namespace' ) );
	}

	public static function getUses( ModelTree $model ) {
		return self::traverse( $model, array( 'PHPParser_Node_Stmt_Use' ) );
	}

	private static function traverse( ModelTree $model, array $nodeTypes ) {
		$visitor = new ModelVisitor( $nodeTypes );

		$traverser = new \PHPParser_NodeTraverser();
		$traverser->addVisitor( $visitor );
		$traverser->traverse(
			array_map(
				function ( PHPParserNode $node ) {
					return $node->getNode();
				},
				(array) $model->getNodes()
			)
		);

		return $visitor->getResult();
	}
}