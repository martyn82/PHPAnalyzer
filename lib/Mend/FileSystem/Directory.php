<?php
namespace Mend\FileSystem;

class Directory {
	/**
	 * @var string
	 */
	private $location;

	/**
	 * Constructs a new directory instance.
	 *
	 * @param string $location
	 *
	 * @throws FileSystemException
	 */
	public function __construct( $location ) {
		if ( !is_dir( $location ) ) {
			throw new FileSystemException( "Given location is not a directory." );
		}

		$this->location = $location;
	}

	/**
	 * Retrieves the full path name.
	 *
	 * @return string
	 */
	public function getName() {
		return $this->location;
	}
}