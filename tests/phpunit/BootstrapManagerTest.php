<?php

namespace Bootstrap\Tests;

use Bootstrap\BootstrapManager;

/**
 * @uses \Bootstrap\BootstrapManager
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
			'scripts'         => array()
		);
	}

	protected function tearDown() {
		$GLOBALS['wgResourceModules'] = $this->wgResourceModules;
		BootstrapManager::clear();

		parent::tearDown();
	}

	public function testCanConstruct() {

		$moduleDefinition = $this->getMockBuilder( '\Bootstrap\Definition\ModuleDefinition' )
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

		BootstrapManager::clear();

		$this->assertInstanceOf(
			'\Bootstrap\BootstrapManager',
			BootstrapManager::getInstance()
		);
	}

	public function testLoadStylesFromModuleDefinition() {

		$this->assertEmpty( $this->getGlobalResourceModuleBootstrapStyles() );

		$moduleDefinition = $this->getMockBuilder( '\Bootstrap\Definition\ModuleDefinition' )
			->disableOriginalConstructor()
			->setMethods( array( 'get' ) )
			->getMock();

		$moduleDefinition->expects( $this->at( 0 ) )
			->method( 'get' )
			->with( $this->stringContains( 'descriptions' ) )
			->will( $this->returnValue( array( 'variables' => array( 'styles' => 'variables' ) ) ) );

		$moduleDefinition->expects( $this->at( 1 ) )
			->method( 'get' )
			->with( $this->stringContains( 'core' ) )
			->will( $this->returnValue( array( 'variables' ) ) );

		$moduleDefinition->expects( $this->at( 2 ) )
			->method( 'get' )
			->with( $this->stringContains( 'optional' ) )
			->will( $this->returnValue( array( 'foo' ) ) );

		$instance = new BootstrapManager( $moduleDefinition );
		$instance->addAllBootstrapModules();

		$this->assertNotEmpty( $this->getGlobalResourceModuleBootstrapStyles() );
	}

	public function testSetLessVariables() {

		$moduleDefinition = $this->getMockBuilder( '\Bootstrap\Definition\ModuleDefinition' )
			->disableOriginalConstructor()
			->setMethods( array( 'get' ) )
			->getMock();

		$moduleDefinition->expects( $this->atLeastOnce() )
			->method( 'get' )
			->will( $this->returnValue( array() ) );

		$instance = new BootstrapManager( $moduleDefinition );
		$instance->setLessVariables( array( 'foo' => 'bar') );
		$instance->setLessVariable( 'ichi', 'ni' );

		$this->assertArrayHasKey(
			'foo',
			$this->getGlobalResourceModuleBootstrapVariables()
		);

		$this->assertArrayHasKey(
			'ichi',
			$this->getGlobalResourceModuleBootstrapVariables()
		);
	}

	public function testAddCacheTriggerFiles() {

		$moduleDefinition = $this->getMockBuilder( '\Bootstrap\Definition\ModuleDefinition' )
			->disableOriginalConstructor()
			->setMethods( array( 'get' ) )
			->getMock();

		$moduleDefinition->expects( $this->atLeastOnce() )
			->method( 'get' )
			->will( $this->returnValue( array() ) );

		$instance = new BootstrapManager( $moduleDefinition );
		$instance-> addCacheTriggerFile( array( 'foo' => 'bar') );
		$instance-> addCacheTriggerFile( 'ichi' );

		$triggers = $this->getGlobalResourceModuleBootstrapCacheTriggers();

		$this->assertTrue(
			isset( $triggers[ 'foo' ] ) && $triggers[ 'foo' ] === 'bar'
		);

		$this->assertTrue(
			array_search( 'ichi', $triggers ) !== false
		);
	}

	public function testAddExternalModule() {

		$moduleDefinition = $this->getMockBuilder( '\Bootstrap\Definition\ModuleDefinition' )
			->disableOriginalConstructor()
			->setMethods( array( 'get' ) )
			->getMock();

		$moduleDefinition->expects( $this->atLeastOnce() )
			->method( 'get' )
			->will( $this->returnValue( array() ) );

		$instance = new BootstrapManager( $moduleDefinition );
		$instance->addExternalModule( 'ExternalFooModule', 'ExternalRemoteBarPath' );

		$this->assertArrayHasKey(
			'ExternalFooModule',
			$this->getGlobalResourceModuleBootstrapExternalStyles()
		);
	}

	private function getGlobalResourceModuleBootstrapStyles() {
		return $GLOBALS['wgResourceModules'][ 'ext.bootstrap.styles' ]['styles'];
	}

	private function getGlobalResourceModuleBootstrapVariables() {
		return $GLOBALS['wgResourceModules'][ 'ext.bootstrap.styles' ]['variables'];
	}

	private function getGlobalResourceModuleBootstrapExternalStyles() {
		return $GLOBALS['wgResourceModules'][ 'ext.bootstrap.styles' ]['external styles'];
	}

	private function getGlobalResourceModuleBootstrapCacheTriggers() {
		return $GLOBALS['wgResourceModules'][ 'ext.bootstrap.styles' ]['cachetriggers'];
	}

}
