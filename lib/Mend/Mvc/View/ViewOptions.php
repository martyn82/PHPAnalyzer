<?php
namespace Mend\Mvc\View;

class ViewOptions {
	/**
	 * @var boolean
	 */
	private $layoutEnabled;

	/**
	 * @var boolean
	 */
	private $renderEnabled;

	/**
	 * @var string
	 */
	private $viewTemplatePath;

	/**
	 * @var string
	 */
	private $layoutTemplatePath;

	/**
	 * @var string
	 */
	private $layoutTemplate;

	/**
	 * Determines whether layout is enabled.
	 *
	 * @return boolean
	 */
	public function getLayoutEnabled() {
		return $this->layoutEnabled;
	}

	/**
	 * Enables or disables layout.
	 *
	 * @param boolean $enable
	 */
	public function setLayoutEnabled( $enable = true ) {
		$this->layoutEnabled = (bool) $enable;
	}

	/**
	 * Determines whether renderer is enabled.
	 *
	 * @return boolean
	 */
	public function getRendererEnabled() {
		return $this->renderEnabled;
	}

	/**
	 * Enables or disables renderer.
	 *
	 * @param boolean $enable
	 */
	public function setRendererEnabled( $enable = true ) {
		$this->renderEnabled = (bool) $enable;
	}

	/**
	 * Retrieves the view template path.
	 *
	 * @return string
	 */
	public function getViewTemplatePath() {
		return $this->viewTemplatePath;
	}

	/**
	 * Sets the view template path.
	 *
	 * @param string $value
	 */
	public function setViewTemplatePath( $value ) {
		$this->viewTemplatePath = (string) $value;
	}

	/**
	 * Retrieves the layout template path.
	 *
	 * @return string
	 */
	public function getLayoutTemplatePath() {
		return $this->layoutTemplatePath;
	}

	/**
	 * Sets the layout template path.
	 *
	 * @param string $value
	 */
	public function setLayoutTemplatePath( $value ) {
		$this->layoutTemplatePath = (string) $value;
	}

	/**
	 * Retrieves the layout template.
	 *
	 * @return string
	 */
	public function getLayoutTemplate() {
		return $this->layoutTemplate;
	}

	/**
	 * Sets the layout template.
	 *
	 * @param string $value
	 */
	public function setLayoutTemplate( $value ) {
		$this->layoutTemplate = (string) $value;
	}
}
