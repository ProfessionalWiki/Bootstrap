<?php
/**
 * File holding the SetupAfterCacheTest class
 *
 * @copyright (C) 2013-2018, Stephan Gambke
 * @license       http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 (or later)
 *
 * This file is part of the MediaWiki extension Bootstrap.
 * The Bootstrap extension is free software: you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * The Bootstrap extension is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @file
 * @ingroup Bootstrap
 */

namespace Bootstrap\Tests\Hooks;

use Bootstrap\Hooks\SetupAfterCache;

/**
 * @uses \Bootstrap\Hooks\SetupAfterCache
 *
 * @ingroup Test
 * @ingroup Bootstrap
 *
 * @group extension-bootstrap
 * @group mediawiki-databaseless
 *
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

		$configuration = [];

		$this->assertInstanceOf(
			'\Bootstrap\Hooks\SetupAfterCache',
			new SetupAfterCache( $configuration )
		);
	}

	public function testProcessWithAccessibilityOnBootstrapVendorPath() {

		$configuration = [
			'localBasePath'  => $this->localBootstrapVendorPath,
			'remoteBasePath' => '',
			'IP' => 'someIP',
		];

		$instance = new SetupAfterCache( $configuration );

		$this->assertTrue( $instance->process() );
	}

	public function testProcess_setsDefaultCacheTriggers() {

		$configuration = [
			'localBasePath'  => $this->localBootstrapVendorPath,
			'remoteBasePath' => '',
			'IP' => 'someIP',
		];

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

		$configuration = [
			'localBasePath'  => $this->localBootstrapVendorPath,
			'remoteBasePath' => '',
			'IP' => 'someIP',
		];

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

		$configuration = [
			'localBasePath'  => 'Foo',
			'remoteBasePath' => '',
			'IP' => 'someIP',
		];

		$instance = new SetupAfterCache( $configuration );

		$this->setExpectedException( 'RuntimeException' );
		$instance->process();
	}

	public function invalidConfigurationProvider() {

		$provider = [];
		$provider[] = [ [] ];
		$provider[] = [ [ 'localBasePath' => 'Foo' ] ];
		$provider[] = [ [ 'remoteBasePath' => 'Foo' ] ];

		return $provider;
	}

	protected function assertThatPathIsReadable( $path ) {
		$this->assertTrue( is_readable( $path ) );
	}

	private function resetGlobals() {
		$GLOBALS[ 'wgResourceModules' ][ 'ext.bootstrap.styles' ] = [
			'class'         => 'SCSS\ResourceLoaderSCSSModule',
			'styles'        => [],
			'variables'     => [],
			'dependencies'  => [],
			'cachetriggers' => [
				'LocalSettings.php' => null,
				'composer.lock'     => null,
			],
		];
	}

}
