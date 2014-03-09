<?php
namespace Mend;

class ClassInformation {
	/**
	 * Retrieves the fully qualified class name of the given object.
	 *
	 * @param object $object
	 *
	 * @return string
	 *
	 * @throws \InvalidArgumentException
	 */
	public function getClassName( $object ) {
		if ( !\is_object( $object ) ) {
			throw new \InvalidArgumentException( "The argument 'object' must be of type object." );
		}

		return '\\' . \get_class( $object );
	}

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
	 * The subclassOf relation is reflexive, transitive, and anti-symmetric.
	 *
	 * @param string $className
	 * @param string $ofClassName
	 *
	 * @return boolean
	 */
	public function isSubclassOf( $className, $ofClassName ) {
		return \is_subclass_of( $className, $ofClassName, true ) || $className == $ofClassName;
	}
}
