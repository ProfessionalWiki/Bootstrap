<?php
/**
 * File holding the Bootstrap class
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

namespace bootstrap;

/**
 * Class managing the Bootstrap framework.
 */
class BootstrapManager {


	static private $bootstrapManagerSingleton = null;
	static private $moduleDescriptions = array(
		'variables'            => array( 'styles' => 'variables' ),
		'mixins'               => array( 'styles' => 'mixins' ),
		'normalize'            => array( 'styles' => 'normalize' ),
		'print'                => array( 'styles' => 'print' ),
		'scaffolding'          => array( 'styles' => 'scaffolding' ),
		'type'                 => array( 'styles' => 'type' ),
		'code'                 => array( 'styles' => 'code' ),
		'grid'                 => array( 'styles' => 'grid' ),
		'tables'               => array( 'styles' => 'tables' ),
		'forms'                => array( 'styles' => 'forms' ),
		'buttons'              => array( 'styles' => 'buttons' ),
		'component-animations' => array( 'styles' => 'component-animations' ),
		'glyphicons'           => array( 'styles' => 'glyphicons' ),
		'dropdowns'            => array( 'styles' => 'dropdowns' ),
		'button-groups'        => array( 'styles' => 'button-groups' ),
		'input-groups'         => array( 'styles' => 'input-groups' ),
		'navs'                 => array( 'styles' => 'navs' ),
		'navbar'               => array( 'styles' => 'navbar' ),
		'breadcrumbs'          => array( 'styles' => 'breadcrumbs' ),
		'pagination'           => array( 'styles' => 'pagination' ),
		'pager'                => array( 'styles' => 'pager' ),
		'labels'               => array( 'styles' => 'labels' ),
		'badges'               => array( 'styles' => 'badges' ),
		'jumbotron'            => array( 'styles' => 'jumbotron' ),
		'thumbnails'           => array( 'styles' => 'thumbnails' ),
		'alerts'               => array( 'styles' => 'alerts' ),
		'progress-bars'        => array( 'styles' => 'progress-bars' ),
		'media'                => array( 'styles' => 'media' ),
		'list-group'           => array( 'styles' => 'list-group' ),
		'panels'               => array( 'styles' => 'panels' ),
		'wells'                => array( 'styles' => 'wells' ),
		'close'                => array( 'styles' => 'close' ),

		// Components w/ JavaScript
		'modals'               => array( 'styles' => 'modals', 'scripts' => 'modal' ),
		'tooltip'              => array( 'styles' => 'tooltip', 'scripts' => 'tooltip' ),
		'popovers'             => array( 'styles' => 'popovers', 'scripts' => 'popover', 'dependencies' => 'tooltip' ),
		'carousel'             => array( 'styles' => 'carousel', 'scripts' => 'carousel' ),

		// Utility classes
		'utilities'            => array( 'styles' => 'utilities' ),
		'responsive-utilities' => array( 'styles' => 'responsive-utilities' ),

		// JS-only components
		'affix'                => array( 'scripts' => 'affix' ),
		'alert'                => array( 'scripts' => 'alert' ),
		'button'               => array( 'scripts' => 'button' ),
		'collapse'             => array( 'scripts' => 'collapse' ),
		'dropdown'             => array( 'scripts' => 'dropdown' ),
		'scrollspy'            => array( 'scripts' => 'scrollspy' ),
		'tab'                  => array( 'scripts' => 'tab' ),
		'transition'           => array( 'scripts' => 'transition' ),

	);

	static private $coreModules = array(
		'variables', 'mixins', 'normalize', 'print', 'scaffolding', 'type', 'code', 'grid',
		'tables', 'forms', 'buttons'
	);

	static private $optionalModules = array(
		'component-animations', 'glyphicons', 'dropdowns', 'button-groups', 'input-groups', 'navs',
		'navbar', 'breadcrumbs', 'pagination', 'pager', 'labels', 'badges', 'jumbotron',
		'thumbnails', 'alerts', 'progress-bars', 'media', 'list-group', 'panels', 'wells', 'close',
		'modals', 'tooltip', 'popovers', 'carousel', 'utilities', 'responsive-utilities', 'affix',
		'alert', 'button', 'collapse', 'dropdown', 'scrollspy', 'tab', 'transition'
	);

	private $mModuleDescriptions;

	protected function __construct() {
		$this->mModuleDescriptions = self::$moduleDescriptions;
	}

	/**
	 * Returns the Bootstrap singleton.
	 *
	 * @return BootstrapManager
	 */
	public static function getBootstrapManager() {

		// if singleton was not yet created
		if ( self::$bootstrapManagerSingleton === null ) {
			self::initializeBootstrap();
		}

		return self::$bootstrapManagerSingleton;
	}

	/**
	 * sets up the Bootstrap singleton and does some initialization
	 */
	protected static function initializeBootstrap() {

		self::$bootstrapManagerSingleton = new BootstrapManager();

		// add core Bootstrap modules
		self::$bootstrapManagerSingleton->addBootstrapModule( self::$coreModules );
	}

	/**
	 * Adds the given Bootstrap module or modules.
	 *
	 * @param string|array(string) $modules
	 */
	public function addBootstrapModule( $modules ) {

		$modules = (array) $modules;

		foreach ( $modules as $module ) {

			// if the module is known
			if ( isset( $this->mModuleDescriptions[ $module ] ) ) {

				$description = $this->mModuleDescriptions[ $module ];

				// prevent adding this module again; this also prevents infinite recursion in case
				// of dependency resolution
				unset( $this->mModuleDescriptions[ $module ] );

				// first add any dependencies recursively, so they are available when the styles and
				// scripts of $module are loaded
				if ( isset( $description[ 'dependencies' ] ) ) {
					$this->addBootstrapModule( $description[ 'dependencies' ] );
				}

				$this->addFilesToGlobalResourceModules( 'styles', $description, '.less' );
				$this->addFilesToGlobalResourceModules( 'scripts', $description, '.js' );

			}
		}

	}

	/**
	 * @param string       $filetype 'styles'|'scripts'
	 * @param array|string $description
	 * @param              $fileExt
	 *
	 * @internal param $relativePath
	 */
	protected function addFilesToGlobalResourceModules ( $filetype, $description, $fileExt ) {

		if ( isset( $description[ $filetype ] ) ) {

			$path = $GLOBALS[ 'wgResourceModules' ][ 'ext.bootstrap.' . $filetype ][ 'localBasePath' ];

			$GLOBALS[ 'wgResourceModules' ][ 'ext.bootstrap.' . $filetype ][ $filetype ] =
				array_merge(
					$GLOBALS[ 'wgResourceModules' ][ 'ext.bootstrap.' . $filetype ][ $filetype ],
					array_map( function ( $filename ) use ( $fileExt ) { return $filename . $fileExt; }, (array) $description[ $filetype ])
				);

		}
	}

	/**
	 * Adds all bootstrap modules
	 */
	public function addAllBootstrapModules() {

		$this->addBootstrapModule( self::$optionalModules );
	}

	/**
	 * @param string $file
	 * @param string $remotePath
	 *
	 * @internal param string $path
	 */
	public function addExternalModule( $file, $remotePath = '' ) {

		global $wgResourceModules;
		$wgResourceModules[ 'ext.bootstrap.styles' ][ 'external styles' ][ $file ] = $remotePath;
	}

	/**
	 * @param string $key   the LESS variable name
	 * @param string $value the value to assign to the variable
	 */
	public function setLessVariable( $key, $value ) {

		$this->setLessVariables( array( $key => $value ) );
	}

	/**
	 * @param $variables
	 */
	public function setLessVariables( $variables ) {

		global $wgResourceModules;

		$wgResourceModules[ 'ext.bootstrap.styles' ][ 'variables' ] =
			array_merge( $wgResourceModules[ 'ext.bootstrap.styles' ][ 'variables' ], $variables );
	}

}
