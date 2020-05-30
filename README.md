# Bootstrap extension

[![Build Status](https://scrutinizer-ci.com/g/ProfessionalWiki/Bootstrap/badges/build.png?b=master)](https://scrutinizer-ci.com/g/ProfessionalWiki/Bootstrap/build-status/master)
[![Latest Stable Version](https://poser.pugx.org/mediawiki/bootstrap/version.png)](https://packagist.org/packages/mediawiki/bootstrap)
[![License](https://poser.pugx.org/mediawiki/bootstrap/license)](https://packagist.org/packages/mediawiki/bootstrap)

The [Bootstrap extension][mw-bootstrap] provides the
[Bootstrap web front-end framework][bootstrap] to skins and extensions.

This version of the extension provides Bootstrap 4.3.1.

## Requirements

- PHP 5.6 or later
- MediaWiki 1.27 or later

## Installation

There are two methods for installing Bootstrap: with or without [Composer][composer].

If you install Bootstrap with Composer, further required software packages will be installed
automatically. In this case, it is *not* necessary to install any dependencies. Composer will
take care of that.

If you install Bootstrap without Composer, you will still need to use Composer to install
the [SCSS library][scss] before you enable Bootstrap.

### Installation with Composer

1. In the MediaWiki installation directory, add `"mediawiki/bootstrap":"~4.0"`
   to the `require` section in the file `composer.local.json`.
   
2. Still in the MediaWiki installation directory, from a command line run<br>
   `composer update "mediawiki/bootstrap --no-dev -o"`.

3. Load the extension by adding the following line to `LocalSettings.php`:

   ```php
   wfLoadExtension( 'Bootstrap' );
   ``` 
4. __Done:__ Navigate to _Special:Version_ on your wiki to verify that the
   extension is successfully installed.

**Remark:** It is _NOT_ necessary to install or load any extensions this extensions
depends on.

### Installation without Composer

1. Install and enable the [Bootstrap][bootstrap] extension.

2. [Download][download] Bootstrap and place the file(s) in a directory called Bootstrap in your
    skins/ folder. 

3. In the MediaWiki installation directory, add `"extensions/Bootstrap/composer.json`
   to the `include` section of the `merge-plugin` section in the file `composer.local.json`.

4. Still in the MediaWiki installation directory, from a command line run<br>
   `composer update --no-dev -o`.

5. Add the following code at the bottom of your LocalSettings.php:

```php
wfLoadExtension( 'Bootstrap' );
```

6. __Done:__ Navigate to _Special:Version_ on your wiki to verify that the skin
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
[mw-testing]: https://www.mediawiki.org/wiki/Manual:PHP_unit_testing
[scss]: https://github.com/professionalwiki/SCSS
[composer]: https://getcomposer.org/
[license]: https://www.gnu.org/copyleft/gpl.html
[contact-form]: https://professional.wiki/en/contact
