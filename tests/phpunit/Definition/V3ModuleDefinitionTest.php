<?php

namespace Bootstrap\Tests\Definition;

use Bootstrap\Definition\V3ModuleDefinition;
use Bootstrap\BootstrapManager;

/**
 * @uses \Bootstrap\Definition\V3ModuleDefinition
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
class V3ModuleDefinitionTest extends \PHPUnit_Framework_TestCase {

	public function testCanConstruct() {

		$this->assertInstanceOf(
			'\Bootstrap\Definition\ModuleDefinition',
			new V3ModuleDefinition()
		);
	}

	/**
	 * @dataProvider keyProvider
	 */
	public function testGet( $key ) {

		$instance = new V3ModuleDefinition();

		$this->assertInternalType(
			'array',
			$instance->get( $key )
		);
	}

	public function testBootstrapManagerIntegration() {

		$instance = new BootstrapManager( new V3ModuleDefinition() );
		$instance->addAllBootstrapModules();

		$this->assertTrue( true );
	}

	public function testGetOnInvalidKeyThrowsException() {

		$instance = new V3ModuleDefinition();

		$this->setExpectedException( 'InvalidArgumentException' );
		$instance->get( 'Foo' );
	}

	public function keyProvider() {

		$provider = array(
			array( 'core' ),
			array( 'optional' ),
			array( 'descriptions' )
		);

		return $provider;
	}

}
