<?php
namespace Mend\Mvc;

use Mend\Network\Web\WebRequest;
use Mend\Network\Web\WebResponse;

abstract class Controller {
	/**
	 * @var WebRequest
	 */
	private $request;

	/**
	 * @var WebResponse
	 */
	private $response;

	/**
	 * @var ControllerFactory
	 */
	private $factory;

	/**
	 * Constructs a new controller instance.
	 *
	 * @param WebRequest $request
	 * @param WebResponse $response
	 * @param ControllerFactory $factory
	 */
	public function __construct( WebRequest $request, WebResponse $response, ControllerFactory $factory ) {
		$this->request = $request;
		$this->response = $response;
		$this->factory = $factory;
	}

	/**
	 * Retrieves the controller factory.
	 *
	 * @return ControllerFactory
	 */
	protected function getFactory() {
		return $this->factory;
	}

	/**
	 * Retrieves the request.
	 *
	 * @return WebRequest
	 */
	public function getRequest() {
		return $this->request;
	}

	/**
	 * Retrieves the response.
	 *
	 * @return WebResponse
	 */
	public function getResponse() {
		return $this->response;
	}

	/**
	 * Retrieves the current controller name.
	 *
	 * @return string
	 */
	abstract protected function getControllerName();

	/**
	 * Retrieves the current action name.
	 *
	 * @return string
	 */
	abstract protected function getActionName();
}
