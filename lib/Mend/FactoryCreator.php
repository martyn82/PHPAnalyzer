<?php
namespace Mend;

class FactoryCreator {
	/**
	 * @var string
	 */
	const EXTENSION_PHP = 'php';

	/**
	 * Creates a factory instance by given file name extension.
	 *
	 * @param string $extension
	 *
	 * @return Factory
	 *
	 * @throws \InvalidArgumentException
	 * @throws \UnexpectedValueException
	 */
	public function createFactoryByFileExtension( $extension ) {
		if ( empty( $extension ) || !is_string( $extension ) ) {
			throw new \InvalidArgumentException( "Argument \$extension must be a non-empty string." );
		}

		switch ( strtolower( $extension ) ) {
			case self::EXTENSION_PHP:
				return new PHPFactory();

			default:
				throw new \UnexpectedValueException( "No factory instance for {$extension}-files." );
		}
	}
}