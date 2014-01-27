<?php
namespace Mend\Source\Filter;

class SourceLineFilterFactory {
	/**
	 * Creates a source line filter by file extension.
	 *
	 * @param string $extension
	 *
	 * @return PHPSourceLineFilter
	 *
	 * @throws \InvalidArgumentException
	 * @throws \UnexpectedValueException
	 */
	public function createByFileExtension( $extension ) {
		if ( empty( $extension ) || !is_string( $extension ) ) {
			throw new \InvalidArgumentException( "Argument \$extension must be of type string and cannot be empty." );
		}

		switch ( strtolower( $extension ) ) {
			case 'php':
				return new PHPSourceLineFilter();

			default:
				throw new \UnexpectedValueException( "No SourceLineFilter implementation for {$extension} files." );
		}
	}
}