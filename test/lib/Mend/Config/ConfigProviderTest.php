<?php
namespace Mend\Config;

class ConfigProviderTest extends \TestCase {
	private static $SETTINGS_1 = array(
		'development' => array(
			'project.key' => 'Project Key',
			'numfiles' => '12',
			'intval' => '-131',
			'boolvalt' => 'true',
			'boolvalf' => 'false',
			'floatval' => '4512.21',
			'arrayval' => 'abc,2,efg,hij'
		)
	);

	/**
	 * @dataProvider settingsProvider
	 *
	 * @param array $settings
	 * @param string searchKey
	 * @param mixed $expectedValue
	 * @param string $expectedType
	 */
	public function test( array $settings, $searchKey, $expectedValue, $expectedType ) {
		$reader = new ArrayConfigReader( $settings );
		$provider = new ConfigProvider( $reader );

		switch ( $expectedType ) {
			case 'string':
				$actual = $provider->getString( $searchKey );
				break;

			case 'int':
				$actual = $provider->getInteger( $searchKey );
				break;

			case 'boolean':
				$actual = $provider->getBoolean( $searchKey );
				break;

			case 'float':
				$actual = $provider->getFloat( $searchKey );
				break;

			case 'array':
				$actual = $provider->getArray( $searchKey );
				break;

			default:
				self::fail( "Unexpected type: '{$expectedType}'" );
				break;
		}

		self::assertEquals( $expectedValue, $actual );
		self::assertInternalType( $expectedType, $actual );
	}

	public function settingsProvider() {
		return array(
			array( self::$SETTINGS_1, 'development:project.key', 'Project Key', 'string' ),
			array( self::$SETTINGS_1, 'development:numfiles', 12, 'int' ),
			array( self::$SETTINGS_1, 'development:intval', -131, 'int' ),
			array( self::$SETTINGS_1, 'development:boolvalt', true, 'boolean' ),
			array( self::$SETTINGS_1, 'development:boolvalf', false, 'boolean' ),
			array( self::$SETTINGS_1, 'development:floatval', 4512.21, 'float' ),
			array( self::$SETTINGS_1, 'development:arrayval', array( 'abc', '2', 'efg', 'hij' ), 'array' )
		);
	}

	/**
	 * @dataProvider booleanSettingsProvider
	 *
	 * @param string $key
	 * @param mixed $value
	 * @param boolean $expected
	 */
	public function testGetBoolean( $key, $value, $expected ) {
		$reader = new ArrayConfigReader( array( $key => $value ) );
		$provider = new ConfigProvider( $reader );

		self::assertEquals( $expected, $provider->getBoolean( $key ) );
	}

	public function booleanSettingsProvider() {
		return array(
			array( 'string1', 'false', false ),
			array( 'string2', 'true', true ),
			array( 'string3', 'foo', false ),
			array( 'string4', '1', true ),
			array( 'string5', '0', false ),
			array( 'integer1', 1, true ),
			array( 'integer2', 0, false ),
			array( 'integer3', 312, true ),
			array( 'integer4', -12, true ),
			array( 'boolean1', true, true ),
			array( 'boolean2', false, false ),
			array( 'float1', 0.113, true ),
			array( 'float2', 0.0, false ),
			array( 'float3', -0.00001, true )
		);
	}

	public function testGetDefault() {
		$reader = $this->getMockBuilder( '\Mend\Config\ArrayConfigReader' )
			->setConstructorArgs( array( array() ) )
			->getMock();

		$provider = new ConfigProvider( $reader );
		$default = 'defaultString';

		self::assertEquals( $default, $provider->getValue( 'foo', $default ) );
	}

	public function testGetDefaultNull() {
		$reader = $this->getMockBuilder( '\Mend\Config\ArrayConfigReader' )
			->setConstructorArgs( array( array() ) )
			->getMock();

		$provider = new ConfigProvider( $reader );
		$default = null;

		self::assertEquals( $default, $provider->getValue( 'foo', $default ) );
		self::assertEquals( $default, $provider->getString( 'foo', $default ) );
		self::assertEquals( (array) $default, $provider->getArray( 'foo', $default ) );
		self::assertEquals( $default, $provider->getInteger( 'foo', $default ) );
		self::assertEquals( $default, $provider->getBoolean( 'foo', $default ) );
		self::assertEquals( $default, $provider->getFloat( 'foo', $default ) );
	}
}
