<?php
/**
 * File holding the V4ModuleDefinitionTest class
 *
 * @copyright (C) 2013-2018, Stephan Gambke
 * @license       GPL-3.0-or-later
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

namespace Bootstrap\Tests\Definition;

use Bootstrap\BootstrapManager;
use Bootstrap\Definition\V5ModuleDefinition;
use PHPUnit\Framework\TestCase;

/**
 * @uses \Bootstrap\Definition\V5ModuleDefinition
 *
 * @ingroup Test
 * @ingroup Bootstrap
 *
 * @group extension-bootstrap
 * @group mediawiki-databaseless
 *
 * @since 5.0
 *
 * @author mwjames
 */
class V5ModuleDefinitionTest extends TestCase {

	/**
	 * @dataProvider keyProvider
	 * @covers \Bootstrap\Definition\V5ModuleDefinition
	 */
	public function testGet( $key ) {
		$instance = new V5ModuleDefinition();

		$this->assertIsArray(

			$instance->get( $key )
		);
	}

	/**
	 * @covers \Bootstrap\Definition\V5ModuleDefinition
	 */
	public function testBootstrapManagerIntegration() {
		$instance = new BootstrapManager( new V5ModuleDefinition() );
		$instance->addAllBootstrapModules();

		$this->assertTrue( true );
	}

	/**
	 * @covers \Bootstrap\Definition\V5ModuleDefinition
	 */
	public function testGetOnInvalidKeyThrowsException() {
		$instance = new V5ModuleDefinition();

		$this->expectException( \InvalidArgumentException::class );
		$instance->get( 'Foo' );
	}

	public function keyProvider() {
		$provider = [
			[ 'core' ],
			[ 'optional' ],
			[ 'descriptions' ]
		];

		return $provider;
	}

}
