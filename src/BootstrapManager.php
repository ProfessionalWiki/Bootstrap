<?php

namespace Bootstrap;

use Bootstrap\Definition\V3ModuleDefinition;
use Bootstrap\Definition\ModuleDefinition;

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
class BootstrapManager {

	/** @var ModuleDefinition */
	protected $moduleDefinition = null;

	/** @var BootstrapManager */
	private static $instance = null;

	private $mModuleDescriptions;

	/**
	 * @since  1.0
	 *
	 * @param ModuleDefinition $moduleDefinition
	 */
	public function __construct( ModuleDefinition $moduleDefinition ) {
		$this->moduleDefinition = $moduleDefinition;
		$this->initCoreModules();
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
			self::$instance = new self( new V3ModuleDefinition );
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
	 * @param mixed[]      $description
	 * @param              $fileExt
	 */
	protected function addFilesToGlobalResourceModules ( $filetype, $description, $fileExt ) {

		if ( isset( $description[ $filetype ] ) ) {

			$files = array_map(
				function ( $filename ) use ( $fileExt ) {
					return $filename . $fileExt;
				},
				(array) $description[ $filetype ]
			);

			$this->adjustArrayElementOfResourceModuleDescription( $filetype, $files, $filetype );

		}
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
	 * @param string $file
	 * @param string $remotePath
	 *
	 * @internal param string $path
	 */
	public function addExternalModule( $file, $remotePath = '' ) {
		$this->adjustArrayElementOfResourceModuleDescription( 'external styles', array( $file => $remotePath ) );
	}

	/**
	 * @since  1.0
	 *
	 * @param string $key   the LESS variable name
	 * @param string $value the value to assign to the variable
	 */
	public function setLessVariable( $key, $value ) {
		$this->setLessVariables( array( $key => $value ) );
	}

	/**
	 * @since  1.0
	 *
	 * @param mixed[] $variables
	 */
	public function setLessVariables( $variables ) {
		$this->adjustArrayElementOfResourceModuleDescription( 'variables', $variables );
	}

	/**
	 * @since 1.1
	 * @param string|string[] $files
	 */
	public function addCacheTriggerFile( $files ){
		$this->adjustArrayElementOfResourceModuleDescription( 'cachetriggers', $files );
	}

	protected function initCoreModules() {
		$this->mModuleDescriptions = $this->moduleDefinition->get( 'descriptions' );
		$this->addBootstrapModule( $this->moduleDefinition->get( 'core' ) );
	}

	/**
	 * @param string $key
	 * @param mixed  $value
	 * @param string $filetype 'styles'|'scripts'
	 */
	protected function adjustArrayElementOfResourceModuleDescription( $key, $value, $filetype = 'styles' ) {

		if (!isset($GLOBALS[ 'wgResourceModules' ][ 'ext.bootstrap.' . $filetype ][ $key ])) {
			$GLOBALS[ 'wgResourceModules' ][ 'ext.bootstrap.' . $filetype ][ $key ] = $value;
		} else {
			$GLOBALS[ 'wgResourceModules' ][ 'ext.bootstrap.' . $filetype ][ $key ] =
				array_merge(
					$GLOBALS[ 'wgResourceModules' ][ 'ext.bootstrap.' . $filetype ][ $key ],
					(array) $value
				);
		}
	}
}
