<?php
namespace Mend\Data\Storage;

use Mend\Collections\Map;

class Record {
	/**
	 * @var Map
	 */
	private $fields;

	/**
	 * Constructs a new Record instance.
	 *
	 * @param Map $fields
	 *
	 * @throws \UnexpectedValueException
	 */
	public function __construct( Map $fields ) {
		if ( $fields->getSize() == 0 ) {
			throw new \UnexpectedValueException( "Argument \$fields cannot be empty." );
		}

		$this->fields = $fields;
	}

	/**
	 * Retrieves the value of given field.
	 *
	 * @param string $field
	 *
	 * @return mixed
	 *
	 * @throws \InvalidArgumentException
	 */
	public function getValue( $field ) {
		if ( !$this->fields->hasKey( $field ) ) {
			throw new \InvalidArgumentException( "Field does not exist in record: '{$field}'." );
		}

		return $this->fields->get( $field );
	}

	/**
	 * Sets a field value.
	 *
	 * @param string $field
	 * @param mixed $value
	 *
	 * @throws \InvalidArgumentException
	 */
	public function setValue( $field, $value ) {
		if ( !$this->fields->hasKey( $field ) ) {
			throw new \InvalidArgumentException( "Field does not exist in record: '{$field}'." );
		}

		$this->fields->set( $field, $value );
	}

	/**
	 * Retrieves all fields.
	 *
	 * @return Map
	 */
	public function getFields() {
		return $this->fields;
	}
}
