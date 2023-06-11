<?php
/**
 * @copyright 2013 - 2019, Stephan Gambke
 * @license   GPL-3.0-or-later
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
 * @since 5.0
 * @ingroup   Bootstrap
 */
class V5ModuleDefinition implements ModuleDefinition {

	private static $moduleDescriptions = [
		// Configuration
		'functions'         => [ 'styles' => [ 'functions' => [ 'position' => 'functions' ] ] ],
		'variables'         => [ 'styles' => [ 'variables' => [ 'position' => 'variables' ] ], 'dependencies' => 'functions' ],
		'variables-dark'    => [ 'styles' => [ 'variables-dark' => [ 'position' => 'variables' ] ], 'dependencies' => 'functions' ],
		'maps'              => [ 'styles' => 'maps' ],
		'mixins'            => [ 'styles' => 'mixins' ],
		'utilities'         => [ 'styles' => 'utilities' ],

		 // Layout & components
		'root'              => [ 'styles' => 'root' ],
		'reboot'            => [ 'styles' => 'reboot' ],
		'type'              => [ 'styles' => 'type' ],
		'images'            => [ 'styles' => 'images' ],
		'containers'        => [ 'styles' => 'containers' ],
		'grid'              => [ 'styles' => 'grid' ],
		'tables'            => [ 'styles' => 'tables' ],
		'forms'             => [ 'styles' => 'forms' ],
		'buttons'           => [ 'styles' => 'buttons' ],
		'transitions'       => [ 'styles' => 'transitions' ],
		'dropdown'          => [ 'styles' => 'dropdown', 'packageFiles' => 'dropdown.js', 'dependencies' => [ 'popper', 'base-component' ] ],
		'button-group'      => [ 'styles' => 'button-group', 'dependencies' => [ 'buttons' ] ],
		'nav'               => [ 'styles' => 'nav' ],
		'navbar'            => [ 'styles' => 'navbar' ],
		'card'              => [ 'styles' => 'card' ],
		'accordion'         => [ 'styles' => 'accordion' ],
		'breadcrumb'        => [ 'styles' => 'breadcrumb' ],
		'pagination'        => [ 'styles' => 'pagination' ],
		'badge'             => [ 'styles' => 'badge' ],
		'alert'             => [ 'styles' => 'alert' ],
		'progress'          => [ 'styles' => 'progress' ],
		'list-group'        => [ 'styles' => 'list-group' ],
		'close'             => [ 'styles' => 'close' ],
		'toasts'            => [ 'styles' => 'toasts', 'packageFiles' => 'toast.js', 'dependencies' => 'base-component' ],
		'modal'             => [ 'styles' => 'modal', 'packageFiles' => 'modal.js', 'dependencies' => 'base-component' ],
		'tooltip'           => [ 'styles' => 'tooltip', 'packageFiles' => 'tooltip.js', 'dependencies' => [ 'popper', 'base-component' ] ],
		'popover'           => [ 'styles' => 'popover', 'packageFiles' => 'popover.js', 'dependencies' => [ 'popper', 'tooltip', 'base-component' ] ],
		'carousel'          => [ 'styles' => 'carousel', 'packageFiles' => 'carousel.js', 'dependencies' => 'base-component' ],
		'spinners'          => [ 'styles' => 'spinners' ],
		'offcanvas'         => [ 'styles' => 'offcanvas', 'packageFiles' => 'offcanvas.js' ],
		'placeholders'      => [ 'styles' => 'placeholders' ],

		// Helpers
		'helpers'           => [ 'styles' => 'helpers' ],

		// Helpers
		'utilities/api'     => [ 'styles' => 'utilities/api' ],

		// Component JavaScript requirements
		'base-component'    => [ 'packageFiles' => 'base-component.js', 'dependencies' => [ 'js-util' ] ],
		'active-buttons'    => [ 'packageFiles' => 'button.js', 'dependencies' => [ 'buttons', 'js-util' ] ],
		'dismissable-alert' => [ 'packageFiles' => 'alert.js', 'dependencies' => [ 'alert', 'base-component' ] ],
		'collapse'          => [ 'packageFiles' => 'collapse.js', 'dependencies' => [ 'js-util' ] ],
		'scrollspy'         => [ 'packageFiles' => 'scrollspy.js', 'dependencies' => [ 'popper', 'js-util' ] ],
		'tab'               => [ 'packageFiles' => 'tab.js', 'dependencies' => [ 'list-group', 'js-util' ] ],
		// TODO: this needs to be included via ResourceLoader module
		'popper'            => [ 'packageFiles' => 'popper.js', 'dependencies' => [ 'js-util' ] ],

		// General JavaScript requirements
		'js-dom' => [
			'packageFiles' => [
				'dom/data.js',
				'dom/event-handler.js',
				'dom/manipulator.js',
				'dom/selector-engine.js'
			]
		],
		'js-util'  => [
			'packageFiles' => [
				'util/config.js',
				'util/backdrop.js',
				'util/component-functions.js',
				'util/focustrap.js',
				'util/index.js',
				'util/sanitizer.js',
				'util/scrollbar.js',
				'util/swipe.js',
				'util/template-factory.js'
			],
			'dependencies' => ['js-dom' ]
		],

		// Pre-defined collections
		'bs-core'   => [ 'dependencies' => [ 'variables', 'variables-dark', 'maps', 'mixins', 'utilities' ] ],
		'bs-reboot' => [ 'dependencies' => [ 'bs-core', 'reboot' ] ],
		'bs-grid'   => [ 'styles' => 'bootstrap-grid' ],

		'bs-basic'  => [ 'dependencies' => [
			'bs-core', 'root', 'reboot', 'type', 'images', 'containers', 'grid',
			'tables', 'transitions', 'tooltip'
		] ],

		'bs-all'    => [ 'dependencies' => [
			'bs-core', 'root', 'reboot', 'type', 'images', 'containers', 'grid',
			'tables', 'forms', 'buttons', 'transitions', 'dropdown',
			'button-group', 'nav', 'navbar',
			'card', 'accordion', 'breadcrumb', 'pagination', 'badge', 'alert',
			'progress', 'list-group', 'close', 'toasts', 'modal', 'tooltip',
			'popover', 'carousel', 'spinners', 'offcanvas', 'placeholders', 'active-buttons',
			'helpers', 'utilities/api',
			'dismissable-alert', 'collapse', 'scrollspy', 'tab', 'js-util',
		] ],

		// TODO: Add each SCSS util separately?
		// TODO: Add each SCSS mixin module separately?

	];

	private static $coreModules = [ 'bs-core' ];
	private static $optionalModules = [ 'bs-all' ];

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
