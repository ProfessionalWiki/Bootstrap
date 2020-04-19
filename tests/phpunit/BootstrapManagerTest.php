<?php
/**
 * File holding the BootstrapManagerTest class
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

namespace Bootstrap\Tests;

use Bootstrap\BootstrapManager;

/**
 * @uses \Bootstrap\BootstrapManager
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
class BootstrapManagerTest extends \PHPUnit_Framework_TestCase {

	protected $wgResourceModules = null;

	protected function setUp() {
		parent::setUp();
		$this->wgResourceModules = $GLOBALS['wgResourceModules'];

		// Preset with empty default values to verify the initialization status
		// during invocation
		$GLOBALS['wgResourceModules'][ 'ext.bootstrap.styles' ] = [
			'localBasePath'   => '',
			'remoteBasePath'  => '',
			'class'           => '',
			'dependencies'    => [],
			'styles'          => [],
			'variables'       => [],
			'external styles' => []
		];

		$GLOBALS['wgResourceModules'][ 'ext.bootstrap.scripts' ] = [
			'dependencies'    => [],
			'scripts'         => []
		];
	}

	protected function tearDown() {
		$GLOBALS['wgResourceModules'] = $this->wgResourceModules;
		BootstrapManager::clear();

		parent::tearDown();
	}

	public function testCanConstruct() {

		$moduleDefinition = $this->getMockBuilder( '\Bootstrap\Definition\ModuleDefinition' )
			->disableOriginalConstructor()
			->setMethods( [ 'get' ] )
			->getMock();

		$moduleDefinition->expects( $this->atLeastOnce() )
			->method( 'get' )
			->will( $this->returnValue( [] ) );

		$this->assertInstanceOf(
			'\Bootstrap\BootstrapManager',
			new BootstrapManager( $moduleDefinition )
		);

		BootstrapManager::clear();

		$this->assertInstanceOf(
			'\Bootstrap\BootstrapManager',
			BootstrapManager::getInstance()
		);
	}

	public function testSetLessVariables() {

		$moduleDefinition = $this->getMockBuilder( '\Bootstrap\Definition\ModuleDefinition' )
			->disableOriginalConstructor()
			->setMethods( [ 'get' ] )
			->getMock();

		$moduleDefinition->expects( $this->atLeastOnce() )
			->method( 'get' )
			->will( $this->returnValue( [] ) );

		$instance = new BootstrapManager( $moduleDefinition );
		$instance->setScssVariables( [ 'foo' => 'bar' ] );
		$instance->setScssVariable( 'ichi', 'ni' );

		$this->assertArrayHasKey(
			'foo',
			$this->getGlobalResourceModuleBootstrapVariables()
		);

		$this->assertArrayHasKey(
			'ichi',
			$this->getGlobalResourceModuleBootstrapVariables()
		);
	}

	private function getGlobalResourceModuleBootstrapVariables() {
		return $GLOBALS['wgResourceModules'][ 'ext.bootstrap.styles' ]['variables'];
	}

}
