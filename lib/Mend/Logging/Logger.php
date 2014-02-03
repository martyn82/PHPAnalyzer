<?php
namespace Mend\Logging;

class Logger {
	/**
	 * @var string
	 */
	const DEFAULT_FORMAT = '%datetime% - %level%: %message%';

	/**
	 * @var array
	 */
	private static $levels = array(
		LogLevel::LEVEL_DEBUG => 'DEBUG',
		LogLevel::LEVEL_INFO => 'INFO',
		LogLevel::LEVEL_NOTICE => 'NOTICE',
		LogLevel::LEVEL_WARNING => 'WARNING',
		LogLevel::LEVEL_ERROR => 'ERROR',
		LogLevel::LEVEL_CRITICAL => 'CRITICAL',
		LogLevel::LEVEL_ALERT => 'ALERT',
		LogLevel::LEVEL_EMERGENCY => 'EMERGENCY'
	);

	/**
	 * @var array
	*/
	private static $handlers = array();

	/**
	 * Registers a log handler.
	 *
	 * @param LogHandler $handler
	 * @param array $levels
	 * @param string $format
	 *
	 * @throws LogException
	*/
	public static function registerHandler( LogHandler $handler, array $levels = null, $format = null ) {
		if ( is_null( $levels ) ) {
			$levels = array_keys( self::$levels );
		}

		foreach ( $levels as $level ) {
			$level = (int) $level;

			if ( !in_array( $level, array_keys( self::$levels ) ) ) {
				throw new LogException( "Invalid log level: '{$level}'." );
			}

			if ( is_null( $format ) ) {
				$format = self::DEFAULT_FORMAT;
			}

			self::$handlers[ $level ][] = array( 'handler' => $handler, 'format' => $format );
		}
	}

	/**
	 * Clears all handlers.
	 */
	public static function clearHandlers() {
		self::$handlers = array();
	}

	/**
	 * Logs the given message.
	 *
	 * @param string $message
	 * @param integer $level
	 *
	 * @throws LogException
	 */
	public static function log( $message, $level ) {
		if ( empty( self::$handlers[ $level ] ) ) {
			throw new LogException( "No handler defined for level: '{$level}'." );
		}

		$registers = self::$handlers[ $level ];

		foreach ( $registers as $register ) {
			$format = $register[ 'format' ];
			/* @var $handler LogHandler */
			$handler = $register[ 'handler' ];

			$handler->log(
					str_replace(
							array(
								'%datetime%',
								'%level%',
								'%message%'
							),
							array(
								date( 'r' ),
								self::$levels[ $level ],
								$message
							),
							$format
					) . "\n"
			);
		}
	}

	/**
	 * Logs a debug message.
	 *
	 * @param string $message
	 */
	public static function debug( $message ) {
		self::log( $message, LogLevel::LEVEL_DEBUG );
	}

	/**
	 * Logs an information message.
	 *
	 * @param string $message
	 */
	public static function info( $message ) {
		self::log( $message, LogLevel::LEVEL_INFO );
	}

	/**
	 * Logs an uncommon message.
	 *
	 * @param string $message
	 */
	public static function notice( $message ) {
		self::log( $mssage, LogLevel::LEVEL_NOTICE );
	}

	/**
	 * Logs a warning message.
	 *
	 * @param string $message
	 */
	public static function warning( $message ) {
		self::log( $message, LogLevel::LEVEL_WARNING );
	}

	/**
	 * Logs an error message.
	 *
	 * @param string $message
	 */
	public static function error( $message ) {
		self::log( $message, LogLevel::LEVEL_ERROR );
	}

	/**
	 * Logs a critical message.
	 *
	 * @param string $message
	 */
	public static function critical( $message ) {
		self::log( $message, LogLevel::LEVEL_CRITICAL );
	}

	/**
	 * Logs an alarming message.
	 *
	 * @param string $message
	 */
	public static function alert( $message ) {
		self::log( $message, LogLevel::LEVEL_ALERT );
	}

	/**
	 * Logs an emergency message.
	 *
	 * @param string $message
	 */
	public static function emergency( $message ) {
		self::log( $message, LogLevel::LEVEL_EMERGENCY );
	}
}