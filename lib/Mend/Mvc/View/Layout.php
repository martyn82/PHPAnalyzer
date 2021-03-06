<?php
namespace Mend\Mvc\View;

use Mend\I18n\CharacterSet;
use Mend\I18n\Culture;
use Mend\I18n\Locale;
use Mend\I18n\ReadingDirection;
use Mend\Mvc\View;
use Mend\IO\FileSystem\File;

class Layout extends View {
	/**
	 * @var string
	 */
	private $content;

	/**
	 * @var Culture
	 */
	private $culture;

	/**
	 * @var string
	 */
	private $title;

	/**
	 * Sets the title.
	 *
	 * @param string $title
	 */
	public function setTitle( $title ) {
		$this->title = $title;
	}

	/**
	 * Sets the contents.
	 *
	 * @param string $content
	 */
	public function setContent( $content ) {
		$this->content = $content;
	}

	/**
	 * Sets the cultural properties to layout.
	 *
	 * @param Culture $culture
	 */
	public function setCulture( Culture $culture ) {
		$this->culture = $culture;
	}

	/**
	 * Renders the layout.
	 *
	 * @param File $templateFile
	 *
	 * @return string
	 */
	public function render( File $templateFile ) {
		$this->preRender();
		return parent::render( $templateFile );
	}

	/**
	 * Prepares the rendering of the layout.
	 */
	private function preRender() {
		if ( is_null( $this->content ) ) {
			$this->content = '';
		}

		if ( is_null( $this->culture ) ) {
			$this->setCulture(
				new Culture(
					Locale::ENGLISH_UNITEDKINGDOM,
					ReadingDirection::LEFT_TO_RIGHT,
					CharacterSet::UNICODE_UTF8
				)
			);
		}

		if ( is_null( $this->title ) ) {
			$this->title = '';
		}

		$this->assign( 'culture', $this->culture );
		$this->assign( 'content', $this->content );
		$this->assign( 'title', $this->title );
	}
}