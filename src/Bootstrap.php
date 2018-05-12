<?php
/**
 * An extension providing the Bootstrap library to other extensions
 *
 * @see      https://www.mediawiki.org/wiki/Extension:Bootstrap
 * @see      https://getbootstrap.com/
 *
 * @author   Stephan Gambke
 *
 * @defgroup Bootstrap Bootstrap
 */

/**
 * The main file of the Bootstrap extension
 *
 * @copyright (C) 2013 - 2018, Stephan Gambke
 * @license       https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 (or later)
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
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 * @file
 * @ingroup       Bootstrap
 */

namespace Bootstrap;

/**
 * Class Bootstrap
 *
 * @since 1.0
 * @ingroup Bootstrap
 */
class Bootstrap {

	public static function init() {

		$GLOBALS[ 'wgHooks' ][ 'SetupAfterCache' ][] = function () {

			$configuration = [];
			$configuration[ 'IP' ] = $GLOBALS[ 'IP' ];
			$configuration[ 'remoteBasePath' ] = $GLOBALS[ 'wgExtensionAssetsPath' ] . '/Bootstrap/resources/bootstrap';

			if ( isset( $GLOBALS[ 'wgExtensionDirectory' ] ) ) { // MW >= 1.25
				$configuration[ 'localBasePath' ] = $GLOBALS[ 'wgExtensionDirectory' ] . '/Bootstrap/resources/bootstrap';
			} else {
				$configuration[ 'localBasePath' ] = __DIR__ . '/resources/bootstrap';
			}

			$setupAfterCache = new \Bootstrap\Hooks\SetupAfterCache( $configuration );
			$setupAfterCache->process();
		};

		// register skeleton resource module with the Resource Loader
		// do not add paths, globals are not set yet
		$GLOBALS[ 'wgResourceModules' ][ 'ext.bootstrap.styles' ] = [
			'class' => 'SCSS\\ResourceLoaderSCSSModule',
			'position' => 'top',
			'styles' => [],
			'variables' => [],
			'dependencies' => [],
			'cachetriggers' => [
				'LocalSettings.php' => null,
				'composer.lock' => null,
			],
		];

		$GLOBALS[ 'wgResourceModules' ][ 'ext.bootstrap.scripts' ] = [
			'scripts' => [],
		];

		$GLOBALS[ 'wgResourceModules' ][ 'ext.bootstrap' ] = [
			'dependencies' => [ 'ext.bootstrap.styles', 'ext.bootstrap.scripts' ],
		];

	}
}