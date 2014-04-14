<?php

namespace Bootstrap\Hooks;

use RuntimeException;
use InvalidArgumentException;

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

/**
 * @see https://www.mediawiki.org/wiki/Manual:Hooks/SetupAfterCache
 *
 * @package bootstrap
 * @license GNU GPL v3+
 * @since 1.0
 *
 * @author mwjames
 * @author Stephan Gambke
 */
class SetupAfterCache {

	protected $configuration = array();

	/**
	 * @since  1.0
	 *
	 * @param array $configuration
	 */
	public function __construct( array $configuration ) {
		$this->configuration = $configuration;
	}

	/**
	 * @since 1.0
	 *
	 * @throws InvalidArgumentException
	 * @throws RuntimeException
	 */
	public function process() {

		if ( !$this->hasConfiguration( 'localBasePath' ) || !$this->hasConfiguration( 'localBasePath' ) ) {
			throw new InvalidArgumentException( 'Expected a valid configuration' );
		}

		$this->registerBootstrapResourcePaths(
			$this->isReadablePath( $this->configuration['localBasePath'] ),
			$this->configuration[ 'remoteBasePath' ]
		);

		return true;
	}

	/**
	 * Add paths to resource modules if they are not there yet (e.g. set in LocalSettings.php)
	 */
	protected function registerBootstrapResourcePaths( $localBasePath, $remoteBasePath ) {

		$GLOBALS[ 'wgResourceModules' ][ 'ext.bootstrap.styles' ] = array_replace_recursive( array(
				'localBasePath'  => $localBasePath . '/less',
				'remoteBasePath' => $remoteBasePath . '/less',
				'variables'      => array(
					'icon-font-path' => "\"$remoteBasePath/fonts/\"",
				),
			),
			$GLOBALS[ 'wgResourceModules' ][ 'ext.bootstrap.styles' ]
		);

		$GLOBALS[ 'wgResourceModules' ][ 'ext.bootstrap.scripts' ] = array_replace_recursive( array(
				'localBasePath'  => $localBasePath . '/js',
				'remoteBasePath' => $remoteBasePath . '/js',
			),
			$GLOBALS[ 'wgResourceModules' ][ 'ext.bootstrap.scripts' ]
		);
	}

	protected function hasConfiguration( $id ) {
		return isset( $this->configuration[ $id ] );
	}

	protected function isReadablePath( $localBasePath ) {

		$localBasePath = str_replace( array( '\\', '/' ), DIRECTORY_SEPARATOR, $localBasePath );

		if ( is_readable( $localBasePath ) ) {
			return $localBasePath;
		}

		throw new RuntimeException( "Expected an accessible {$localBasePath} path" );
	}

}
