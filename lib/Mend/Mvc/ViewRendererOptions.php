<?php
namespace Mend\Mvc;

class ViewRendererOptions {
	/**
	 * @var string
	 */
	private $layoutTemplatePath;

	/**
	 * @var string
	 */
	private $layoutTemplateSuffix;

	/**
	 * @var string
	 */
	private $viewTemplateSuffix;

	/**
	 * @var string
	 */
	private $viewTemplatePath;

	/**
	 * Constructs a new View renderer options instance.
	 */
	public function __construct() {
		$this->layoutTemplatePath = null;
		$this->layoutTemplateSuffix = null;
		$this->viewTemplatePath = null;
		$this->viewTemplateSuffix = null;
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
	 * Retrieves the view template suffix.
	 *
	 * @return string
	 */
	public function getViewTemplateSuffix() {
		return $this->viewTemplateSuffix;
	}

	/**
	 * Sets view template suffix.
	 *
	 * @param string $value
	 */
	public function setViewTemplateSuffix( $value ) {
		$this->viewTemplateSuffix = (string) $value;
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
	 * Retrieves the layout template suffix.
	 *
	 * @return string
	 */
	public function getLayoutTemplateSuffix() {
		return $this->layoutTemplateSuffix;
	}

	/**
	 * Sets the layout template suffix.
	 *
	 * @param string $value
	 */
	public function setLayoutTemplateSuffix( $value ) {
		$this->layoutTemplateSuffix = (string) $value;
	}
}