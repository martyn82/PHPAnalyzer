<?php
namespace Mend;

use Mend\Parser\Adapter;
use Mend\Parser\Node\NodeMapper;
use Mend\Source\Filter\SourceLineFilter;

abstract class Factory {
	/**
	 * Creates a NodeMapper instance.
	 *
	 * @return NodeMapper
	 */
	abstract public function createNodeMapper();

	/**
	 * Creates a SourceLineFilter instance.
	 *
	 * @return SourceLineFilter
	 */
	abstract public function createSourceLineFilter();

	/**
	 * Creates a parser Adapter instance.
	 *
	 * @return Adapter
	 */
	abstract public function createParserAdapter();
}