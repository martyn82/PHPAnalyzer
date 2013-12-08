<?php
const TEMPLATE = <<<JSON
var Report = %s;
JSON;

$data = file_get_contents( realpath( __DIR__  ) . "/data.js" );
header( 'Content-Type: text/javascript;charset=utf-8' );

print sprintf( TEMPLATE, $data );
