<?php
namespace resource;

use rest\Resource;

class ProjectResource extends Resource {
	public function select( $id, array $properties = array() ) {
		$reports = $this->dataIndex( $id );
		$firstReport = reset( $reports );

		$data = $this->readData( $firstReport );
		$project = $data[ 'project' ];

		$result = array(
			'id' => $project[ 'key' ],
			'name' => $project[ 'name' ],
			'absolutePath' => $project[ 'path' ],
			'reports' => array()
		);

		foreach ( $reports as $report ) {
			$matches = array();
			preg_match( '/^[a-z]+_([0-9]{4})([0-9]{2})([0-9]{2})\.json$/', $report, $matches );
			$result[ 'reports' ][] = "report/{$id}/{$matches[ 1 ]}/{$matches[ 2 ]}/{$matches[ 3 ]}/";
		}

		return $result;
	}
}