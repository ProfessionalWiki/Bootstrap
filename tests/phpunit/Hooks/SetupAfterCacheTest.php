<?php

namespace Bootstrap\Tests\Hooks;

use Bootstrap\Hooks\SetupAfterCache;

/**
 * @uses \Bootstrap\Hooks\SetupAfterCache
 *
 * @ingroup Test
 *
 * @group extension-bootstrap
 * @group mediawiki-databaseless
 *
 * @license GNU GPL v3+
 * @since 1.0
 *
 * @author mwjames
 */
class SetupAfterCacheTest extends \PHPUnit_Framework_TestCase {

	protected $localBasePath = null;

	protected function setUp() {
		parent::setUp();
		$this->localBootstrapVendorPath = $GLOBALS[ 'IP' ] . '/vendor/twitter/bootstrap';
	}

	public function testCanConstruct() {

		$configuration = array();

		$this->assertInstanceOf(
			'\Bootstrap\Hooks\SetupAfterCache',
			new SetupAfterCache( $configuration )
		);
	}

	public function testProcessWithAccessibilityOnBootstrapVendorPath() {

		$configuration = array(
			'localBasePath'  => $this->localBootstrapVendorPath,
			'remoteBasePath' => ''
		);

		$instance = new SetupAfterCache( $configuration );

		$this->assertTrue( $instance->process() );
	}

	public function testProcessWithAccessibilityOnAddedLocalResourcePaths() {

		$configuration = array(
			'localBasePath'  => $this->localBootstrapVendorPath,
			'remoteBasePath' => ''
		);

		$instance = new SetupAfterCache( $configuration );
		$instance->process();

		$this->assertThatPathIsReadable(
			$GLOBALS[ 'wgResourceModules' ][ 'ext.bootstrap.styles' ]['localBasePath']
		);

		$this->assertThatPathIsReadable(
			$GLOBALS[ 'wgResourceModules' ][ 'ext.bootstrap.scripts' ]['localBasePath']
		);
	}

	/**
	 * @dataProvider invalidConfigurationProvider
	 */
	public function testProcessOnInvalidConfigurationThrowsException( $configuration ) {

		$instance = new SetupAfterCache( $configuration );

		$this->setExpectedException( 'InvalidArgumentException' );
		$instance->process();
	}

	public function testProcessOnInvalidLocalPathThrowsException() {

		$configuration = array(
			'localBasePath'  => 'Foo',
			'remoteBasePath' => ''
		);

		$instance = new SetupAfterCache( $configuration );

		$this->setExpectedException( 'RuntimeException' );
		$instance->process();
	}

	public function invalidConfigurationProvider() {

		$provider = array();

		$provider[] = array(
			array()
		);

		$provider[] = array(
			array(
				'localBasePath' => 'Foo'
			)
		);

		$provider[] = array(
			array(
				'remoteBasePath' => 'Foo'
			)
		);

		return $provider;
	}

	protected function assertThatPathIsReadable( $path ) {
		$this->assertTrue( is_readable( $path ) );
	}

}
