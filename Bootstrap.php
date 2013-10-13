<?php
/**
 * An extension providing the Bootstrap library to other extensions
 *
 * @see https://www.mediawiki.org/wiki/Extension:Bootstrap
 * @see http://twitter.github.io/bootstrap
 *
 * @author Stephan Gambke
 * @version 0.1
 *
 * @defgroup Bootstrap Bootstrap
 */

/**
 * The main file of the Bootstrap extension
 *
 * @copyright (C) 2013, Stephan Gambke
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 (or later)
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
if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'This file is part of the MediaWiki extension Bootstrap, it is not a valid entry point.' );
}

if ( version_compare( $wgVersion, '1.22alpha', 'lt' ) ) {
	die( '<b>Error:</b> This version of <a href="https://www.mediawiki.org/wiki/Extension:Bootstrap">Bootstrap</a> is only compatible with MediaWiki 1.22 or above. You need to upgrade MediaWiki first.' );
}

/**
 * The extension version
 */
define( 'BS_VERSION', '0.1' );

// register the extension
$wgExtensionCredits['other'][] = array(
	'path' => __FILE__,
	'name' => 'Bootstrap',
	'author' => '[http://www.mediawiki.org/wiki/User:F.trott Stephan Gambke]',
	'url' => 'https://www.mediawiki.org/wiki/Extension:Bootstrap',
	'descriptionmsg' => 'bootstrap-desc',
	'version' => BS_VERSION,
);

// server-local path to this file
$dir = dirname( __FILE__ );

// register message files
$wgExtensionMessagesFiles['Bootstrap'] = $dir . '/Bootstrap.i18n.php';

$wgAutoloadClasses['bootstrap\ResourceLoaderBootstrapModule'] = $dir . '/ResourceLoaderBootstrapModule.php';
$wgAutoloadClasses['Bootstrap'] = $dir . '/Bootstrap.class.php';

// register skeleton resource module with the Resource Loader
$wgResourceModules['ext.bootstrap'] = array(
	'localBasePath' => $dir,
	'remoteExtPath' => 'Bootstrap',
	'class' => 'bootstrap\ResourceLoaderBootstrapModule',
	'styles' => array(),
	'variables' => array(),
	'paths' => array( $dir . '/bootstrap/less' ),
	'dependencies' => array(),
);

unset( $dir );
