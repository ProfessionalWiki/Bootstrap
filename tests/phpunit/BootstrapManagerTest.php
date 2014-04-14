<?php

namespace Bootstrap\Tests;

use Bootstrap\BootstrapManager;
use Bootstrap\V3ModuleDefinition;

/**
 * @covers \Bootstrap\BootstrapManager
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
class BootstrapManagerTest extends \PHPUnit_Framework_TestCase {

	protected $wgResourceModules = null;

	protected function setUp() {
		parent::setUp();
		$this->wgResourceModules = $GLOBALS['wgResourceModules'];

		// Preset with empty default values to verify the initialization status
		// during invocation
		$GLOBALS['wgResourceModules'][ 'ext.bootstrap.styles' ] = array(
			'localBasePath'   => '',
			'remoteBasePath'  => '',
			'class'           => '',
			'dependencies'    => array(),
			'styles'          => array(),
			'variables'       => array(),
			'external styles' => array()
		);

		$GLOBALS['wgResourceModules'][ 'ext.bootstrap.scripts' ] = array(
			'dependencies'    => array(),
			'scripts'          => array()
		);
	}

	protected function tearDown() {
		parent::tearDown();
		$GLOBALS['wgResourceModules'] = $this->wgResourceModules;
		BootstrapManager::clear();
	}

	public function testCanConstruct() {

		$moduleDefinition = $this->getMockBuilder( '\Bootstrap\ModuleDefinition' )
			->disableOriginalConstructor()
			->setMethods( array( 'get' ) )
			->getMock();

		$moduleDefinition->expects( $this->atLeastOnce() )
			->method( 'get' )
			->will( $this->returnValue( array() ) );

		$this->assertInstanceOf(
			'\Bootstrap\BootstrapManager',
			new BootstrapManager( $moduleDefinition )
		);

		$this->assertInstanceOf(
			'\Bootstrap\BootstrapManager',
			BootstrapManager::getInstance()
		);
	}

	public function testLoadStylesFromModuleDefinition() {

		$this->assertEmpty( $this->getGlobalResourceModuleBootstrapStyles() );

		$instance = new BootstrapManager( new V3ModuleDefinition );
		$this->assertNotEmpty( $this->getGlobalResourceModuleBootstrapStyles() );
	}

	public function testSetLessVariables() {

		$moduleDefinition = $this->getMockBuilder( '\Bootstrap\ModuleDefinition' )
			->disableOriginalConstructor()
			->setMethods( array( 'get' ) )
			->getMock();

		$moduleDefinition->expects( $this->atLeastOnce() )
			->method( 'get' )
			->will( $this->returnValue( array() ) );

		$instance = new BootstrapManager( $moduleDefinition );
		$instance->setLessVariables( array( 'foo' => 'bar') );
		$instance->setLessVariable( 'ichi', 'ni' );

		$this->assertArrayHasKey( 'foo', $this->getGlobalResourceModuleBootstrapVariables() );
		$this->assertArrayHasKey( 'ichi', $this->getGlobalResourceModuleBootstrapVariables() );
	}

	private function getGlobalResourceModuleBootstrapStyles() {
		return $GLOBALS['wgResourceModules'][ 'ext.bootstrap.styles' ]['styles'];
	}

	private function getGlobalResourceModuleBootstrapVariables() {
		return $GLOBALS['wgResourceModules'][ 'ext.bootstrap.styles' ]['variables'];
	}

}
