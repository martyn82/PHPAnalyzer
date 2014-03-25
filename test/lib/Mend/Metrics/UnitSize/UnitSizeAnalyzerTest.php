<?php
namespace Mend\Metrics\UnitSize;

use Mend\IO\FileSystem\File;
use Mend\IO\Stream\FileStreamReader;
use Mend\Network\Web\Url;
use Mend\Parser\Node\PHPNode;
use Mend\Source\Code\Location\SourceUrl;
use Mend\Source\Code\Model\Method;
use Mend\Source\Extract\SourceFileExtractor;

class UnitSizeAnalyzerTest extends \TestCase {
	private static $CODE_FRAGMENT_1 = <<<PHP
<?php
namespace Vendor\Package;

class Foo extends Bar {
	/**
	 * Returns always true.
	 *
	 * @return boolean
	 */
	public function fooMethod() {
		return true;
	}
}
PHP;

	private static $CODE_FRAGMENT_2 = <<<PHP
<?php
function fooMethod() {
	if ( true ) {
	}
	else if ( false ) {
	}
	else {
	}

	while ( true ) {
	}

	foreach ( \$one ) {
	}
}
PHP;

	private static $CODE_FRAGMENT_3 = <<<PHP
<?php
function foo() {
	if ( true ) {
	}
	else if ( false )
	{
	}
	else
	{
	}

	while ( true )
	{
	}

	foreach ( \$one )
	{
	}

	if ( true ) {
	}
	else if ( false )
	{
	}
	else
	{
	}

	while ( true )
	{
	}

	foreach ( \$one )
	{
	}
}
PHP;

	private static $CODE_FRAGMENT_4 = <<<PHP
<?php
function foo() {
	if ( true ) {
	}
	else if ( false )
	{
	}
	else
	{
	}

	while ( true )
	{
	}

	foreach ( \$one )
	{
	}

	if ( true ) {
	}
	else if ( false )
	{
	}
	else
	{
	}

	while ( true )
	{
	}

	foreach ( \$one )
	{
	}

	if ( true ) {
	}
	else if ( false )
	{
	}
	else
	{
	}

	while ( true )
	{
	}

	foreach ( \$one )
	{
	}

	if ( true ) {
	}
	else if ( false )
	{
	}
	else
	{
	}

	while ( true )
	{
	}

	foreach ( \$one )
	{
	}
}
PHP;

	public function setUp() {
		\FileSystem::resetResults();
	}

	public function tearDown() {
		\FileSystem::resetResults();
	}

	/**
	 * @dataProvider methodBodyProvider
	 *
	 * @param array $lines
	 * @param integer $startLine
	 * @param integer $endLine
	 * @param integer $size
	 * @param integer $category
	 */
	public function testUnitSizeAnalysis( array $lines, $startLine, $endLine, $size, $category ) {
		$analyzer = $this->getMock(
			'\Mend\Metrics\UnitSize\UnitSizeAnalyzer',
			array( 'getSourceLines' )
		);
		$analyzer->expects( self::any() )->method( 'getSourceLines' )->will( self::returnValue( $lines ) );

		$node = $this->getMockBuilder( '\PHPParser_Node_Stmt_Function' )
			->setConstructorArgs( array( 'fooMethod' ) )
			->getMock();

		$url = new SourceUrl( Url::createFromString( "file:///tmp/foo.php#({$startLine},0),({$endLine},10)" ) );
		$method = new Method( new PHPNode( $node ), $url );

		$result = $analyzer->calculateMethodSize( $method );

		self::assertEquals( $size, $result->getUnitSize() );
		self::assertEquals( $category, $result->getCategory() );
	}

	public function methodBodyProvider() {
		return array(
			array( $this->toLines( self::$CODE_FRAGMENT_1 ), 10,  12,   3, UnitSizeCategory::SIZE_SMALL ),
			array( $this->toLines( self::$CODE_FRAGMENT_2 ),  2,  15,  14, UnitSizeCategory::SIZE_MEDIUM ),
			array( $this->toLines( self::$CODE_FRAGMENT_3 ),  2,  37,  35, UnitSizeCategory::SIZE_LARGE ),
			array( $this->toLines( self::$CODE_FRAGMENT_4 ),  2,  54,  53, UnitSizeCategory::SIZE_VERY_LARGE )
		);
	}

	private function toLines( $source ) {
		$lines = explode( "\n", $source );
		$numbers = range( 1, count( $lines ) );
		return array_combine( $numbers, $lines );
	}

	public function testGetSourceLines() {
		$name = \FileSystem::SCHEME . '://tmp/foo';

		$file = $this->getMockBuilder( '\Mend\IO\FileSystem\File' )
			->setMethods( array( 'getExtension', 'getName', 'canRead' ) )
			->setConstructorArgs( array( $name ) )
			->disableOriginalConstructor()
			->getMock();

		$file->expects( self::any() )
			->method( 'getExtension' )
			->will( self::returnValue( 'php' ) );

		$file->expects( self::any() )
			->method( 'getName' )
			->will( self::returnValue( $name ) );

		$file->expects( self::any() )
			->method( 'canRead' )
			->will( self::returnValue( true ) );

		\FileSystem::setFReadResult( self::$CODE_FRAGMENT_1 );

		$analyzer = new DummyUnitSizeAnalyzer();
		$actualLines = $analyzer->getSourceLines( $file );

		$extractor = new SourceFileExtractor( $file );
		$filter = $extractor->getSourceLineFilter();
		$expectedLines = array_filter(
			$this->toLines( self::$CODE_FRAGMENT_1 ),
			function ( $line ) use ( $filter ) {
				return $filter->isCode( $line );
			}
		);

		self::assertEquals( $expectedLines, $actualLines );
	}
}

class DummyUnitSizeAnalyzer extends UnitSizeAnalyzer {
	public function getSourceLines( File $file ) {
		return parent::getSourceLines( $file );
	}
}
