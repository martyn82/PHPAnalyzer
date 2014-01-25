<?php
namespace Mend\Parser\Node;

abstract class NodeMapper {
	/**
	 * Retrieves the mapping.
	 *
	 * @return array
	 */
	abstract protected function getMapping();

	/**
	 * Maps all given nodes.
	 *
	 * @param array $nodes
	 *
	 * @return array
	*/
	public function getMapped( array $nodes ) {
		$self = $this;

		return array_map(
			function ( $node ) use ( $self ) {
				return $self->mapNode( $node );
			},
			$nodes
		);
	}

	/**
	 * Maps given node to its mapped name.
	 *
	 * @param string $name
	 *
	 * @return string
	 *
	 * @throws \Exception
	 */
	public function mapNode( $name ) {
		$mapping = $this->getMapping();

		if ( !isset( $mapping[ $name ] ) ) {
			throw new \Exception( "Unrecognized node name: {$name}." );
		}

		return $mapping[ $name ];
	}
}
