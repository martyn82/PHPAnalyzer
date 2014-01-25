<?php
namespace Mend\Metrics\Analyze\Volume;

use Mend\IO\FileSystem\FileArray;
use Mend\Metrics\Extract\SourceFileExtractor;
use Mend\Metrics\Source\SourceLineFilter;

class VolumeAnalyzer {
	/**
	 * @var FileArray
	 */
	private $files;

	/**
	 * @var array
	 */
	private $extractors;

	/**
	 * Constructs a new VolumeAnalyzer.
	 *
	 * @param FileArray $files
	 */
	public function __construct( FileArray $files ) {
		$this->files = $files;
	}

	/**
	 * Retrieves an array of source extractor instances.
	 *
	 * @return SourceExtractor
	 */
	protected function getSourceExtractors() {
		if ( is_null( $this->extractors ) ) {
			$this->extractors = array_map(
				function ( File $file ) {
					return new SourceFileExtractor( $file );
				},
				(array) $this->files
			);
		}

		return $this->extractors;
	}

	/**
	 * Counts and returns the number of source lines.
	 *
	 * @return integer
	 */
	public function getLinesCount() {
		return array_reduce(
			$this->getSourceExtractors(),
			function ( $result, SourceFileExtractor $extractor ) {
				$result += count( $extractor->getSourceLines() );
				return $result;
			},
			0
		);
	}

	/**
	 * Counts and returns the number of source lines after using the given filter function.
	 *
	 * @param string $filterType
	 *
	 * @return integer
	 */
	private function getFilteredLinesCount( $filterType ) {
		return array_reduce(
			$this->getSourceExtractors(),
			function ( $result, SourceFileExtractor $extractor ) use ( $filterType ) {
				$lines = $extractor->getSourceLines();
				$filter = $extractor->getSourceLineFilter();

				$filteredLines = array_filter( $lines, array( $filter, $filterType ) );
				return $result + count( $filteredLines );
			},
			0
		);
	}

	/**
	 * Counts and returns the number of lines of code.
	 *
	 * @return integer
	 */
	public function getLinesOfCodeCount() {
		return $this->getFilteredLinesCount( SourceLineFilter::FILTER_CODE );
	}

	/**
	 * Counts and returns the number of lines of comments.
	 *
	 * @return integer
	 */
	public function getLinesOfCommentsCount() {
		return $this->getFilteredLinesCount( SourceLineFilter::FILTER_COMMENT );
	}

	/**
	 * Counts and returns the number of blank lines.
	 *
	 * @return integer
	 */
	public function getBlankLinesCount() {
		return $this->getFilteredLinesCount( SourceLineFilter::FILTER_BLANK );
	}
}
