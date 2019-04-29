<?php
/**
 * File holding the BootstrapManager class
 *
 * @copyright 2013 - 2019, Stephan Gambke
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

use Bootstrap\Definition\V4ModuleDefinition;
use Bootstrap\Definition\ModuleDefinition;

/**
 * Class managing the Bootstrap framework.
 *
 * @since 1.0
 * @ingroup   Bootstrap
 */
class BootstrapManager {

	/** @var ModuleDefinition */
	protected $moduleDefinition = null;

	/** @var BootstrapManager */
	private static $instance = null;

	private $moduleDescriptions;

	/**
	 * @since  1.0
	 *
	 * @param ModuleDefinition $moduleDefinition
	 */
	public function __construct( ModuleDefinition $moduleDefinition ) {
		$this->moduleDefinition = $moduleDefinition;
		$this->moduleDescriptions = $this->moduleDefinition->get( 'descriptions' );
	}

	/**
	 * Returns the Bootstrap singleton.
	 *
	 * @since  1.0
	 *
	 * @return BootstrapManager
	 */
	public static function getInstance() {

		if ( self::$instance === null ) {
			self::$instance = new self( new V4ModuleDefinition );
		}

		return self::$instance;
	}

	/**
	 * @since  1.0
	 */
	public static function clear() {
		self::$instance = null;
	}

	/**
	 * Adds the given Bootstrap module or modules.
	 *
	 * @since  1.0
	 *
	 * @param string|string[] $modules
	 */
	public function addBootstrapModule( $modules ) {

		$modules = (array) $modules;

		foreach ( $modules as $module ) {

			// if the module is known
			if ( isset( $this->moduleDescriptions[ $module ] ) ) {

				$description = $this->moduleDescriptions[ $module ];

				// prevent adding this module again; this also prevents infinite recursion in case
				// of dependency resolution
				unset( $this->moduleDescriptions[ $module ] );

				// first add any dependencies recursively, so they are available when the styles and
				// scripts of $module are loaded
				if ( isset( $description[ 'dependencies' ] ) ) {
					$this->addBootstrapModule( $description[ 'dependencies' ] );
				}

				$this->addFilesToGlobalResourceModules( 'styles', $description );
				$this->addFilesToGlobalResourceModules( 'scripts', $description );

			}
		}
	}

	/**
	 * @param string       $filetype 'styles'|'scripts'
	 * @param mixed[]      $description
	 */
	protected function addFilesToGlobalResourceModules ( $filetype, $description ) {

		if ( isset( $description[ $filetype ] ) ) {

			$this->adjustArrayElementOfResourceModuleDescription( $filetype, $description[ $filetype ], $filetype );

		}
	}

	/**
	 * Adds core bootstrap modules
	 *
	 * @since  2.0
	 */
	public function addCoreBootstrapModules() {
		$this->addBootstrapModule( $this->moduleDefinition->get( 'core' ) );
	}

	/**
	 * Adds all bootstrap modules
	 *
	 * @since  1.0
	 */
	public function addAllBootstrapModules() {
		$this->addBootstrapModule( $this->moduleDefinition->get( 'optional' ) );
	}

	/**
	 * @since  1.0
	 *
	 * @param string $path
	 * @param string $position
	 *
	 * @internal param string $path
	 */
	public function addStyleFile( $path, $position = 'main' ) {
		$this->adjustArrayElementOfResourceModuleDescription( 'styles', [ $path => [ 'position' => $position ] ] );
	}

	/**
	 * @since  2.0
	 *
	 * @param string $key   the SCSS variable name
	 * @param string $value the value to assign to the variable
	 */
	public function setScssVariable( $key, $value ) {
		$this->setScssVariables( [ $key => $value ] );
	}

	/**
	 * @since  2.0
	 *
	 * @param mixed[] $variables
	 */
	public function setScssVariables( $variables ) {
		$this->adjustArrayElementOfResourceModuleDescription( 'variables', $variables );
	}

	/**
	 * @since 1.1
	 * @param string|string[] $files
	 */
	public function addCacheTriggerFile( $files ){
		$this->adjustArrayElementOfResourceModuleDescription( 'cacheTriggers', $files );
	}

	/**
	 * @param string $key
	 * @param mixed  $value
	 * @param string $filetype 'styles'|'scripts'
	 */
	protected function adjustArrayElementOfResourceModuleDescription( $key, $value, $filetype = 'styles' ) {

		if (!isset($GLOBALS[ 'wgResourceModules' ][ 'ext.bootstrap.' . $filetype ][ $key ])) {
			$GLOBALS[ 'wgResourceModules' ][ 'ext.bootstrap.' . $filetype ][ $key ] = [];
		}

		$GLOBALS[ 'wgResourceModules' ][ 'ext.bootstrap.' . $filetype ][ $key ] =
			array_merge(
				$GLOBALS[ 'wgResourceModules' ][ 'ext.bootstrap.' . $filetype ][ $key ],
				(array) $value
			);
	}
}
