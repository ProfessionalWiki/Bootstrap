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

		$this->assertAcceptableConfiguration();

		$this->removeLegacyLessCompilerFromComposerAutoloader();

		$this->registerBootstrapResourcePaths(
			$this->isReadablePath( $this->configuration['localBasePath'] ),
			$this->configuration[ 'remoteBasePath' ]
		);

		$this->registerCacheTriggers();

		return true;
	}

	/**
	 * Add paths to resource modules if they are not there yet (e.g. set in LocalSettings.php)
	 * @param string $localBasePath
	 * @param string $remoteBasePath
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

	/**
	 * Remove lessc adapter of the less.php compiler from Composer autoloader
	 *
	 * MediaWiki core uses the lessc compiler from http://leafo.net/lessphp .
	 * This compiler requires non-standard Less files which are incompatible
	 * with the compiler used by the Bootstrap extension. It is therefore
	 * necessary to ensure that MW will load its own lessc compiler class
	 * and not the adapter class provided by the Less compiler used by the
	 * Bootstrap extension or else it will not be able to compile its broken
	 * Less files.
	 */
	protected function removeLegacyLessCompilerFromComposerAutoloader() {

		$autoloadFunctions = spl_autoload_functions();

		foreach ( $autoloadFunctions as $autoloadFunction ) {

			if ( is_object( $autoloadFunction ) && ( $autoloadFunction instanceof Closure ) ) {
				continue;
			}

			$classLoader = $autoloadFunction[ 0 ];

			if ( is_a( $classLoader, '\Composer\Autoload\ClassLoader' ) ) {

				$classMap = $classLoader->getClassMap();

				if ( !is_array( $classMap ) ||
					!array_key_exists( 'lessc', $classMap ) ||
					strpos( $classMap[ 'lessc' ], '/less.php/less.php/lessc.inc.php') !== false ) {

					$classLoader->addClassMap( array( 'lessc' => null ) );
				}
				break;
			}
		}

	}

	/**
	 * @param string $id
	 * @return bool
	 */
	protected function hasConfiguration( $id ) {
		return isset( $this->configuration[ $id ] );
	}

	/**
	 * @param string $localBasePath
	 * @return string
	 * @throws RuntimeException
	 */
	protected function isReadablePath( $localBasePath ) {

		$localBasePath = str_replace( array( '\\', '/' ), DIRECTORY_SEPARATOR, $localBasePath );

		if ( is_readable( $localBasePath ) ) {
			return $localBasePath;
		}

		throw new RuntimeException( "Expected an accessible {$localBasePath} path" );
	}

	protected function registerCacheTriggers() {

		$defaultRecacheTriggers = array(
			'LocalSettings.php' => $this->configuration[ 'IP' ] . '/LocalSettings.php',
			'composer.lock' => $this->configuration[ 'IP' ] . '/composer.lock',
		);

		foreach ( $defaultRecacheTriggers as $key => $filename ) {
			if ( array_key_exists( $key, $GLOBALS[ 'wgResourceModules' ][ 'ext.bootstrap.styles' ][ 'cachetriggers' ] ) &&
				$GLOBALS[ 'wgResourceModules' ][ 'ext.bootstrap.styles' ][ 'cachetriggers' ][ $key ] === null ) {
				$GLOBALS[ 'wgResourceModules' ][ 'ext.bootstrap.styles' ][ 'cachetriggers' ][ $key ] = $filename;
			}
		}
	}

	public function assertAcceptableConfiguration() {

		$configElements = array(
			'localBasePath' => 'Local base path to Bootstrap modules not found.',
			'remoteBasePath' => 'Remote base path to Bootstrap modules not found.',
			'IP' => 'Full path to working directory ($IP) not found.',
		);

		foreach ( $configElements as $key => $errorMessage ) {
			if ( !$this->hasConfiguration( $key ) ) {
				throw new InvalidArgumentException( $errorMessage );
			}
		}
	}

}
