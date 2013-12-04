<?php
namespace Report;

use Report\Rank;

class DuplicationReport extends Partition implements Rank {
	public function getRank() {
		$relativeDuplications = $this->getRelativeLOC();

		if ( $relativeDuplications >= 20 ) {
			return self::RANK_VERY_BAD;
		}

		if ( $relativeDuplications >= 10 ) {
			return self::RANK_BAD;
		}

		if ( $relativeDuplications >= 5 ) {
			return self::RANK_OK;
		}

		if ( $relativeDuplications >= 3 ) {
			return self::RANK_GOOD;
		}

		return self::RANK_VERY_GOOD;
	}
}