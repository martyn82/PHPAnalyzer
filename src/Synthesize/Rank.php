<?php
namespace Synthesize;

class Rank {
	const DOUBLE_MINUS = 1;
	const MINUS = 2;
	const NEUTRAL = 3;
	const PLUS = 4;
	const DOUBLE_PLUS = 5;
	
	public static function getVolumeRank( $linesOfCodeCount ) {
		if ( $linesOfCodeCount > 130000 ) {
			return self::DOUBLE_MINUS;
		}
		
		if ( $linesOfCodeCount > 655000 ) {
			return self::MINUS;
		}
		
		if ( $linesOfCodeCount > 246000 ) {
			return self::NEUTRAL;
		}
		
		if ( $linesOfCodeCount > 66000 ) {
			return self::PLUS;
		}
		
		return self::DOUBLE_PLUS;
	}
	
	public static function getComplexityRank( array $complexities ) {
		if ( $complexities[ 'moderate' ] <= 25 && $complexities[ 'high' ] == 0 && $complexities[ 'veryHigh' ] == 0 ) {
			return self::DOUBLE_PLUS;
		}
		
		if ( $complexities[ 'moderate' ] <= 30 && $complexities[ 'high' ] <= 5 && $complexities[ 'veryHigh' ] == 0 ) {
			return self::PLUS;
		}
		
		if ( $complexities[ 'moderate' ] <= 40 && $complexities[ 'high' ] <= 10 && $complexities[ 'veryHigh' ] <= 0 ) {
			return self::NEUTRAL;
		}
		
		if ( $complexities[ 'moderate' ] <= 50 && $complexities[ 'high' ] <= 15 && $complexities[ 'veryHigh' ] <= 5 ) {
			return self::MINUS;
		}
		
		return self::DOUBLE_MINUS;
	}
	
	public static function getDuplicationRank( $relativeDuplications ) {
		if ( $relativeDuplications >= 20 ) {
			return self::DOUBLE_MINUS;
		}
		
		if ( $relativeDuplications >= 10 ) {
			return self::MINUS;
		}
		
		if ( $relativeDuplications >= 5 ) {
			return self::NEUTRAL;
		}
		
		if ( $relativeDuplications >= 3 ) {
			return self::PLUS;
		}
		
		return self::DOUBLE_PLUS;
	}
	
	public static function getUnitSizeRank( array $sizes ) {
		if ( $sizes[ 'medium' ] <= 25 && $sizes[ 'large' ] == 0 && $sizes[ 'huge' ] == 0 ) {
			return self::DOUBLE_PLUS;
		}
		
		if ( $sizes[ 'medium' ] <= 30 && $sizes[ 'large' ] <= 5 && $sizes[ 'huge' ] == 0 ) {
			return self::PLUS;
		}
		
		if ( $sizes[ 'medium' ] <= 40 && $sizes[ 'large' ] <= 10 && $sizes[ 'huge' ] == 0 ) {
			return self::NEUTRAL;
		}
		
		if ( $sizes[ 'medium' ] <= 50 && $sizes[ 'large' ] <= 15 && $sizes[ 'huge' ] <= 5 ) {
			return self::MINUS;
		}
		
		return self::DOUBLE_MINUS;
	}
	
	public static function getUnitTestRank() {
		return self::NEUTRAL;
	}
	
	public static function rankToString( $rank ) {
		switch ( $rank ) {
			case self::DOUBLE_MINUS: return '--';
			case self::MINUS: return '-';
			case self::NEUTRAL: return 'o';
			case self::PLUS: return '+';
			case self::DOUBLE_PLUS: return '++';
		}
	}
}