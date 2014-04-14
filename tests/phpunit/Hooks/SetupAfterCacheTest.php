<?php

namespace Bootstrap\Tests\Hooks;

use Bootstrap\Hooks\SetupAfterCache;

/**
 * @covers \Bootstrap\Hooks\SetupAfterCache
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
		$this->localBasePath = $GLOBALS[ 'IP' ] . '/vendor/twitter/bootstrap';
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
			'localBasePath'  => $this->localBasePath,
			'remoteBasePath' => ''
		);

		$instance = new SetupAfterCache( $configuration );

		$this->assertTrue( $instance->process() );
	}

	public function testProcessWithAccessibilityOnAddedLocalResourcePaths() {

		$configuration = array(
			'localBasePath'  => $this->localBasePath,
			'remoteBasePath' => ''
		);

		$instance = new SetupAfterCache( $configuration );
		$instance->process();

		$this->assertTrue( is_readable( $GLOBALS[ 'wgResourceModules' ][ 'ext.bootstrap.styles' ]['localBasePath'] ) );
		$this->assertTrue( is_readable( $GLOBALS[ 'wgResourceModules' ][ 'ext.bootstrap.scripts' ]['localBasePath'] ) );
	}

	public function testProcessOnInvalidConfigurationThrowsException() {

		$configuration = array();

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

}
