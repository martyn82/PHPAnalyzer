<?php
namespace resource;

use rest\Resource;

class ReportResource extends Resource {
	public function select( $id, array $properties = array() ) {
		if ( count( $properties ) < 3 ) {
			throw new \Exception( "Insufficient parameters given." );
		}

		$year = $properties[ 0 ];
		$month = $properties[ 1 ];
		$day = $properties[ 2 ];

		$date = \DateTime::createFromFormat( 'Y-m-d', "{$year}-{$month}-{$day}" );
		$dateString = $date->format( 'Ymd' );

		$reportLocation = "{$id}/{$year}/{$month}/{$day}/";

		return array(
			'project' => $id,
			'dateTime' => $date->format( 'r' ),
			'volume' => "volume/{$reportLocation}/",
			'complexity' => "complexity/{$reportLocation}/",
			'unitSize' => "unitSize/{$reportLocation}/",
			'duplication' => "duplication/{$reportLocation}/"
		);
	}
}