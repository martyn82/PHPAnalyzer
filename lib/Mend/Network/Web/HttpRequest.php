<?php
namespace Mend\Network\Web;

class HttpRequest {
	const METHOD_POST = 'POST';
	const METHOD_GET = 'GET';
	const METHOD_PUT = 'PUT';
	const METHOD_DELETE = 'DELETE';
	const METHOD_HEAD = 'HEAD';
	const METHOD_OPTIONS = 'OPTIONS';
	const METHOD_PATCH = 'PATCH';

	/**
	 * @var array
	 */
	private $headers;

	/**
	 * @var array
	 */
	private $get;

	/**
	 * @var array
	 */
	private $post;

	/**
	 * @var array
	 */
	private $params;

	/**
	 * @var string
	 */
	private $method;

	/**
	 * @var string
	 */
	private $body;

	/**
	 * Creates a HttpRequest.
	 *
	 * @return HttpRequest
	 */
	public static function create() {
		return new self(
			$_SERVER[ 'REQUEST_METHOD' ],
			getallheaders(),
			$_GET,
			$_POST,
			file_get_contents( 'php://input' )
		);
	}

	/**
	 * Constructs a new Request.
	 *
	 * @param string $method
	 * @param array $headers
	 * @param array $getParams
	 * @param array $postParams
	 * @param string $body
	 */
	public function __construct(
		$method = null,
		array $headers = array(),
		array $getParams = array(),
		array $postParams = array(),
		$body = ''
	) {
		$this->method = (string) $method;
		$this->headers = $headers;
		$this->get = $getParams;
		$this->post = $postParams;
		$this->params = array_merge( $getParams, $postParams );
		$this->body = (string) $body;
	}

	/**
	 * Retrieves a header.
	 *
	 * @param string $name
	 * @param mixed $default
	 *
	 * @return mixed
	 */
	public function getHeader( $name, $default = null ) {
		if ( !isset( $this->headers[ $name ] ) ) {
			return $default;
		}

		return $this->headers[ $name ];
	}

	/**
	 * Retrieves a parameter.
	 *
	 * @param string $name
	 * @param mixed $default
	 *
	 * @return mixed
	 */
	public function getParam( $name, $default = null ) {
		if ( !isset( $this->params[ $name ] ) ) {
			return $default;
		}

		return $this->params[ $name ];
	}

	/**
	 * Retrieves a GET parameter.
	 *
	 * @param string $name
	 * @param mixed $default
	 *
	 * @return mixed
	 */
	public function getGetParam( $name, $default = null ) {
		if ( !isset( $this->get[ $name ] ) ) {
			return $default;
		}

		return $this->get[ $name ];
	}

	/**
	 * Retrieves a POST parameter.
	 *
	 * @param string $name
	 * @param mixed $default
	 *
	 * @return mixed
	 */
	public function getPostParam( $name, $default = null ) {
		if ( !isset( $this->post[ $name ] ) ) {
			return $default;
		}

		return $this->post[ $name ];
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
	 * Retrieves the raw request body.
	 *
	 * @return string
	 */
	public function getBody() {
		return $this->body;
	}
}