<?php
/**
 * File holding the ResourceLoaderBootstrapModule class
 *
 * @copyright (C) 2013, Stephan Gambke
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 (or later)
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
 * @ingroup   Bootstrap
 */

namespace Bootstrap;

use ResourceLoader;
use ResourceLoaderContext;
use ResourceLoaderFileModule;

/**
 * ResourceLoader module based on local JavaScript/LESS files.
 *
 * Different to the behaviour of ResourceLoaderFileModule this module compiles all LESS files in one compile context.
 *
 * It recognizes the following additional fields in $wgResourceModules:
 * * styles: array of LESS file names (with or without extension .less)
 * * variables: array of key value pairs representing LESS variables, that will be added to the LESS script after all
 *              files imports, i.e. that may override any variable set in style files
 * * paths: array of paths to search for style files; all these paths together represent one virtual file base and will
 * 	        be searched for a style file; this means it is not possible to include two LESS files with the same name
 *          even if in different paths
 *
 * @package Bootstrap
 */
class ResourceLoaderBootstrapModule extends ResourceLoaderFileModule {

	protected $variables = array();
	protected $paths = array();

	public function __construct( $options = array(), $localBasePath = null, $remoteBasePath = null
	) {

		parent::__construct( $options, $localBasePath, $remoteBasePath );

		if ( isset( $options[ 'variables' ] ) ) {
			$this->variables = $options[ 'variables' ];
		}

		if ( isset( $options[ 'paths' ] ) ) {
			$this->paths = $options[ 'paths' ];
		}

	}

	/**
	 * Get the compiled Bootstrap styles
	 *
	 * @param ResourceLoaderContext $context
	 *
	 * @return array
	 */
	public function getStyles( ResourceLoaderContext $context ) {

		global $IP, $wgUser;

		// Try for cache hit
		$data     = $wgUser->getId(); // caching styles per user
		$cacheKey = wfMemcKey( 'ext', 'bootstrap', $data );

		$cache       = wfGetCache( CACHE_ANYTHING );
		$cacheResult = $cache->get( $cacheKey );

		// only use styles from cache if LocalSettings was not modified after the caching
		if ( is_array( $cacheResult ) && $cacheResult[ 'storetime' ] >= filemtime( $IP . '/LocalSettings.php' ) ) {

			wfDebug( "ext.bootstrap: Cache hit: Got styles from cache.\n" );
			$styles = $cacheResult[ 'styles' ];

		} else {

			if ( is_array( $cacheResult ) ) {
				wfDebug( "ext.bootstrap: Cache miss: Cache outdated, LocalSettings have changed.\n" );
			} else {
				wfDebug( "ext.bootstrap: Cache miss: Styles not found in cache.\n" );
			}

			$compiler = ResourceLoader::getLessCompiler();

			// prepare a temp file containing all the variables to load
			// have to use a temp file for variables because inline variables do not overwrite @import'ed variables even if
			// set after the @import (see https://github.com/leafo/lessphp/issues/302 )
			$tmpFile = null;

			if ( !empty( $this->variables ) ) {

				$tmpFile = tempnam( sys_get_temp_dir(), 'php' );

				$handle = fopen( $tmpFile, 'w' );

				foreach ( $this->variables as $key => $value ) {
					fwrite( $handle, "@$key: $value;\n" );
				}

				fclose( $handle );

				$this->styles[ ] = basename( $tmpFile );
				$this->paths[ ]  = dirname( $tmpFile );

			}

			// add all
			$lessCode = implode( array_map( function ( $module ) { return "@import \"$module\";\n"; }, $this->styles ) );

			// add additional paths for external files
			foreach ( $this->paths as $path ) {
				$compiler->addImportDir( $path );
			}

			try {

				$styles = array( 'all' => $compiler->compile( $lessCode ) );
				$cache->set( $cacheKey, array( 'styles' => $styles, 'storetime' => time() ) );

			} catch ( \Exception $e ) {
				wfDebug( $e->getMessage() );
				$styles = '/* LESS compile error: ' . $e->getMessage() . '*/';
			}

			unlink( $tmpFile );

		}

		return $styles;
	}

	public function supportsURLLoading() {

		return false;
	}
}
