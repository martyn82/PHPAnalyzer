<?php
namespace Mend\Source\Code\Model;

use Mend\Parser\Node\Node;
use Mend\Source\Code\Model\Method;

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

	/**
	 * @see Model::toArray()
	 */
	public function toArray() {
		$result = parent::toArray();
		$methods = array();

		foreach ( $this->methods as $method ) {
			/* @var $method Method */
			$methods[] = $method->toArray();
		}

		$result[ 'methods' ] = $methods;
		return $result;
	}
}