<?php
namespace Mend\Data\Storage;

use Mend\Data\DataPage;

class ResultSet {
	/**
	 * @var RecordSet
	 */
	private $recordSet;

	/**
	 * @var integer
	 */
	private $totalCount;

	/**
	 * @var DataPage
	 */
	private $dataPage;

	/**
	 * Constructs a new ResultSet instance.
	 *
	 * @param RecordSet $records
	 * @param DataPage $dataPage
	 * @param integer $totalCount
	 */
	public function __construct( RecordSet $records, DataPage $dataPage, $totalCount ) {
		$this->recordSet = $records;
		$this->dataPage = $dataPage;
		$this->totalCount = abs( (int) $totalCount );
	}

	/**
	 * Retrieves the record set.
	 *
	 * @return RecordSet
	 */
	public function getRecordSet() {
		return $this->recordSet;
	}

	/**
	 * Retrieves the page information.
	 *
	 * @return DataPage
	 */
	public function getDataPage() {
		return $this->dataPage;
	}

	/**
	 * Retrieves the total count.
	 *
	 * @return integer
	 */
	public function getTotalCount() {
		return $this->totalCount;
	}
}
