<?php
namespace Mend\Metrics\Analyze\UnitSize;

use Mend\IO\FileSystem\File;
use Mend\Metrics\Extract\SourceFileExtractor;
use Mend\Metrics\Model\Code\Method;
use Mend\Metrics\Model\Code\SourceUrl;
use Mend\Metrics\Source\SourceLineFilter;
use Mend\Metrics\Source\SourceLineFilterFactory;

class UnitSizeAnalyzer {
	/**
	 * @var SourceLineFilter
	 */
	private $filter;

	/**
	 * Calculates the size of the given method.
	 *
	 * @param Method $method
	 *
	 * @return UnitSizeResult
	 */
	public function calculateMethodSize( Method $method ) {
		$methodSource = $this->getMethodBody( $method );
		$unitSize = $this->countLines( $methodSource );
		$category = $this->getCategory( $unitSize );

		return new UnitSizeResult( $unitSize, $category );
	}

	/**
	 * Retrieves the category for the given size.
	 *
	 * @param integer $size
	 *
	 * @return integer
	 */
	private function getCategory( $size ) {
		if ( $size <= 10 ) {
			return UnitSizeCategory::SIZE_SMALL;
		}

		if ( $size > 10 && $size <= 20 ) {
			return UnitSizeCategory::SIZE_MEDIUM;
		}

		if ( $size > 20 && $size <= 50 ) {
			return UnitSizeCategory::SIZE_LARGE;
		}

		return UnitSizeCategory::SIZE_VERY_LARGE;
	}

	/**
	 * Retrieves the method body.
	 *
	 * @param Method $method
	 *
	 * @return array
	 */
	private function getMethodBody( Method $method ) {
		/* @var $url SourceUrl */
		$url = $method->getSourceUrl();
		$start = $url->getStart();
		$end = $url->getEnd();

		$file = new File( $url->getFilename() );
		$lines = $this->getSourceLines( $file );

		return $this->getMethodLines( $lines, $start->getLine(), $end->getLine() );
	}

	/**
	 * Retrieves the source lines for given file.
	 *
	 * @param File $file
	 *
	 * @return array
	 */
	protected function getSourceLines( File $file ) {
		$extractor = new SourceFileExtractor( $file );
		$filter = $this->getFilter( $file );

		return array_filter(
			$extractor->getSourceLines(),
			function ( $line ) use ( $filter ) {
				return $filter->isCode( trim( $line ) );
			}
		);
	}

	/**
	 * Retrieves the source line filter for given file.
	 *
	 * @param File $file
	 *
	 * @return SourceLineFilter
	 */
	private function getFilter( File $file ) {
		if ( is_null( $this->filter ) ) {
			$filterFactory = new SourceLineFilterFactory();
			$this->filter = $filterFactory->createByFileExtension( $file->getExtension() );
		}

		return $this->filter;
	}

	/**
	 * Slice the given lines between start and end.
	 *
	 * @param array $lines
	 * @param integer $startLine
	 * @param integer $endLine
	 *
	 * @return array
	 */
	private function getMethodLines( array $lines, $startLine, $endLine ) {
		$result = array();

		foreach ( $lines as $number => $line ) {
			if ( $number < $startLine ) {
				continue;
			}

			if ( $number > $endLine ) {
				break;
			}

			$result[ $number ] = $line;
		}

		return $result;
	}

	/**
	 * Counts the number of lines in given string.
	 *
	 * @param array $source
	 *
	 * @return integer
	 */
	private function countLines( array $source ) {
		return count( $source );
	}
}
