<?php
namespace Controller;

use Mend\Mvc\Controller\PageController;
use Mend\Network\MimeType;
use Mend\Rest\ResourceResult;
use Mend\Mvc\Context\JsonContext;

class ServiceController extends PageController {
	private $data;

	protected function init() {
		$this->setContext( new JsonContext() );
	}

	protected function render() {
		$this->getViewRenderer()->disable();
		return $this->data;
	}

	public function actionProjects() {
		$request = $this->getRequest();
		$parameters = $request->getParameters();

		if ( $parameters->hasKey( 'id' ) ) {
			$id = $parameters->get( 'id' );
			$this->data = $this->getReports( $id );
		}
		else {
			$this->data = $this->getProjects();
		}
	}

	private function getProjects() {
		return $this->get( 'reports' );
	}

	private function getReports( $projectId ) {
		return $this->get( 'reports/' . $projectId );
	}

	private function get( $uri ) {
		$ch = curl_init( 'http://service.analyze.local/' . $uri );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		$result = curl_exec( $ch );
		curl_close( $ch );

		return $result;
	}
}