<?php
namespace Mend\Metrics\Extract;

use \Mend\Logging\Logger;

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
	 * @return Normalizer
	 *
	 * @throws \ClassNotFoundException
	 */
	public static function createNormalizerByExtension( $extension ) {
		Logger::info( "Factoring normalizer for <{$extension}>." );

		switch ( strtolower( $extension ) ) {
			case self::EXTENSION_PHP:
				return new PHPSourceNormalizer();
		}

		throw new \ClassNotFoundException( "No normalizer for <.{$extension}> files." );
	}
}