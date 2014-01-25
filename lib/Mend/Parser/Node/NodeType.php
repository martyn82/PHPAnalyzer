<?php
namespace Mend\Parser\Node;

class NodeType {
	const STATEMENT_IF = 'ifStatement';
	const STATEMENT_ELSEIF = 'elseIfStatement';
	const STATEMENT_CATCH = 'catchStatement';
	const STATEMENT_CASE = 'caseStatement';
	const STATEMENT_FOR = 'forStatement';
	const STATEMENT_FOREACH = 'forEachStatement';
	const STATEMENT_WHILE = 'whileStatement';
	const STATEMENT_PACKAGE = 'packageStatement';
	const STATEMENT_CLASS = 'classStatement';
	const STATEMENT_INTERFACE = 'interfaceStatement';
	const STATEMENT_IMPORT = 'importStatement';
	const STATEMENT_METHOD = 'methodStatement';
	const STATEMENT_FUNCTION = 'functionStatement';
	const STATEMENT_RETURN = 'returnStatement';

	const EXPRESSION_TERNARY = 'ternaryExpression';
	const EXPRESSION_LOGICAL_AND = 'logicalAndExpression';
	const EXPRESSION_LOGICAL_OR = 'logicalOrExpression';
	const EXPRESSION_BOOLEAN_AND = 'booleanAndExpression';
	const EXPRESSION_BOOLEAN_OR = 'booleanOrExpression';
	const EXPRESSION_NEW = 'newExpression';
	const EXPRESSION_METHOD_CALL = 'methodCallExpression';
	const EXPRESSION_STATIC_CALL = 'staticCallExpression';
}
