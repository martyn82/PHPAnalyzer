<?php
namespace Mend\Data;

interface DataObject {
	/**
	 * Retrieves the identity.
	 *
	 * @return string
	 */
	function getIdentity();

	/**
	 * Sets the identity.
	 *
	 * @param string $identity
	 */
	function setIdentity( $identity );

	/**
	 * Converts this object to an array.
	 *
	 * @return array
	 */
	function toArray();
}
