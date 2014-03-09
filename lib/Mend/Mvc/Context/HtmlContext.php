<?php
namespace Mend\Mvc\Context;

use Mend\Mvc\Context;
use Mend\Network\MimeType;

class HtmlContext extends Context {
	/**
	 * Constructs a new HtmlContext instance.
	 */
	public function __construct() {
		parent::__construct( MimeType::HTML, '.phtml' );
	}
}
