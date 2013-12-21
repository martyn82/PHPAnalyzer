<?php
namespace Mend\Network\Web;

class HttpResponse {
	const STATUS_CONTINUE = 100;
	const STATUS_SWITCH_PROTOCOLS = 101;

	const STATUS_OK = 200;
	const STATUS_CREATED = 201;
	const STATUS_ACCEPTED = 202;
	const STATUS_NONAUTHORITIVE = 203;
	const STATUS_NO_CONTENT = 204;
	const STATUS_RESET_CONTENT = 205;
	const STATUS_PARTIAL_CONTENT = 206;

	const STATUS_MULTIPLE_CHOICES = 300;
	const STATUS_MOVED_PERMANENTLY = 301;
	const STATUS_FOUND = 302;
	const STATUS_SEE_OTHER = 303;
	const STATUS_NOT_MODIFIED = 304;
	const STATUS_USE_PROXY = 305;
	const STATUS_TEMP_REDIRECT = 306;

	const STATUS_BAD_REQUEST = 400;
	const STATUS_UNAUTHORIZED = 401;
	const STATUS_PAYMENT_REQUIRED = 402;
	const STATUS_FORBIDDEN = 403;
	const STATUS_NOT_FOUND = 404;
	const STATUS_METHOD_NOT_ALLOWED = 405;
	const STATUS_NOT_ACCEPTABLE = 406;
	const STATUS_PROXY_AUTHENTICATION_REQUIRED = 407;
	const STATUS_REQUEST_TIMEOUT = 408;
	const STATUS_CONFLICT = 409;
	const STATUS_GONE = 410;
	const STATUS_LENGTH_REQUIRED = 411;
	const STATUS_PRECONDITION_FAILED = 412;
	const STATUS_REQUEST_ENTITY_TOO_LARGE = 413;
	const STATUS_REQUEST_URI_TOO_LONG = 414;
	const STATUS_UNSUPPORTED_MEDIA_TYPE = 415;
	const STATUS_REQUESTED_RANGE_NOT_SATISFIABLE = 416;
	const STATUS_EXPECTATION_FAILED = 417;

	const STATUS_INTERNAL_SERVER_ERROR = 500;
	const STATUS_NOT_IMPLEMENTED = 501;
	const STATUS_BAD_GATEWAY = 502;
	const STATUS_SERVICE_UNAVAILABLE = 503;
	const STATUS_GATEWAY_TIMEOUT = 504;
	const STATUS_HTTP_VERSION_NOT_SUPPORTED = 505;

	/**
	 * @var array
	 */
	private $headers;

	/**
	 * @var string
	 */
	private $body;

	/**
	 * @var integer
	 */
	private $status;

	/**
	 * Constructs a new response.
	 */
	public function __construct() {
		$this->clear();
	}

	/**
	 * Clears the response.
	 */
	public function clear() {
		$this->clearStatus();
		$this->clearHeaders();
		$this->clearBody();
	}

	/**
	 * Clears the status.
	 */
	public function clearStatus() {
		$this->status = self::STATUS_OK;
	}

	/**
	 * Clears all headers.
	 */
	public function clearHeaders() {
		$this->headers = array();
	}

	/**
	 * Clears the body.
	 */
	public function clearBody() {
		$this->body = '';
	}

	/**
	 * Sets a named header.
	 *
	 * @param string $name
	 * @param string $value
	 */
	public function setHeader( $name, $value ) {
		$this->headers[ (string) $name ] = (string) $value;
	}

	/**
	 * Sets status code.
	 *
	 * @param integer $code
	 */
	public function setStatusCode( $code ) {
		$this->status = (int) $code;
	}

	/**
	 * Sets the body.
	 *
	 * @param string $body
	 */
	public function setBody( $body ) {
		$this->body = (string) $body;
	}

	/**
	 * Retrieves the body.
	 *
	 * @return string
	 */
	public function getBody() {
		return $this->body;
	}

	/**
	 * Retrieves the status code.
	 *
	 * @return integer
	 */
	public function getStatusCode() {
		return $this->status;
	}

	/**
	 * Retrieves the headers as key-value map.
	 *
	 * @return array
	 */
	public function getHeaders() {
		return $this->headers;
	}
}