<?php
namespace Mend\Source\Extract;

use Mend\IO\FileSystem\File;
use Mend\Network\Web\Url;
use Mend\Parser\Adapter;
use Mend\Parser\Node\Node;
use Mend\Parser\Node\NodeMapper;
use Mend\Parser\Node\NodeType;
use Mend\Parser\Parser;
use Mend\Source\Code\ModelTraverser;
use Mend\Source\Code\Location\SourceUrl;
use Mend\Source\Code\Model\ClassModel;
use Mend\Source\Code\Model\ClassModelArray;
use Mend\Source\Code\Model\Method;
use Mend\Source\Code\Model\MethodArray;
use Mend\Source\Code\Model\Package;
use Mend\Source\Code\Model\PackageArray;

class EntityExtractor {
	/**
	 * @var SourceFileExtractor
	 */
	private $extractor;

	/**
	 * @var Adapter
	 */
	private $adapter;

	/**
	 * @var array
	 */
	private $ast;

	/**
	 * @var NodeMapper
	 */
	private $mapper;

	/**
	 * @var File
	 */
	private $file;

	/**
	 * Constructs a new entity extractor.
	 *
	 * @param File $file
	 * @param Adapter $adapter
	 * @param NodeMapper $mapper
	 */
	public function __construct( File $file, Adapter $adapter, NodeMapper $mapper ) {
		$this->file = $file;
		$this->adapter = $adapter;
		$this->mapper = $mapper;
	}

	/**
	 * Retrieves the source extractor for given file.
	 *
	 * @return SourceFileExtractor
	 */
	protected function getSourceExtractor() {
		if ( is_null( $this->extractor ) ) {
			$this->extractor = new SourceFileExtractor( $this->file );
		}

		return $this->extractor;
	}

	/**
	 * Retrieves the source of the file.
	 *
	 * @return string
	 */
	protected function getFileSource() {
		$extractor = $this->getSourceExtractor();
		return $extractor->getFileSource();
	}

	/**
	 * Retrieves the AST.
	 *
	 * @return array
	 */
	public function getAST() {
		if ( is_null( $this->ast ) ) {
			$source = $this->getFileSource();
			$parser = new Parser( $this->adapter );
			$this->ast = $parser->parse( $source );
		}

		return $this->ast;
	}

	/**
	 * Retrieves the node mapper.
	 *
	 * @return NodeMapper
	 */
	private function getNodeMapper() {
		return $this->mapper;
	}

	/**
	 * Creates a model traverser.
	 *
	 * @return ModelTraverser
	 */
	private function createTraverser() {
		return new ModelTraverser();
	}

	/**
	 * Creates a node visitor.
	 *
	 * @param array $nodeTypes
	 *
	 * @return EntityVisitor
	 */
	private function createVisitor( array $nodeTypes ) {
		return new EntityVisitor( $nodeTypes );
	}

	/**
	 * Traverses the current AST in search for given node types.
	 *
	 * @param array $nodeTypes
	 * @param array $ast
	 *
	 * @return array
	 */
	private function traverse( array $nodeTypes, array $ast ) {
		$visitor = $this->createVisitor( $nodeTypes );

		$traverser = $this->createTraverser();
		$traverser->addVisitor( $visitor );
		$traverser->traverse( $ast );

		return $visitor->getResult();
	}

	/**
	 * Creates a new SourceUrl instance based on current file and given locations.
	 *
	 * @param integer $startLine
	 * @param integer $endLine
	 *
	 * @return SourceUrl
	 */
	private function createSourceUrl( $startLine, $endLine ) {
		return new SourceUrl(
			Url::createFromString(
				'file://' . $this->file->getName() . '#'
				. sprintf( "(%d,0),(%d,0)", (int) $startLine, (int) $endLine )
			)
		);
	}

	/**
	 * Retrieves all method nodes.
	 *
	 * @param ClassModel $class
	 *
	 * @return MethodArray
	 */
	public function getMethods( ClassModel $class = null ) {
		if ( !is_null( $class ) ) {
			$ast = array( $class->getNode()->getInnerNode() );
		}
		else {
			$ast = $this->getAST();
		}

		$mapper = $this->getNodeMapper();
		$nodeTypes = $mapper->getMapped( array( NodeType::STATEMENT_METHOD, NodeType::STATEMENT_FUNCTION ) );
		$nodes = $this->traverse( $nodeTypes, $ast );

		$methods = new MethodArray();

		foreach ( $nodes as $node ) {
			/* @var $node Node */
			$startLine = $node->getStartLine();
			$endLine = $node->getEndLine();

			$url = $this->createSourceUrl( $startLine, $endLine );
			$methods[] = new Method( $node, $url );
		}

		return $methods;
	}

	/**
	 * Retrieves all package nodes.
	 *
	 * @return PackageArray
	 */
	public function getPackages() {
		$mapper = $this->getNodeMapper();
		$nodeTypes = $mapper->getMapped( array( NodeType::STATEMENT_PACKAGE ) );
		$nodes = $this->traverse( $nodeTypes, $this->getAST() );

		$packages = new PackageArray();

		foreach ( $nodes as $node ) {
			/* @var $node Node */
			$startLine = $node->getStartLine();
			$endLine = $node->getEndLine();

			$url = $this->createSourceUrl( $startLine, $endLine );
			$packages[] = new Package( $node, $url );
		}

		return $packages;
	}

	/**
	 * Retrieves all class nodes.
	 *
	 * @param Package $package
	 *
	 * @return ClassModelArray
	 */
	public function getClasses( Package $package = null ) {
		if ( !is_null( $package ) ) {
			$ast = array( $package->getNode()->getInnerNode() );
		}
		else {
			$ast = $this->getAST();
		}

		$mapper = $this->getNodeMapper();
		$nodeTypes = $mapper->getMapped( array( NodeType::STATEMENT_CLASS, NodeType::STATEMENT_INTERFACE ) );
		$nodes = $this->traverse( $nodeTypes, $ast );

		$classes = new ClassModelArray();

		foreach ( $nodes as $node ) {
			/* @var $node Node */
			$startLine = $node->getStartLine();
			$endLine = $node->getEndLine();

			$url = $this->createSourceUrl( $startLine, $endLine );
			$classes[] = new ClassModel( $node, $url );
		}

		return $classes;
	}
}
