<?php
namespace Mend\Mvc;

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

		if ( !defined( 'ENT_HTML5' ) ) {
			define( 'ENT_HTML5', 0 );
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
	 * @throws ViewException
	 */
	public function render( $scriptFile ) {
		if ( !is_file( $scriptFile ) ) {
			throw new ViewException( "No such view script: '{$scriptFile}'." );
		}

		ob_start();
		require $scriptFile;
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}

	/**
	 * Does not escape the value with given name.
	 *
	 * @param string $name
	 *
	 * @return mixed
	 */
	public function noEscape( $name ) {
		return $this->get( $name, false );
	}

	/**
	 * Magic getter to return view var values.
	 *
	 * @param string $name
	 *
	 * @return mixed
	 *
	 * @throws \Exception
	 */
	public function __get( $name ) {
		return $this->get( $name );
	}

	/**
	 * Retrieves a view var value.
	 *
	 * @param string $name
	 * @param boolean $useAutoEscape
	 *
	 * @return mixed
	 *
	 * @throws ViewException
	 */
	private function get( $name, $useAutoEscape = true ) {
		if ( !isset( $this->vars[ $name ] ) ) {
			throw new ViewException( "Undefined view var: <{$name}>." );
		}

		if ( $this->autoEscape && $useAutoEscape ) {
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