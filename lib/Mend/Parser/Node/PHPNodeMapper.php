<?php
namespace Mend\Parser\Node;

class PHPNodeMapper extends NodeMapper {
	/**
	 * @see NodeMapper::getMapping()
	 */
	protected function getMapping() {
		return array(
			NodeType::STATEMENT_IF => 'PHPParser_Node_Stmt_If',
			NodeType::STATEMENT_ELSEIF => 'PHPParser_Node_Stmt_ElseIf',
			NodeType::EXPRESSION_TERNARY => 'PHPParser_Node_Expr_Ternary',

			NodeType::STATEMENT_FOR => 'PHPParser_Node_Stmt_For',
			NodeType::STATEMENT_FOREACH => 'PHPParser_Node_Stmt_Foreach',
			NodeType::STATEMENT_WHILE => 'PHPParser_Node_Stmt_While',

			NodeType::STATEMENT_CATCH => 'PHPParser_Node_Stmt_Catch',
			NodeType::STATEMENT_CASE => 'PHPParser_Node_Stmt_Case',

			NodeType::STATEMENT_PACKAGE => 'PHPParser_Node_Stmt_Namespace',
			NodeType::STATEMENT_CLASS => 'PHPParser_Node_Stmt_Class',
			NodeType::STATEMENT_INTERFACE => 'PHPParser_Node_Stmt_Interface',
			NodeType::STATEMENT_IMPORT => 'PHPParser_Node_Stmt_Use',
			NodeType::STATEMENT_METHOD => 'PHPParser_Node_Stmt_ClassMethod',
			NodeType::STATEMENT_FUNCTION => 'PHPParser_Node_Stmt_Function',
			NodeType::STATEMENT_RETURN => 'PHPParser_Node_Stmt_Return',

			NodeType::EXPRESSION_LOGICAL_AND => 'PHPParser_Node_Expr_LogicalAnd',
			NodeType::EXPRESSION_LOGICAL_OR => 'PHPParser_Node_Expr_LogicalOr',
			NodeType::EXPRESSION_BOOLEAN_AND => 'PHPParser_Node_Expr_BooleanAnd',
			NodeType::EXPRESSION_BOOLEAN_OR => 'PHPParser_Node_Expr_BooleanOr',

			NodeType::EXPRESSION_NEW => 'PHPParser_Node_Expr_New',
			NodeType::EXPRESSION_METHOD_CALL => 'PHPParser_Node_Expr_MethodCall',
			NodeType::EXPRESSION_STATIC_CALL => 'PHPParser_Node_Expr_StaticCall'
		);
	}
}