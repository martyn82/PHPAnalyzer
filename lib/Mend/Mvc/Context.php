<?php
namespace Mend\Mvc;

use Mend\I18n\CharacterSet;

class Context {
	/**
	 * @var string
	 */
	private $contentType;

	/**
	 * @var string
	 */
	private $templateFileSuffix;

	/**
	 * @var string
	 */
	private $characterSet;

	/**
	 * Creates a new Context instance.
	 *
	 * @param string $contentType
	 * @param string $templateFileSuffix
	 * @param string $characterSet
	 *
	 * @return Context
	 */
	public static function create( $contentType, $templateFileSuffix, $characterSet = CharacterSet::UNICODE_UTF8 ) {
		return new self( $contentType, $templateFileSuffix, $characterSet );
	}

	/**
	 * Constructs a new Context instance.
	 *
	 * @param string $contentType
	 * @param string $templateFileSuffix
	 * @param string $characterSet
	 */
	protected function __construct( $contentType, $templateFileSuffix, $characterSet = CharacterSet::UNICODE_UTF8 ) {
		$this->contentType = (string) $contentType;
		$this->templateFileSuffix = (string) $templateFileSuffix;
		$this->characterSet = (string) $characterSet;
	}

	/**
	 * Retrieves the content type.
	 *
	 * @return string
	 */
	public function getContentType() {
		return $this->contentType;
	}

	/**
	 * Retrieves the template file suffix.
	 *
	 * @return string
	 */
	public function getTemplateFileSuffix() {
		return $this->templateFileSuffix;
	}

	/**
	 * Retrieves the character set encoding.
	 *
	 * @return string
	 */
	public function getCharacterSet() {
		return $this->characterSet;
	}
}
