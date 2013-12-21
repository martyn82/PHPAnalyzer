<?php
namespace Mend\Network\Web;

use rest\ResourceFactory;
use rest\Resource;

class RESTServer {
	/**
	 * @var HttpRequest
	 */
	private $request;

	/**
	 * @var Response
	 */
	private $response;

	/**
	 * Constructs a new server.
	 *
	 * @param HttpRequest $request
	 */
	public function __construct( HttpRequest $request ) {
		$this->request = $request;
		$this->response = new HttpResponse();
	}

	/**
	 * Dispatches to the given resource.
	 *
	 * @param string $resource
	 */
	public function dispatch( $resource ) {
		try {
			$resourceObject = ResourceFactory::createResourceFromString( $resource );
			$resourceObject->preDispatch();

			$output = $this->dispatchMethod( $resourceObject );
			$resourceObject->postDispatch();

			if ( !is_null( $output ) ) {
				$this->setOutput( $output );
			}
		}
		catch ( \Exception $e ) {
			$this->setError( HttpResponse::STATUS_INTERNAL_SERVER_ERROR, $e->__toString() );
		}

		$this->sendResponse();
	}

	/**
	 * Dispatches the resource with given method.
	 *
	 * @param Resource $resource
	 *
	 * @return array
	 *
	 * @throws \Exception
	 */
	private function dispatchMethod( Resource $resource ) {
		$result = null;
		$method = $this->request->getMethod();

		switch ( $method ) {
			case HttpRequest::METHOD_GET:
				$identifier = $this->request->getGetParam( 'id' );
				$properties = $this->request->getGetParam( 'properties', array() );
				$result = $resource->select( $identifier, $properties );
				break;

			case HttpRequest::METHOD_POST:
				$body = json_decode( $this->request->getBody(), true );
				$result = $resource->create( $body );
				$this->response->setStatusCode( Response::STATUS_CREATED );
				break;

			case HttpRequest::METHOD_PUT:
			case HttpRequest::METHOD_PATCH:
				$body = json_decode( $this->request->getBody(), true );
				$identifier = $this->request->getGetParam( 'id' );
				$result = $resource->update( $identifier, $body );
				break;

			case HttpRequest::METHOD_DELETE:
				$identifier = $this->request->getGetParam( 'id' );
				$resource->delete( $identifier );
				break;

			case HttpRequest::METHOD_OPTIONS:
			case HttpRequest::METHOD_HEAD:
			default:
				throw new \Exception( "Invalid request method <{$method}>." );
		}

		return $result;
	}

	/**
	 * Sets an error response.
	 *
	 * @param integer $statusCode
	 * @param string $message
	 */
	private function setError( $statusCode, $message ) {
		$this->response->clear();

		$this->response->setStatusCode( (int) $statusCode );
		$this->response->setBody( (string) $message );
	}

	/**
	 * Sets the output.
	 *
	 * @param array $output
	 */
	private function setOutput( array $output ) {
		$this->response->clear();

		$this->response->setHeader( 'content-type', 'application/json' );
		$this->response->setBody( json_encode( $output ) );
	}

	/**
	 * Sends the response.
	 */
	public function sendResponse() {
		$this->sendStatus( $this->response->getStatusCode() );
		$this->sendHeaders( $this->response->getHeaders() );
		$this->sendBody( $this->response->getBody() );
	}

	/**
	 * Sets status code.
	 *
	 * @param integer $statusCode
	 */
	private function sendStatus( $statusCode ) {
		header( $statusCode, true, (int) $statusCode );
	}

	/**
	 * Sends headers.
	 *
	 * @param array $headers
	 */
	private function sendHeaders( array $headers = array() ) {
		foreach ( $headers as $name => $value ) {
			header( "{$name}: {$value}" );
		}
	}

	/**
	 * Sends the body.
	 *
	 * @param string $body
	 */
	private function sendBody( $body ) {
		print $body;
	}
}