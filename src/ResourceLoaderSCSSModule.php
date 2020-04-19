<?php
/**
 * File containing the ResourceLoaderSCSSModule class
 *
 * @copyright 2018 - 2019, Stephan Gambke
 * @license   GNU General Public License, version 3 (or any later version)
 *
 * This file is part of the MediaWiki extension SCSS.
 * The SCSS extension is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by the Free
 * Software Foundation, either version 3 of the License, or (at your option) any
 * later version.
 *
 * The Bootstrap extension is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @file
 * @ingroup Bootstrap
 */

namespace Bootstrap;

use BagOStuff;
use CSSJanus;
use Exception;
use Leafo\ScssPhp\Compiler;
use ObjectCache;
use ResourceLoaderContext;
use ResourceLoaderFileModule;


/**
 * ResourceLoader module based on local JavaScript/SCSS files.
 *
 * It recognizes the following additional fields in $wgResourceModules:
 * * styles: array of SCSS file names (with or without extension .scss)
 * * variables: array of key value pairs representing SCSS variables, that will
 *              be added to the SCSS script after all files imports, i.e. that
 *              may override any variable set in style files
 * * paths: array of paths to search for style files; all these paths together
 *              represent one virtual file base and will be searched for a style
 *              file; this means it is not possible to include two SCSS files
 *              with the same name even if in different paths
 *
 * @ingroup Bootstrap
 */
class ResourceLoaderSCSSModule extends ResourceLoaderFileModule {

	private $styleModulePositions = [
		'beforeFunctions', 'functions', 'afterFunctions',
		'beforeVariables', 'variables', 'afterVariables',
		'beforeMain', 'main', 'afterMain',
	];

	private $cache = null;
	private $cacheKey = null;

	protected $variables = [];
	protected $paths = [];
	protected $cacheTriggers = [];

	protected $styleText = null;

	/**
	 * ResourceLoaderSCSSModule constructor.
	 *
	 * @param mixed[] $options
	 * @param string|null $localBasePath
	 * @param string|null $remoteBasePath
	 */
	public function __construct( $options = [], $localBasePath = null, $remoteBasePath = null ) {

		parent::__construct( $options, $localBasePath, $remoteBasePath );

		$this->applyOptions( $options );
	}

	/**
	 * @param mixed[] $options
	 */
	protected function applyOptions( $options ) {

		$mapConfigToLocalVar = [
			'variables'      => 'variables',
			'paths'          => 'paths',
			'cacheTriggers' => 'cacheTriggers',
		];

		foreach ( $mapConfigToLocalVar as $config => $local ) {
			if ( isset( $options[ $config ] ) ) {
				$this->$local = $options[ $config ];
			}
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
				$this->compileStyles( $context );
			}
		}

		return [ 'all' => $this->styleText ];
	}

	/**
	 * @param ResourceLoaderContext $context
	 */
	protected function retrieveStylesFromCache( ResourceLoaderContext $context ) {

		// Try for cache hit
		$cacheKey = $this->getCacheKey( $context );
		$cacheResult = $this->getCache()->get( $cacheKey );

		if ( is_array( $cacheResult ) ) {

			if ( $this->isCacheOutdated( $cacheResult[ 'storetime' ] ) ) {
				wfDebug( "SCSS: Cache miss for {$this->getName()}: Cache outdated.\n", 'private' );
			} else {
				$this->styleText = $cacheResult[ 'styles' ];
				wfDebug( "SCSS: Cache hit for {$this->getName()}: Got styles from cache.\n", 'private' );
			}

		} else {
			wfDebug( "SCSS: Cache miss for {$this->getName()}: Styles not found in cache.\n", 'private' );
		}
	}

	/**
	 * @return BagOStuff|null
	 */
	protected function getCache() {

		if ( $this->cache === null ) {
			$this->cache = ObjectCache::getInstance( $GLOBALS[ 'egScssCacheType' ] );
		}

		return $this->cache;
	}

	/**
	 * @since  1.0
	 *
	 * @param BagOStuff $cache
	 */
	public function setCache( BagOStuff $cache ) {
		$this->cache = $cache;
	}

	/**
	 * @param ResourceLoaderContext $context
	 *
	 * @return string
	 */
	protected function getCacheKey( ResourceLoaderContext $context ) {

		if ( $this->cacheKey === null ) {

			$styles = serialize( $this->styles );

			$vars = $this->variables;
			ksort( $vars );
			$vars = serialize( $vars );

			// have to hash the module config, else it may become too long
			$configHash = md5( $styles . $vars );

			$this->cacheKey = wfMemcKey(
				'ext',
				'scss',
				$configHash,
				$context->getDirection()
			);
		}

		return $this->cacheKey;
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

	/**
	 * @param ResourceLoaderContext $context
	 */
	protected function compileStyles( ResourceLoaderContext $context ) {

		$scss = new Compiler();
		$scss->setImportPaths( $this->getLocalPath( '' ) );

		// Allows inlining of arbitrary files regardless of extension, .css in particular
		$scss->addImportPath(

			// addImportPath is declared as requiring a string param, but actually also understand callables
			/** @scrutinizer ignore-type */
			function ( $path ) {
				if ( file_exists( $path ) ) {
					return $path;
				}
				return null;
			}

		);

		try {

			$imports = $this->getStyleFilesList();

			foreach ( $imports as $key => $import ) {
				$path = str_replace( [ '\\', '"' ], [ '\\\\', '\\"' ], $import );
				$imports[ $key ] = '@import "' . $path . '";';
			}

			$scss->setVariables( $this->variables );

			$style = $scss->compile( implode( $imports ) );

			if ( $this->getFlip( $context ) ) {
				$style = CSSJanus::transform( $style, true, false );
			}

			$this->styleText = $style;

			$this->updateCache( $context );

		} catch ( Exception $e ) {

			$this->purgeCache( $context );
			wfDebug( $e->getMessage() );
			$this->styleText = '/* SCSS compile error: ' . $e->getMessage() . '*/';
		}

	}

	/**
	 * @param ResourceLoaderContext $context
	 */
	protected function updateCache( ResourceLoaderContext $context ) {

		$this->getCache()->set(
			$this->getCacheKey( $context ),
			[ 'styles' => $this->styleText, 'storetime' => time() ]
		);
	}

	/**
	 * @param ResourceLoaderContext $context
	 */
	protected function purgeCache( ResourceLoaderContext $context ) {
		$this->getCache()->delete( $this->getCacheKey( $context ) );
	}

	/**
	 * @see ResourceLoaderFileModule::supportsURLLoading
	 */
	public function supportsURLLoading() {
		return false;
	}

	/**
	 * @return array
	 */
	protected function getStyleFilesList() {
		$styles = self::collateFilePathListByOption( $this->styles, 'position', 'main' );
		$imports = [];

		foreach ( $this->styleModulePositions as $position ) {
			if ( isset( $styles[ $position ] ) ) {
				$imports = array_merge( $imports, $styles[ $position ] );
			}
		}

		return $imports;
	}

}
