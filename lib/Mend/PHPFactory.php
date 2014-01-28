<?php
namespace Mend;

use Mend\Parser\Adapter\PHPParserAdapter;
use Mend\Parser\Node\PHPNodeMapper;
use Mend\Source\Filter\PHPSourceLineFilter;

class PHPFactory extends Factory {
	/**
	 * @see Factory::createNodeMapper()
	 */
	public function createNodeMapper() {
		return new PHPNodeMapper();
	}

	/**
	 * @see Factory::createSourceLineFilter()
	 */
	public function createSourceLineFilter() {
		return new PHPSourceLineFilter();
	}

	/**
	 * @see Factory::createParserAdapter()
	 */
	public function createParserAdapter() {
		return new PHPParserAdapter();
	}
}