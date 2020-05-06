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
 * @copyright 2013 - 2019, Stephan Gambke
 * @license   GPL-3.0-or-later
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

use Bootstrap\Hooks\SetupAfterCache;

/**
 * Class Bootstrap
 *
 * @since 1.0
 * @ingroup Bootstrap
 */
class Bootstrap {

	/**
	 * @throws \Exception
	 */
	public static function init() {
		$GLOBALS[ 'wgHooks' ][ 'SetupAfterCache' ][] = function () {
			$configuration = [];
			$configuration[ 'IP' ] = $GLOBALS[ 'IP' ];
			$configuration[ 'remoteBasePath' ] =
				$GLOBALS[ 'wgExtensionAssetsPath' ] . '/Bootstrap/resources/bootstrap';
			$configuration[ 'localBasePath' ] =
				$GLOBALS[ 'wgExtensionDirectory' ] . '/Bootstrap/resources/bootstrap';

			$setupAfterCache = new SetupAfterCache( $configuration );
			$setupAfterCache->process();
		};
	}
}
