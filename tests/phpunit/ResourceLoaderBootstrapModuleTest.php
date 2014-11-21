<?php

namespace Bootstrap\Tests;

use Bootstrap\ResourceLoaderBootstrapModule;

use HashBagOStuff;

/**
 * @uses \Bootstrap\ResourceLoaderBootstrapModule
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
class ResourceLoaderBootstrapModuleTest extends \PHPUnit_Framework_TestCase {

	public function testCanConstruct() {

		$this->assertInstanceOf(
			'\Bootstrap\ResourceLoaderBootstrapModule',
			new ResourceLoaderBootstrapModule()
		);
	}

	public function testGetStyles() {

		$resourceLoaderContext = $this->getMockBuilder( '\ResourceLoaderContext' )
			->disableOriginalConstructor()
			->getMock();

		$instance = new ResourceLoaderBootstrapModule;
		$instance->setCache( new HashBagOStuff );

		$this->assertArrayHasKey( 'all', $instance->getStyles( $resourceLoaderContext ) );
	}

	public function testGetStylesFromPresetCache() {

		$resourceLoaderContext = $this->getMockBuilder( '\ResourceLoaderContext' )
			->disableOriginalConstructor()
			->getMock();

		$cache = new HashBagOStuff;

		$cache->set(
			wfMemcKey( 'ext', 'bootstrap', $resourceLoaderContext->getHash() ),
			array(
				'storetime' => time(),
				'styles'    => 'foo'
			)
		);

		$instance = new ResourceLoaderBootstrapModule;
		$instance->setCache( $cache );

		$styles = $instance->getStyles( $resourceLoaderContext );

		$this->assertArrayHasKey( 'all', $styles );
		$this->assertEquals( 'foo', $styles['all'] );
	}

	public function testGetStylesTryCatchExceptionIsThrownByLessParser() {

		$resourceLoaderContext = $this->getMockBuilder( '\ResourceLoaderContext' )
			->disableOriginalConstructor()
			->getMock();

		$options = array(
			'external styles' => array( 'Foo' => 'bar' )
		);

		$instance = new ResourceLoaderBootstrapModule( $options );
		$instance->setCache( new HashBagOStuff );

		$result = $instance->getStyles( $resourceLoaderContext );

		$this->assertContains( 'LESS compile error', $result['all'] );
	}

	public function testSupportsURLLoading() {
		$instance = new ResourceLoaderBootstrapModule();
		$this->assertFalse( $instance->supportsURLLoading() );
	}
}
