<?php
namespace Mend\Mvc\Context;

use Mend\Mvc\Context;
use Mend\Network\MimeType;

class TextContext extends Context {
	/**
	 * Constructs a new TextContext instance.
	 */
	public function __construct() {
		parent::__construct( MimeType::TEXT, '.txt' );
	}
}
