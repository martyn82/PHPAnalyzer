<?php
namespace Extract\Normalizer;

use FileSystem\File;

class NormalizerFactory {
	const EXT_PHP = "php";
	
	public static function createNormalizerByFile( File $file ) {
		$extension = $file->getExtension();
		return self::createNormalizerByName( $extension );
	}
	
	public static function createNormalizerByName( $name ) {
		switch ( strtolower( $name ) ) {
			case self::EXT_PHP:
				require_once __DIR__ . "/PHPNormalizer.php";
				return new PHPNormalizer();
		}

		throw new \Exception( "No normalizer for <.{$extension}> files." );
	}
}