<?php

namespace Bootstrap;

use Less_Parser;
use ResourceLoaderContext;
use ResourceLoaderFileModule;
use BagOStuff;

use Exception;

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

	/** @var BagOStuff */
	protected $cache = null;

	protected $variables = array();
	protected $paths = array();
	protected $extStyles = array();
	protected $cacheTriggers = array();

	protected $styleText = null;

	public function __construct( $options = array(), $localBasePath = null, $remoteBasePath = null
	) {

		parent::__construct( $options, $localBasePath, $remoteBasePath );

		$this->applyOptions( $options );
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
				$this->compileStyles( $context );
			}
		}

		return array( 'all' => $this->styleText );
	}

	/**
	 * @see ResourceLoaderFileModule::supportsURLLoading
	 *
	 * @since  1.0
	 */
	public function supportsURLLoading() {
		return false;
	}

	/**
	 * @since  1.0
	 *
	 * @param BagOStuff $cache
	 */
	public function setCache( BagOStuff $cache ) {
		$this->cache = $cache;
	}

	protected function getCache() {

		if ( $this->cache === null ) {
			$this->cache = wfGetCache( CACHE_ANYTHING );
		}

		return $this->cache;
	}

	protected function getCacheKey( ResourceLoaderContext $context ) {
		return wfMemcKey( 'ext', 'bootstrap', $context->getHash() );
	}

	protected function retrieveStylesFromCache( ResourceLoaderContext $context ) {

		// Try for cache hit
		$cacheResult = $this->getCache()->get( $this->getCacheKey( $context ) );

		if ( is_array( $cacheResult ) ) {

			if ( $this->isCacheOutdated( $cacheResult[ 'storetime' ] ) ) {
				wfDebug( __METHOD__ . " ext.bootstrap: Cache miss: Cache outdated.\n" );
			} else {
				$this->styleText = $cacheResult[ 'styles' ];
				wfDebug( __METHOD__ . " ext.bootstrap: Cache hit: Got styles from cache.\n" );
			}

		} else {
			wfDebug( __METHOD__ .  " ext.bootstrap: Cache miss: Styles not found in cache.\n" );
		}
	}

	protected function updateCache( ResourceLoaderContext $context ) {

		$this->getCache()->set(
			$this->getCacheKey( $context ),
			array( 'styles' => $this->styleText, 'storetime' => time() )
		);
	}

	protected function purgeCache( ResourceLoaderContext $context ) {
		$this->getCache()->delete( $this->getCacheKey( $context ) );
	}

	protected function compileStyles( ResourceLoaderContext $context ) {

		$lessParser = new Less_Parser();
		$remotePath = $this->getRemotePath( '' );

		try {

			foreach ( $this->styles as $style ) {
				$lessParser->parseFile( $this->getLocalPath( $style ), $remotePath );
			}

			foreach ( $this->extStyles as $stylefile => $remotePath ) {
				$lessParser->parseFile( $stylefile, $remotePath );
			}

			$lessParser->ModifyVars( $this->variables );

			$this->styleText = $lessParser->getCss();

			$this->updateCache( $context );

		} catch ( Exception $e ) {

			$this->purgeCache( $context );
			wfDebug( $e->getMessage() );
			$this->styleText = '/* LESS compile error: ' . $e->getMessage() . '*/';
		}

	}

	/**
	 * @param mixed[] $options
	 */
	protected function applyOptions( $options ) {
		$mapConfigToLocalVar = array (
			'variables'       => 'variables',
			'paths'           => 'paths',
			'external styles' => 'extStyles',
			'cachetriggers'   => 'cacheTriggers',
		);

		foreach ( $mapConfigToLocalVar as $config => $local ) {
			if ( isset( $options[ $config ] ) ) {
				$this->$local = $options[ $config ];
			}
		}
	}

	/**
	 * @param int $cacheStoreTime
	 *
	 * @return bool
	 */
	protected function isCacheOutdated( $cacheStoreTime ) {

		foreach ( $this->cacheTriggers as $triggerFile ) {
			if ( $triggerFile !== null && $cacheStoreTime < filemtime( $triggerFile ) ) {
				return true;
			}
		}

		return false;
	}

}
