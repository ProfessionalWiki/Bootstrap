# Bootstrap extension
[![Latest Stable Version](https://poser.pugx.org/mediawiki/bootstrap/version.png)](https://packagist.org/packages/mediawiki/bootstrap)
[![Packagist download count](https://poser.pugx.org/mediawiki/bootstrap/d/total.png)](https://packagist.org/packages/mediawiki/bootstrap)
[![Dependency Status](https://www.versioneye.com/php/mediawiki:bootstrap/badge.png)](https://www.versioneye.com/php/mediawiki:bootstrap)

The [Bootstrap extension][mw-bootstrap] provides Twitter's Bootstrap web front-end framework to skins and extensions.

## Requirements

- PHP 5.3.2 or later
- MediaWiki 1.22 or later

## Installation

The recommended way to install this skin is by using [Composer][composer]. Just add the following to the MediaWiki `composer.json` file and run the `php composer.phar install/update` command.

```json
{
	"require": {
		"mediawiki/bootstrap": "~1.0"
	}
}
```

## Tests

This extension provides unit tests that can be run by a continues integration platform or manually by executing the `mw-phpunit-runner.php` script or [`phpunit`][mw-testing] together with the PHPUnit configuration file found in the root directory.

```sh
php mw-phpunit-runner.php [options]
```

## License

[GNU General Public License 3.0][license].

[mw-bootstrap]: https://www.mediawiki.org/wiki/Extension:Bootstrap
[mw-testing]: https://www.mediawiki.org/wiki/Manual:PHP_unit_testing
[composer]: https://getcomposer.org/
[license]: https://www.gnu.org/copyleft/gpl.html