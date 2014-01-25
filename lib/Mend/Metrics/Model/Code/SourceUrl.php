<?php
namespace Mend\Metrics\Model\Code;

use Mend\Network\Web\Url;

class SourceUrl extends Url {
	/**
	 * @var Location
	 */
	private $start;

	/**
	 * @var Location
	 */
	private $end;

	/**
	 * Constructs a new SourceUrl.
	 *
	 * @param Url $url
	 */
	public function __construct( Url $url ) {
		$instance = parent::createFromString( $url->__toString() );

		parent::__construct(
				$instance->getScheme(),
				$instance->getHost(),
				$instance->getPort(),
				$instance->getUsername(),
				$instance->getPassword(),
				$instance->getPath(),
				$instance->getQueryString(),
				$instance->getFragment()
		);

		$this->setUrl( $url->__toString() );
	}

	/**
	 * Parses the URL fragment.
	 */
	private function parseFragment() {
		$parts = array();
		preg_match( '/(\(\d+,\d+\)),(\(\d+,\d+\))/', $this->getFragment(), $parts );

		if ( empty( $parts ) || count( $parts ) < 2 ) {
			$this->start = Location::createEmpty();
			$this->end = Location::createEmpty();
			return;
		}

		$locationPattern = '/\((\d+),(\d+)\)/';

		$start = array();
		preg_match( $locationPattern, $parts[ 1 ], $start );

		if ( empty( $start ) ) {
			$this->start = Location::createEmpty();
			$this->end = Location::createEmpty();
			return;
		}

		$this->start = new Location( (int) $start[ 1 ], (int) $start[ 2 ] );

		if ( empty( $parts[ 2 ] ) ) {
			$this->end = Location::createEmpty();
			return;
		}

		$end = array();
		preg_match( $locationPattern, $parts[ 2 ], $end );

		if ( empty( $end ) ) {
			$this->end = Location::createEmpty();
			return;
		}

		$this->end = new Location( (int) $end[ 1 ], (int) $end[ 2 ] );
	}

	/**
	 * Retrieves the filename part.
	 *
	 * @return string
	 */
	public function getFilename() {
		return $this->getPath();
	}

	/**
	 * Retrieves the start location.
	 *
	 * @return Location
	 */
	public function getStart() {
		if ( is_null( $this->start ) ) {
			$this->parseFragment();
		}

		return $this->start;
	}

	/**
	 * Retrieves the end location.
	 *
	 * @return Location
	 */
	public function getEnd() {
		if ( is_null( $this->end ) ) {
			$this->parseFragment();
		}

		return $this->end;
	}
}