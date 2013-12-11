<?php
namespace MVC;

class Layout extends View {
	/**
	 * @var string
	 */
	private $content;

	/**
	 * @var \stdClass
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
	 * @param string $locale
	 * @param string $readingDirection
	 * @param string $charset
	 */
	public function setCulture( $locale, $readingDirection, $charset = 'utf-8' ) {
		$this->culture = new \stdClass();
		$this->culture->locale = $locale;
		$this->culture->readingDirection = $readingDirection;
		$this->culture->charset = $charset;
	}

	/**
	 * Renders the layout.
	 *
	 * @param string $scriptFile
	 *
	 * @return string
	 *
	 * @throws \Exception
	 */
	public function render( $scriptFile ) {
		$this->preRender();
		return parent::render( $scriptFile );
	}

	/**
	 * Prepares the rendering of the layout.
	 */
	private function preRender() {
		if ( is_null( $this->content ) ) {
			$this->content = '';
		}

		if ( is_null( $this->culture ) ) {
			$this->setCulture( 'en-GB', 'ltr' );
		}

		if ( is_null( $this->title ) ) {
			$this->title = '';
		}

		$this->assign( 'culture', $this->culture );
		$this->assign( 'content', $this->content );
		$this->assign( 'title', $this->title );
	}
}