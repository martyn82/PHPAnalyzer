<?php
namespace Mend\Metrics\Extract;

use \Mend\FileSystem\File;

use \Mend\Metrics\Model\Location;
use \Mend\Metrics\Model\Model;
use \Mend\Metrics\Model\ModelTraverser;
use \Mend\Metrics\Model\ModelVisitor;
use \Mend\Metrics\Model\Method;
use \Mend\Metrics\Model\MethodArray;

use \Mend\Parser\Parser;
use \Mend\Parser\Adapter\PHPParserAdapter;

use \Mend\Logging\Logger;

class ModelExtractor extends ModelVisitor {
	/**
	 * @var Parser
	 */
	private static $parser;

	/**
	 * Creates a Model from given file.
	 *
	 * @param File $file
	 *
	 * @return Model
	 */
	public static function createModelFromFile( File $file ) {
		Logger::info( "Creating model from file <{$file->getName()}>..." );

		$fileSource = $file->getContents();
		$fileLines = SourceExtractor::getLines( $fileSource );

		$lineCount = count( $fileLines );
		$location = new Location( $file->getName(), 1, $lineCount );

		$parser = self::getParser();
		$ast = $parser->parse( $fileSource );

		return new Model( $location, $ast );
	}

	/**
	 * Retrieves all methods from the given Model.
	 *
	 * @param Model $model
	 *
	 * @return MethodArray
	 */
	public static function getMethodsFromModel( Model $model ) {
		$modelLocation = $model->getLocation();
		$fileName = $modelLocation->getFileName();

		return new MethodArray(
			array_map(
				function ( \PHPParser_Node $node ) use ( $fileName ) {
					$startLine = $node->getAttribute( 'startLine' );
					$endLine = $node->getAttribute( 'endLine' );
					$methodLocation = new Location( $fileName, $startLine, $endLine );
					return new Method( $node, $methodLocation );
				},
				self::traverse( $model, array( 'PHPParser_Node_Stmt_Function', 'PHPParser_Node_Stmt_ClassMethod' ) )
			)
		);
	}

	/**
	 * Traverses the given model in search of nodes which type occurs in the node types array.
	 *
	 * @param Model $model
	 * @param array $nodeTypes
	 *
	 * @return array
	 */
	private static function traverse( Model $model, array $nodeTypes ) {
		$visitor = new self( $nodeTypes );

		$traverser = new ModelTraverser();
		$traverser->addVisitor( $visitor );
		$traverser->traverse( (array) $model->getAST() );

		return $visitor->getResult();
	}

	/**
	 * Retrieves the parser.
	 *
	 * @return Parser
	 */
	private static function getParser() {
		if ( is_null( self::$parser ) ) {
			self::$parser = new Parser( new PHPParserAdapter() );
		}

		return self::$parser;
	}

	/**
	 * Initializer.
	 */
	protected function init() {
		$this->result = array();
	}

	/**
	 * Adds a node to the result.
	 *
	 * @param \PHPParser_Node $node
	 */
	protected function addResult( \PHPParser_Node $node ) {
		$this->result[] = $node;
	}
}