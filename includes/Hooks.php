<?php
/**
 * File holding the Hooks class
 *
 * @copyright (C) 2013, Stephan Gambke
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
 * @ingroup       Bootstrap
 */

namespace bootstrap;

/**
 * Class Hooks holds the Bootstrap extension hook handlers
 *
 * @package bootstrap
 */
class Hooks {


	public static function onSetupAfterCache() {

		$localBasePath = $GLOBALS[ 'IP' ] . '/vendor/twitter/bootstrap';
		$remoteBasePath = $GLOBALS[ 'wgScriptPath' ] . '/vendor/twitter/bootstrap';

		// register skeleton resource module with the Resource Loader
		$GLOBALS[ 'wgResourceModules' ][ 'ext.bootstrap.styles' ] = array(
			'localBasePath'  => $localBasePath . '/less',
			'remoteBasePath' => $remoteBasePath. '/less',
			'class'          => 'bootstrap\ResourceLoaderBootstrapModule',
			'styles'         => array(),
			'variables'      => array(
				'icon-font-path' => "\"$remoteBasePath/fonts/\"",
			),
			'dependencies'   => array(),
		);

		$GLOBALS[ 'wgResourceModules' ][ 'ext.bootstrap.scripts' ] = array(
			'localBasePath'  => $localBasePath . '/js',
			'remoteBasePath' => $remoteBasePath. '/js',
			'scripts'        => array(),
		);

		$GLOBALS[ 'wgResourceModules' ][ 'ext.bootstrap' ] = array(
			'dependencies' => array( 'ext.bootstrap.styles', 'ext.bootstrap.scripts' ),
		);

		return true;

	}

}
