<?php
/**
 * File holding the SetupAfterCache class
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
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @file
 * @ingroup Bootstrap
 */

namespace Bootstrap\Hooks;

use InvalidArgumentException;
use RuntimeException;
use SCSS\ResourceLoaderSCSSModule;

/**
 * Handler for the SetupAfterCache hook.
 *
 * @see https://www.mediawiki.org/wiki/Manual:Hooks/SetupAfterCache
 *
 * @since 1.0
 *
 * @author mwjames
 * @author Stephan Gambke
 * @ingroup Bootstrap
 */
class SetupAfterCache {

	protected $configuration = [];

	/**
	 * @since  1.0
	 *
	 * @param mixed[] $configuration
	 */
	public function __construct( array $configuration ) {
		$this->configuration = $configuration;
	}

	/**
	 * Process the hook
	 *
	 * @codingStandardsIgnoreStart
	 * @callgraph
	 * @codingStandardsIgnoreEnd
	 *
	 * @since 1.0
	 *
	 * @throws InvalidArgumentException
	 * @throws RuntimeException
	 * @return bool
	 */
	public function process() {
		$this->assertAcceptableConfiguration();

		$this->registerResourceLoaderModules(
			$this->isReadablePath( $this->configuration['localBasePath'] ),
			$this->configuration[ 'remoteBasePath' ]
		);

		$this->registerCacheTriggers();

		return true;
	}

	/**
	 * Add paths to resource modules if they are not there yet (e.g. set in LocalSettings.php)
	 *
	 * @param string $localBasePath
	 * @param string $remoteBasePath
	 */
	protected function registerResourceLoaderModules( $localBasePath, $remoteBasePath ) {
		$GLOBALS[ 'wgResourceModules' ][ 'ext.bootstrap.styles' ] = array_replace_recursive(
			[
				'localBasePath' => $localBasePath . '/scss',
				'remoteBasePath' => $remoteBasePath . '/scss',
				'class' => ResourceLoaderSCSSModule::class,
				'position' => 'top',
				'styles' => [],
				'variables' => [],
				'dependencies' => [],
				'cacheTriggers' => [
					'LocalSettings.php' => null,
					'composer.lock' => null,
				],
			],
			array_key_exists( 'ext.bootstrap.styles', $GLOBALS[ 'wgResourceModules' ] ) ?
				$GLOBALS[ 'wgResourceModules' ][ 'ext.bootstrap.styles' ] : []
		);

		$GLOBALS[ 'wgResourceModules' ][ 'ext.bootstrap.scripts' ] = array_replace_recursive(
			[
				'localBasePath'  => $localBasePath . '/js',
				'remoteBasePath' => $remoteBasePath . '/js',
				'scripts' => [],
			],
			array_key_exists( 'ext.bootstrap.scripts', $GLOBALS[ 'wgResourceModules' ] ) ?
				$GLOBALS[ 'wgResourceModules' ][ 'ext.bootstrap.scripts' ] : []
		);

		$GLOBALS[ 'wgResourceModules' ][ 'ext.bootstrap' ] = [
			'dependencies' => [ 'ext.bootstrap.styles', 'ext.bootstrap.scripts' ],
		];
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
		$localBasePath = str_replace( [ '\\', '/' ], DIRECTORY_SEPARATOR, $localBasePath );

		if ( is_readable( $localBasePath ) ) {
			return $localBasePath;
		}

		throw new RuntimeException( "Expected an accessible {$localBasePath} path" );
	}

	protected function registerCacheTriggers() {
		$defaultRecacheTriggers = [
			'LocalSettings.php' => $this->configuration[ 'IP' ] . '/LocalSettings.php',
			'composer.lock' => $this->configuration[ 'IP' ] . '/composer.lock',
		];

		foreach ( $defaultRecacheTriggers as $key => $filename ) {
			if ( array_key_exists( $key,
				$GLOBALS[ 'wgResourceModules' ][ 'ext.bootstrap.styles' ][ 'cacheTriggers' ] ) &&
				$GLOBALS[ 'wgResourceModules' ][ 'ext.bootstrap.styles' ][ 'cacheTriggers' ][ $key ]
					=== null ) {
				$GLOBALS[ 'wgResourceModules' ][ 'ext.bootstrap.styles' ][ 'cacheTriggers' ][ $key ] =
					$filename;
			}
		}
	}

	protected function assertAcceptableConfiguration() {
		$configElements = [
			'localBasePath' => 'Local base path to Bootstrap modules not found.',
			'remoteBasePath' => 'Remote base path to Bootstrap modules not found.',
			'IP' => 'Full path to working directory ($IP) not found.',
		];

		foreach ( $configElements as $key => $errorMessage ) {
			if ( !$this->hasConfiguration( $key ) ) {
				throw new InvalidArgumentException( $errorMessage );
			}
		}
	}

}
