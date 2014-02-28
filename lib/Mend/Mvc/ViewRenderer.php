<?php
namespace Mend\Mvc;

class ViewRenderer {
	/**
	 * @var ViewRendererOptions
	 */
	private $options;

	/**
	 * Constructs a new ViewRenderer.
	 *
	 * @param ViewRendererOptions $options
	 */
	public function __construct( ViewRendererOptions $options ) {
		$this->options = $options;
	}

	/**
	 * Renders the given view.
	 *
	 * @param View $view
	 * @param string $templateFile
	 * @param string $templatePath
	 * @param string $templateSuffix
	 *
	 * @return string
	 */
	public function renderView( View $view, $templateFile, $templatePath = null, $templateSuffix = null ) {
		$templateSuffix = $templateSuffix ? : $this->options->getViewTemplateSuffix();
		$basePath = $this->options->getViewTemplatePath();

		$templatePath = $basePath . DIRECTORY_SEPARATOR . ( $templatePath ? : '' . DIRECTORY_SEPARATOR );
		$templateFile = $templateFile . $templateSuffix;

		return $this->render( $view, $templateFile, $templatePath );
	}

	/**
	 * Renders the given layout.
	 *
	 * @param Layout $layout
	 * @param string $templateFile
	 * @param string $templatePath
	 * @param string $templateSuffix
	 *
	 * @return string
	 */
	public function renderLayout( Layout $layout, $templateFile = null, $templatePath = null, $templateSuffix = null ) {
		$templateFile = $templateFile ? : $this->options->getLayoutDefaultTemplate();
		$templateSuffix = $templateSuffix ? : $this->options->getLayoutTemplateSuffix();
		$basePath = $this->options->getLayoutTemplatePath();

		$templatePath = $basePath . DIRECTORY_SEPARATOR . ( $templatePath ? : '' . DIRECTORY_SEPARATOR );
		$templateFile = $templateFile . $templateSuffix;

		return $this->render( $layout, $templateFile, $templatePath );
	}

	/**
	 * Renders the given view.
	 *
	 * @param View $view
	 * @param string $templatePath
	 * @param string $templateSuffix
	 * @param string $basePath
	 *
	 * @return string
	 *
	 * @throws ViewException
	 */
	protected function render( View $view, $templateFile, $templatePath ) {
		$fullPath = realpath( $templatePath );

		if ( !is_dir( $fullPath ) ) {
			throw new ViewException( "No such template directory: '{$templatePath}'." );
		}

		$templateFile = $fullPath
			. DIRECTORY_SEPARATOR
			. $templateFile;

		return $view->render( $templateFile );
	}
}