# Bootstrap extension
[![Latest Stable Version](https://poser.pugx.org/mediawiki/bootstrap/version.png)](https://packagist.org/packages/mediawiki/bootstrap)
[![Packagist download count](https://poser.pugx.org/mediawiki/bootstrap/d/total.png)](https://packagist.org/packages/mediawiki/bootstrap)
[![Dependency Status](https://www.versioneye.com/php/mediawiki:bootstrap/badge.png)](https://www.versioneye.com/php/mediawiki:bootstrap)

The [Bootstrap extension][mw-bootstrap] provides Twitter's Bootstrap web
front-end framework to skins and extensions.

## Requirements

- PHP 5.3.2 or later
- MediaWiki 1.22 or later
- [Composer][composer]

## Installation

1. On a command line go to your MediaWiki installation directory
2. If necessary (on MediaWiki up to 1.23) copy the file `composer.json.example`
   to `composer.json`
3. With Composer installed, run
   `composer require "mediawiki/bootstrap:~1.0"`
4. __Done:__ Navigate to _Special:Version_ on your wiki to verify that the
   extension is successfully installed.

## Documentation

See the [Bootstrap extension documentation](docs).

It may also be worthwhile to have a look at the [Bootstrap site on
MediaWiki][mw-bootstrap] and the related [talk page][mw-bootstrap-talk]

## License

You can use the Bootstrap extension under the [GNU General Public License,
version 3][license] (or any later version).


[mw-bootstrap]: https://www.mediawiki.org/wiki/Extension:Bootstrap
[mw-bootstrap-talk]: https://www.mediawiki.org/wiki/Extension_Talk:Bootstrap
[mw-testing]: https://www.mediawiki.org/wiki/Manual:PHP_unit_testing
[composer]: https://getcomposer.org/
[license]: https://www.gnu.org/copyleft/gpl.html
