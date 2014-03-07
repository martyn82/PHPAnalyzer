<?php
namespace Mend;

class RClass {
	/**
	 * Determines whether the class exists.
	 *
	 * @param string $className
	 * @param boolean $autoLoad
	 *
	 * @return boolean
	 */
	public function exists( $className, $autoLoad = null ) {
		return \class_exists( $className, $autoLoad );
	}

	/**
	 * Determines whether the current class is a subclass of the given class name.
	 *
	 * @param string $className
	 * @param string $ofClassName
	 *
	 * @return boolean
	 */
	public function isSubclassOf( $className, $ofClassName ) {
		return \is_subclass_of( $className, $ofClassName, true );
	}
}
