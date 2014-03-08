<?php
namespace Mend\Mvc\View;

use Mend\IO\FileSystem\File;
use Mend\IO\FileSystem\FileSystem;
use Mend\Mvc\View;

class ViewRenderer {
	/**
	 * @var Layout
	 */
	private $layout;

	/**
	 * @var View
	 */
	private $view;

	/**
	 * @var ViewOptions
	 */
	private $options;

	/**
	 * @var boolean
	 */
	private $enabled;

	/**
	 * Constructs a new ViewRenderer instance.
	 *
	 * @param ViewOptions $options
	 * @param View $view
	 * @param Layout $layout
	 */
	public function __construct( ViewOptions $options, View $view, Layout $layout = null ) {
		$this->options = $options;
		$this->view = $view;
		$this->layout = $layout;
		$this->enabled = $this->options->getRendererEnabled();
	}

	/**
	 * Enables the renderer.
	 */
	public function enable() {
		$this->enabled = true;
	}

	/**
	 * Disables the renderer.
	 */
	public function disable() {
		$this->enabled = false;
	}

	/**
	 * Resets the enabled state to what is given in the ViewOptions.
	 */
	public function resetEnabled() {
		$this->enabled = $this->options->getRendererEnabled();
	}

	/**
	 * Determines whether this renderer is enabled.
	 *
	 * @return boolean
	 */
	public function isEnabled() {
		return $this->enabled;
	}

	/**
	 * Determines whether this renderer is disabled.
	 *
	 * @return boolean
	 */
	public function isDisabled() {
		return !$this->enabled;
	}

	/**
	 * Renders current view.
	 *
	 * @param File $viewTemplate
	 */
	public function render( File $viewTemplate ) {
		if ( !$this->enabled || is_null( $this->view ) ) {
			return '';
		}

		$templateFile = new File(
			$this->options->getViewTemplatePath()
			. FileSystem::DIRECTORY_SEPARATOR
			. $viewTemplate->getName()
		);

		$renderedView = $this->view->render( $templateFile );

		if ( $this->options->getLayoutEnabled() && !is_null( $this->layout ) ) {
			$this->layout->setContent( $renderedView );

			$layoutFile = new File(
				$this->options->getLayoutTemplatePath()
				. FileSystem::DIRECTORY_SEPARATOR
				. $this->options->getLayoutTemplate()
			);

			$renderedView = $this->layout->render( $layoutFile );
		}

		return $renderedView;
	}

	/**
	 * Retrieves the view.
	 *
	 * @return View
	 */
	public function getView() {
		return $this->view;
	}

	/**
	 * Retrieves the layout.
	 *
	 * @return Layout
	 */
	public function getLayout() {
		return $this->layout;
	}

	/**
	 * Retrieves the options.
	 *
	 * @return ViewOptions
	 */
	public function getOptions() {
		return $this->options;
	}
}
