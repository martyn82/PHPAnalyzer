<?php
namespace Mend\Mvc\Rest;

use Mend\Mvc\Controller\ActionResult;

class RestResult extends ActionResult {
	/**
	 * @var integer
	 */
	private $pageNumber;

	/**
	 * @var integer
	 */
	private $totalResultCount;

	/**
	 * @var integer
	 */
	private $resultsPerPage;

	/**
	 * Constructs a new rest response instance.
	 *
	 * @param array $data
	 * @param integer $pageNumber
	 * @param integer $totalResultCount
	 * @param integer $resultsPerPage
	 */
	public function __construct( array $data, $pageNumber = null, $totalResultCount = null, $resultsPerPage = null ) {
		parent::__construct( $data );
		$this->pageNumber = (int) $pageNumber ? : 1;
		$this->totalResultCount = (int) $totalResultCount ? : count( $data );
		$this->resultsPerPage = (int) $resultsPerPage;
	}

	/**
	 * Retrieves the current page number.
	 *
	 * @return integer
	 */
	public function getPageNumber() {
		return $this->pageNumber;
	}

	/**
	 * Retrieves the total results count.
	 *
	 * @return integer
	 */
	public function getTotalResultsCount() {
		return $this->totalResultCount;
	}

	/**
	 * Retrieves the number of results per page.
	 *
	 * @return integer
	 */
	public function getResultsPerPage() {
		return $this->resultsPerPage;
	}
}
