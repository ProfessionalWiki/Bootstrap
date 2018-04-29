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
 *
 * @codeCoverageIgnore
 */
call_user_func( function () {

	if ( !defined( 'MEDIAWIKI' ) ) {
		die( 'This file is part of the MediaWiki extension Bootstrap, it is not a valid entry point.' );
	}

	if ( version_compare( $GLOBALS[ 'wgVersion' ], '1.22', 'lt' ) ) {
		die( '<b>Error:</b> This version of <a href="https://www.mediawiki.org/wiki/Extension:Bootstrap">Bootstrap</a> is only compatible with MediaWiki 1.22 or above. You need to upgrade MediaWiki first.' );
	}

	/**
	 * The extension version
	 */
	define( 'BS_VERSION', '1.2.3' );

	// register the extension
	$GLOBALS[ 'wgExtensionCredits' ][ 'other' ][ ] = array(
		'path'           => __FILE__,
		'name'           => 'Bootstrap',
		'author' => array( '[https://www.mediawiki.org/wiki/User:F.trott Stephan Gambke]', 'James Hong Kong' ),
		'url'            => 'https://www.mediawiki.org/wiki/Extension:Bootstrap',
		'descriptionmsg' => 'bootstrap-desc',
		'version'        => BS_VERSION,
		'license-name'   => 'GPL-3.0+',
	);

	// register message files
	$GLOBALS[ 'wgMessagesDirs' ][ 'Bootstrap' ] = __DIR__ . '/i18n';
	$GLOBALS[ 'wgExtensionMessagesFiles' ][ 'Bootstrap' ] = __DIR__ . '/Bootstrap.i18n.php';

	// register classes
	$GLOBALS[ 'wgAutoloadClasses' ][ 'Bootstrap\ResourceLoaderBootstrapModule' ] = __DIR__ . '/src/ResourceLoaderBootstrapModule.php';
	$GLOBALS[ 'wgAutoloadClasses' ][ 'Bootstrap\BootstrapManager' ]      = __DIR__ . '/src/BootstrapManager.php';
	$GLOBALS[ 'wgAutoloadClasses' ][ 'Bootstrap\Hooks\SetupAfterCache' ] = __DIR__ . '/src/Hooks/SetupAfterCache.php';
	$GLOBALS[ 'wgAutoloadClasses' ][ 'Bootstrap\Definition\ModuleDefinition' ]   = __DIR__ . '/src/Definition/ModuleDefinition.php';
	$GLOBALS[ 'wgAutoloadClasses' ][ 'Bootstrap\Definition\V3ModuleDefinition' ] = __DIR__ . '/src/Definition/V3ModuleDefinition.php';

	$GLOBALS[ 'wgHooks' ][ 'SetupAfterCache' ][ ] = function() {

		$configuration = array();
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
	$GLOBALS[ 'wgResourceModules' ][ 'ext.bootstrap.styles' ] = array(
		'class'          => 'Bootstrap\ResourceLoaderBootstrapModule',
		'position'       => 'top',
		'styles'         => array(),
		'variables'      => array(),
		'dependencies'   => array(),
		'cachetriggers'   => array(
			'LocalSettings.php' => null,
			'composer.lock'     => null,
		),
	);

	$GLOBALS[ 'wgResourceModules' ][ 'ext.bootstrap.scripts' ] = array(
		'scripts'        => array(),
	);

	$GLOBALS[ 'wgResourceModules' ][ 'ext.bootstrap' ] = array(
		'dependencies' => array( 'ext.bootstrap.styles', 'ext.bootstrap.scripts' ),
	);

} );
