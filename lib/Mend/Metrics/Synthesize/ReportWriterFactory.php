<?php
namespace Mend\Metrics\Synthesize;

use \Mend\Metrics\Synthesize\ReportWriterText;

class ReportWriterFactory {
	const WRITER_TEXT = 'text';

	/**
	 * Creates a report writer instance from name.
	 *
	 * @param string $name
	 *
	 * @return ReportWriter
	 *
	 * @throws \Exception
	 */
	public static function createWriterByName( $name ) {
		switch ( $name ) {
			case self::WRITER_TEXT:
				return new ReportWriterText();
		}

		throw new \Exception( "No report writer with name <{$name}>." );
	}
}