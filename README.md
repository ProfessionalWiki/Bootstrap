# Bootstrap extension

[![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/ProfessionalWiki/Bootstrap/ci.yml?branch=master)](https://github.com/ProfessionalWiki/Bootstrap/actions?query=workflow%3ACI)
[![Latest Stable Version](https://poser.pugx.org/mediawiki/bootstrap/version.png)](https://packagist.org/packages/mediawiki/bootstrap)
[![License](https://poser.pugx.org/mediawiki/bootstrap/license)](https://packagist.org/packages/mediawiki/bootstrap)

The [Bootstrap extension][mw-bootstrap] provides the
[Bootstrap web front-end framework][bootstrap] to skins and extensions.

This version of the extension provides Bootstrap 4.6.2 and Popper 1.16.1.

## Requirements

- PHP 5.6 or later
- MediaWiki 1.29 or later

## Installation

There are two methods for installing Bootstrap. You can select the method that best fits your
environment.

### Method 1

If you install Bootstrap with [Composer](composer), further required software packages will be installed
automatically. In this case, it is *not* necessary to install any dependencies. Composer will
take care of that.

1. On a command line go to your MediaWiki installation directory and run these two commands
   ```
   COMPOSER=composer.local.json composer require --no-update mediawiki/bootstrap:~4.0
   ```
   ```
   composer update mediawiki/bootstrap --no-dev -o
   ```

2. Load the extension by adding the following line to `LocalSettings.php`:

   ```php
   wfLoadExtension( 'Bootstrap' );
   ```

3. __Done:__ Navigate to _Special:Version_ on your wiki to verify that the
   extension is successfully installed.

**Remark:** It is _NOT_ necessary to install or load any extensions this extensions
depends on.

### Method 2

If you install Bootstrap without Composer, you will still need to use Composer to install
the [SCSS library][scss] before you enable Bootstrap.

1. [Download][download] Bootstrap and place the file(s) in a directory called Bootstrap in your
    extensions/ folder.

2. In the MediaWiki installation directory, add `"extensions/Bootstrap/composer.json`
   to the `extra/merge-plugin/include` section in the file `composer.local.json`.
   For example,

   ```json
   {
		"extra": {
			"merge-plugin": {
				"include": [
					"extensions/Bootstrap/composer.json"
				]
			}
		}
	}
   ```

3. Still in the MediaWiki installation directory, from a command line run<br>

   ```
   composer update
   ```
4. Add the following code at the bottom of your LocalSettings.php:

   ```php
   wfLoadExtension( 'Bootstrap' );
   ```

5. __Done:__ Navigate to _Special:Version_ on your wiki to verify that the extension
   is successfully installed.

## Documentation

See the [Bootstrap extension documentation](docs).

It may also be worthwhile to have a look at the [Bootstrap site on
MediaWiki][mw-bootstrap] and the related [talk page][mw-bootstrap-talk]

## Professional Support

The Bootstrap extension is maintained by [Professional.Wiki](https://professional.wiki).
You can [contract us][contact-form] to help you with installation or customization of Bootstrap.
We also do development work.

## License

You can use the Bootstrap extension under the [GNU General Public License,
version 3][license] (or any later version).

[bootstrap]: https://getbootstrap.com
[mw-bootstrap]: https://www.mediawiki.org/wiki/Extension:Bootstrap
[mw-bootstrap-talk]: https://www.mediawiki.org/wiki/Extension_Talk:Bootstrap
[download]: https://github.com/ProfessionalWiki/Bootstrap/archive/master.zip
[scss]: https://github.com/professionalwiki/SCSS
[composer]: https://getcomposer.org/
[license]: https://www.gnu.org/copyleft/gpl.html
[contact-form]: https://professional.wiki/en/contact
