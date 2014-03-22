<?php
namespace Model\Project;

use Mend\Data\DataObject;

class Project extends \Mend\Metrics\Project\Project implements DataObject {
	/**
	 * @var string
	 */
	private $identity;

	/**
	 * @see DataObject::setIdentity()
	 */
	public function setIdentity( $identity ) {
		$this->identity = $identity;
	}

	/**
	 * @see DataObject::getIdentity()
	 */
	public function getIdentity() {
		return $this->identity;
	}

	/**
	 * @see DataObject::toArray()
	 */
	public function toArray() {
		return parent::toArray();
	}
}