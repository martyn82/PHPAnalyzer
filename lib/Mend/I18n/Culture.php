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
	private $currency;

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
	 * @param string $currency
	 * @param string $readingDirection
	 * @param string $charset
	 */
	public function __construct( $locale, $currency, $readingDirection, $charset ) {
		$this->locale = $locale;
		$this->currency = $currency;
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
	 * Retrieves the currency.
	 *
	 * @return string
	 */
	public function getCurrency() {
		return $this->currency;
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