<?php
namespace Mend\Parser\Node;

class NodeFilter {
	/**
	 * Drops duplicate dependencies from the given array.
	 *
	 * @param array $nodes
	 *
	 * @return array
	 */
	public function getUnique( array $nodes ) {
		$names = array();
		$self = $this;

		return array_filter(
			(array) $nodes,
			function ( Node $node ) use ( & $names, $self ) {
				$name = $self->getClassName( $node );

				if ( in_array( $name, $names ) ) {
					return false;
				}

				$names[] = $name;
				return true;
			}
		);
	}

	/**
	 * Retrieves the class name of the node.
	 *
	 * @param Node $node
	 *
	 * @return string
	 *
	 * @throws \Exception
	 */
	public function getClassName( Node $node ) {
		$fqName = self::getFullyQualifiedName( $node );

		if ( is_null( $fqName ) ) {
			throw new \Exception( "Unable to determine the fully qualified class name of node '{$node->getName()}'." );
		}

		$parts = explode( $node->getPackageSeparator(), $fqName );
		return end( $parts );
	}

	/**
	 * Retrieves the full name of given node.
	 *
	 * @param Node $node
	 *
	 * @return string
	 */
	public function getFullyQualifiedName( Node $node ) {
		$innerNode = $node->getInnerNode();

		if ( !isset( $innerNode->class ) ) {
			return null;
		}

		$parts = $node->getInnerNode()->class->parts;
		$separator = $node->getPackageSeparator();
		return ltrim( implode( $separator, $parts ), $separator );
	}
}