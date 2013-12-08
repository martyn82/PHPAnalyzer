<?php
namespace Metrics\Extract;

use \Logging\Logger;

class SourceNormalizerFactory {
	/**
	 * @var string
	 */
	const EXTENSION_PHP = "php";

	/**
	 * Creates a source normalizer by file extension.
	 *
	 * @param string $name
	 *
	 * @return \Metrics\Extract\Normalizer
	 *
	 * @throws \Exception
	 */
	public static function createNormalizerByExtension( $extension ) {
		Logger::info( "Factoring normalizer for <{$extension}>." );

		switch ( strtolower( $extension ) ) {
			case self::EXTENSION_PHP:
				return new PHPSourceNormalizer();
		}

		throw new \Exception( "No normalizer for <.{$extension}> files." );
	}
}