<?php
namespace Mend\Metrics\Analyze\Dependency;

use Mend\Metrics\Model\Code\ClassModel;
use Mend\Metrics\Model\Code\Method;
use Mend\Metrics\Model\Code\Package;
use Mend\Metrics\Model\ModelTraverser;

use Mend\Metrics\Model\Code\ClassModelArray;
use Mend\Metrics\Model\Code\SourceUrl;

use Mend\Network\Web\Url;

use Mend\Parser\Node\Node;
use Mend\Parser\Node\NodeArray;
use Mend\Parser\Node\NodeFilter;
use Mend\Parser\Node\NodeMapper;
use Mend\Parser\Node\NodeType;

class DependencyAnalyzer {
	/**
	 * Counts and returns the number of classes that depend on the given method.
	 *
	 * @param Method $method
	 *
	 * @return integer
	 */
	public function countMethodFanIn( Method $method ) {
		$nodes = $this->getMethodFanIn( $method );
		return count( $nodes );
	}

	/**
	 * Retrieves the classes that depend on the given method.
	 *
	 * @param Method $method
	 *
	 * @return NodeArray
	 */
	public function getMethodFanIn( Method $method ) {
		throw new \BadMethodCallException( 'This method is not implemented yet.' );
	}

	/**
	 * Counts and returns the number of classes that the given method depends on.
	 *
	 * @param Method $method
	 * @param NodeMapper $mapper
	 *
	 * @return integer
	 */
	public function countMethodFanOut( Method $method, NodeMapper $mapper ) {
		$nodes = $this->getMethodFanOut( $method, $mapper );
		return count( $nodes );
	}

	/**
	 * Retrieves the classes that the given method depends on.
	 *
	 * @param Method $method
	 * @param NodeMapper $mapper
	 *
	 * @return NodeArray
	 */
	public function getMethodFanOut( Method $method, NodeMapper $mapper ) {
		$nodeTypes = $mapper->getMapped( array( NodeType::EXPRESSION_NEW, NodeType::EXPRESSION_STATIC_CALL ) );
		$visitor = new DependencyVisitor( $nodeTypes );

		$traverser = new ModelTraverser();
		$traverser->addVisitor( $visitor );

		$node = $method->getNode();
		$traverser->traverse( array( $node->getInnerNode() ) );

		$nodes = $visitor->getResult();
		return new NodeArray( $nodes );
	}

	/**
	 * Counts and returns the number of classes that depend on the given class.
	 *
	 * @param ClassModel $class
	 *
	 * @return integer
	 */
	public function countClassFanIn( ClassModel $class ) {
		$nodes = $this->getClassFanIn( $class );
		return count( $nodes );
	}

	/**
	 * Retrieves the classes that depend on the given class.
	 *
	 * @param ClassModel $class
	 *
	 * @return NodeArray
	 */
	public function getClassFanIn( ClassModel $class ) {
		throw new \BadMethodCallException( 'This method is not implemented yet.' );
	}

	/**
	 * Counts and returns the number of classes that the given class depends on.
	 *
	 * @param ClassModel $class
	 * @param NodeMapper $mapper
	 *
	 * @return integer
	 */
	public function countClassFanOut( ClassModel $class, NodeMapper $mapper ) {
		$nodes = $this->getClassFanOut( $class, $mapper );
		return count( $nodes );
	}

	/**
	 * Retrieves the classes that the given class depends on.
	 *
	 * @param ClassModel $class
	 * @param NodeMapper $mapper
	 *
	 * @return NodeArray
	 */
	public function getClassFanOut( ClassModel $class, NodeMapper $mapper ) {
		$methods = $class->methods();
		$nodes = array();

		foreach ( $methods as $method ) {
			/* @var $method Method */
			$nodes = array_merge(
				$nodes,
				(array) $this->getMethodFanOut( $method, $mapper )
			);
		}

		$nodeFilter = new NodeFilter();
		return new NodeArray( $nodeFilter->getUnique( $nodes ) );
	}

	/**
	 * Counts and returns the number of classes that depend on the given package.
	 *
	 * @param Package $package
	 *
	 * @return integer
	 */
	public function countPackageFanIn( Package $package ) {
		$nodes = $this->getPackageFanIn( $package );
		return count( $nodes );
	}

	/**
	 * Retrieves the classes that depend on the given package.
	 *
	 * @param Package $package
	 *
	 * @return NodeArray
	 */
	public function getPackageFanIn( Package $package ) {
		throw new \BadMethodCallException( 'This method is not implemented yet.' );
	}

	/**
	 * Counts and returns the number of classes that the given package depends on.
	 *
	 * @param Package $package
	 * @param NodeMapper $mapper
	 *
	 * @return integer
	 */
	public function countPackageFanOut( Package $package, NodeMapper $mapper ) {
		$nodes = $this->getPackageFanOut( $package, $mapper );
		return count( $nodes );
	}

	/**
	 * Retrieves the classes that the given package depends on.
	 *
	 * @param Package $package
	 * @param NodeMapper $mapper
	 *
	 * @return NodeArray
	 */
	public function getPackageFanOut( Package $package, NodeMapper $mapper ) {
		$packageClasses = $package->classes();
		$nodes = array();

		foreach ( $packageClasses as $class ) {
			/* @var $class ClassModel */
			$nodes = array_merge(
				$nodes,
				(array) $this->getClassFanOut( $class, $mapper )
			);
		}

		$nodeFilter = new NodeFilter();
		return new NodeArray( $nodeFilter->getUnique( $nodes ) );
	}

	/**
	 * Retrieves the dependency risk level.
	 *
	 * @param integer $dependencyCount
	 *
	 * @return integer
	 */
	public function getLevel( $dependencyCount ) {
		if ( $dependencyCount <= 5 ) {
			return DependencyAmount::AMOUNT_SMALL;
		}

		if ( $dependencyCount > 5 && $dependencyCount <= 9 ) {
			return DependencyAmount::AMOUNT_MEDIUM; // 7 +/- 2 is optimal
		}

		if ( $dependencyCount > 9 && $dependencyCount <= 14 ) {
			return DependencyAmount::AMOUNT_LARGE;
		}

		return DependencyAmount::AMOUNT_VERY_LARGE;
	}
}
