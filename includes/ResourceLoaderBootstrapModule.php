<?php
/**
 * File holding the ResourceLoaderBootstrapModule class
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

use lessc;
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
 *            be searched for a style file; this means it is not possible to include two LESS files with the same name
 *          even if in different paths
 *
 * @package Bootstrap
 */
class ResourceLoaderBootstrapModule extends ResourceLoaderFileModule {

	protected $variables = array();
	protected $paths = array();
	protected $extStyles = array();

	protected $styleText = null;

	public function __construct( $options = array(), $localBasePath = null, $remoteBasePath = null
	) {

		parent::__construct( $options, $localBasePath, $remoteBasePath );

		if ( isset( $options[ 'variables' ] ) ) {
			$this->variables = $options[ 'variables' ];
		}

		if ( isset( $options[ 'paths' ] ) ) {
			$this->paths = $options[ 'paths' ];
		}

		if ( isset( $options[ 'external styles' ] ) ) {
			$this->extStyles = $options[ 'external styles' ];
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

		if ( $this->styleText === null ) {

			$this->retrieveStylesFromCache( $context );

			if ( $this->styleText === null ) {

				$this->compileStyles();
				$this->updateCache( $context );

			}
		}

		return array( 'all' => $this->styleText );
	}

	protected function getCacheKey( ResourceLoaderContext $context ) {
		return wfMemcKey( 'ext', 'bootstrap', $context->getHash() );
	}

	protected function retrieveStylesFromCache( ResourceLoaderContext $context ) {

		// Try for cache hit
		$cache = wfGetCache( CACHE_ANYTHING );
		$cacheResult = $cache->get( $this->getCacheKey( $context ) );

		if ( is_array( $cacheResult ) ) {

			if ( $cacheResult[ 'storetime' ] >= filemtime( $GLOBALS[ 'IP' ] . '/LocalSettings.php' ) ) {

				$this->styleText = $cacheResult[ 'styles' ];

				wfDebug( "ext.bootstrap: Cache hit: Got styles from cache.\n" );
			} else {
				wfDebug( "ext.bootstrap: Cache miss: Cache outdated, LocalSettings have changed.\n" );
			}
		} else {
			wfDebug( "ext.bootstrap: Cache miss: Styles not found in cache.\n" );
		}
	}

	protected function updateCache( ResourceLoaderContext $context ) {

		$cache = wfGetCache( CACHE_ANYTHING );
		$cache->set( $this->getCacheKey( $context ), array( 'styles' => $this->styleText, 'storetime' => time() ) );

	}

	protected function compileStyles() {

		$parser = new \Less_Parser();
		$remotePath = $this->getRemotePath( '' );

		try {

			foreach ( $this->styles as $style ) {
				$parser->parseFile( $this->getLocalPath( $style ), $remotePath );
			}

			foreach ( $this->extStyles as $stylefile => $remotePath ) {
				$parser->parseFile( $stylefile, $remotePath );
			}

			$parser->ModifyVars( $this->variables );

			$this->styleText = $parser->getCss();

		} catch ( \Exception $e ) {
			wfDebug( $e->getMessage() );
			$this->styleText = '/* LESS compile error: ' . $e->getMessage() . '*/';
		}

	}

	public function supportsURLLoading() {

		return false;
	}
}
