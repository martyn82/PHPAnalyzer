<?php
namespace Mend\Metrics\Model\Code;

use Mend\Parser\Node\Node;

class ClassModel extends Model {
	/**
	 * @var MethodArray
	 */
	private $methods;

	/**
	 * @see Model::init()
	 */
	protected function init() {
		$this->methods = new MethodArray();
	}

	/**
	 * Gets/Sets the methods of the class.
	 *
	 * @param MethodArray $value
	 *
	 * @return MethodArray
	 */
	public function methods( MethodArray $value = null ) {
		if ( !is_null( $value ) ) {
			$this->methods = $value;
		}

		return $this->methods;
	}
}