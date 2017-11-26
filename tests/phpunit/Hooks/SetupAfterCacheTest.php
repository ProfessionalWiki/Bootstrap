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
	protected $localBootstrapVendorPath = null;

	protected function setUp() {
		parent::setUp();
		$this->localBootstrapVendorPath = __DIR__ . '/../../../resources/bootstrap';
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
			'remoteBasePath' => '',
			'IP' => 'someIP',
		);

		$instance = new SetupAfterCache( $configuration );

		$this->assertTrue( $instance->process() );
	}

	public function testProcess_setsDefaultCacheTriggers() {

		$configuration = array(
			'localBasePath'  => $this->localBootstrapVendorPath,
			'remoteBasePath' => '',
			'IP' => 'someIP',
		);

		$this->resetGlobals();

		$instance = new SetupAfterCache( $configuration );

		$this->assertTrue( $instance->process() );

		$this->assertEquals(
			'someIP/LocalSettings.php',
			$GLOBALS[ 'wgResourceModules' ][ 'ext.bootstrap.styles' ][ 'cachetriggers' ][ 'LocalSettings.php' ]
		);

		$this->assertEquals(
			'someIP/composer.lock',
			$GLOBALS[ 'wgResourceModules' ][ 'ext.bootstrap.styles' ][ 'cachetriggers' ][ 'composer.lock' ]
		);
	}

	public function testProcessWithAccessibilityOnAddedLocalResourcePaths() {

		$configuration = array(
			'localBasePath'  => $this->localBootstrapVendorPath,
			'remoteBasePath' => '',
			'IP' => 'someIP',
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
			'remoteBasePath' => '',
			'IP' => 'someIP',
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

	private function resetGlobals() {
		$GLOBALS[ 'wgResourceModules' ][ 'ext.bootstrap.styles' ] = array (
			'class'         => 'Bootstrap\ResourceLoaderBootstrapModule',
			'styles'        => array (),
			'variables'     => array (),
			'dependencies'  => array (),
			'cachetriggers' => array (
				'LocalSettings.php' => null,
				'composer.lock'     => null,
			),
		);
	}

}
