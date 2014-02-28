<?php
namespace Mend\I18n;

class Culture {
	/**
	 * @var string
	 */
	private $locale;

	/**
	 * @var string
	 */
	private $readingDirection;

	/**
	 * @var string
	 */
	private $charset;

	/**
	 * Constructs a new culture instance.
	 *
	 * @param string $locale
	 * @param string $readingDirection
	 * @param string $charset
	 */
	public function __construct( $locale, $readingDirection, $charset ) {
		$this->locale = $locale;
		$this->readingDirection = $readingDirection;
		$this->charset = $charset;
	}

	/**
	 * Retrieves the locale.
	 *
	 * @return string
	 */
	public function getLocale() {
		return $this->locale;
	}

	/**
	 * Retrieves the reading direction.
	 *
	 * @return string
	 */
	public function getReadingDirection() {
		return $this->readingDirection;
	}

	/**
	 * Retrieves the character set encoding.
	 *
	 * @return string
	 */
	public function getCharset() {
		return $this->charset;
	}
}