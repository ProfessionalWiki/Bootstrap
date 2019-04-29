<?php
/**
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

namespace Bootstrap\Definition;

use InvalidArgumentException;

/**
 * Class describing the Bootstrap 4 module definitions
 *
 * @since 4.0
 * @ingroup   Bootstrap
 */
class V4ModuleDefinition implements ModuleDefinition {

	static private $moduleDescriptions = [
		'functions'         => [ 'styles' => [ 'functions' => [ 'position' => 'functions' ] ] ],
		'variables'         => [ 'styles' => [ 'variables' => [ 'position' => 'variables' ] ], 'dependencies' => 'functions' ],
		'mixins'            => [ 'styles' => 'mixins' ],
		'root'              => [ 'styles' => 'root' ],
		'reboot'            => [ 'styles' => 'reboot' ],
		'type'              => [ 'styles' => 'type' ],
		'images'            => [ 'styles' => 'images' ],
		'code'              => [ 'styles' => 'code' ],
		'grid'              => [ 'styles' => 'grid' ],
		'tables'            => [ 'styles' => 'tables' ],
		'forms'             => [ 'styles' => 'forms' ],
		'buttons'           => [ 'styles' => 'buttons' ],
		'transitions'       => [ 'styles' => 'transitions' ],
		'dropdown'          => [ 'styles' => 'dropdown', 'scripts' => 'dropdown.js', 'dependencies' => [ 'popper', 'js-util' ] ],
		'button-group'      => [ 'styles' => 'button-group', 'dependencies' => [ 'buttons' ] ],
		'input-group'       => [ 'styles' => 'input-group', 'dependencies' => [ 'forms' ] ],
		'custom-forms'      => [ 'styles' => 'custom-forms' ],
		'nav'               => [ 'styles' => 'nav' ],
		'navbar'            => [ 'styles' => 'navbar' ],
		'card'              => [ 'styles' => 'card' ],
		'breadcrumb'        => [ 'styles' => 'breadcrumb' ],
		'pagination'        => [ 'styles' => 'pagination' ],
		'badge'             => [ 'styles' => 'badge' ],
		'jumbotron'         => [ 'styles' => 'jumbotron' ],
		'alert'             => [ 'styles' => 'alert' ],
		'progress'          => [ 'styles' => 'progress' ],
		'media'             => [ 'styles' => 'media' ],
		'list-group'        => [ 'styles' => 'list-group' ],
		'close'             => [ 'styles' => 'close' ],
		'toasts'            => [ 'styles' => 'toasts', 'scripts' => 'toast.js', 'dependencies' => 'js-util' ],
		'modal'             => [ 'styles' => 'modal', 'scripts' => 'modal.js' ],
		'tooltip'           => [ 'styles' => 'tooltip', 'dependencies' => [ 'popper', 'js-util' ] ],
		'popover'           => [ 'styles' => 'popover', 'dependencies' => [ 'popper', 'tooltip', 'js-util' ] ],
		'carousel'          => [ 'styles' => 'carousel', 'scripts' => 'carousel.js', 'dependencies' => 'js-util' ],
		'spinners'          => [ 'styles' => 'spinners' ],
		'utilities'         => [ 'styles' => 'utilities' ],
		'print'             => [ 'styles' => 'print' ],
		'active-buttons'    => [ 'scripts' => 'button.js', 'dependencies' => [ 'buttons' ] ],
		'dismissable-alert' => [ 'scripts' => 'alert.js', 'dependencies' => [ 'alert', 'js-util' ] ],
		'collapse'          => [ 'scripts' => 'collapse.js' ],
		'scrollspy'         => [ 'scripts' => 'scrollspy.js', 'dependencies' => [ 'popper', 'js-util' ] ],
		'tab'               => [ 'scripts' => 'tab.js', 'dependencies' => [ 'list-group' ] ],
		'js-util'           => [ 'scripts' => 'util.js' ],
		'popper'            => [ 'scripts' => 'popper.js' ],

		'bs-core'   => [ 'dependencies' => [ 'variables', 'mixins' ] ],
		'bs-reboot' => [ 'dependencies' => [ 'bs-core', 'reboot' ] ],
		'bs-grid'   => [ 'styles' => 'bootstrap-grid' ],

		'bs-basic'  => [ 'dependencies' => [
			'bs-core', 'root', 'reboot', 'type', 'images', 'code', 'grid',
			'tables', 'transitions', 'utilities', 'print'
		] ],

		'bs-all'    => [ 'dependencies' => [
			'bs-core', 'root', 'reboot', 'type', 'images', 'code', 'grid',
			'tables', 'forms', 'buttons', 'transitions', 'dropdown',
			'button-group', 'input-group', 'custom-forms', 'nav', 'navbar',
			'card', 'breadcrumb', 'pagination', 'badge', 'jumbotron', 'alert',
			'progress', 'media', 'list-group', 'close', 'toasts', 'modal', 'tooltip',
			'popover', 'carousel', 'spinners', 'utilities', 'print', 'active-buttons',
			'dismissable-alert', 'collapse', 'scrollspy', 'tab', 'js-util',
		] ],


		// TODO: Add each SCSS util separately?
		// TODO: Add each SCSS mixin module separately?

	];

	static private $coreModules = [	'bs-core' ];
	static private $optionalModules = [ 'bs-all' ];

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
