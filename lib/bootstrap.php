<?php
if ( !defined( 'ROOT_DIR' ) ) {
	define( 'ROOT_DIR', realpath( __DIR__ ) );
}

require_once ROOT_DIR . "/../vendor/nikic/php-parser/lib/bootstrap.php";
require_once ROOT_DIR . "/Autoloader.php";

Autoloader::addIgnorePrefix( 'PHPParser' );
Autoloader::setRootDir( ROOT_DIR );
Autoloader::register();