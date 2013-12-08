<?php
namespace MVC;

class View {
	/**
	 * @var array
	 */
	private $vars;

	/**
	 * @var boolean
	 */
	private $autoEscape;

	/**
	 * Constructs a new view.
	 *
	 * @param boolean $autoEscape
	 */
	public function __construct( $autoEscape = true ) {
		$this->autoEscape = $autoEscape;
		$this->vars = array();
	}

	/**
	 * Assigns a named value.
	 *
	 * @param string $name
	 * @param mixed $value
	 */
	public function assign( $name, $value ) {
		$this->vars[ $name ] = $value;
	}

	/**
	 * Escapes the given value.
	 *
	 * @param string $value
	 *
	 * @return string
	 */
	public function escape( $value ) {
		if ( !is_string( $value ) ) {
			return $value;
		}

		return htmlentities( $value, ENT_QUOTES | ENT_HTML5, 'utf-8' );
	}

	/**
	 * Renders the given script file name.
	 *
	 * @param string $scriptFile
	 *
	 * @return string
	 *
	 * @throws \Exception
	 */
	public function render( $scriptFile ) {
		if ( !file_exists( $scriptFile ) ) {
			throw new \Exception( "No such view script: <{$scriptFile}>" );
		}

		ob_start();
		include $scriptFile;
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}

	/**
	 * Magic getter to return view var values.
	 *
	 * @param string $name
	 */
	public function __get( $name ) {
		if ( !isset( $this->vars[ $name ] ) ) {
			throw new \Exception( "Undefined view var: <{$name}>." );
		}

		if ( $this->autoEscape ) {
			return $this->escape( $this->vars[ $name ] );
		}

		return $this->vars[ $name ];
	}

	/**
	 * Converts this view to string.
	 *
	 * @return string
	 */
	public function __toString() {
		return var_export( $this->vars, true );
	}
}