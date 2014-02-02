<?php
namespace Mend\Logging;

use Mend\IO\Stream\StreamWriter;

class Logger {
	const LEVEL_DEBUG = 1;
	const LEVEL_INFO = 2;
	const LEVEL_WARNING = 3;
	const LEVEL_ERROR = 4;
	const LEVEL_EXCEPTION = 5;

	/**
	 * @var array
	 */
	private static $levels = array(
		self::LEVEL_DEBUG => 'DEBUG',
		self::LEVEL_INFO => 'INFO',
		self::LEVEL_WARNING => 'WARN',
		self::LEVEL_ERROR => 'ERR',
		self::LEVEL_EXCEPTION => 'EXCEPTION'
	);

	/**
	 * @var StreamWriter
	 */
	private static $writer;

	/**
	 * @var string
	 */
	private static $messageTemplate = "<%s> %s %s\n";

	/**
	 * Sets the writer.
	 *
	 * @param StreamWriter $writer
	 */
	public static function setWriter( StreamWriter $writer ) {
		self::$writer = $writer;
	}

	/**
	 * Logs a debug message.
	 *
	 * @param string $message
	 */
	public static function debug( $message ) {
		self::log( $message, self::LEVEL_DEBUG );
	}

	/**
	 * Logs an information message.
	 *
	 * @param string $message
	 */
	public static function info( $message ) {
		self::log( $message, self::LEVEL_INFO );
	}

	/**
	 * Logs a warning.
	 *
	 * @param string $message
	 */
	public static function warning( $message ) {
		self::log( $message, self::LEVEL_WARNING );
	}

	/**
	 * Logs an error.
	 *
	 * @param string $message
	 */
	public static function error( $message ) {
		self::log( $message, self::LEVEL_ERROR );
	}

	/**
	 * Logs an exception.
	 *
	 * @param \Exception $e
	 */
	public static function exception( \Exception $e ) {
		self::log( $e->toString(), self::LEVEL_EXCEPTION );
	}

	/**
	 * Logs a message.
	 *
	 * @param string $message
	 * @param integer $level
	 */
	private static function log( $message, $level ) {
		$log = sprintf(
			self::$messageTemplate,
			date( 'r' ),
			self::$levels[ $level ],
			$message
		);

		if ( self::$writer->isClosed() ) {
			self::$writer->open();
		}

		self::$writer->write( $log );
	}
}