<?php
namespace Mend\Source\Code\Model;

class Package extends Model{
	/**
	 * @var ClassModelArray
	 */
	private $classes;

	/**
	 * Gets/sets the classes of the package.
	 *
	 * @param ClassModelArray $value
	 *
	 * @return ClassModelArray
	 */
	public function classes( ClassModelArray $value = null ) {
		if ( !is_null( $value ) ) {
			$this->classes = $value;
		}

		return $this->classes;
	}
}