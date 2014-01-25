<?php
namespace Mend\Network\Web;

class Url {
	/**
	 * @var string
	 */
	private $scheme;

	/**
	 * @var string
	 */
	private $host;

	/**
	 * @var integer
	 */
	private $port;

	/**
	 * @var string
	 */
	private $user;

	/**
	 * @var string
	 */
	private $pass;

	/**
	 * @var string
	 */
	private $path;

	/**
	 * @var string
	 */
	private $query;

	/**
	 * @var string
	 */
	private $fragment;

	/**
	 * @var string
	 */
	private $url;

	/**
	 * Creates an URL instance from string.
	 *
	 * @param string $url
	 *
	 * @return URL
	 *
	 * @throws \InvalidArgumentException
	 */
	public static function createFromString( $url ) {
		if ( empty( $url ) || !is_string( $url ) ) {
			throw new \InvalidArgumentException( "Argument '\$url' must be a string and cannot be empty." );
		}

		$defaults = array(
			'scheme' => null,
			'host' => null,
			'port' => null,
			'user' => null,
			'pass' => null,
			'path' => null,
			'query' => null,
			'fragment' => null
		);

		$components = parse_url( $url );

		if ( !is_array( $components ) ) {
			$components = array();
		}

		$components = array_merge( $defaults, $components );

		$result = new self(
			$components[ 'scheme' ],
			$components[ 'host' ],
			(int) $components[ 'port' ],
			$components[ 'user' ],
			$components[ 'pass' ],
			$components[ 'path' ],
			$components[ 'query' ],
			$components[ 'fragment' ]
		);
		$result->setUrl( $url );

		return $result;
	}

	/**
	 * Creates an URL instance from globals.
	 *
	 * @return Url
	 *
	 * @throws \UnexpectedValueException
	 */
	public static function createFromGlobals() {
		if ( !isset( $_SERVER ) || empty( $_SERVER ) ) {
			throw new \UnexpectedValueException( '$_SERVER global is not set or empty.' );
		}

		if (
			empty( $_SERVER[ 'HTTP_HOST' ] )
			|| empty( $_SERVER[ 'REQUEST_URI' ] )
		) {
			throw new \UnexpectedValueException( "Unable to determine request uri." );
		}

		$scheme = ( isset( $_SERVER[ 'HTTPS' ] ) && $_SERVER[ 'HTTPS' ] != 'off' ) ? 'https://' : 'http://';

		$urlString = $scheme
			. $_SERVER[ 'HTTP_HOST' ]
			. $_SERVER[ 'REQUEST_URI' ]
			. '?' . $_SERVER[ 'QUERY_STRING' ];

		return self::createFromString( $urlString );
	}

	/**
	 * Constructs a new URL instance.
	 *
	 * @param string $scheme
	 * @param string $host
	 * @param integer $port
	 * @param string $user
	 * @param string $pass
	 * @param string $path
	 * @param string $query
	 * @param string $fragment
	 */
	protected function __construct( $scheme, $host, $port, $user, $pass, $path, $query, $fragment ) {
		$this->scheme = $scheme;
		$this->host = $host;
		$this->port = ( (int) $port ) ? (int) $port : null;
		$this->user = $user;
		$this->pass = $pass;
		$this->path = $path;
		$this->query = $query;
		$this->fragment = $fragment;
	}

	/**
	 * Sets the URL string.
	 *
	 * @param string $url
	 */
	protected function setUrl( $url ) {
		$this->url = $url;
	}

	/**
	 * Retrieves the URL scheme.
	 *
	 * @return string
	 */
	public function getScheme() {
		return $this->scheme;
	}

	/**
	 * Retrieves the host name.
	 *
	 * @return string
	 */
	public function getHost() {
		return $this->host;
	}

	/**
	 * Retrieves the port number.
	 *
	 * @return integer
	 */
	public function getPort() {
		return $this->port;
	}

	/**
	 * Retrieves the username.
	 *
	 * @return string
	 */
	public function getUsername() {
		return $this->user;
	}

	/**
	 * Retrieves the password.
	 *
	 * @return string
	 */
	public function getPassword() {
		return $this->pass;
	}

	/**
	 * Retrieves the URI path.
	 *
	 * @return string
	 */
	public function getPath() {
		return $this->path;
	}

	/**
	 * Retrieves the query string.
	 *
	 * @return string
	 */
	public function getQueryString() {
		return $this->query;
	}

	/**
	 * Retrieves the document fragment.
	 *
	 * @return string
	 */
	public function getFragment() {
		return $this->fragment;
	}

	/**
	 * Retrieves the string representation of this object.
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->url;
	}
}