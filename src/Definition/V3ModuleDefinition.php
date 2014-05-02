<?php

namespace Bootstrap\Definition;

use InvalidArgumentException;

/**
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
 * Class describing the V3 Bootstrap module definitions
 */
class V3ModuleDefinition implements ModuleDefinition {

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

	/**
	 * @see ModuleDefinition::get
	 *
	 * @since  1.0
	 *
	 * @param string $key
	 *
	 * @return array
	 * @throws InvalidArgumentException
	 */
	public function get( $key ) {

		switch ( $key ) {
			case 'core':
				return self::$coreModules;
			case 'optional':
				return self::$optionalModules;
			case 'descriptions':
				return self::$moduleDescriptions;
		}

		throw new InvalidArgumentException( 'Expected a valid key' );
	}

}
