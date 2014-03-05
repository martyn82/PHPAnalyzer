<?php
use Mend\Application;
use Mend\Config\ConfigProvider;
use Mend\Config\IniConfigReader;
use Mend\IO\FileSystem\File;
use Mend\IO\Stream\FileStreamReader;

require_once realpath( __DIR__ . "/.." ) . "/bootstrap.php";

$file = new File( 'config/application.ini' );
$fsReader = new FileStreamReader( $file );
$reader = new IniConfigReader( $fsReader );
$config = new ConfigProvider( $reader );

$application = new Application( $config );
$application->run();

$controller = $application->getController();
$response = $controller->getResponse();
$headers = $response->getHeaders();

foreach ( $headers as $name => $value ) {
	header( "{$name}: {$value}" );
}

header( 'HTTP/1.1 ' . (string) $response->getStatusCode() . ' ' . $response->getStatusDescription() );
print $response->getBody();
