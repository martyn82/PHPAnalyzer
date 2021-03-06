<?php
namespace Mend\Logging;

class LogLevel {
	const LEVEL_DEBUG = 100;
	const LEVEL_INFO = 200;
	const LEVEL_NOTICE = 250;
	const LEVEL_WARNING = 300;
	const LEVEL_ERROR = 400;
	const LEVEL_CRITICAL = 500;
	const LEVEL_ALERT = 550;
	const LEVEL_EMERGENCY = 600;

	/**
	 * Retrieves all levels.
	 *
	 * @return array
	 */
	public static function getLevels() {
		return array(
			self::LEVEL_DEBUG,
			self::LEVEL_INFO,
			self::LEVEL_NOTICE,
			self::LEVEL_WARNING,
			self::LEVEL_ERROR,
			self::LEVEL_CRITICAL,
			self::LEVEL_ALERT,
			self::LEVEL_EMERGENCY
		);
	}
}