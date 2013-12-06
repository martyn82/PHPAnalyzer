<?php
namespace Synthesize;

class Rank {
	public static function rankToString( $rank ) {
		switch ( $rank ) {
			case \Report\Rank::RANK_VERY_BAD: return '--';
			case \Report\Rank::RANK_BAD: return '-';
			case \Report\Rank::RANK_OK: return 'o';
			case \Report\Rank::RANK_GOOD: return '+';
			case \Report\Rank::RANK_VERY_GOOD: return '++';
		}
	}
}