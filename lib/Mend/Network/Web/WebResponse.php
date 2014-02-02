<?php
namespace Mend\Network\Web;

use Mend\Collections\Map;

class WebResponse {
	/**
	 * @var Url
	 */
	private $url;

	/**
	 * @var Map
	 */
	private $headers;

	/**
	 * @var string
	 */
	private $body;

	/**
	 * @var integer
	 */
	private $statusCode;

	/**
	 * @var string
	 */
	private $statusDescription;

	/**
	 * Constructs a new WebResponse.
	 *
	 * @param Url $url
	 * @param Map $headers
	 * @param string $body
	 */
	public function __construct(
		Url $url,
		Map $headers = null,
		$body = null,
		$statusCode = HttpStatus::STATUS_OK,
		$statusDescription = null
	) {
		$this->url = $url;
		$this->headers = $headers ? : new Map();
		$this->body = (string) $body;
		$this->statusCode = (int) $statusCode;
		$this->statusDescription = (string) $statusDescription;
	}

	/**
	 * Retrieves the response URL.
	 *
	 * @return Url
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * Retrieves the response headers.
	 *
	 * @return Map
	 */
	public function getHeaders() {
		return $this->headers;
	}

	/**
	 * Retrieves the response body.
	 *
	 * @return string
	 */
	public function getBody() {
		return $this->body;
	}

	/**
	 * Retrieves the response status code.
	 *
	 * @return integer
	 */
	public function getStatusCode() {
		return $this->statusCode;
	}

	/**
	 * Retrieves the response status description.
	 *
	 * @return string
	 */
	public function getStatusDescription() {
		return $this->statusDescription;
	}

	/**
	 * Sets the response body.
	 *
	 * @param string $value
	 */
	public function setBody( $value ) {
		$this->body = (string) $value;
	}

	/**
	 * Sets the response status code.
	 *
	 * @param integer $value
	 */
	public function setStatusCode( $value ) {
		$this->statusCode = (int) $value;
	}

	/**
	 * Sets the response status description.
	 *
	 * @param string $value
	 */
	public function setStatusDescription( $value ) {
		$this->statusDescription = (string) $value;
	}
}