<?php
namespace Mend\Metrics\Duplication;

use Mend\IO\FileSystem\File;
use Mend\IO\FileSystem\FileArray;
use Mend\Network\Web\Url;
use Mend\Source\Code\Location\SourceUrl;
use Mend\Source\Extract\SourceFileExtractor;

class CodeBlockExtractor {
	/**
	 * @var integer
	 */
	const DEFAULT_CODE_BLOCK_SIZE = CodeBlock::DEFAULT_SIZE;

	/**
	 * Retrieves the code blocks that can be made from the given files.
	 *
	 * @param FileArray $files
	 * @param integer $blockSize
	 *
	 * @return CodeBlockArray
	 */
	public function getCodeBlocks( FileArray $files, $blockSize = self::DEFAULT_CODE_BLOCK_SIZE ) {
		$blocks = array();

		foreach ( $files as $file ) {
			$fileBlocks = $this->getCodeBlocksFromFile( $file, (int) $blockSize );
			$blocks = array_merge( $blocks, (array) $fileBlocks );
		}

		return new CodeBlockArray( $blocks );
	}

	/**
	 * Retrieves the code blocks that can be made from the given file.
	 *
	 * @param File $file
	 * @param integer $blockSize
	 *
	 * @return array
	 */
	public function getCodeBlocksFromFile( File $file, $blockSize = self::DEFAULT_CODE_BLOCK_SIZE ) {
		$lines = $this->getFileSourceLines( $file );
		return $this->createCodeBlocks( $lines, $file->getName(), (int) $blockSize );
	}

	/**
	 * Creates code blocks from the given lines.
	 *
	 * @param array $lines
	 * @param string $fileName
	 * @param integer $blockSize
	 *
	 * @return CodeBlockArray
	 */
	public function createCodeBlocks( array $lines, $fileName = null, $blockSize = self::DEFAULT_CODE_BLOCK_SIZE ) {
		$blocks = array();
		$length = count( $lines );
		$fileName = (string) $fileName ? : '/';
		$blockSize = (int) $blockSize;

		for ( $index = 0; $length >= ( $index + $blockSize ); $index++ ) {
			$lineNumbers = array_slice( array_keys( $lines ), $index, $blockSize );
			$lineSource = array_slice( $lines, $index, $blockSize );

			$location = $this->createSourceUrl( $fileName, reset( $lineNumbers ), end( $lineNumbers ) );
			$blocks[] = new CodeBlock( $location, array_combine( $lineNumbers, $lineSource ), $index );
		}

		return new CodeBlockArray( $blocks );
	}

	/**
	 * Creates a SourceUrl instance from file and start and end lines.
	 *
	 * @param string $fileName
	 * @param integer $startLine
	 * @param integer $endLine
	 *
	 * @return SourceUrl
	 */
	private function createSourceUrl( $fileName, $startLine, $endLine ) {
		$url = Url::createFromString(
			'file://' . (string) $fileName
			. '#' . sprintf( "(%d,0),(%d,0)", (int) $startLine, (int) $endLine )
		);
		return new SourceUrl( $url );
	}

	/**
	 * Retrieves the contents of the file as an array of numbered lines of code.
	 *
	 * @param File $file
	 *
	 * @return array
	 */
	protected function getFileSourceLines( File $file ) {
		$extractor = new SourceFileExtractor( $file );
		$filter = $extractor->getSourceLineFilter();
		$lines = $extractor->getSourceLines();

		return array_filter(
			$lines,
			function ( $line ) use ( $filter ) {
				return $filter->isCode( $line );
			}
		);
	}
}
