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

/**
 * Class managing the Bootstrap framework.
 */
class Bootstrap {


	static private $bootstrap = null;
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
		'modals'               => array( 'styles' => 'modals', 'scripts' => 'bootstrap/js/modal.js' ),
		'tooltip'              => array( 'styles' => 'tooltip', 'scripts' => 'bootstrap/js/tooltip.js' ),
		'popovers'             => array( 'styles' => 'popovers', 'scripts' => 'bootstrap/js/popover.js', 'dependencies' => 'ext.bootstrap.tooltip' ),
		'carousel'             => array( 'styles' => 'carousel', 'scripts' => 'bootstrap/js/carousel.js' ),

		// Utility classes
		'utilities'            => array( 'styles' => 'utilities' ),
		'responsive-utilities' => array( 'styles' => 'responsive-utilities' ),

		// JS-only components
		'affix'                => array( 'scripts' => 'bootstrap/js/affix.js' ),
		'alert'                => array( 'scripts' => 'bootstrap/js/alert.js' ),
		'button'               => array( 'scripts' => 'bootstrap/js/button.js' ),
		'collapse'             => array( 'scripts' => 'bootstrap/js/collapse.js' ),
		'dropdown'             => array( 'scripts' => 'bootstrap/js/dropdown.js' ),
		'scrollspy'            => array( 'scripts' => 'bootstrap/js/scrollspy.js' ),
		'tab'                  => array( 'scripts' => 'bootstrap/js/tab.js' ),
		'transition'           => array( 'scripts' => 'bootstrap/js/transition.js' ),

	);
	static private $coreModules = array( 'variables', 'mixins', 'normalize', 'print', 'scaffolding', 'type', 'code',
										 'grid', 'tables', 'forms', 'buttons' );
	static private $optionalModules = array( 'component-animations', 'glyphicons', 'dropdowns', 'button-groups',
											 'input-groups', 'navs', 'navbar', 'breadcrumbs', 'pagination', 'pager',
											 'labels', 'badges', 'jumbotron', 'thumbnails', 'alerts', 'progress-bars',
											 'media', 'list-group', 'panels', 'wells', 'close', 'modals', 'tooltip',
											 'popovers', 'carousel', 'utilities', 'responsive-utilities', 'affix',
											 'alert', 'button', 'collapse', 'dropdown', 'scrollspy', 'tab', 'transition' );

	/**
	 * Returns the Bootstrap singleton.
	 *
	 * @return Bootstrap
	 */
	public static function getBootstrap() {

		// if singleton was not yet created
		if ( self::$bootstrap === null ) {
			self::initializeBootstrap();
		}

		return self::$bootstrap;
	}

	/**
	 * sets up the Bootstrap singleton and does some initialization
	 */
	protected static function initializeBootstrap() {

		global $wgResourceModules;

		// register resource loader modules for JS components
		foreach ( self::$moduleDescriptions as $module => $description ) {
			if ( isset( $description[ 'scripts' ] ) ) {

				$wgResourceModules[ 'ext.bootstrap.' . $module ] = array(
					'localBasePath' => $wgResourceModules[ 'ext.bootstrap' ][ 'localBasePath' ],
					'remoteExtPath' => 'Bootstrap',
					'scripts'       => $description[ 'scripts' ],
				);

				if ( isset( $description[ 'dependencies' ] ) ) {
					$wgResourceModules[ 'ext.bootstrap.' . $module ][ 'dependencies' ] = $description[ 'dependencies' ];
				}
			}
		}

		self::$bootstrap = new Bootstrap();

		// add core Bootstrap modules
		self::$bootstrap->addBootstrapModule( self::$coreModules );
	}

	/**
	 * Adds the given Bootstrap module or modules.
	 *
	 * @param string|array(string) $modules
	 */
	public function addBootstrapModule( $modules ) {

		$modules = (array)$modules;

		foreach ( $modules as $module ) {

			// if the module is known
			if ( array_key_exists( $module, self::$moduleDescriptions ) ) {

				global $wgResourceModules;

				// add less files to $wgResourceModules
				if ( isset( self::$moduleDescriptions[ $module ][ 'styles' ] ) ) {
					$wgResourceModules[ 'ext.bootstrap' ][ 'styles' ] = array_merge( $wgResourceModules[ 'ext.bootstrap' ][ 'styles' ], (array)self::$moduleDescriptions[ $module ][ 'styles' ] );
				}

				// ensure loading of js files using dependencies
				if ( isset( self::$moduleDescriptions[ $module ][ 'scripts' ] ) ) {
					$wgResourceModules[ 'ext.bootstrap' ][ 'dependencies' ][ ] = 'ext.bootstrap.' . $module;

				}

				// prevent adding this module again
				unset( self::$moduleDescriptions[ $module ] );
			}
		}

	}

	/**
	 * Adds all bootstrap modules
	 */
	public function addAllBootstrapModules() {

		$this->addBootstrapModule( self::$optionalModules );
	}

	/**
	 * @param string $path
	 * @param string $file
	 */
	public function addExternalModule( $path, $file ) {

		global $wgResourceModules;

		if ( !in_array( $path, $wgResourceModules[ 'ext.bootstrap' ][ 'paths' ] ) ) {
			$wgResourceModules[ 'ext.bootstrap' ][ 'paths' ][ ] = $path;
		}

		$wgResourceModules[ 'ext.bootstrap' ][ 'styles' ][ ] = $file;
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
		$wgResourceModules[ 'ext.bootstrap' ][ 'variables' ] = array_merge( $wgResourceModules[ 'ext.bootstrap' ][ 'variables' ], $variables );
	}

}
