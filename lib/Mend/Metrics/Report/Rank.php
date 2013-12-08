<?php
namespace Mend\Metrics\Report;

interface Rank {
	const RANK_VERY_GOOD = 5;
	const RANK_GOOD = 4;
	const RANK_OK = 3;
	const RANK_BAD = 2;
	const RANK_VERY_BAD = 1;

	/**
	 * Retrieves the rank.
	 *
	 * @return integer
	 */
	function getRank();
}