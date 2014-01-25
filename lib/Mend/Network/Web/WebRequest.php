<?php
namespace Mend\Network\Web;

use Mend\Collections\Map;

class WebRequest {
	/**
	 * @var string
	 */
	private $body;

	/**
	 * @var Map
	 */
	private $headers;

	/**
	 * @var string
	 */
	private $method;

	/**
	 * @var Map
	 */
	private $parameters;

	/**
	 * @var Url
	 */
	private $url;

	/**
	 * Creates a WebRequest instance from global values.
	 *
	 * @return WebRequest
	 *
	 * @throws \UnexpectedValueException
	 */
	public static function createFromGlobals() {
		if ( !isset( $_SERVER ) || empty( $_SERVER ) ) {
			throw new \UnexpectedValueException( '$_SERVER is not set or empty.' );
		}

		if ( empty( $_SERVER[ 'REQUEST_METHOD' ] ) ) {
			throw new \UnexpectedValueException( "Unable to determine request method." );
		}

		$url = Url::createFromGlobals();
		$result = new self( $url );

		$result->method = $_SERVER[ 'REQUEST_METHOD' ];
		$result->body = file_get_contents( 'php://input' );

		$result->parameters->addAll( $_REQUEST );

		$headers = self::getAllHeaders();

		$result->headers = new Map();
		$result->headers->addAll( $headers );

		return $result;
	}

	/**
	 * Extracts the request headers from globals.
	 *
	 * @return array
	 */
	private static function getAllHeaders() {
		$headers = array();

		foreach ( $_SERVER as $key => $value ) {
			if ( substr( $key, 0, 5 ) != 'HTTP_' ) {
				continue;
			}

			$headerNamePart = substr( $key, 5 );
			$headerNameWords = str_replace( '_', ' ', $headerNamePart );
			$headerNameNormalized = ucwords( strtolower( $headerNameWords ) );
			$headerName = str_replace( ' ', '-', $headerNameNormalized );

			$headers[ $headerName ] = $value;
		}

		if ( !empty( $_SERVER[ 'CONTENT_TYPE' ] ) ) {
			$headers[ 'Content-Type' ] = $_SERVER[ 'CONTENT_TYPE' ];
		}

		if ( !empty( $_SERVER[ 'CONTENT_LENGTH' ] ) ) {
			$headers[ 'Content-Length' ] = $_SERVER[ 'CONTENT_LENGTH' ];
		}

		return $headers;
	}

	/**
	 * Constructs a new WebRequest.
	 *
	 * @param Url $url
	 * @param string $method
	 * @param Map $headers
	 * @param Map $parameters
	 * @param string $body
	 */
	public function __construct(
		Url $url,
		$method = HttpMethod::METHOD_GET,
		Map $headers = null,
		Map $parameters = null,
		$body = null
	) {
		$this->url = $url;
		$this->method = $method;

		$this->headers = $headers ? : new Map();
		$this->body = (string) $body;

		$queryParameters = $this->parseQueryString( $url->getQueryString() );

		$this->parameters = new Map();
		$this->parameters->addAll( $queryParameters );

		if ( !is_null( $parameters ) ) {
			$additionalParameters = $parameters->toArray();
			$this->parameters->addAll( $additionalParameters );
		}
	}

	/**
	 * Parses the given query string into a key-value map.
	 *
	 * @param string $query
	 *
	 * @return array
	 */
	private function parseQueryString( $query ) {
		$params = array();
		parse_str( $query, $params );
		return $params;
	}

	/**
	 * Retrieves the URL.
	 *
	 * @return Url
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * Retrieves the body of the request.
	 *
	 * @return string
	 */
	public function getBody() {
		return $this->body;
	}

	/**
	 * Retrieves the request method.
	 *
	 * @return string
	 */
	public function getMethod() {
		return $this->method;
	}

	/**
	 * Retrieves the headers.
	 *
	 * @return Map
	 */
	public function getHeaders() {
		return $this->headers;
	}

	/**
	 * Retrieves the request parameters.
	 *
	 * @return Map
	 */
	public function getParameters() {
		return $this->parameters;
	}
}