<?php
namespace Mend\Data;

interface ActiveRecord {
	/**
	 * Saves this record as new.
	 */
	function create();

	/**
	 * Updates this record.
	 */
	function update();

	/**
	 * Deletes this record.
	 */
	function delete();
}
