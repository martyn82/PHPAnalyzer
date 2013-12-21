<?php
namespace resource;

use rest\Resource;

class VolumeResource extends Resource {
	public function select( $id, array $properties = array() ) {
		if ( count( $properties ) < 3 ) {
			throw new \Exception( "Insufficient parameters given." );
		}

		$year = $properties[ 0 ];
		$month = $properties[ 1 ];
		$day = $properties[ 2 ];

		$date = \DateTime::createFromFormat( 'Y-m-d', "{$year}-{$month}-{$day}" );
		$dateString = $date->format( 'Ymd' );

		$fileName = "{$id}_{$dateString}.json";
		$data = $this->readData( $fileName );

		$volume = $data[ 'volume' ];

// 			'classCount' => (int) $volume[ 'classCount' ],
// 			'fileCount' => (int) $volume[ 'fileCount' ],
// 			'methodCount' => (int) $volume[ 'methodCount' ],
// 			'packageCount' => (int) $volume[ 'packageCount' ],

		return $volume;
	}
}