<?php
namespace Mend\Mvc\Context;

use Mend\Mvc\Context;
use Mend\Network\MimeType;

class JsonContext extends Context {
	/**
	 * Creates a new JsonContext instance.
	 */
	public function __construct() {
		parent::__construct( MimeType::JSON, '.json' );
	}
}
