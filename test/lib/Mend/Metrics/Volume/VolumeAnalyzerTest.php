<?php
namespace Mend\Metrics\Volume;

use Mend\IO\DirectoryStream;
use Mend\IO\FileSystem\Directory;
use Mend\IO\FileSystem\File;
use Mend\IO\FileSystem\FileArray;
use Mend\IO\Stream\FileStreamReader;

use Mend\Source\SourceLineFilter;

class VolumeAnalyzerTest extends \TestCase {
	private static $CODE_FRAGMENT_1 = <<<PHP
<?php
function main() {
	\$foo = 'bar';

	// this is a comment
	\$baz = 'boo';
}

main();

PHP;

	private static $CODE_FRAGMENT_2 = <<<PHP
<?php
namespace Vendor\Default\Package;

class Foo
	extends Bar
	implements Baz
{
	private \$foo;
	private \$bar;

	/**
	 * Constructs a new instance.
	 *
	 * @param string \$foo
	 * @param integer \$bar
	 */
	public function __construct( \$foo, \$bar ) {
		\$this->foo = \$foo;
		\$this->bar = \$bar;
	}

	/**
	 * Do something.
	 */
	public function do() {
		/* this will
		   cover
		   multiple lines
		*/

		\$i = 0;
		while ( true && \$i < 1000 ) {
			\$i++;
		}
	}
}

PHP;

	/**
	 * @dataProvider lineCountProvider
	 *
	 * @param string $code
	 * @param integer $lineCount
	 * @param integer $linesOfCodeCount
	 * @param integer $linesOfCommentCount
	 * @param integer $linesBlankCount
	 */
	public function testLineCounts( $code, $lineCount, $linesOfCodeCount, $linesOfCommentCount, $linesBlankCount ) {
		$file = $this->getMock( '\Mend\IO\FileSystem\File', array( 'getExtension' ), array( '/tmp/foo.php' ) );
		$file->expects( self::any() )->method( 'getExtension' )->will( self::returnValue( 'php' ) );

		$files = new FileArray( array( $file ) );

		$sourceExtractor = $this->getMock(
			'\Mend\Source\Extract\SourceFileExtractor',
			array( 'getFileSource' ),
			array( $file )
		);

		$sourceExtractor->expects( self::any() )->method( 'getFileSource' )->will( self::returnValue( $code ) );

		$volumeAnalyzer = $this->getMock(
			'\Mend\Metrics\Volume\VolumeAnalyzer',
			array( 'getSourceExtractors' ),
			array( $files )
		);

		$volumeAnalyzer->expects( self::any() )
			->method( 'getSourceExtractors' )
			->will( self::returnValue( array( $sourceExtractor ) ) );

		self::assertEquals( $lineCount, $volumeAnalyzer->getLinesCount() );
		self::assertEquals( $linesOfCodeCount, $volumeAnalyzer->getLinesOfCodeCount() );
		self::assertEquals( $linesOfCommentCount, $volumeAnalyzer->getLinesOfCommentsCount() );
		self::assertEquals( $linesBlankCount, $volumeAnalyzer->getBlankLinesCount() );
	}

	/**
	 * @return array of array( code, lineCount, linesOfCodeCount, linesOfCommentCount, linesBlankCount )
	 */
	public function lineCountProvider() {
		return array(
			array( self::$CODE_FRAGMENT_1, 10,  6,  1,  3 ),
			array( self::$CODE_FRAGMENT_2, 37, 19, 13,  5 )
		);
	}

	public function testGetSourceExtractors() {
		$file = $this->getMock( '\Mend\IO\FileSystem\File', array( 'getExtension' ), array(), '', false );
		$file->expects( self::any() )
			->method( 'getExtension' )
			->will( self::returnValue( 'php' ) );

		$files = new FileArray( array( $file ) );

		$analyzer = new DummyVolumeAnalyzer( $files );
		$extractors = $analyzer->getSourceExtractors();

		self::assertEquals( count( $files ), count( $extractors ) );
	}
}

class DummyVolumeAnalyzer extends VolumeAnalyzer {
	public function getSourceExtractors() {
		return parent::getSourceExtractors();
	}
}
