<?php
namespace Mend\Metrics\Synthesize;

class ReportWriterFactory {
	const WRITER_TEXT = 'text';

	/**
	 * Creates a report writer instance from name.
	 *
	 * @param string $name
	 *
	 * @return ReportWriter
	 *
	 * @throws \ClassNotFoundException
	 */
	public static function createWriterByName( $name ) {
		switch ( $name ) {
			case self::WRITER_TEXT:
				return new ReportWriterText();
		}

		throw new \ClassNotFoundException( "No report writer with name <{$name}>." );
	}
}