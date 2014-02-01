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
}
