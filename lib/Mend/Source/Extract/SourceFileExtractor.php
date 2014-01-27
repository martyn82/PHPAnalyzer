<?php
namespace Mend\Source\Extract;

use Mend\IO\FileSystem\File;
use Mend\IO\Stream\FileStreamReader;
use Mend\Source\Filter\SourceLineFilterFactory;

class SourceFileExtractor {
	/**
	 * @var File
	 */
	private $file;

	/**
	 * @var string
	 */
	private $source;

	/**
	 * @var SourceLineFilter
	 */
	private $filter;

	/**
	 * @var array
	 */
	private $lines;

	/**
	 * Constructs a new source file analyzer instance.
	 *
	 * @param File $file
	 */
	public function __construct( File $file ) {
		$this->file = $file;
	}

	/**
	 * Retrieves the source line filter.
	 *
	 * @return SourceLineFilter
	 */
	public function getSourceLineFilter() {
		if ( is_null( $this->filter ) ) {
			$factory = new SourceLineFilterFactory();
			$this->filter = $factory->createByFileExtension( $this->getFileExtension() );
		}

		return $this->filter;
	}

	/**
	 * Retrieves the file extension.
	 *
	 * @return string
	 */
	private function getFileExtension() {
		return $this->file->getExtension();
	}

	/**
	 * Retrieves the file source.
	 *
	 * @return string
	 */
	public function getFileSource() {
		if ( is_null( $this->source ) ) {
			$this->source = $this->readFileContents( $this->file );
		}

		return $this->source;
	}

	/**
	 * Reads and returns the given file's contents.
	 *
	 * @param File $file
	 *
	 * @return string
	 */
	private function readFileContents( File $file ) {
		$reader = new FileStreamReader( $file );
		$reader->open();

		$contents = $reader->read();
		$reader->close();

		return $contents;
	}

	/**
	 * Retrieves an array with source lines as values and line numbers as keys.
	 *
	 * @return array
	 */
	public function getSourceLines() {
		if ( is_null( $this->lines ) ) {
			$source = $this->getFileSource();
			$lines = explode( "\n", $source );
			$numbers = range( 1, count( $lines ) );
			$this->lines = array_combine( $numbers, $lines );
		}

		return $this->lines;
	}
}
